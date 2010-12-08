<?php

class Association_Controller extends Controller {
	
	/**
	 * Show the profile of an association
	 */
	public function index($params){
		$this->setView('index.php');
		
		$is_logged = isset(User_Model::$auth_data);
		$is_admin = $is_logged && User_Model::$auth_data['admin']=='1';
		
		$associations = $this->model->getAll();
		$this->set(array(
			'associations'	=> $associations,
			'is_logged'		=> $is_logged,
			'is_admin'		=> $is_admin
		));
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
	
	
	/**
	 * Edit an association
	 */
	public function edit($params){
		$this->setView('edit.php');
		
		try {
			$association = $this->model->getInfoByName($params['association']);
		}catch(Exception $e){
			throw new ActionException('Page', 'error404');
		}
		
		$associations_auth = Association_Model::getAuth();
		
		$is_logged = isset(User_Model::$auth_data);
		$is_admin = $is_logged && User_Model::$auth_data['admin']=='1';
		
		// Authorization
		if(!$is_admin && !(isset($associations_auth[(int) $association['id']]) && $associations_auth[(int) $association['id']]['admin']))
			throw new ActionException('Page', 'error404');
		
		$this->set('association_name', $association['name']);
		
		$association['creation_date'] = date(__('ASSOCIATION_EDIT_FORM_CREATION_DATE_FORMAT'), strtotime($association['creation_date']));
		
		// Saving data
		if(isset($_POST['name']) && isset($_POST['creation_date']) && isset($_POST['mail']) && isset($_POST['description'])){
			
			$uploaded_files = array();
			try {
				
				// Members
				$members = array();
				if(isset($_POST['members_ids']) && is_array($_POST['members_ids'])){
					foreach($_POST['members_ids'] as $id){
						if(ctype_digit($id)){
							$id = (int) $id;
							$members[$id] = array(
								'title'	=> isset($_POST['member_title_'.$id]) ? $_POST['member_title_'.$id] : '',
								'admin'	=> isset($_POST['member_admin_'.$id])
							);
						}
					}
				}
				
				// Other info
				$data = array(
					'name'			=> $_POST['name'],
					'creation_date'	=> $_POST['creation_date'],
					'mail'			=> $_POST['mail'],
					'description'	=> $_POST['description'],
					'members'		=> $members
				);
				
				// Avatar
				if(isset($_FILES['avatar']) && !is_array($_FILES['avatar']['name'])){
					if($_FILES['avatar']['size'] > Config::UPLOAD_MAX_SIZE_PHOTO)
						throw new FormException('avatar');
					if($avatarpath = File::upload('avatar')){
						$uploaded_files[] = $avatarpath;
						try {
							$img = new Image();
							$img->load($avatarpath);
							$type = $img->getType();
							if($type==IMAGETYPE_JPEG)
								$ext = 'jpg';
							else if($type==IMAGETYPE_GIF)
								$ext = 'gif';
							else if($type==IMAGETYPE_PNG)
								$ext = 'png';
							else
								throw new Exception();
							
							if($img->getWidth() > 800)
								$img->setWidth(800, true);
							$img->setType(IMAGETYPE_JPEG);
							$img->save($avatarpath);
							
							// Thumb
							$avatarthumbpath = $avatarpath.'.thumb';
							$img->thumb(Config::$AVATARS_THUMBS_SIZES[0], Config::$AVATARS_THUMBS_SIZES[1]);
							$img->setType(IMAGETYPE_JPEG);
							$img->save($avatarthumbpath);
							
							unset($img);
							$uploaded_files[] = $avatarthumbpath;
							
							$data['avatar_path'] = $avatarthumbpath;
							$data['avatar_big_path'] = $avatarpath;
							
						}catch(Exception $e){
							throw new FormException('avatar');
						}
					}
				}
				
				$url_name = $this->model->save((int) $association['id'], $data);
				Routes::redirect('association', array('association' => $url_name));
				
			}catch(FormException $e){
				foreach($uploaded_files as $uploaded_file)
					File::delete($uploaded_file);
				foreach($data as $key => $value)
					$association[$key] = $value;
				
				$association['members'] = Student_Model::getInfoByUsersIds(array_keys($members));
				foreach($association['members'] as &$member){
					if(isset($members[(int) $member['user_id']])){
						$member['title'] = $members[(int) $member['user_id']]['title'];
						$member['admin'] = $members[(int) $member['user_id']]['admin'] ? '1' : '0';
					}
				}
				
				$this->set('form_error', $e->getError());
			}
		}
		
		
		$this->set('association', $association);
		$this->addJSCode('Association.initEdit();');
		
	}
	
	
	/**
	 * Add an association
	 */
	public function add($params){
		$this->setView('add.php');
		
		$is_logged = isset(User_Model::$auth_data);
		$is_admin = $is_logged && User_Model::$auth_data['admin']=='1';
		
		// Authorization
		if(!$is_admin)
			throw new ActionException('Page', 'error404');
		
		$association = array();
		
		// Saving data
		if(isset($_POST['name']) && isset($_POST['creation_date']) && isset($_POST['mail']) && isset($_POST['description'])){
			
			$uploaded_files = array();
			try {
				
				// Members
				$members = array();
				if(isset($_POST['members_ids']) && is_array($_POST['members_ids'])){
					foreach($_POST['members_ids'] as $id){
						if(ctype_digit($id)){
							$id = (int) $id;
							$members[$id] = array(
								'title'	=> isset($_POST['member_title_'.$id]) ? $_POST['member_title_'.$id] : '',
								'admin'	=> isset($_POST['member_admin_'.$id])
							);
						}
					}
				}
				
				// Other info
				$data = array(
					'name'			=> $_POST['name'],
					'creation_date'	=> $_POST['creation_date'],
					'mail'			=> $_POST['mail'],
					'description'	=> $_POST['description'],
					'members'		=> $members
				);
				
				// Avatar
				if(isset($_FILES['avatar']) && !is_array($_FILES['avatar']['name'])){
					if($_FILES['avatar']['size'] > Config::UPLOAD_MAX_SIZE_PHOTO)
						throw new FormException('avatar');
					if($avatarpath = File::upload('avatar')){
						$uploaded_files[] = $avatarpath;
						try {
							$img = new Image();
							$img->load($avatarpath);
							$type = $img->getType();
							if($type==IMAGETYPE_JPEG)
								$ext = 'jpg';
							else if($type==IMAGETYPE_GIF)
								$ext = 'gif';
							else if($type==IMAGETYPE_PNG)
								$ext = 'png';
							else
								throw new Exception();
							
							if($img->getWidth() > 800)
								$img->setWidth(800, true);
							$img->setType(IMAGETYPE_JPEG);
							$img->save($avatarpath);
							
							// Thumb
							$avatarthumbpath = $avatarpath.'.thumb';
							$img->thumb(Config::$AVATARS_THUMBS_SIZES[0], Config::$AVATARS_THUMBS_SIZES[1]);
							$img->setType(IMAGETYPE_JPEG);
							$img->save($avatarthumbpath);
							
							unset($img);
							$uploaded_files[] = $avatarthumbpath;
							
							$data['avatar_path'] = $avatarthumbpath;
							$data['avatar_big_path'] = $avatarpath;
							
						}catch(Exception $e){
							throw new FormException('avatar');
						}
					}
				}
				
				$url_name = $this->model->create($data);
				Routes::redirect('association', array('association' => $url_name));
				
			}catch(FormException $e){
				foreach($uploaded_files as $uploaded_file)
					File::delete($uploaded_file);
				foreach($data as $key => $value)
					$association[$key] = $value;
				
				$association['members'] = Student_Model::getInfoByUsersIds(array_keys($members));
				foreach($association['members'] as &$member){
					if(isset($members[(int) $member['user_id']])){
						$member['title'] = $members[(int) $member['user_id']]['title'];
						$member['admin'] = $members[(int) $member['user_id']]['admin'] ? '1' : '0';
					}
				}
				
				$this->set('form_error', $e->getError());
			}
		}
		
		
		$this->set('association', $association);
		$this->addJSCode('Association.initEdit();');
		
	}
	
}
