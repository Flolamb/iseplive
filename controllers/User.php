<?php

class User_Controller extends Controller {
	
	/**
	 * Sign-in form
	 */
	public function signin($params){
		
		$this->setView('signin.php');
		
		// Successful
		if(User_Model::$auth_status == User_Model::AUTH_STATUS_LOGGED){
			header('Location: '.$params['redirect']);
			exit;
		}
		
		$this->set('signin_redirect', $params['redirect']);
	}
	
	/**
	 * Logout
	 */
	public function logout($params){
		Cookie::delete('login');
		Session::delete('username');
		
		header('Location: '.$params['redirect']);
		exit;
	}
	
	
	/**
	 * Edit personnal information
	 */
	public function profile_edit($params){
		$this->setView('profile_edit.php');
		$this->setTitle(__('USER_EDIT_TITLE'));
		
		$is_logged = isset(User_Model::$auth_data);
		$is_student = $is_logged && isset(User_Model::$auth_data['student_number']);
		
		// Authorization
		if(!$is_student)
			throw new ActionException('Page', 'error404');
		
		$user = User_Model::$auth_data;
		
		// Birthday
		$user['birthday'] = date(__('USER_EDIT_FORM_BIRTHDAY_FORMAT'), strtotime($user['birthday']));
		
		// Saving data
		if(isset($_POST['mail']) && isset($_POST['msn']) && isset($_POST['jabber'])
		&& isset($_POST['address']) && isset($_POST['zipcode']) && isset($_POST['city'])
		&& isset($_POST['cellphone']) && isset($_POST['phone']) && isset($_POST['birthday'])){
			
			try {
				
				// Other info
				$data = array(
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
				
				$this->model->save((int) User_Model::$auth_data['id'], $data);
				Routes::redirect('student', array('username' => User_Model::$auth_data['username']));
				
			}catch(FormException $e){
				foreach($data as $key => $value)
					$user[$key] = $value;
				
				$this->set('form_error', $e->getError());
			}
		}
		
		$this->set('user', $user);
		$this->addJSCode('User.initEdit();');
		
	}
	
}
