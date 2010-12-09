<?php

class Event_Model extends Model {
	
	/**
	 * Returns the events of a month
	 *
	 * @param int $year		Year
	 * @param int $month	Month number
	 * @param array $params	Associative array of paramaters. Possibles keys :
	 *							* official: Only official posts if true, only non-official posts if false, all posts if null
	 *							* show_private: Private posts include if true
	 *							* group_id: Group's id
	 * @return array
	 */
	public function getByMonth($year, $month, $params){
		$cache_entry = 'events-'.$year.'-'.$month;
		foreach($params as $key => $value){
			if(isset($value))
				$cache_entry .= '-'.$key.':'.$value;
		}
		$events = Cache::read($cache_entry);
		if($events !== false)
			return $events;
		
		$date_start = date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, $year));
		$date_start_for_end = date('Y-m-d H:i:s', mktime(12, 0, 0, $month, 1, $year));
		$date_end = date('Y-m-d H:i:s', mktime(0, 0, 0, $month+1, 1, $year)-1);
		
		$where = array('
			(e.date_start BETWEEN "'.$date_start.'" AND "'.$date_end.'"
				OR
			 e.date_end BETWEEN "'.$date_start_for_end.'" AND "'.$date_end.'")
		');
		
		if(isset($params['official']))
			$where[] = 'p.official = '.($params['official'] ? 1 : 0);
		if(!isset($params['show_private']) || !$params['show_private'])
			$where[] = 'p.private = 0';
		if(isset($params['group_id']))
			$where[] = 'p.group_id = '.$params['group_id'];
		
		$events = DB::select('
			SELECT e.title, e.date_start, e.date_end, e.post_id
			FROM `events` e
			INNER JOIN `posts` p ON p.id = e.post_id
			WHERE '.implode(' AND ', $where).'
			ORDER BY e.date_start ASC
		');
		
		foreach($events as &$event){
			$event['date_start'] = strtotime($event['date_start']);
			$event['date_end'] = strtotime($event['date_end']);
		}
		
		// Write the cache
		Cache::write($cache_entry, $events, 20*60);
		$cache_list = Cache::read('posts-cachelist');
		if(!$cache_list)
			$cache_list = array();
		if(!in_array($cache_entry, $cache_list))
			$cache_list[] = $cache_entry;
		Cache::write('posts-cachelist', $cache_list, 20*60);
		
		return $events;
	}
	
	
	/**
	 * Returns the upcoming events
	 *
	 * @param boolean $group_name		Group's name (optional)
	 * @param boolean $official		Only official posts if true, only non-official posts otherwise, all posts if null (optional)
	 * @param boolean $private		Private posts include if true (optional)
	 * @return array
	 */
	public function getUpcoming($group_name=null, $official=null, $show_private=null){
		$date_start = date('Y-m-d H:i:s');
		
		$where = array('e.date_start > "'.$date_start.'"');
		if(isset($group_name))
			$where[] = 'a.url_name = '.DB::quote($group_name);
		if(isset($official))
			$where[] = 'p.official = '.($official ? 1 : 0);
		if(!$show_private)
			$where[] = 'p.private = 0';
		
		$events = DB::select('
			SELECT e.title, e.date_start, e.date_end, p.message
			FROM `events` e
			INNER JOIN `posts` p ON p.id = e.post_id
			'.(isset($group_name) ? 'INNER JOIN `groups` a ON a.id = p.group_id' : '').'
			WHERE '.implode(' AND ', $where).'
			ORDER BY e.date_start ASC
		');
		
		foreach($events as &$event){
			$event['date_start'] = strtotime($event['date_start']);
			$event['date_end'] = strtotime($event['date_end']);
		}
		
		return $events;
	}
	
}
