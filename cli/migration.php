<?php
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
	
	
	/*
	$users = DB::createQuery('users')
		->fields('id', 'phone')
		->where('phone != ""')
		->select();
	foreach($users as $user){
		$phone = $user['phone'];
		$phone = preg_replace('#^(00|\+) ?33#', '0', $phone);
		$phone = preg_replace('#[^0-9]#', '', $phone);
		$phone = substr($phone, 1);
		if(preg_match('#^[0-9]{9}$#', $phone))
			$phone = '0'.$phone[0].' '.$phone[1].$phone[2].' '.$phone[3].$phone[4].' '.$phone[5].$phone[6].' '.$phone[7].$phone[8];
		else
			$phone = '';
		
		DB::createQuery('users')
			->set(array('phone' => $phone))
			->update((int) $user['id']);
	}
	*/
	
	
	// CSV des élèves
	$list = '
';

	
	$list = explode("\n", trim($list));
	foreach($list as $list_){
		preg_match_all('#(""|".*(?<!")"|[^,"]+)(?:,|$)#U', trim($list_), $matches);
		$matches = $matches[1];
		foreach($matches as &$match){
			if($match[0] == '"')
				$match = substr($match, 1, -1);
		}
		$student_number = (int) $matches[1];
		$promo = $matches[0];
		$lastname = $matches[3];
		$firstname = $matches[4];
		$birthday = $matches[5];
		$address = $matches[6];
		$zipcode = $matches[7];
		$city = ucwords(strtolower($matches[8]));
		$phone = $matches[9];
		$cellphone = $matches[10];
		
		$lastname = ucwords(str_replace('-', ' ', strtolower($lastname)));
		$firstname = str_replace(' ', '-', ucwords(str_replace('-', ' ', $firstname)));
		if(preg_match('#^([0-9]{2})/([0-9]{2})/([0-9]{4})$#', $birthday, $birthday))
			$birthday = $birthday[3].'-'.$birthday[2].'-'.$birthday[1];
		else
			$birthday = null;
		
		if(strlen($phone) == 10)
			$phone = $phone[0].$phone[1].' '.$phone[2].$phone[3].' '.$phone[4].$phone[5].' '.$phone[6].$phone[7].' '.$phone[8].$phone[9];
		if(strlen($cellphone) == 10)
			$cellphone = $cellphone[0].$cellphone[1].' '.$cellphone[2].$cellphone[3].' '.$cellphone[4].$cellphone[5].' '.$cellphone[6].$cellphone[7].' '.$cellphone[8].$cellphone[9];
		
		//echo 'n°'.$student_number.' - '.$firstname.' '.$lastname.' - né le '.$birthday.' - '.$address.', '.$zipcode.', '.$city.' - '.$phone.' - '.$cellphone."\n";
		//continue;
		
		$students = DB::createQuery('students')
			->fields('username', 'firstname', 'lastname')
			->where(array(
				'student_number' => $student_number
			))
			->select();
		try {
			if(!isset($students[0]))
				throw new Exception('Student n°'.$student_number.' ('.$firstname.' '.$lastname.') not found!');
			$student = $students[0];
			//if($student['firstname'] != $firstname && $student['lastname'] != $lastname)
			//	throw new Exception('Student n°'.$student_number.' : different names : '.$firstname.' '.$lastname.' != '.$student['firstname'].' '.$student['lastname']);
			
			if($promo == 'CESURE-A2-A3'){
				DB::createQuery('students')
					->set('cesure', 1)
					->where(array('student_number' => $student_number))
					->update();
			}
			
			$users = DB::select('
				SELECT 1
				FROM users
				WHERE username = '.DB::quote($student['username']).'
			');
			$db_query = DB::createQuery('users')
				->set(array(
					'address'		=> $address,
					'zipcode'		=> $zipcode,
					'city'			=> $city,
					'cellphone'		=> $cellphone,
					'phone'			=> $phone
				));
			if(isset($birthday))
				$db_query->set('birthday', $birthday);
			if(!isset($users[0])){
				echo 'Creating user "'.$student['username'].'"'."\n";
				$db_query->set(array('username' => $student['username']))->insert();
			}else{
				$db_query->where(array('username' => $student['username']))->update();
			}
		}catch(Exception $e){
			echo $e->getMessage()."\n";
		}
	}
	
	
	
	/*
	$users = DB::select('
		SELECT o.login, o.mail_perso, o.msn_perso, o.adresse, o.portable, o.telephone, o.naissance
		FROM old_users o
		INNER JOIN students s ON s.username = o.login
	');
	
	foreach($users as $user){
		try {
			DB::createQuery('users')
				->set(array(
					'username'	=> $user['login'],
					'mail'		=> $user['mail_perso'],
					'msn'		=> $user['msn_perso'],
					'address'	=> $user['adresse'],
					'phone'		=> $user['portable']=='' ? $user['telephone'] : $user['portable'],
					'birthday'	=> $user['naissance']
				))
				->insert();
		}catch(Exception $e){
			echo $e->getMessage()."\n";
		}
	}
	*/
	
	
}catch(Exception $e){
	echo $e->getMessage();
}
