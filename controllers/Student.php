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
				'avatar_url'		=> $student['avatar_url'],
				'avatar_big_url'	=> $student['avatar_big_url'],
				'is_owner'			=> User_Model::$auth_data['username'] == $student['username']
			));
			
		}catch(Exception $e){
			throw new ActionException('Page', 'error404');
		}
	}
	
	
	/**
	 * Autocomplete the firstname / lastname of a student
	 */
	public function autocomplete($params){
		$this->setView('autocomplete.php');
		
		// If the user isn't logged in
		if(!isset(User_Model::$auth_data))
			throw new Exception('You must be a student');
		
		if(!isset($_GET['q']))
			throw new Exception('Query parameter "q" not set');
		
		$limit = isset($_GET['limit']) && ctype_digit($_GET['limit']) ? (int) $_GET['limit'] : 10;
		
		$this->set('students', $this->model->autocomplete($_GET['q'], $limit));
		
	}
	
}
