<?php

class Association_Model extends Model {
	
	
	/**
	 * Returns an associative array of the information of an association,
	 * including members info
	 *
	 * @param string $url_name	Association's name
	 * @return array
	 */
	public function getInfoByName($url_name){
		$association = Cache::read('association-'.$url_name);
		if($association !== false)
			return $association;
		
		$associations = $this->createQuery()
			->where(array('url_name' => $url_name))
			->select();
		
		if(!isset($associations[0]))
			throw new Exception('Association not found');
		
		$association = $associations[0];
		
		// Avatar
		$association['avatar_url'] = self::getAvatarURL((int) $association['id'], true);
		$association['avatar_big_url'] = self::getAvatarURL((int) $association['id']);
		
		// Members
		$association['members'] = DB::select('
			SELECT
				s.username, s.firstname, s.lastname,
				a_s.title, a_s.admin
			FROM associations_users a_s
			INNER JOIN users u ON u.id = a_s.user_id
			INNER JOIN students s ON s.username = u.username
			WHERE a_s.association_id = ?
			ORDER BY a_s.order ASC
		', array((int) $association['id']));
		
		Cache::write('association-'.$url_name, $association, 12*3600);
		
		return $association;
	}
	
	
	/**
	 * Returns an associative array of all associations
	 *
	 * @return array
	 */
	public function getAll(){
		$associations = Cache::read('associations');
		if($associations !== false)
			return $associations;
		
		$associations = $this->createQuery()
			->order(array('name', 'ASC'))
			->select();
		
		// Avatar
		foreach($associations as &$association){
			$association['avatar_url'] = self::getAvatarURL((int) $association['id'], true);
			$association['avatar_big_url'] = self::getAvatarURL((int) $association['id']);
		}
		
		Cache::write('associations', $associations, 12*3600);
		
		return $associations;
	}
	
	
	/**
	 * Returns the list of the association for which the authenticated user is admin
	 *
	 * @return array
	 */
	public static function getAuth(){
		if(!isset(User_Model::$auth_data))
			throw new Exception('No user authenticated');
		$cache_entry = 'associations_auth_'.User_Model::$auth_data['id'];
		if($categories = Cache::read($cache_entry))
			return $categories;
		
		$associations_data = DB::select('
			SELECT a.id, a.name, au.admin
			FROM associations_users au
			INNER JOIN associations a ON a.id = au.association_id
			WHERE au.user_id = ?
		', array((int) User_Model::$auth_data['id']));
		$associations_auth = array();
		foreach($associations_data as &$asso){
			$associations_auth[(int) $asso['id']] = array(
				'name'	=> $asso['name'],
				'admin'	=> $asso['admin']=='1'
			);
		}
		
		Cache::write($cache_entry, $associations_auth, 60*10);
		return $associations_auth;
	}
	
	
	/**
	 * Returns  the path of an avatar
	 *
	 * @param int $id			Id of the association
	 * @param boolean $thumb	Thumb's path if true, big photo otherwise
	 * @return string
	 */
	public static function getAvatarPath($id, $thumb=false){
		$id = (string) ((int) $id);
		return DATA_DIR.Config::DIR_DATA_STORAGE.'associations/'.$id.($thumb ? '_thumb' : '').'.jpg';
	}
	
	/**
	 * Returns  the absolute URL of an avatar
	 *
	 * @param int $id			Id of the association
	 * @param boolean $thumb	Thumb's path if true, big photo otherwise
	 * @return string
	 */
	public static function getAvatarURL($id, $thumb=false){
		$student_number = (string) ((int) $id);
		return Config::URL_STORAGE.'associations/'.$id.($thumb ? '_thumb' : '').'.jpg';
	}
	
}
