<?php

class PostComment_Controller extends Controller {
	
	/**
	 * Add a comment to a post
	 */
	public function add($params){
		$this->setView('add.php');
		
		if(!isset(User_Model::$auth_data))
			throw Exception('You must be logged in');
		if(!isset(User_Model::$auth_data['student_number']))
			throw Exception('You must be a student to post a comment');
		
		$message = isset($_POST['message']) ? trim($_POST['message']) : '';
		if($message == '')
			throw Exception('You must write a message');
		
		try {
			$id = $this->model->add($params['post_id'], (int) User_Model::$auth_data['id'], $message);
			$this->set(array(
				'id'			=> $id,
				'username'		=> User_Model::$auth_data['username'],
				'firstname'		=> User_Model::$auth_data['firstname'],
				'lastname'		=> User_Model::$auth_data['lastname'],
				'avatar_url'	=> User_Model::getAvatarURL(User_Model::$auth_data['student_number'], true),
				'message'		=> $message
			));
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	
}
