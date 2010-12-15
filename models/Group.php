<?php

class Group_Model extends Model {
	
	
	/**
	 * Returns an associative array of the information of a group,
	 * including members info
	 *
	 * @param string $url_name	Group's name
	 * @return array
	 */
	public function getInfoByName($url_name){
		$group = Cache::read('group-'.$url_name);
		if($group !== false)
			return $group;
		
		$groups = $this->createQuery()
			->where(array('url_name' => $url_name))
			->select();
		
		if(!isset($groups[0]))
			throw new Exception('Group not found');
		
		$group = $groups[0];
		
		// Avatar
		$group['avatar_url'] = self::getAvatarURL((int) $group['id'], true);
		$group['avatar_big_url'] = self::getAvatarURL((int) $group['id']);
		
		// Members
		$group['members'] = DB::select('
			SELECT
				u.id AS user_id,
				s.username, s.firstname, s.lastname,
				a_s.title, a_s.admin
			FROM groups_users a_s
			INNER JOIN users u ON u.id = a_s.user_id
			INNER JOIN students s ON s.username = u.username
			WHERE a_s.group_id = ?
			ORDER BY a_s.order ASC
		', array((int) $group['id']));
		
		Cache::write('group-'.$url_name, $group, 12*3600);
		
		return $group;
	}
	
	
	/**
	 * Returns an associative array of all groups
	 *
	 * @return array
	 */
	public function getAll(){
		$groups = Cache::read('groups');
		if($groups !== false)
			return $groups;
		
		$groups = $this->createQuery()
			->order(array('name', 'ASC'))
			->select();
		
		// Avatar
		foreach($groups as &$group){
			$group['avatar_url'] = self::getAvatarURL((int) $group['id'], true);
			$group['avatar_big_url'] = self::getAvatarURL((int) $group['id']);
		}
		
		Cache::write('groups', $groups, 12*3600);
		
		return $groups;
	}
	
	
	/**
	 * Returns information of groups by ids
	 *
	 * @param string $ids	List of ids
	 * @return array
	 */
	public static function getInfoByIds($ids){
		if(!isset($ids[0]))
			return array();
		
		$groups = DB::createQuery('groups')
			->fields('id', 'name', 'url_name')
			->where('id IN ('.implode(',', $ids).')')
			->select();
		
		Utils::arraySort($groups, 'id', $ids);
		
		// Avatar
		foreach($groups as &$group){
			$group['avatar_url'] = self::getAvatarURL((int) $group['id'], true);
			$group['avatar_big_url'] = self::getAvatarURL((int) $group['id'], false);
		}
		
		return $groups;
	}
	
	
	/**
	 * Save the data of a group
	 *
	 * @param int $id		Group's id
	 * @param array $data	Group's data
	 * @return string	URL name
	 */
	public function save($id, $data){
		$group_data = array();
		
		$old_data = DB::createQuery('groups')
			->fields('name', 'url_name', 'description')
			->select($id);
		if(!$old_data[0])
			throw new Exception('Group not found');
		$old_data = $old_data[0];
		
		// Name
		$change_name = false;
		if(isset($data['name']) && trim($data['name']) != $old_data['name']){
			$name = trim($data['name']);
			$change_name = true;
			$group_data['name'] = $name;
			
			// URL name
			$url_name = Text::forURL($name);
			if($url_name == '')
				throw new FormException('invalid_name');
			$i = '';
			while($url_name != $old_data['url_name'] && self::urlExists($url_name.$i))
				$i = $i=='' ? 1 : $i+1;
			$url_name .= $i;
			$group_data['url_name'] = $url_name;
		}else{
			$url_name = $old_data['url_name'];
		}
		
		// Creation date
		if(isset($data['creation_date'])){
			if(!($creation_date = strptime($data['creation_date'], __('GROUP_EDIT_FORM_CREATION_DATE_FORMAT_PARSE'))))
					throw new FormException('invalid_creation_date');
				
			$group_data['creation_date'] = ($creation_date['tm_year']+1900).'-'.($creation_date['tm_mon']+1).'-'.$creation_date['tm_mday'];
		}
		
		// Email
		if(isset($data['mail'])){
			if($data['mail'] != '' && !Validation::isEmail($data['mail']))
					throw new FormException('invalid_mail');
				
			$group_data['mail'] = $data['mail'];
		}
		
		// Description
		if(isset($data['description']))
			$group_data['description'] = $data['description'];
		
		
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
			$groups_users = DB::createQuery('groups_users')
				->fields('user_id')
				->where(array('group_id' => $id))
				->select();
			$groups_users_ids = array();
			foreach($groups_users as $groups_user)
				$groups_users_ids[] = (int) $groups_user['user_id'];
			unset($groups_users);
			
			$i = 0;
			foreach($data['members'] as &$member)
				$member['order'] = $i++;
			
			if(count($data['members']) != 0){
				$users = DB::createQuery('users')
					->fields('id')
					->where('id IN ('.implode(',', array_keys($data['members'])).')')
					->select();
				foreach($users as $user){
					if(($pos = array_search((int) $user['id'], $groups_users_ids)) !== false){
						array_splice($groups_users_ids, $pos, 1);
						DB::createQuery('groups_users')
							->set(array(
								'title'				=> $data['members'][(int) $user['id']]['title'],
								'admin'				=> $data['members'][(int) $user['id']]['admin'] ? '1' : '0',
								'order'				=> $data['members'][(int) $user['id']]['order']
							))
							->where(array(
								'group_id' => $id,
								'user_id' => (int) $user['id']
							))
							->update();
					}else{
						DB::createQuery('groups_users')
							->set(array(
								'group_id'	=> $id,
								'user_id'			=> $user['id'],
								'title'				=> $data['members'][(int) $user['id']]['title'],
								'admin'				=> $data['members'][(int) $user['id']]['admin'] ? '1' : '0',
								'order'				=> $data['members'][(int) $user['id']]['order']
							))
							->insert();
					}
				}
			}
			
			if(count($groups_users_ids) != 0){
				$users = DB::createQuery('groups_users')
					->where(array(
						'group_id' => $id,
						'user_id IN ('.implode(',', $groups_users_ids).')'
					))
					->delete();
			}
		}
		
		
		$this->createQuery()
			->set($group_data)
			->update($id);
		
		self::clearCache();
		Cache::delete('group-'.$old_data['url_name']);
		
		if($change_name)
			Post_Model::clearCache();
		
		// Update the search index
		$search_model = new Search_Model();
		$search_model->index(array(
			'name'			=> Search_Model::sanitize(isset($group_data['name']) ? $group_data['name'] : $old_data['name']),
			'url_name'		=> isset($group_data['url_name']) ? $group_data['url_name'] : $old_data['url_name'],
			'description'	=> Search_Model::sanitize(isset($group_data['description']) ? $group_data['description'] : $old_data['description'])
		), 'group', $id);
		
		return $url_name;
	}
	
	
	/**
	 * Create a group
	 *
	 * @param array $data	Group's data
	 * @return string	URL name
	 */
	public function create($data){
		$group_data = array();
		
		// Name
		$change_name = false;
		if(!isset($data['name']))
			throw new FormException('invalid_name');
		$name = trim($data['name']);
		$group_data['name'] = $name;
		
		// URL name
		$url_name = Text::forURL($name);
		if($url_name == '')
			throw new FormException('invalid_name');
		$i = '';
		while(self::urlExists($url_name.$i))
			$i = $i=='' ? 1 : $i+1;
		$url_name .= $i;
		$group_data['url_name'] = $url_name;
		
		// Creation date
		if(!isset($data['creation_date']) || !($creation_date = strptime($data['creation_date'], __('GROUP_EDIT_FORM_CREATION_DATE_FORMAT_PARSE'))))
			throw new FormException('invalid_creation_date');
		$group_data['creation_date'] = ($creation_date['tm_year']+1900).'-'.($creation_date['tm_mon']+1).'-'.$creation_date['tm_mday'];
		
		// Email
		if(isset($data['mail'])){
			if($data['mail'] != '' && !Validation::isEmail($data['mail']))
					throw new FormException('invalid_mail');
			
			$group_data['mail'] = $data['mail'];
		}
		
		// Description
		if(isset($data['description']))
			$group_data['description'] = $data['description'];
		
		// Avatar
		if(!isset($data['avatar_path']) || !File::exists($data['avatar_path']) || !isset($data['avatar_big_path']) && !File::exists($data['avatar_big_path']))
			throw new FormException('avatar');
		
		// Insertion in the DB
		$id = $this->createQuery()
			->set($group_data)
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
					DB::createQuery('groups_users')
						->set(array(
							'group_id'	=> $id,
							'user_id'	=> $user['id'],
							'title'		=> $data['members'][(int) $user['id']]['title'],
							'admin'		=> $data['members'][(int) $user['id']]['admin'] ? '1' : '0',
							'order'		=> $data['members'][(int) $user['id']]['order']
						))
						->insert();
				}
			}
		}
		
		// Add to the search index
		$search_model = new Search_Model();
		$search_model->index(array(
			'name'			=> Search_Model::sanitize($group_data['name']),
			'url_name'		=> $group_data['url_name'],
			'description'	=> Search_Model::sanitize($group_data['description'])
		), 'group', $id);
		
		self::clearCache();
		
		return $url_name;
	}
	
	
	/**
	 * Delete a group
	 *
	 * @param int $id	Id of the group
	 */
	public function delete($id){
		$this->createQuery()->delete($id);
		self::clearCache();
		Post_Model::clearCache();
		
		// Delete the avatar
		File::delete(self::getAvatarPath($id, true));
		File::delete(self::getAvatarPath($id, false));
		
		// Delete from the search index
		$search_model = new Search_Model();
		$search_model->delete('group', $id);
	}
	
	
	/**
	 * Returns true if a group already exists with this url_name, false otherwise
	 *
	 * @return boolean
	 */
	public static function urlExists($url_name){
		$result = DB::createQuery('groups')
			->fields('1')
			->where(array('url_name' => $url_name))
			->select();
		return isset($result[0]);
	}
	
	
	/**
	 * Returns the list of the groups related to a user
	 *
	 * @param int $id	Id of a user (if not set, the id of the authenticated user is used)
	 * @return array
	 */
	public static function getAuth($id=null){
		if(!isset($id) && !isset(User_Model::$auth_data))
			throw new Exception('User id not found');
		if(!isset($id))
			$id = (int) User_Model::$auth_data['id'];
		
		$cache_entry = 'groups-auth-'.$id;
		if($categories = Cache::read($cache_entry))
			return $categories;
		
		$groups_data = DB::select('
			SELECT g.id, g.name, g.url_name, gu.title, gu.admin
			FROM groups_users gu
			INNER JOIN groups g ON g.id = gu.group_id
			WHERE gu.user_id = ?
		', array($id));
		$groups_auth = array();
		foreach($groups_data as &$asso){
			$groups_auth[(int) $asso['id']] = array(
				'name'		=> $asso['name'],
				'url_name'	=> $asso['url_name'],
				'title'		=> $asso['title'],
				'admin'		=> $asso['admin']=='1'
			);
		}
		
		Cache::write($cache_entry, $groups_auth, 60*10);
		$cache_list = Cache::read('groups-auth-cachelist');
		if(!$cache_list)
			$cache_list = array();
		if(!in_array($cache_entry, $cache_list))
			$cache_list[] = $cache_entry;
		Cache::write('groups-auth-cachelist', $cache_list, 60*10);
		
		return $groups_auth;
	}
	
	
	/**
	 * Delete all the cache entries related to the groups
	 */
	public static function clearCache(){
		Cache::delete('groups');
		if($cache_list = Cache::read('groups-auth-cachelist')){
			foreach($cache_list as $cache_entry)
				Cache::delete($cache_entry);
			Cache::delete('groups-auth-cachelist');
		}
	}
	
	
	/**
	 * Returns  the path of an avatar
	 *
	 * @param int $id			Id of the group
	 * @param boolean $thumb	Thumb's path if true, big photo otherwise
	 * @return string
	 */
	public static function getAvatarPath($id, $thumb=false){
		$id = (string) ((int) $id);
		return DATA_DIR.Config::DIR_DATA_STORAGE.'groups/'.$id.($thumb ? '_thumb' : '').'.jpg';
	}
	
	/**
	 * Returns  the absolute URL of an avatar
	 *
	 * @param int $id			Id of the group
	 * @param boolean $thumb	Thumb's path if true, big photo otherwise
	 * @return string
	 */
	public static function getAvatarURL($id, $thumb=false){
		$student_number = (string) ((int) $id);
		return Config::URL_STORAGE.'groups/'.$id.($thumb ? '_thumb' : '').'.jpg';
	}
	
}
