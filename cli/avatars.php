<?php
/**
 * Fill the directory with the info of the LDAP
 *
 * @example /usr/bin/php -f avatars.php
 */

define('CLI_MODE', true);
define('APP_DIR', realpath(dirname(__FILE__).'/../').'/');
define('CF_DIR', realpath(dirname(__FILE__).'/../../confeature/').'/');
define('DATA_DIR', realpath(dirname(__FILE__).'/../../data/').'/');

try{
	
	// Loading Confeature
	require_once CF_DIR.'init.php';
	
	$avatars_tmp_path = DATA_DIR.Config::DIR_DATA_TMP.'/avatars';
	if(!is_dir($avatars_tmp_path))
		throw new Exception($avatars_tmp_path.' not found!');
	
	$students = DB::createQuery('students')
		->fields('student_number', 'firstname', 'lastname')
		->select();
	
	foreach($students as $student){
		try {
			$avatar_path = Student_Model::getAvatarPath((int) $student['student_number']);
			$avatar_thumb_path = Student_Model::getAvatarPath((int) $student['student_number'], true);
			if(file_exists($avatar_path))
				continue;
			$original_path = $avatars_tmp_path.'/'.$student['student_number'].'.jpg';
			if(!file_exists($original_path))
				throw new Exception('Photo of the student n°'.$student['student_number'].' ('.$student['firstname'].' '.$student['lastname'].') not found!');
			
			$avatar_dir = File::getPath($avatar_path);
			if(!is_dir($avatar_dir))
				File::makeDir($avatar_dir, 0777, true);
			
			File::copy($original_path, $avatar_path);
			
			// Thumb
			$img = new Image();
			$img->load($original_path);
			$img->thumb(Config::$AVATARS_THUMBS_SIZES[0], Config::$AVATARS_THUMBS_SIZES[1]);
			$img->save($avatar_thumb_path);
			
		}catch(Exception $e){
			echo $e->getMessage()."\n";
		}

	}
	
	
	
}catch(Exception $e){
	echo $e->getMessage();
}
