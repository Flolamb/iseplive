<?php

class Association_Model extends Model {
	
	
	/**
	 * Returns an associative array of the information of an association,
	 * including members info
	 *
	 * @param string $url_name	Association's name
	 * @return array
	 */
	public function getInfoByName($url_name){
		$association = Cache::read('association-'.$url_name);
		if($association !== false)
			return $association;
		
		$associations = $this->createQuery()
			->where(array('url_name' => $url_name))
			->select();
		
		if(!isset($associations[0]))
			throw new Exception('Association not found');
		
		$association = $associations[0];
		
		// Avatar
		$association['avatar_url'] = self::getAvatarURL((int) $association['id'], true);
		$association['avatar_big_url'] = self::getAvatarURL((int) $association['id']);
		
		// Members
		$association['members'] = DB::select('
			SELECT
				u.id AS user_id,
				s.username, s.firstname, s.lastname,
				a_s.title, a_s.admin
			FROM associations_users a_s
			INNER JOIN users u ON u.id = a_s.user_id
			INNER JOIN students s ON s.username = u.username
			WHERE a_s.association_id = ?
			ORDER BY a_s.order ASC
		', array((int) $association['id']));
		
		Cache::write('association-'.$url_name, $association, 12*3600);
		
		return $association;
	}
	
	
	/**
	 * Returns an associative array of all associations
	 *
	 * @return array
	 */
	public function getAll(){
		$associations = Cache::read('associations');
		if($associations !== false)
			return $associations;
		
		$associations = $this->createQuery()
			->order(array('name', 'ASC'))
			->select();
		
		// Avatar
		foreach($associations as &$association){
			$association['avatar_url'] = self::getAvatarURL((int) $association['id'], true);
			$association['avatar_big_url'] = self::getAvatarURL((int) $association['id']);
		}
		
		Cache::write('associations', $associations, 12*3600);
		
		return $associations;
	}
	
	
	/**
	 * Save the data of an association
	 *
	 * @param int $id		Association's id
	 * @param array $data	Association's data
	 * @return string	URL name
	 */
	public function save($id, $data){
		$association_data = array();
		
		$old_data = DB::createQuery('associations')
			->fields('name', 'url_name')
			->select($id);
		if(!$old_data[0])
			throw new Exception('Association not found');
		$old_data = $old_data[0];
		
		// Name
		$change_name = false;
		if(isset($data['name']) && trim($data['name']) != $old_data['name']){
			$name = trim($data['name']);
			$change_name = true;
			if($name == '')
				throw new FormException('invalid_name');
			$association_data['name'] = $name;
			
			// URL name
			$url_name = Text::forURL($name);
			$i = '';
			while($url_name != $old_data['url_name'] && self::urlExists($url_name.$i))
				$i = $i=='' ? 1 : $i+1;
			$url_name .= $i;
			$association_data['url_name'] = $url_name;
		}else{
			$url_name = $old_data['url_name'];
		}
		
		// Creation date
		if(isset($data['creation_date'])){
			if(!($creation_date = strptime($data['creation_date'], __('ASSOCIATION_EDIT_FORM_CREATION_DATE_FORMAT_PARSE'))))
					throw new FormException('invalid_creation_date');
				
			$association_data['creation_date'] = ($creation_date['tm_year']+1900).'-'.($creation_date['tm_mon']+1).'-'.$creation_date['tm_mday'];
		}
		
		// Email
		if(isset($data['mail'])){
			if($data['mail'] != '' && !Validation::isEmail($data['mail']))
					throw new FormException('invalid_mail');
				
			$association_data['mail'] = $data['mail'];
		}
		
		// Description
		if(isset($data['description']))
			$association_data['description'] = $data['description'];
		
		
		// Avatar
		if(isset($data['avatar_path']) && File::exists($data['avatar_path'])){
			$avatar_path = self::getAvatarPath($id, true);
			$avatar_dir = File::getPath($avatar_path);
			if(!is_dir($avatar_dir))
				File::makeDir($avatar_dir, 0777, true);
			File::rename($data['avatar_path'], $avatar_path);
		}
		if(isset($data['avatar_big_path']) && File::exists($data['avatar_big_path'])){
			$avatar_path = self::getAvatarPath($id, false);
			$avatar_dir = File::getPath($avatar_path);
			if(!is_dir($avatar_dir))
				File::makeDir($avatar_dir, 0777, true);
			File::rename($data['avatar_big_path'], $avatar_path);
		}
		
		
		// Members
		if(isset($data['members']) && is_array($data['members'])){
			$associations_users = DB::createQuery('associations_users')
				->fields('user_id')
				->where(array('association_id' => $id))
				->select();
			$associations_users_ids = array();
			foreach($associations_users as $associations_user)
				$associations_users_ids[] = (int) $associations_user['user_id'];
			unset($associations_users);
			
			$i = 0;
			foreach($data['members'] as &$member)
				$member['order'] = $i++;
			
			if(count($data['members']) != 0){
				$users = DB::createQuery('users')
					->fields('id')
					->where('id IN ('.implode(',', array_keys($data['members'])).')')
					->select();
				foreach($users as $user){
					if(($pos = array_search((int) $user['id'], $associations_users_ids)) !== false){
						array_splice($associations_users_ids, $pos, 1);
						DB::createQuery('associations_users')
							->set(array(
								'title'				=> $data['members'][(int) $user['id']]['title'],
								'admin'				=> $data['members'][(int) $user['id']]['admin'] ? '1' : '0',
								'order'				=> $data['members'][(int) $user['id']]['order']
							))
							->where(array(
								'association_id' => $id,
								'user_id' => (int) $user['id']
							))
							->update();
					}else{
						DB::createQuery('associations_users')
							->set(array(
								'association_id'	=> $id,
								'user_id'			=> $user['id'],
								'title'				=> $data['members'][(int) $user['id']]['title'],
								'admin'				=> $data['members'][(int) $user['id']]['admin'] ? '1' : '0',
								'order'				=> $data['members'][(int) $user['id']]['order']
							))
							->insert();
					}
				}
			}
			
			if(count($associations_users_ids) != 0){
				$users = DB::createQuery('associations_users')
					->where(array(
						'association_id' => $id,
						'user_id IN ('.implode(',', $associations_users_ids).')'
					))
					->delete();
			}
		}
		
		
		$this->createQuery()
			->set($association_data)
			->update($id);
		
		Cache::delete('associations');
		Cache::delete('association-'.$old_data['url_name']);
		if($change_name)
			Post_Model::clearCache();
		
		return $url_name;
	}
	
	
	/**
	 * Create an association
	 *
	 * @param array $data	Association's data
	 * @return string	URL name
	 */
	public function create($data){
		$association_data = array();
		
		// Name
		$change_name = false;
		if(!isset($data['name']) || trim($data['name']) == '')
			throw new FormException('invalid_name');
		$name = trim($data['name']);
		$association_data['name'] = $name;
		
		// URL name
		$url_name = Text::forURL($name);
		$i = '';
		while(self::urlExists($url_name.$i))
			$i = $i=='' ? 1 : $i+1;
		$url_name .= $i;
		$association_data['url_name'] = $url_name;
		
		// Creation date
		if(!isset($data['creation_date']) || !($creation_date = strptime($data['creation_date'], __('ASSOCIATION_EDIT_FORM_CREATION_DATE_FORMAT_PARSE'))))
			throw new FormException('invalid_creation_date');
		$association_data['creation_date'] = ($creation_date['tm_year']+1900).'-'.($creation_date['tm_mon']+1).'-'.$creation_date['tm_mday'];
		
		// Email
		if(isset($data['mail'])){
			if($data['mail'] != '' && !Validation::isEmail($data['mail']))
					throw new FormException('invalid_mail');
			
			$association_data['mail'] = $data['mail'];
		}
		
		// Description
		if(isset($data['description']))
			$association_data['description'] = $data['description'];
		
		// Avatar
		if(!isset($data['avatar_path']) || !File::exists($data['avatar_path']) || !isset($data['avatar_big_path']) && !File::exists($data['avatar_big_path']))
			throw new FormException('avatar');
		
		// Insertion in the DB
		$id = $this->createQuery()
			->set($association_data)
			->insert();
		
		
		// Avatar
		$avatar_path = self::getAvatarPath($id, true);
		$avatar_dir = File::getPath($avatar_path);
		if(!is_dir($avatar_dir))
			File::makeDir($avatar_dir, 0777, true);
		File::rename($data['avatar_path'], $avatar_path);
		
		$avatar_path = self::getAvatarPath($id, false);
		$avatar_dir = File::getPath($avatar_path);
		if(!is_dir($avatar_dir))
			File::makeDir($avatar_dir, 0777, true);
		File::rename($data['avatar_big_path'], $avatar_path);
		
		
		// Members
		if(isset($data['members']) && is_array($data['members'])){
			$i = 0;
			foreach($data['members'] as &$member)
				$member['order'] = $i++;
			
			if(count($data['members']) != 0){
				$users = DB::createQuery('users')
					->fields('id')
					->where('id IN ('.implode(',', array_keys($data['members'])).')')
					->select();
				foreach($users as $user){
					DB::createQuery('associations_users')
						->set(array(
							'association_id'	=> $id,
							'user_id'			=> $user['id'],
							'title'				=> $data['members'][(int) $user['id']]['title'],
							'admin'				=> $data['members'][(int) $user['id']]['admin'] ? '1' : '0',
							'order'				=> $data['members'][(int) $user['id']]['order']
						))
						->insert();
				}
			}
		}
		
		Cache::delete('associations');
		
		return $url_name;
	}
	
	
	/**
	 * Returns true if an association already exists with this url_name, false otherwise
	 *
	 * @return boolean
	 */
	public static function urlExists($url_name){
		$result = DB::createQuery('associations')
			->fields('1')
			->where(array('url_name' => $url_name))
			->select();
		return isset($result[0]);
	}
	
	
	/**
	 * Returns the list of the association for which the authenticated user is admin
	 *
	 * @return array
	 */
	public static function getAuth(){
		if(!isset(User_Model::$auth_data))
			return array();
		$cache_entry = 'associations_auth_'.User_Model::$auth_data['id'];
		if($categories = Cache::read($cache_entry))
			return $categories;
		
		$associations_data = DB::select('
			SELECT a.id, a.name, au.admin
			FROM associations_users au
			INNER JOIN associations a ON a.id = au.association_id
			WHERE au.user_id = ?
		', array((int) User_Model::$auth_data['id']));
		$associations_auth = array();
		foreach($associations_data as &$asso){
			$associations_auth[(int) $asso['id']] = array(
				'name'	=> $asso['name'],
				'admin'	=> $asso['admin']=='1'
			);
		}
		
		Cache::write($cache_entry, $associations_auth, 60*10);
		return $associations_auth;
	}
	
	
	/**
	 * Returns  the path of an avatar
	 *
	 * @param int $id			Id of the association
	 * @param boolean $thumb	Thumb's path if true, big photo otherwise
	 * @return string
	 */
	public static function getAvatarPath($id, $thumb=false){
		$id = (string) ((int) $id);
		return DATA_DIR.Config::DIR_DATA_STORAGE.'associations/'.$id.($thumb ? '_thumb' : '').'.jpg';
	}
	
	/**
	 * Returns  the absolute URL of an avatar
	 *
	 * @param int $id			Id of the association
	 * @param boolean $thumb	Thumb's path if true, big photo otherwise
	 * @return string
	 */
	public static function getAvatarURL($id, $thumb=false){
		$student_number = (string) ((int) $id);
		return Config::URL_STORAGE.'associations/'.$id.($thumb ? '_thumb' : '').'.jpg';
	}
	
}
