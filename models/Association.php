<?php

class Association_Model extends Model {
	
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
			WHERE au.user_id='.User_Model::$auth_data['id'].'
		');
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
