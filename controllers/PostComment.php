<?php

class PostComment_Controller extends Controller {
	
	/**
	 * Add a comment to a post
	 */
	public function add($params){
		$this->setView('add.php');
		
		if(!isset(User_Model::$auth_data))
			throw new Exception('You must be logged in');
		if(!isset(User_Model::$auth_data['student_number']))
			throw new Exception('You must be a student to post a comment');
		
		$message = isset($_POST['message']) ? trim($_POST['message']) : '';
		if($message == '')
			throw new Exception('You must write a message');
		
		$attachment_id = isset($_POST['attachment']) && ctype_digit($_POST['attachment']) ? (int) $_POST['attachment'] : null;
		
		try {
			$id = $this->model->add($params['post_id'], (int) User_Model::$auth_data['id'], $message, $attachment_id);
			$this->set(array(
				'id'			=> $id,
				'username'		=> User_Model::$auth_data['username'],
				'firstname'		=> User_Model::$auth_data['firstname'],
				'lastname'		=> User_Model::$auth_data['lastname'],
				'avatar_url'	=> User_Model::$auth_data['avatar_url'],
				'message'		=> $message,
				'attachment_id'	=> $attachment_id
			));
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	
	/**
	 * Delete a post
	 */
	public function delete($params){
		$this->setView('delete.php');
		
		try {
			$comment = $this->model->get((int) $params['id']);
			
			$is_logged = isset(User_Model::$auth_data);
			$is_admin = $is_logged && User_Model::$auth_data['admin']=='1';
			$associations_auth = Association_Model::getAuth();
			
			if(($is_logged && User_Model::$auth_data['id'] == $comment['user_id'])
			|| $is_admin
			|| (isset($post['association_id']) && isset($associations_auth[(int) $post['association_id']])) && $associations_auth[(int) $post['association_id']]['admin']){
				
				$this->model->delete((int) $params['id']);
				$this->set('success', true);
				
			}else{
				$this->set('success', false);
			}
		}catch(Exception $e){
			// Post not found
			$this->set('success', true);
		}
	}
	
	
}
