<?php

class Student_Controller extends Controller {
	
	/**
	 * Show the profile of a student
	 */
	public function view($params){
		$this->setView('view.php');
		
		$is_logged = isset(User_Model::$auth_data);
		$is_admin = $is_logged && User_Model::$auth_data['admin']=='1';
		
		// If the user isn't logged in
		if(!$is_logged)
			throw new ActionException('User', 'signin', array('referrer' => $_SERVER['REQUEST_URI']));
		
		try {
			
			$student = $this->model->getInfo($params['username']);
			$this->set($student);
			
			// Avatar
			$this->set(array(
				'groups'	=> isset($student['id']) ? Group_Model::getAuth((int) $student['id']) : array(),
				'is_owner'	=> User_Model::$auth_data['username'] == $student['username'],
				'is_admin'	=> $is_admin
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
	
	
	/**
	 * Edit a user
	 */
	public function edit($params){
		$this->setView('edit.php');
		
		$is_logged = isset(User_Model::$auth_data);
		$is_admin = $is_logged && User_Model::$auth_data['admin']=='1';
		
		// Authorization
		if(!$is_admin)
			throw new ActionException('Page', 'error404');
		
		try {
			$student = $this->model->getInfo($params['username']);
		}catch(Exception $e){
			throw new ActionException('Page', 'error404');
		}
		
		// Birthday
		$student['birthday'] = date(__('USER_EDIT_FORM_BIRTHDAY_FORMAT'), strtotime($student['birthday']));
		
		// Saving data
		if(isset($_POST['mail']) && isset($_POST['msn']) && isset($_POST['jabber'])
		&& isset($_POST['address']) && isset($_POST['zipcode']) && isset($_POST['city'])
		&& isset($_POST['cellphone']) && isset($_POST['phone']) && isset($_POST['birthday'])
		&& isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['student_number'])
		&& isset($_POST['promo'])){
			
			$uploaded_files = array();
			try {
				
				// Other info
				$user_data = array(
					'mail'		=> $_POST['mail'],
					'msn'		=> $_POST['msn'],
					'jabber'	=> $_POST['jabber'],
					'address'	=> $_POST['address'],
					'zipcode'	=> $_POST['zipcode'],
					'city'		=> $_POST['city'],
					'cellphone'	=> $_POST['cellphone'],
					'phone'		=> $_POST['phone'],
					'birthday'	=> $_POST['birthday']
				);
				$student_data = array(
					'firstname'			=> $_POST['firstname'],
					'lastname'			=> $_POST['lastname'],
					'student_number'	=> $_POST['student_number'],
					'promo'				=> $_POST['promo'],
					'cesure'			=> isset($_POST['cesure'])
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
							
							$student_data['avatar_path'] = $avatarthumbpath;
							$student_data['avatar_big_path'] = $avatarpath;
							
						}catch(Exception $e){
							throw new FormException('avatar');
						}
					}
				}
				
				$user_model = new User_Model();
				$user_model->save((int) $student['id'], $user_data);
				$this->model->save($student['username'], $student_data);
				Routes::redirect('student', array('username' => $params['username']));
				
			}catch(FormException $e){
				foreach($uploaded_files as $uploaded_file)
					File::delete($uploaded_file);
				foreach($student_data as $key => $value)
					$student[$key] = $value;
				foreach($user_data as $key => $value)
					$student[$key] = $value;
				
				$this->set('form_error', $e->getError());
			}
		}
		
		$this->set('student', $student);
		$this->addJSCode('User.initEdit();');
		
	}
	
}
