<?php

class Student_Controller extends Controller {
	
	/**
	 * Show the profile of a student
	 */
	public function view($params){
		$this->setView('view.php');
		
		// If the user isn't logged in
		if(!isset(User_Model::$auth_data))
			throw new ActionException('User', 'signin', array('referrer' => $_SERVER['REQUEST_URI']));
		
		try {
			
			$student = $this->model->getInfo($params['username']);
			$this->set($student);
			
			// Avatar
			$this->set(array(
				'avatar_url'		=> User_Model::getAvatarURL($student['student_number'], true),
				'avatar_big_url'	=> User_Model::getAvatarURL($student['student_number'])
			));
			
		}catch(Exception $e){
			throw new ActionException('Page', 'error404');
		}
	}
	
}
