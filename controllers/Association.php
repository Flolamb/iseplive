<?php

class Association_Controller extends Controller {
	
	/**
	 * Show the profile of an association
	 */
	public function index($params){
		$this->setView('index.php');
		
		$associations = $this->model->getAll();
		$this->set('associations', $associations);
	}
	
	/**
	 * Show the profile of an association
	 */
	public function view($params){
		$this->setView('view.php');
		
		try {
			
			$association = $this->model->getInfoByName($params['association']);
			$this->set('association', $association);
			
		}catch(Exception $e){
			throw new ActionException('Page', 'error404');
		}
		
		$is_logged = isset(User_Model::$auth_data);
		$is_student = $is_logged && isset(User_Model::$auth_data['student_number']);
		$is_admin = $is_logged && User_Model::$auth_data['admin']=='1';
		$category = isset($params['category']) ? $params['category'] : null;
		
		$category_model = new Category_Model();
		$post_model = new Post_Model();
		
		$this->set(array(
			'is_logged'			=> $is_logged,
			'is_student'		=> $is_student,
			'is_admin'			=> $is_admin,
			'categories'		=> $category_model->getAll(),
			'current_category'	=> $category,
			'posts'				=> $post_model->getPosts(array(
				'restricted'	=> true,
				'association_id'	=> (int) $association['id'],
				'category_name'		=> $category,
				'official'			=> $is_logged ? null : true,
				'show_private'		=> $is_student
			), Config::POST_DISPLAYED)
		));
		
		// Events
		$event_model = new Event_Model();
		$this->set(array(
			'events' 			=> $event_model->getByMonth((int) date('Y'), (int) date('n'), array(
				'association_id'	=> (int) $association['id'],
				'official'			=> $is_logged ? null : true,
				'show_private'		=> $is_student
			)),
			'calendar_month'	=> (int) date('n'),
			'calendar_year'		=> (int) date('Y')
		));
		
		// If the user is logged
		if($is_logged){
			
			$this->set(array(
				'username'			=> User_Model::$auth_data['username'],
				'firstname'			=> User_Model::$auth_data['firstname'],
				'lastname'			=> User_Model::$auth_data['lastname'],
				'associations_auth'	=> Association_Model::getAuth()
			));
			
			if($is_student){
				$this->set('avatar_url', User_Model::getAvatarURL(User_Model::$auth_data['student_number'], true));
			}
		
		}
		
	}
	
}
