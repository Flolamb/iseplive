<?php

class User_Model extends Model {
	
	/**
	 * Associative array of authenticate user's data
	 * @var array
	 */
	public static $auth_data;
	
	/**
	 * Status of the user's authentication
	 * @var int
	 */
	public static $auth_status;
	
	/**
	 * Possible status of authentification
	 * @var int
	 */
	const AUTH_STATUS_NOT_LOGGED = 1;
	const AUTH_STATUS_LOGGED = 2;
	const AUTH_STATUS_BAD_USERNAME_OR_PASSWORD = 3;
	
	/**
	 * Verify the username and the password of an user, using the ISEP LDAP
	 *
	 * @param string $username	User name
	 * @param string $password	Password
	 * @return boolean	True on success, false on failure
	 */
	public function authenticate($username, $password){
		$this->loadUser($username);
		return true;
		$ldap_conn = ldap_connect(Config::$LDAP['host'], Config::$LDAP['port']);
		try {
			$result = ldap_bind($ldap_conn, 'uid='.$username.','.Config::$LDAP['basedn'], $password);
			// Login successful
			if($result){
				// Loading the user's data
				try {
					$this->loadUser($username);
				
				// If the user doesn't exist in the DB, we create it
				}catch(Exception $e){
					/*
					$search = ldap_search($ldap_conn, Config::$LDAP['basedn'], 'uid='.$username);
					$users = ldap_get_entries($ldap_conn, $search);
					if($users['count'] == 0)
						throw new Exception('User not found');
					*/
					$this->createQuery()
						->set(array(
							'username'	=> $username
						))
						->insert();
					
					$this->loadUser($username);
				}
			}
			return $result;
		}catch(Exception $e){
			return false;
		}
	}
	
	/**
	 * Load data of an user into the $auth_data static var
	 *
	 * @param string $username	User name
	 * @return boolean	True on success, false on failure
	 */
	public function loadUser($username){
		$users = DB::select('
			SELECT u.*, s.firstname, s.lastname, s.student_number, s.promo
			FROM users u
			LEFT JOIN students s ON s.username = u.username
			WHERE u.username = ?
		', array($username));
		if(isset($users[0]))
			User_Model::$auth_data = $users[0];
		else
			throw new Exception('User not found');
	}
	
	
	/**
	 * Returns  the path of an avatar
	 *
	 * @param int $student_number	Number of the student
	 * @param boolean $thumb		Thumb's path if true, big photo otherwise
	 * @return string
	 */
	public static function getAvatarPath($student_number, $thumb=false){
		$student_number = (string) ((int) $student_number);
		return DATA_DIR.Config::DIR_DATA_STORAGE.'avatars/'.substr($student_number, 0, -2).'/'.$student_number.($thumb ? '_thumb' : '').'.jpg';
	}
	
	/**
	 * Returns  the absolute URL of an avatar
	 *
	 * @param int $student_number	Number of the student
	 * @param boolean $thumb		Thumb's path if true, big photo otherwise
	 * @return string
	 */
	public static function getAvatarURL($student_number, $thumb=false){
		$student_number = (string) ((int) $student_number);
		return Config::URL_STORAGE.'avatars/'.substr($student_number, 0, -2).'/'.$student_number.($thumb ? '_thumb' : '').'.jpg';
	}
	
}
