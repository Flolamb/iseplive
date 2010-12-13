<?php

class Survey_Controller extends Controller {
	
	/**
	 * Send votes in a survey
	 */
	public function vote($params){
		$this->setView('vote.php');
		
		if(!isset(User_Model::$auth_data))
			throw new Exception('You must be logged in');
		if(!isset(User_Model::$auth_data['student_number']))
			throw new Exception('You must be a student to vote');
		
		$votes = array();
		foreach($_POST as $key => $value){
			if(strpos($key, 'answer') === 0 && ctype_digit($value))
				$votes[] = (int) $value;
		}
		try {
			$post_id = $this->model->vote($params['id'], $votes, User_Model::$auth_data['username']);
			$post_model = new Post_Model();
			$post = $post_model->getPost($post_id);
			$this->set(array(
				'is_logged'		=> true,
				'is_student'	=> true,
				'is_admin'		=> User_Model::$auth_data['admin']=='1',
				'username'		=> User_Model::$auth_data['username'],
				'firstname'		=> User_Model::$auth_data['firstname'],
				'lastname'		=> User_Model::$auth_data['lastname'],
				'avatar_url'	=> User_Model::$auth_data['avatar_url'],
				'post'			=> $post
			));
		}catch(Exception $e){}
	}
	
	
}
