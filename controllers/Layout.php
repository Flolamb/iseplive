<?php

class Layout_Controller extends Controller {
	
	public function __beforeAction(){
		
		// User authentication
		$user_model = new User_Model();
		User_Model::$auth_status = User_Model::AUTH_STATUS_NOT_LOGGED;
		
		// Authentication by post
		if(isset($_POST['username']) && isset($_POST['password'])){
			$username = $_POST['username'];
			$password = $_POST['password'];
			try {
				if(!preg_match('#^[a-z0-9-]+$#', $username))
					throw new Exception('Invalid username');
				if($user_model->authenticate($username, $password)){
					User_Model::$auth_status = User_Model::AUTH_STATUS_LOGGED;
					// Write session and cookie to remember sign-in
					Cookie::write('login', Encryption::encode($username.':'.$password), 60*24*3600);
					Session::write('username', $username);
					
				}else{
					throw new Exception('Bad username or password');
				}
				
			}catch(Exception $e){
				User_Model::$auth_status = User_Model::AUTH_STATUS_BAD_USERNAME_OR_PASSWORD;
				Cookie::delete('login');
				Session::delete('username');
			}
		
		}else{
			
			// Authentication by session
			if(($username = Session::read('username')) !== null){
				try {
					$user_model->loadUser($username);
					User_Model::$auth_status = User_Model::AUTH_STATUS_LOGGED;
				}catch(Exception $e){
					Session::delete('username');
					Cookie::delete('login');
				}
			
			// Authentication by cookies
			}else if(($login = Cookie::read('login')) !== null){
				try {
					if(isset($login) && $login = Encryption::decode($login)){
						$login = explode(':', $login);
						$username = $login[0];
						if(!preg_match('#^[a-z0-9-]+$#', $username))
							throw new Exception('Invalid username');
						array_splice($login, 0, 1);
						$password = implode(':', $login);
						if($user_model->authenticate($username, $password)){
							User_Model::$auth_status = User_Model::AUTH_STATUS_LOGGED;
							// Write session to remember sign-in
							Session::write('username', $username);
						}else{
							throw new Exception('Bad username or password');
						}
					}else{
						throw new Exception('Invalid user cookie');
					}
				}catch(Exception $e){
					Cookie::delete('login');
				}
			}
			
		}
		
	}
	
	
	
	// Standard mode
	public function index(){
		$this->setView('index.php');
		
		// JS
		if(File::exists(Config::DIR_APP_STATIC.'js/script.js')){
			$this->addJSFile(Config::URL_STATIC.'js/script.js');
		}else{
			$files = glob(Config::DIR_APP_STATIC.'js/[0-9]-*.js');
			foreach($files as $file)
				$this->addJSFile(Config::URL_STATIC.'js/'.substr($file, strrpos($file, '/')+1));
		}
		
		$this->addJSFile($this->specificController->jsFiles);
		$this->addJSCode($this->specificController->jsCode);
		
		// CSS
		if(File::exists(Config::DIR_APP_STATIC.'css/style.css')){
			$this->addCSSFile(Config::URL_STATIC.'css/style.css');
		}else{
			$files = glob(Config::DIR_APP_STATIC.'css/[0-9]-*.css');
			foreach($files as $file)
				$this->addCSSFile(Config::URL_STATIC.'css/'.substr($file, strrpos($file, '/')+1));
		}
		if(Config::DEBUG)
			$this->addCSSFile(Config::URL_STATIC.'css/debug.css');
		$this->addCSSFile($this->specificController->cssFiles);
		
		$this->set(array(
			'jsFiles'			=> & $this->jsFiles,
			'jsCode'			=> & $this->jsCode,
			'cssFiles'			=> & $this->cssFiles
		));
		
		$is_logged = isset(User_Model::$auth_data);
		$is_student = $is_logged && isset(User_Model::$auth_data['student_number']);
		$this->set(array(
			'is_logged'			=> $is_logged,
			'is_student'		=> $is_student
		));
		if($is_student)
			$this->set('username', User_Model::$auth_data['username']);
		
	}
	
	// Print mode
	public function printmode(){
		$this->setView('printmode.php');
	}
	
	// Raw
	public function raw(){
		$this->setView('raw.php');
	}
	
	// XML mode
	public function xml(){
		$this->setView('xml.php');
		header('Content-Type: text/xml; charset=utf-8');
	}
	
	// JSON mode
	public function json(){
		$this->setView('json.php');
		header('Content-Type: application/json; charset=utf-8');
	}
	
	// Iframe mode
	public function iframe(){
		$this->setView('iframe.php');
		
		// JS
		$this->addJSFile($this->specificController->jsFiles);
		$this->addJSCode($this->specificController->jsCode);
		$this->set(array(
			'jsFiles'			=> & $this->jsFiles,
			'jsCode'			=> & $this->jsCode
		));
	}
	
	// Rendering the specific view
	public function __renderContent(){
		$this->specificController->render();
	}
}
