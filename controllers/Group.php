<?php

class Group_Controller extends Controller {
	
	/**
	 * List of the groups
	 */
	public function index($params){
		$this->setView('index.php');
		$this->setTitle(__('GROUPS_TITLE'));
		
		$is_logged = isset(User_Model::$auth_data);
		$is_admin = $is_logged && User_Model::$auth_data['admin']=='1';
		
		$groups = $this->model->getAll();
		$this->set(array(
			'groups'	=> $groups,
			'is_logged'		=> $is_logged,
			'is_admin'		=> $is_admin
		));
	}
	
	
	/**
	 * Show the profile of a group
	 */
	public function view($params){
		$this->setView('view.php');
		
		try {
			$group = $this->model->getInfoByName($params['group']);
			$this->set('group', $group);
			
		}catch(Exception $e){
			throw new ActionException('Page', 'error404');
		}
		
		$this->setTitle(__('GROUP_TITLE', array('group' => htmlspecialchars($group['name']))));
		
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
				'restricted'		=> true,
				'group_id'			=> (int) $group['id'],
				'category_name'		=> $category,
				'official'			=> $is_logged ? null : true,
				'show_private'		=> $is_student
			), Config::POST_DISPLAYED)
		));
		
		// Events
		$event_model = new Event_Model();
		$this->set(array(
			'events' 			=> $event_model->getByMonth((int) date('Y'), (int) date('n'), array(
				'group_id'	=> (int) $group['id'],
				'official'			=> $is_logged ? null : true,
				'show_private'		=> $is_student
			)),
			'calendar_month'	=> (int) date('n'),
			'calendar_year'		=> (int) date('Y')
		));
		
		// If the user is logged
		if($is_logged)
			$this->set(array(
				'username'		=> User_Model::$auth_data['username'],
				'groups_auth'	=> Group_Model::getAuth()
			));
		if($is_student)
			$this->set(array(
				'firstname'		=> User_Model::$auth_data['firstname'],
				'lastname'		=> User_Model::$auth_data['lastname'],
				'avatar_url'	=> User_Model::$auth_data['avatar_url']
			));
		
	}
	
	
	/**
	 * Edit a group
	 */
	public function edit($params){
		$this->setView('edit.php');
		
		$is_logged = isset(User_Model::$auth_data);
		$is_admin = $is_logged && User_Model::$auth_data['admin']=='1';
		
		try {
			if(!$is_logged)
				throw new Exception();
			$group = $this->model->getInfoByName($params['group']);
		}catch(Exception $e){
			throw new ActionException('Page', 'error404');
		}
		
		$this->setTitle(__('GROUP_EDIT_TITLE', array('group' => htmlspecialchars($group['name']))));
		
		// Authorization
		$groups_auth = Group_Model::getAuth();
		if(!$is_admin && !(isset($groups_auth[(int) $group['id']]) && $groups_auth[(int) $group['id']]['admin']))
			throw new ActionException('Page', 'error404');
		
		$this->set('group_name', $group['name']);
		
		$group['creation_date'] = date(__('GROUP_EDIT_FORM_CREATION_DATE_FORMAT'), strtotime($group['creation_date']));
		
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
				
				$url_name = $this->model->save((int) $group['id'], $data);
				Routes::redirect('group', array('group' => $url_name));
				
			}catch(FormException $e){
				foreach($uploaded_files as $uploaded_file)
					File::delete($uploaded_file);
				foreach($data as $key => $value)
					$group[$key] = $value;
				
				$group['members'] = Student_Model::getInfoByUsersIds(array_keys($members));
				foreach($group['members'] as &$member){
					if(isset($members[(int) $member['user_id']])){
						$member['title'] = $members[(int) $member['user_id']]['title'];
						$member['admin'] = $members[(int) $member['user_id']]['admin'] ? '1' : '0';
					}
				}
				
				$this->set('form_error', $e->getError());
			}
		}
		
		
		$this->set('group', $group);
		$this->addJSCode('Group.initEdit();');
		
	}
	
	
	/**
	 * Add a group
	 */
	public function add($params){
		$this->setView('add.php');
		$this->setTitle(__('GROUP_ADD_TITLE'));
		
		$is_logged = isset(User_Model::$auth_data);
		$is_admin = $is_logged && User_Model::$auth_data['admin']=='1';
		
		// Authorization
		if(!$is_admin)
			throw new ActionException('Page', 'error404');
		
		$group = array();
		
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
				Routes::redirect('group', array('group' => $url_name));
				
			}catch(FormException $e){
				foreach($uploaded_files as $uploaded_file)
					File::delete($uploaded_file);
				foreach($data as $key => $value)
					$group[$key] = $value;
				
				$group['members'] = Student_Model::getInfoByUsersIds(array_keys($members));
				foreach($group['members'] as &$member){
					if(isset($members[(int) $member['user_id']])){
						$member['title'] = $members[(int) $member['user_id']]['title'];
						$member['admin'] = $members[(int) $member['user_id']]['admin'] ? '1' : '0';
					}
				}
				
				$this->set('form_error', $e->getError());
			}
		}
		
		
		$this->set('group', $group);
		$this->addJSCode('Group.initEdit();');
		
	}
	
	
	/**
	 * Delete a group
	 */
	public function delete($params){
		$this->setView('delete.php');
		
		$is_logged = isset(User_Model::$auth_data);
		$is_admin = $is_logged && User_Model::$auth_data['admin']=='1';
		
		try {
			if(!$is_logged)
				throw new Exception();
			$group = $this->model->getInfoByName($params['group']);
		}catch(Exception $e){
			throw new ActionException('Page', 'error404');
		}
		
		$this->setTitle(__('GROUP_DELETE_TITLE'));
		
		// Authorization
		$groups_auth = Group_Model::getAuth();
		if(!$is_admin && !(isset($groups_auth[(int) $group['id']]) && $groups_auth[(int) $group['id']]['admin']))
			throw new ActionException('Page', 'error404');
		
		$this->set('group_name', $group['name']);
		$this->model->delete((int) $group['id']);
		
	}
	
}
