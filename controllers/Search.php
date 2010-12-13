<?php

class Search_Controller extends Controller {
	
	/**
	 * Search posts, groups, and students
	 */
	public function index($params){
		$this->setView('index.php');
		
		$is_logged = isset(User_Model::$auth_data);
		$is_student = $is_logged && isset(User_Model::$auth_data['student_number']);
		$is_admin = $is_logged && User_Model::$auth_data['admin']=='1';
		
		if(!isset($_GET['q']))
			throw new ActionException('Page', 'error404');
		
		$limit = 100;
		$results = $this->model->search($_GET['q'], null, $limit, !$is_logged, $is_student);
		
		$posts_ids = array();
		$students_usernames = array();
		$groups_ids = array();
		
		foreach($results as &$result){
			switch($result['_type']){
				case 'student':
					$students_usernames[] = $result['_id'];
					break;
				case 'group':
					$groups_ids[] = (int) $result['_id'];
					break;
				case 'post':
					$posts_ids[] = (int) $result['_id'];
					break;
			}
		}
		
		$post_model = new Post_Model();
		
		$this->setTitle(__('SEARCH_TITLE', array('query' => htmlspecialchars($_GET['q']))));
		
		$this->set(array(
			'query'			=> $_GET['q'],
			'posts'			=> $post_model->getPosts(array(
				'restricted'	=> true,
				'ids'			=> $posts_ids,
				'show_private'	=> $is_student
			), $limit),
			'students'		=> Student_Model::getInfoByUsernames($students_usernames),
			'groups'		=> Group_Model::getInfoByIds($groups_ids),
			'is_logged'		=> true,
			'is_student'	=> $is_student,
			'is_admin'		=> $is_admin
		));
		
		if($is_logged)
			$this->set(array(
				'username'		=> User_Model::$auth_data['username']
			));
		if($is_student)
			$this->set(array(
				'firstname'		=> User_Model::$auth_data['firstname'],
				'lastname'		=> User_Model::$auth_data['lastname'],
				'avatar_url'	=> User_Model::$auth_data['avatar_url']
			));
		
	}
	
	
	/**
	 * Autocomplete a search query
	 */
	public function autocomplete($params){
		$this->setView('autocomplete.php');
		
		$is_logged = isset(User_Model::$auth_data);
		$is_student = $is_logged && isset(User_Model::$auth_data['student_number']);
		
		if(!isset($_GET['q']))
			throw new Exception('Query parameter "q" not set');
		
		$limit = isset($_GET['limit']) && ctype_digit($_GET['limit']) ? (int) $_GET['limit'] : 10;
		
		$this->set(array(
			'is_student'	=> $is_student,
			'query'			=> $_GET['q'],
			'results'		=> $this->model->autocomplete($_GET['q'], null, $limit, !$is_logged, $is_student)
		));
		
	}
	
}
