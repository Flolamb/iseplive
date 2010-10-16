<?php

class Association_Model extends Model {
	
	/**
	 * Returns the list of the association for which the authenticated user is admin
	 *
	 * @return array
	 */
	public function getAuth(){
		if(!isset(User_Model::$auth_data))
			throw new Exception('No user authenticated');
		$cache_entry = 'associations_auth_'.User_Model::$auth_data['id'];
		if($categories = Cache::read($cache_entry))
			return $categories;
		
		$associations_auth = DB::select('
			SELECT a.id, a.name, au.admin
			FROM associations_users au
			INNER JOIN associations a ON a.id = au.association_id
			WHERE au.user_id='.User_Model::$auth_data['id'].'
		');
		Cache::write($cache_entry, $associations_auth, 60*10);
		return $associations_auth;
	}
	
}
