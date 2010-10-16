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
	
}
