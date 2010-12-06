<?php

class Post_Controller extends Controller {
	
	/*
	 * Show the posts
	 */
	public function index($params){
		$this->setView('index.php');
		
		$is_logged = isset(User_Model::$auth_data);
		$is_student = $is_logged && isset(User_Model::$auth_data['student_number']);
		$is_admin = $is_logged && User_Model::$auth_data['admin']=='1';
		$category = isset($params['category']) ? $params['category'] : null;
		
		$category_model = new Category_Model();
		
		$this->set(array(
			'is_logged'			=> $is_logged,
			'is_student'		=> $is_student,
			'is_admin'			=> $is_admin,
			'categories'		=> $category_model->getAll(),
			'current_category'	=> $category
		));
		
		// If the user is logged
		if($is_logged){
			
			$this->set(array(
				'username'			=> User_Model::$auth_data['username'],
				'firstname'			=> User_Model::$auth_data['firstname'],
				'lastname'			=> User_Model::$auth_data['lastname'],
				'associations_auth'	=> Association_Model::getAuth(),
				// Non-official posts
				'posts'				=> $this->model->getPosts(array(
					'restricted'	=> true,
					'official'		=> false,
					'category_name'	=> $category,
					'show_private'	=> $is_student
				), Config::POST_DISPLAYED)
			));
			
			if($is_student){
				$this->set('avatar_url', User_Model::getAvatarURL(User_Model::$auth_data['student_number'], true));
			}
		
		}
		
		// Official posts
		$this->set('official_posts', $this->model->getPosts(array(
			'restricted'	=> true,
			'official'		=> true,
			'category_name'	=> $category,
			'show_private'	=> $is_student
		), Config::POST_DISPLAYED));
		
		// Events
		$event_model = new Event_Model();
		$this->set(array(
			'events' 			=> $event_model->getByMonth((int) date('Y'), (int) date('n'), array(
				'official'			=> $is_logged ? null : true,
				'show_private'		=> $is_student
			)),
			'calendar_month'	=> (int) date('n'),
			'calendar_year'		=> (int) date('Y')
		));
	}
	
	
	/*
	 * Show the posts by Ajax
	 */
	public function index_ajax($params){
		$this->setView('index_ajax.php');
		
		$is_logged = isset(User_Model::$auth_data);
		$is_student = $is_logged && isset(User_Model::$auth_data['student_number']);
		$is_admin = $is_logged && User_Model::$auth_data['admin']=='1';
		
		$category = isset($params['category']) ? $params['category'] : null;
		$offset = (((int) $params['page']) - 1) * Config::POST_DISPLAYED;
		
		$this->set(array(
			'is_logged'			=> $is_logged,
			'is_student'		=> $is_student,
			'is_admin'			=> $is_admin
		));
		
		// If the user is logged in
		if($is_logged){
			
			$this->set(array(
				'username'			=> User_Model::$auth_data['username'],
				'firstname'			=> User_Model::$auth_data['firstname'],
				'lastname'			=> User_Model::$auth_data['lastname'],
				'associations_auth'	=> Association_Model::getAuth()
			));
			
			// Non-official posts
			if(isset($params['official']) && $params['official'] == '0')
				$this->set('posts', $this->model->getPosts(array(
					'restricted'	=> true,
					'official'		=> false,
					'category_name'	=> $category,
					'show_private'	=> $is_student
				), Config::POST_DISPLAYED, $offset));
			
			
			if($is_student){
				$this->set('avatar_url', User_Model::getAvatarURL(User_Model::$auth_data['student_number'], true));
			}
		
		}
		
		// Official posts
		if(!isset($params['official']) && isset($params['association'])){
			$this->set('posts', $this->model->getPosts(array(
				'restricted'	=> true,
				'association_name'	=> $params['association'],
				'category_name'		=> $category,
				'show_private'		=> $is_student
			), Config::POST_DISPLAYED, $offset));
			
		}else if($params['official'] == '1'){
			$this->set('posts', $this->model->getPosts(array(
				'restricted'	=> true,
				'official'		=> true,
				'category_name'	=> $category,
				'show_private'	=> $is_student
			), Config::POST_DISPLAYED, $offset));
			
		}else if(!$is_logged){
			throw new Exception('You must be logged');
		}
	}
	
	
	/*
	 * Show a post
	 */
	public function view($params){
		$this->setView('view.php');
		
		$is_logged = isset(User_Model::$auth_data);
		$is_student = $is_logged && isset(User_Model::$auth_data['student_number']);
		$is_admin = $is_logged && User_Model::$auth_data['admin']=='1';
		
		try {
			$post = $this->model->getPost((int) $params['id'], $is_logged ? null : true, $is_student);
			if(!$is_logged && $post['official'] == '0')
				throw new Exception('You must be logged');
			if(!$is_student && $post['private'] == '1')
				throw new Exception('You must be a student');
		}catch(Exception $e){
			throw new ActionException('Page', 'error404');
		}
		
		$this->set(array(
			'is_logged'		=> $is_logged,
			'is_student'	=> $is_student,
			'is_admin'		=> $is_admin,
			'associations_auth'	=> Association_Model::getAuth(),
			'post'			=> $post,
			'one_post'		=> true
		));
		
		if($is_logged){
			$this->set(array(
				'username'		=> User_Model::$auth_data['username'],
				'firstname'		=> User_Model::$auth_data['firstname'],
				'lastname'		=> User_Model::$auth_data['lastname'],
				'avatar_url'	=> User_Model::getAvatarURL(User_Model::$auth_data['student_number'], true)
			));
		}
		
		if($post['attachments_nb_photos'] != 0){
			$photos = array();
			foreach($post['attachments'] as $attachment){
				if(in_array($attachment['ext'], array('jpg', 'png', 'gif')))
					$photos[] = array(
						'id'	=> (int) $attachment['id'],
						'url'	=> $attachment['url']
					);
			}
			$this->addJSCode('Post.photos = '.json_encode($photos).';');
		}
		
	}
	
	
	/*
	 * Show a post with events of a day
	 */
	public function events($params){
		$this->setView('events.php');
		
		$is_logged = isset(User_Model::$auth_data);
		$is_student = $is_logged && isset(User_Model::$auth_data['student_number']);
		$is_admin = $is_logged && User_Model::$auth_data['admin']=='1';
		
		// Association
		if(isset($params['association'])){
			try {
				$association_model = new Association_Model();
				$association = $association_model->getInfoByName($params['association']);
				$this->set('association', $association);
				
			}catch(Exception $e){
				throw new ActionException('Page', 'error404');
			}
		}
		
		$year = (int) $params['year'];
		$month = (int) $params['month'];
		$day = isset($params['day']) ? (int) $params['day'] : null;
		
		$event_model = new Event_Model();
		$events = $event_model->getByMonth($year, $month, array(
			'association_id'	=> isset($association) ? $association['id'] : null,
			'official'			=> $is_logged ? null : true,
			'show_private'		=> $is_student
		));
		
		$post_ids = array();
		if(isset($day)){
			$day_time = mktime(0, 0, 0, $month, $day, $year);
			for($j = 0; $j < count($events); $j++){
				$event = & $events[$j];
				if(($event['date_start'] >= $day_time && $event['date_start'] <= $day_time+86400-1)
					|| ($event['date_end'] >= $day_time && $event['date_end'] <= $day_time+86400-1 && !(date('H', $event['date_end']) < 12 && date('Y-m-d', $event['date_end']) !=date('Y-m-d', $event['date_start'])))){
					$post_ids[] = (int) $event['post_id'];
				}
			}
		}else{
			foreach($events as &$event)
				$post_ids[] = (int) $event['post_id'];
		}
		
		$this->set(array(
			'is_logged'		=> $is_logged,
			'is_student'	=> $is_student,
			'is_admin'		=> $is_admin,
			'associations_auth'	=> Association_Model::getAuth(),
			'posts'			=> count($post_ids)==0 ? array() : $this->model->getPosts(array(
				'restricted'		=> true,
				'show_private'		=> $is_student,
				'ids'				=> $post_ids
			), 1000, 0),
			'events' 			=> $events,
			'calendar_month'	=> $month,
			'calendar_year'		=> $year,
			'day_time'			=> isset($day_time) ? $day_time : null
		));
		
		if($is_logged){
			$this->set(array(
				'username'		=> User_Model::$auth_data['username'],
				'firstname'		=> User_Model::$auth_data['firstname'],
				'lastname'		=> User_Model::$auth_data['lastname'],
				'avatar_url'	=> User_Model::getAvatarURL(User_Model::$auth_data['student_number'], true)
			));
		}
		
	}
	
	
	
	/**
	 * Add a post
	 */
	public function iframe_add(){
		$this->setView('iframe_add.php');
		
		$uploaded_files = array();
		try {
			if(!isset(User_Model::$auth_data))
				throw new Exception(__('POST_ADD_ERROR_SESSION_EXPIRED'));
			$is_student = isset(User_Model::$auth_data['student_number']);
			
			// Message
			$message = isset($_POST['message']) ? trim($_POST['message']) : '';
			if($message == '' || $message == __('PUBLISH_DEFAULT_MESSAGE'))
				throw new Exception(__('POST_ADD_ERROR_NO_MESSAGE'));
			$message = preg_replace('#\n{2,}#', "\n\n", $message);
			
			// Category
			if(!isset($_POST['category']) || !ctype_digit($_POST['category']))
				throw new Exception(__('POST_ADD_ERROR_NO_CATEGORY'));
			$category = (int) $_POST['category'];
			
			// Official post (in an association)
			$official = isset($_POST['official']);
			
			// Association
			$association = isset($_POST['association']) && ctype_digit($_POST['association']) ? (int) $_POST['association'] : 0;
			if($association == 0){
				$association = null;
				$official = false;
			}else{
				$associations_auth = Association_Model::getAuth();
				if(isset($associations_auth[$association])){
					if($official && !$associations_auth[$association]['admin'])
						throw new Exception(__('POST_ADD_ERROR_OFFICIAL'));
				}else{
					throw new Exception(__('POST_ADD_ERROR_ASSOCIATION_NOT_FOUND'));
				}
			}
			
			// Private message
			$private = isset($_POST['private']);
			if($private && !$is_student)
				throw new Exception(__('POST_ADD_ERROR_PRIVATE'));
			
			
			$attachments = array();
			
			// Photos
			if(isset($_FILES['attachment_photo']) && is_array($_FILES['attachment_photo']['name'])){
				foreach($_FILES['attachment_photo']['size'] as $size){
					if($size > Config::UPLOAD_MAX_SIZE_PHOTO)
						throw new Exception(__('POST_ADD_ERROR_PHOTO_SIZE', array('size' => File::humanReadableSize(Config::UPLOAD_MAX_SIZE_PHOTO))));
				}
				if($filepaths = File::upload('attachment_photo')){
					foreach($filepaths as $filepath)
						$uploaded_files[] = $filepath;
					foreach($filepaths as $i => $filepath){
						$name = isset($_FILES['attachment_photo']['name'][$i]) ? $_FILES['attachment_photo']['name'][$i] : '';
						try {
							$img = new Image();
							$img->load($filepath);
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
							$img->save($filepath);
							
							// Thumb
							$thumbpath = $filepath.'.thumb';
							$img->thumb(Config::$THUMBS_SIZES[0], Config::$THUMBS_SIZES[1]);
							$img->setType(IMAGETYPE_JPEG);
							$img->save($thumbpath);
							
							unset($img);
							$attachments[] = array($filepath, $name, $thumbpath);
							$uploaded_files[] = $thumbpath;
							
						}catch(Exception $e){
							throw new Exception(__('POST_ADD_ERROR_PHOTO_FORMAT'));
						}
					}
				}
			}
			
			// Vidéos
			/* @uses PHPVideoToolkit : http://code.google.com/p/phpvideotoolkit/
			 * @requires ffmpeg, php5-ffmpeg
			 */
			if(isset($_FILES['attachment_video']) && is_array($_FILES['attachment_video']['name'])){
				foreach($_FILES['attachment_video']['size'] as $size){
					if($size > Config::UPLOAD_MAX_SIZE_VIDEO)
						throw new Exception(__('POST_ADD_ERROR_VIDEO_SIZE', array('size' => File::humanReadableSize(Config::UPLOAD_MAX_SIZE_VIDEO))));
				}
				if($filepaths = File::upload('attachment_video')){
					foreach($filepaths as $filepath)
						$uploaded_files[] = $filepath;
					foreach($filepaths as $i => $filepath){
						$name = isset($_FILES['attachment_video']['name'][$i]) ? $_FILES['attachment_video']['name'][$i] : '';
						try {
							$video = new ffmpeg_movie($filepath, false);
							if(!$video->hasVideo())
								throw new Exception('No video stream found in the file');
							if(!$video->hasAudio())
								throw new Exception('No audio stream found in the file');
						}catch(Exception $e){
							throw new Exception(__('POST_ADD_ERROR_VIDEO_FORMAT'));
						}
						// Video conversion
						try {
							$video_current_width = $video->getFrameWidth();
							$video_width = min($video_current_width, Config::VIDEO_MAX_WIDTH);
							if($video_width % 2 == 1)	// Even number required
								$video_width--;
							$video_height = $video_width * $video->getFrameHeight() / $video_current_width;
							if($video_height % 2 == 1)	// Even number required
								$video_height--;
							
							// Extract thumb
							$video_thumb = $video->getFrame(round($video->getFrameCount()*0.2));
							unset($video);
							$video_thumb = $video_thumb->toGDImage();
							$thumbpath = DATA_DIR.Config::DIR_DATA_TMP.File::getName($filepath).'.thumb';
							imagejpeg($video_thumb, $thumbpath, 95);
							unset($video_thumb);
							$img = new Image();
							$img->load($thumbpath);
							$img->setWidth($video_width, true);
							$img->setType(IMAGETYPE_JPEG);
							$img->save($thumbpath);
							$uploaded_files[] = $thumbpath;
							unset($img);
							
							// Convert to FLV
							$toolkit = new PHPVideoToolkit();
							$toolkit->on_error_die = true;	// Will throw exception on error
							$toolkit->setInputFile($filepath);
							$toolkit->setVideoOutputDimensions($video_width, $video_height);
							$toolkit->setFormatToFLV(Config::VIDEO_SAMPLING_RATE, Config::VIDEO_AUDIO_BIT_RATE);
							$toolkit->setOutput(DATA_DIR.Config::DIR_DATA_TMP, File::getName($filepath).'.flv', PHPVideoToolkit::OVERWRITE_EXISTING);
							$toolkit->execute(false, false);	// Multipass: false, Log: false
							
							File::delete($filepath);
							$filepath = $toolkit->getLastOutput();
							
							unset($toolkit);
							$attachments[] = array($filepath[0], $name, $thumbpath);
							$uploaded_files[] = $filepath[0];
							
						}catch(Exception $e){
							throw new Exception(__('POST_ADD_ERROR_VIDEO_CONVERT').$e->getMessage());
						}
					}
				}
			}
			
			
			// Audios
			if(isset($_FILES['attachment_audio']) && is_array($_FILES['attachment_audio']['name'])){
				foreach($_FILES['attachment_audio']['size'] as $size){
					if($size > Config::UPLOAD_MAX_SIZE_AUDIO)
						throw new Exception(__('POST_ADD_ERROR_AUDIO_SIZE', array('size' => File::humanReadableSize(Config::UPLOAD_MAX_SIZE_AUDIO))));
				}
				if($filepaths = File::upload('attachment_audio')){
					foreach($filepaths as $filepath)
						$uploaded_files[] = $filepath;
					foreach($filepaths as $i => $filepath){
						if(!preg_match('#\.mp3$#', $filepath))
							throw new Exception(__('POST_ADD_ERROR_AUDIO_FORMAT'));
						
						$name = isset($_FILES['attachment_audio']['name'][$i]) ? $_FILES['attachment_audio']['name'][$i] : '';
						$attachments[] = array($filepath, $name);
					}
				}
			}
			
			
			// Files
			if(isset($_FILES['attachment_file']) && is_array($_FILES['attachment_file']['name'])){
				foreach($_FILES['attachment_file']['size'] as $size){
					if($size > Config::UPLOAD_MAX_SIZE_FILE)
						throw new Exception(__('POST_ADD_ERROR_FILE_SIZE', array('size' => File::humanReadableSize(Config::UPLOAD_MAX_SIZE_FILE))));
				}
				if($filepaths = File::upload('attachment_file')){
					foreach($filepaths as $filepath)
						$uploaded_files[] = $filepath;
					foreach($filepaths as $i => $filepath){
						if(!preg_match('#\.[a-z0-9]{2,4}$#i', $filepath))
							throw new Exception(__('POST_ADD_ERROR_FILE_FORMAT'));
						if(preg_match('#\.(jpg|png|gif|mp3|flv)$#i', $filepath))
							throw new Exception(__('POST_ADD_ERROR_FILE_FORMAT2'));
						
						$name = isset($_FILES['attachment_file']['name'][$i]) ? $_FILES['attachment_file']['name'][$i] : '';
						$attachments[] = array($filepath, $name);
					}
				}
			}
			
			
			// Event
			if(isset($_POST['event_title']) && isset($_POST['event_start']) && isset($_POST['event_end'])){
				// Title
				$event_title = trim($_POST['event_title']);
				if($event_title == '')
					throw new Exception(__('POST_ADD_ERROR_EVENT_NO_TITLE'));
				
				// Dates
				if(!($event_start = strptime($_POST['event_start'], __('PUBLISH_EVENT_DATE_FORMAT'))))
					throw new Exception(__('POST_ADD_ERROR_EVENT_DATE'));
				if(!($event_end = strptime($_POST['event_end'], __('PUBLISH_EVENT_DATE_FORMAT'))))
					throw new Exception(__('POST_ADD_ERROR_EVENT_DATE'));
				
				$event_start = mktime($event_start['tm_hour'], $event_start['tm_min'], 0, $event_start['tm_mon']+1, $event_start['tm_mday'], $event_start['tm_year']+1900);
				$event_end = mktime($event_end['tm_hour'], $event_end['tm_min'], 0, $event_end['tm_mon']+1, $event_end['tm_mday'], $event_end['tm_year']+1900);
				
				if($event_start > $event_end)
					throw new Exception(__('POST_ADD_ERROR_EVENT_DATE_ORDER'));
				
				$event = array($event_title, $event_start, $event_end);
			}else{
				$event = null;
			}
			
			
			// Survey
			if(isset($_POST['survey_question']) && isset($_POST['survey_end']) && isset($_POST['survey_answer']) && is_array($_POST['survey_answer'])){
				// Question
				$survey_question = trim($_POST['survey_question']);
				if($survey_question == '')
					throw new Exception(__('POST_ADD_ERROR_SURVEY_NO_QUESTION'));
				
				// Date
				if(!($survey_end = strptime($_POST['survey_end'], __('PUBLISH_EVENT_DATE_FORMAT'))))
					throw new Exception(__('POST_ADD_ERROR_SURVEY_DATE'));
				
				$survey_end = mktime($survey_end['tm_hour'], $survey_end['tm_min'], 0, $survey_end['tm_mon']+1, $survey_end['tm_mday'], $survey_end['tm_year']+1900);
				
				// Multiple answers
				$survey_multiple = isset($_POST['survey_multiple']);
				
				// Answers
				$survey_answers = array();
				foreach($_POST['survey_answer'] as $survey_answer){
					$survey_answer = trim($survey_answer);
					if($survey_answer != '')
						$survey_answers[] = $survey_answer;
				}
				if(count($survey_answers) < 2)
					throw new Exception(__('POST_ADD_ERROR_SURVEY_ANSWERS'));
				
				$survey = array($survey_question, $survey_end, $survey_multiple, $survey_answers);
			}else{
				$survey = null;
			}
			
			
			// Creation of the post
			$id = $this->model->addPost((int) User_Model::$auth_data['id'], $message, $category, $association, $official, $private);
			
			
			// Attach files
			foreach($attachments as $attachment)
				$this->model->attachFile($id, $attachment[0], $attachment[1], isset($attachment[2]) ? $attachment[2] : null);
			
			// Event
			if(isset($event))
				$this->model->attachEvent($id, $event[0], $event[1], $event[2]);
			
			// Survey
			if(isset($survey))
				$this->model->attachSurvey($id, $survey[0], $survey[1], $survey[2], $survey[3]);
			
			
			$this->addJSCode('
				parent.location = "'. Config::URL_ROOT.Routes::getPage('home') .'";
			');
			
			
		}catch(Exception $e){
			// Delete all uploading files in tmp
			foreach($uploaded_files as $uploaded_file)
				File::delete($uploaded_file);
			
			$this->addJSCode('
				with(parent){
					Post.errorForm('.json_encode($e->getMessage()).');
				}
			');
		}
	}
	
	
	/**
	 * Delete a post
	 */
	public function delete($params){
		$this->setView('delete.php');
		
		try {
			$post = $this->model->getRawPost((int) $params['id']);
			
			$is_logged = isset(User_Model::$auth_data);
			$is_admin = $is_logged && User_Model::$auth_data['admin']=='1';
			$associations_auth = Association_Model::getAuth();
			
			if(($is_logged && User_Model::$auth_data['id'] == $post['user_id'])
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
