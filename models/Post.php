<?php

class Post_Model extends Model {
	
	/**
	 * Returns the information of the N last posts, with attachments, surveys, events...
	 * 
	 * @param array $params	Associative array of paramaters. Possibles keys :
	 *							* official: Only official posts if true, only non-official posts if false, all posts if null
	 *							* show_private: Private posts include if true
	 *							* category_id: Category's id
	 *							* category_name: Category's name
	 *							* group_id: Group's id
	 *							* group_name: Group's name
	 *							* user_id: User's id
	 *							* id: ID of a post to get
	 *							* ids: List of IDs of post to get
	 *							* restricted: If true, limits the number of photos displayed
	 * @param int $limit	Number of posts to be returned
	 * @param int $offset	Number of posts to skip
	 * @return array
	 */
	public function getPosts($params, $limit, $offset=0){
		$cache_entry = 'posts-'.$limit.'-'.$offset;
		foreach($params as $key => $value){
			if(isset($value))
				$cache_entry .= '-'.$key.':'.(is_array($value) ? implode(',', $value) : $value);
		}
		$posts = Cache::read($cache_entry);
		if($posts !== false)
			return $posts;
		
		$where = array();
		if(isset($params['group_id']))
			$where[] = 'p.group_id = '.$params['group_id'];
		if(isset($params['group_name']))
			$where[] = 'a.url_name = '.DB::quote($params['group_name']);
		if(isset($params['official']))
			$where[] = 'p.official = '.($params['official'] ? 1 : 0);
		if(!isset($params['show_private']) || !$params['show_private'])
			$where[] = 'p.private = 0';
		if(isset($params['category_id']))
			$where[] = 'c.id = "'.$params['category_id'].'"';
		if(isset($params['category_name']))
			$where[] = 'c.url_name = '.DB::quote($params['category_name']);
		if(isset($params['user_id']))
			$where[] = 'p.user_id = '.DB::quote($params['user_id']);
		if(isset($params['ids']) && is_array($params['ids'])){
			if(count($params['ids']) == 0)
				return array();
			$where[] = 'p.id IN ('.implode(',', $params['ids']).')';
		}
		if(isset($params['id']) && (is_int($params['id']) || ctype_digit($params['id'])))
			$where[] = 'p.id = '.$params['id'];
		
		$posts = DB::select('
			SELECT
				p.id, p.message, p.time, p.private, p.official,
				a.id AS group_id, a.name AS group_name, a.url_name AS group_url,
				u.username,
				s.student_number, s.firstname, s.lastname
			FROM posts p
			INNER JOIN categories c ON c.id = p.category_id
			INNER JOIN users u ON u.id = p.user_id
			'.(isset($params['group_id']) || isset($params['group_name']) ? 'INNER' : 'LEFT').' JOIN groups a ON a.id = p.group_id
			LEFT JOIN students s ON s.username = u.username
			'.(count($where) != 0 ? 'WHERE '.implode(' AND ', $where) : '').'
			ORDER BY p.time DESC
			LIMIT '.$offset.', '.$limit.'
		');
		
		if(count($posts) != 0){
			
			if(isset($params['ids']) && is_array($params['ids']))
				Utils::arraySort($posts, 'id', $params['ids']);
			
			$post_ids = array();
			foreach($posts as $post)
				$post_ids[] = (int) $post['id'];
			
			
			// Comments
			$comments = DB::select('
				SELECT
					pc.post_id, pc.id, pc.message, pc.time, pc.attachment_id,
					u.username,
					s.student_number, s.firstname, s.lastname
				FROM post_comments pc
				INNER JOIN users u ON u.id = pc.user_id
				INNER JOIN students s ON s.username = u.username
				WHERE pc.post_id IN ('.implode(',', $post_ids).')
				'.(isset($params['restricted']) && $params['restricted'] ? 'AND pc.attachment_id IS NULL' : '').'
				ORDER BY pc.time ASC
			');
			$comments_by_post_id = array();
			foreach($comments as $comment){
				$post_id = (int) $comment['post_id'];
				if(!isset($comments_by_post_id[$post_id]))
					$comments_by_post_id[$post_id] = array();
				unset($comment['post_id']);
				$comment['avatar_url'] = Student_Model::getAvatarURL($comment['student_number'], true);
				$comments_by_post_id[$post_id][] = $comment;
			}
			unset($comments);
			
			// Attachments
			$attachments = DB::select('
				SELECT post_id, id, name, ext
				FROM attachments
				WHERE post_id IN ('.implode(',', $post_ids).')
				ORDER BY ext
			');
			$attachments_by_post_id = array();
			$nb_photos_by_post_id = array();
			foreach($attachments as $attachment){
				$post_id = (int) $attachment['post_id'];
				
				// Limitation of the number of displayed photos
				if(in_array($attachment['ext'], array('jpg', 'png', 'gif'))){
					if(!isset($nb_photos_by_post_id[$post_id]))
						$nb_photos_by_post_id[$post_id] = 0;
					$nb_photos_by_post_id[$post_id]++;
					if(isset($params['restricted']) && $params['restricted'] && $nb_photos_by_post_id[$post_id] > Config::PHOTOS_PER_POST)
						continue;
				}
				
				$attachment['url'] = self::getAttachedFileURL((int) $attachment['id'], $attachment['ext']);
				if(in_array($attachment['ext'], array('jpg', 'png', 'gif', 'flv')))
					$attachment['thumb'] = self::getAttachedFileURL((int) $attachment['id'], 'jpg', 'thumb');
				
				if(!isset($attachments_by_post_id[$post_id]))
					$attachments_by_post_id[$post_id] = array();
				unset($attachment['post_id']);
				$attachments_by_post_id[$post_id][] = $attachment;
			}
			unset($attachments);
			
			// Events
			$events = DB::select('
				SELECT post_id, id, title, date_start, date_end
				FROM events
				WHERE post_id IN ('.implode(',', $post_ids).')
			');
			$events_by_post_id = array();
			foreach($events as $event){
				$post_id = (int) $event['post_id'];
				unset($event['post_id']);
				$events_by_post_id[$post_id] = $event;
			}
			unset($events);
			
			// Surveys
			$surveys = DB::select('
				SELECT post_id, id, question, multiple, date_end
				FROM surveys
				WHERE post_id IN ('.implode(',', $post_ids).')
			');
			$surveys_by_post_id = array();
			if(count($surveys) != 0){
				$surveys_ids = array();
				foreach($surveys as $survey)
					$surveys_ids[] = (int) $survey['id'];
				$survey_answers = DB::select('
					SELECT id, survey_id, answer, nb_votes, votes 
					FROM survey_answers
					WHERE survey_id IN ('.implode(',', $surveys_ids).')
					ORDER BY id ASC
				');
				$survey_answers_by_survey_id = array();
				foreach($survey_answers as $survey_answer){
					$survey_id = (int) $survey_answer['survey_id'];
					unset($survey_answer['survey_id']);
					if(!isset($survey_answers_by_survey_id[$survey_id]))
						$survey_answers_by_survey_id[$survey_id] = array();
					$survey_answers_by_survey_id[$survey_id][] = $survey_answer;
				}
				unset($survey_answers);
				foreach($surveys as $survey){
					$post_id = (int) $survey['post_id'];
					unset($survey['post_id']);
					$survey['answers'] = isset($survey_answers_by_survey_id[(int) $survey['id']]) ? $survey_answers_by_survey_id[(int) $survey['id']] : array();
					$surveys_by_post_id[$post_id] = $survey;
				}
				unset($survey_answers_by_survey_id);
			}
			unset($surveys);
			
			foreach($posts as &$post){
				$post_id = (int) $post['id'];
				if(isset($comments_by_post_id[$post_id]))
					$post['comments'] = & $comments_by_post_id[$post_id];
				if(isset($attachments_by_post_id[$post_id]))
					$post['attachments'] = & $attachments_by_post_id[$post_id];
				if(isset($events_by_post_id[$post_id]))
					$post['event'] = & $events_by_post_id[$post_id];
				if(isset($surveys_by_post_id[$post_id]))
					$post['survey'] = & $surveys_by_post_id[$post_id];
				$post['attachments_nb_photos'] = isset($nb_photos_by_post_id[$post_id]) ? $nb_photos_by_post_id[$post_id] : 0;
				
				// Avatar
				if(isset($post['group_id']) && $post['official']=='1')
					$post['avatar_url'] = Group_Model::getAvatarURL((int) $post['group_id'], true);
				else if(isset($post['student_number']))
					$post['avatar_url'] = Student_Model::getAvatarURL((int) $post['student_number'], true);
			}
			
		}
		
		// Write the cache
		Cache::write($cache_entry, $posts, 20*60);
		$cache_list = Cache::read('posts-cachelist');
		if(!$cache_list)
			$cache_list = array();
		if(!in_array($cache_entry, $cache_list))
			$cache_list[] = $cache_entry;
		Cache::write('posts-cachelist', $cache_list, 20*60);
		
		return $posts;
	}
	
	
	/**
	 * Returns the information of a post, with attachments, surveys, events...
	 * 
	 * @param int $id				Id of the post
	 * @return array
	 */
	public function getPost($id){
		$posts = $this->getPosts(array(
			'id'			=> $id,
			'show_private'	=> true
		), 1, 0);
		if(!isset($posts[0]))
			throw new Exception('Post not found');
		return $posts[0];
	}
	
	/**
	 * Returns the information of a post
	 * 
	 * @param int $id				Id of the post
	 * @return array
	 */
	public function getRawPost($id){
		$posts = $this->createQuery()->select($id);
		if(!isset($posts[0]))
			throw new Exception('Post not found');
		return $posts[0];
	}
	
	
	/**
	 * Add a new post
	 *
	 * @param int $user_id			User's id (relative to the users table)
	 * @param string $message		Message
	 * @param int $category_id		Category's id (relative to the categories table)
	 * @param int $group_id	Group's id (relative to the groups table)
	 * @param boolean $official		If true, the message is official in a group
	 * @param boolean $private		If true, the message will be visible only to the students
	 * @return int	Id of the new post
	 */
	public function addPost($user_id, $message, $category_id, $group_id, $official, $private){
		$id = $this->createQuery()
			->set(array(
				'user_id'		=> $user_id,
				'message'		=> $message,
				'time'			=> time(),
				'category_id'	=> $category_id,
				'group_id'		=> $group_id,
				'official'		=> $official ? 1 : 0,
				'private'		=> $private ? 1 : 0
			))
			->insert();
		
		self::clearCache();
		
		// Add to the search index
		$search_model = new Search_Model();
		$search_model->index(array(
			'message'	=> Search_Model::sanitize($message),
			'official'	=> $official,
			'private'	=> $private
		), 'post', $id);
		
		return $id;
	}
	
	/**
	 * Attach a file to a post
	 *
	 * @param int $post_id		Post's id
	 * @param string $filepath	Path of the tmp file
	 * @param string $thumbpath	Path of the thumb (optional)
	 */
	public function attachFile($post_id, $filepath, $name, $thumbpath=null){
		$ext = strtolower(File::getExtension($filepath));
		
		// In the DB
		$file_id = DB::createQuery('attachments')
			->set(array(
				'post_id'	=> $post_id,
				'name'		=> $name,
				'ext'		=> $ext
			))
			->insert();
		
		// File, and optionally thumb
		$newfilepath = self::getAttachedFilePath($file_id, $ext);
		if(!File::exists(File::getPath($newfilepath)))
			File::makeDir(File::getPath($newfilepath), 0777, true);
		
		File::rename($filepath, $newfilepath);
		if(isset($thumbpath))
			File::rename($thumbpath, self::getAttachedFilePath($file_id, 'jpg', 'thumb'));
	}
	
	/**
	 * Attach an event to a post
	 *
	 * @param int $post_id		Post's id
	 * @param string $title		Title of the evenement
	 * @param int $date_start	Timestamp of the starting date
	 * @param int $date_end	Timestamp of the ending date
	 */
	public function attachEvent($post_id, $title, $date_start, $date_end){
		DB::createQuery('events')
			->set(array(
				'post_id'		=> $post_id,
				'title'			=> $title,
				'date_start'	=> date('Y-m-d H:i:s', $date_start),
				'date_end'		=> date('Y-m-d H:i:s', $date_end)
			))
			->insert();
	}
	
	/**
	 * Attach a survey to a post
	 *
	 * @param int $post_id		Post's id
	 * @param string $question	Question of the survey
	 * @param int $date_end		Timestamp of the ending date
	 * @param boolean $multiple	If true, several accepted answers
	 * @param array $answers	Possible answers of the survey
	 */
	public function attachSurvey($post_id, $question, $date_end, $multiple, $answers){
		$id = DB::createQuery('surveys')
			->set(array(
				'post_id'	=> $post_id,
				'question'	=> $question,
				'multiple'	=> $multiple ? 1 : 0,
				'date_end'	=> date('Y-m-d H:i:s', $date_end)
			))
			->insert();
		foreach($answers as $answer){
			DB::createQuery('survey_answers')
				->set(array(
					'survey_id'	=> $id,
					'answer'	=> $answer
				))
				->insert();
		}
	}
	
	
	/**
	 * Delete a post
	 *
	 * @param int $post_id		Post's id
	 */
	public function delete($post_id){
		// Delete attachments
		$attachments = DB::createQuery('attachments')
			->fields('id', 'ext')
			->where(array('post_id' => $post_id))
			->select();
		foreach($attachments as $attachment){
			File::delete(self::getAttachedFilePath((int) $attachment['id'], $attachment['ext']));
			File::delete(self::getAttachedFilePath((int) $attachment['id'], 'jpg', 'thumb'));
		}
		
		// Delete the post
		$this->createQuery()->delete($post_id);
		self::clearCache();
		
		// Delete from the search index
		$search_model = new Search_Model();
		$search_model->delete('post', $post_id);
	}
	
	
	/**
	 * Delete all the cache entries related to the posts
	 */
	public static function clearCache(){
		if($cache_list = Cache::read('posts-cachelist')){
			foreach($cache_list as $cache_entry)
				Cache::delete($cache_entry);
			Cache::delete('posts-cachelist');
		}
	}
	
	
	/**
	 * Returns the path of an attached file
	 *
	 * @param int $attachment_id	Attached file's id
	 * @param string $ext			File's extension
	 * @param string $suffix		Optional suffx
	 */
	public static function getAttachedFilePath($file_id, $ext, $suffix=''){
		$extended_file_id = str_pad((string) $file_id, 6, '0', STR_PAD_LEFT);
		return DATA_DIR.Config::DIR_DATA_STORAGE.'files/'.substr($extended_file_id, 0, 2).'/'.substr($extended_file_id, 2, 2).'/'.$file_id.($suffix=='' ? '' : '_'.$suffix).'.'.$ext;
	}
	
	/**
	 * Returns the URL of an attached file
	 *
	 * @param int $attachment_id	Attached file's id
	 * @param string $ext			File's extension
	 * @param string $suffix		Optional suffx
	 */
	public static function getAttachedFileURL($file_id, $ext, $suffix=''){
		$extended_file_id = str_pad((string) $file_id, 6, '0', STR_PAD_LEFT);
		return Config::URL_STORAGE.'files/'.substr($extended_file_id, 0, 2).'/'.substr($extended_file_id, 2, 2).'/'.$file_id.($suffix=='' ? '' : '_'.$suffix).'.'.$ext;
	}
	
}
