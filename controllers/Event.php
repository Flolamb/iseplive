<?php

class Event_Controller extends Controller {
	
	/*
	 * Create an iCal with upcoming events
	 */
	public function ical($params){
		$this->setView('ical.php');
		
		$official = isset($params['official']);
		$group_name = isset($params['group']) ? $params['group'] : null;
		
		$event_model = new Event_Model();
		$events = $event_model->getUpcoming($group_name, $official, false);
		
		// Creation of the iCal content
		$cache_entry = 'ical-'.(isset($group_name) ? $group_name : '').'-'.($official ? 'official' : 'non-official');
		$content = Cache::read($cache_entry);
		
		if(!$content){
			require_once APP_DIR.'classes/class.iCalcreator.php';
			$cal = new vcalendar();
			$cal->setConfig('unique_id', $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
			$cal->setProperty('method', 'PUBLISH' );
			$cal->setProperty('x-wr-calname', $official ? __('EVENTS_TITLE_OFFICIAL') : __('EVENTS_TITLE_NONOFFICIAL'));
			$cal->setProperty('X-WR-CALDESC', '');
			$cal->setProperty('X-WR-TIMEZONE', date('e'));
			
			foreach($events as $event){
				$vevent = new vevent();
				$vevent->setProperty('dtstart', array(
					'year'	=> (int) date('Y', $event['date_start']),
					'month'	=> (int) date('n', $event['date_start']),
					'day'	=> (int) date('j', $event['date_start']),
					'hour'	=> (int) date('G', $event['date_start']),
					'min'	=> (int) date('i', $event['date_start']),
					'sec'	=> (int) date('s', $event['date_start'])
				));
				$vevent->setProperty('dtend', array(
					'year'	=> (int) date('Y', $event['date_end']),
					'month'	=> (int) date('n', $event['date_end']),
					'day'	=> (int) date('j', $event['date_end']),
					'hour'	=> (int) date('G', $event['date_end']),
					'min'	=> (int) date('i', $event['date_end']),
					'sec'	=> (int) date('s', $event['date_end'])
				));
				$vevent->setProperty('summary', $event['title']);
				$vevent->setProperty('description', $event['message']);
				$cal->setComponent($vevent);
			}
			
			$content = $cal->createCalendar();
			Cache::write($cache_entry, $content, 2*3600);
		}
		
		$this->set('content', $content);
	}
	
	
}
