<?php
exit;
/**
 * Fill the directory with the info of the LDAP
 *
 * @example /usr/bin/php -f directory_fill.php
 */

define('CLI_MODE', true);
define('APP_DIR', realpath(dirname(__FILE__).'/../').'/');
define('CF_DIR', realpath(dirname(__FILE__).'/../../confeature/').'/');
define('DATA_DIR', realpath(dirname(__FILE__).'/../../data/').'/');

try{
	
	// Loading Confeature
	require_once CF_DIR.'init.php';
	
	
	$ldap_conn = ldap_connect(Config::$LDAP['host'], Config::$LDAP['port']);

	if(!ldap_bind($ldap_conn))
		die('LDAP Connection error');
	
	/**
	 * Possible "employeetype" : admin, administration, ancien, asso, eleve, prof, -sans-, service
	 */
	$search = ldap_search($ldap_conn, Config::$LDAP['basedn'], 'employeetype=eleve', array('uid', 'sn', 'givenname', 'employeenumber', 'title'));
	$results = ldap_get_entries($ldap_conn, $search);
	$students = array();

	foreach($results as $result){
		if(!isset($result['employeenumber'][0]) || !ctype_digit($result['employeenumber'][0]) || $result['employeenumber'][0] == '0' || !ctype_digit($result['title'][0]) || $result['title'][0] < 2000)
			continue;
		// If it's a test account (ISEP admin...), we continue
		if(((int) $result['employeenumber'][0]) > 8000)
			continue;
		
		$lastname = ucwords(str_replace('-', ' ', $result['sn'][0]));
		$lastname = preg_replace('#(?<=^| )De #', 'de ', $lastname);
		
		$firstname = str_replace(' ', '-', ucwords(str_replace('-', ' ', $result['givenname'][0])));
		
		$students[] = array(
			'username'	=> $result['uid'][0],
			'firstname'	=> $firstname,
			'lastname'	=> $lastname,
			'student_number'	=> (int) $result['employeenumber'][0],
			'promo'		=> (int) $result['title'][0]
		);
	}
	
	// Empty the students table
	DB::createQuery('students')->force()->delete();
	
	// Fill the students table
	while(count($students) != 0){
		$students_ = array_splice($students, 0, 100);
		foreach($students_ as $i => $student){
			foreach($student as $j => $field)
				$student[$j] = DB::quote($field);
			$students_[$i] = implode(',', $student);
		}
		DB::execute('
			INSERT INTO students
			(username, firstname, lastname, student_number, promo)
			VALUES
			('.implode('),(', $students_).')
		');
	}
	
	
}catch(Exception $e){
	echo $e->getMessage();
}
