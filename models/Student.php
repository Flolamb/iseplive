<?php

class Student_Model extends Model {
	
	/**
	 * Returns the names of all students, by promo
	 *
	 * @param int $promo	Promo numbers
	 * @return array
	 */
	public function getAllByPromos(){
		$promos = func_get_args();
		if(count($promos) == 0)
			throw new Exception('getAllByPromos method must have at least 1 parameter');
		
		$cache_entry = 'students-promos-'.implode('-', $promos);
		$students = Cache::read($cache_entry);
		if($students !== false)
			return $students;
		
		$students = $this->createQuery()
			->fields('username', 'firstname', 'lastname', 'promo')
			->where('promo IN ('.implode(',', $promos).')')
			->order('firstname', 'lastname')
			->select();
		
		$students_by_promo = array();
		
		foreach($students as $student){
			if(!isset($students_by_promo[(int) $student['promo']]))
				$students_by_promo[(int) $student['promo']] = array();
			$students_by_promo[(int) $student['promo']][] = $student;
		}
		
		Cache::write($cache_entry, $students_by_promo, 2*3600);
		
		return $students_by_promo;
	}
	
	
	/**
	 * Returns an associative array of the information of a student,
	 * including user info (from the users table)
	 *
	 * @param string $username	User name
	 * @return array
	 */
	public function getInfo($username){
		$students = DB::select('
			SELECT u.*, s.firstname, s.lastname, s.student_number, s.promo, s.cesure
			FROM students s
			LEFT JOIN users u ON u.username = s.username
			WHERE s.username = ?
		', array($username));
		
		if(!isset($students[0]))
			throw new Exception('Student not found');
		
		$student = $students[0];
		
		// Avatar
		$student['avatar_url'] = self::getAvatarURL($student['student_number'], true);
		$student['avatar_big_url'] = self::getAvatarURL($student['student_number'], false);
		
		return $student;
	}
	
	
	/**
	 * Autocomplete the firstname/lastname of a student
	 *
	 * @param string $query	Part of a name
	 * @param string $limit	Number of results
	 * @return array
	 */
	public function autocomplete($query, $limit){
		$query = str_replace('%', '', $query);
		$query = '%'.$query.'%';
		
		$students = DB::select('
			SELECT u.id AS user_id, s.username, s.firstname, s.lastname
			FROM students s
			INNER JOIN users u ON u.username = s.username
			WHERE CONCAT_WS(" ", s.firstname, s.lastname) LIKE '.DB::quote($query).'
			LIMIT '.$limit.'
		');
		
		return $students;
	}
	
	
	/**
	 * Returns information of students by users' ids
	 *
	 * @param string $ids	List of users' ids
	 * @return array
	 */
	public static function getInfoByUsersIds($ids){
		if(!isset($ids[0]))
			return array();
		
		$students = DB::select('
			SELECT u.id AS user_id, s.username, s.firstname, s.lastname
			FROM users u
			INNER JOIN students s ON s.username = u.username
			WHERE u.id IN ('.implode(',', $ids).')
		');
		
		$students_by_id = array();
		foreach($students as &$student)
			$students_by_id[(int) $student['user_id']] = &$student;
		$students_ = array();
		foreach($ids as $id)
			$students_[] = &$students_by_id[$id];
		
		return $students_;
	}
	
	
	/**
	 * Save the data of a student
	 *
	 * @param string $username	student's username
	 * @param array $data	student's data
	 */
	public function save($username, $data){
		$student_data = array();
		
		$old_data = DB::createQuery('students')
			->fields('student_number')
			->where(array('username' => $username))
			->select();
		if(!$old_data[0])
			throw new Exception('Student not found');
		$old_data = $old_data[0];
		
		// Firstname
		if(isset($data['firstname'])){
			if(trim($data['firstname']) == '')
					throw new FormException('firstname');
			$student_data['firstname'] = trim($data['firstname']);
		}
		
		// Lastname
		if(isset($data['lastname'])){
			if(trim($data['lastname']) == '')
					throw new FormException('lastname');
			$student_data['lastname'] = trim($data['lastname']);
		}
		
		// Student number
		if(isset($data['student_number'])){
			if(!ctype_digit(trim($data['student_number'])))
					throw new FormException('student_number');
			$student_data['student_number'] = (int) trim($data['student_number']);
			
			// Moving the avatar
			if($student_data['student_number'] != $old_data['student_number']){
				// Thumb
				$avatar_path = self::getAvatarPath($student_data['student_number'], true);
				$avatar_dir = File::getPath($avatar_path);
				if(!is_dir($avatar_dir))
					File::makeDir($avatar_dir, 0777, true);
				File::rename(self::getAvatarPath($old_data['student_number'], true), $avatar_path);
				// Big
				$avatar_path = self::getAvatarPath($student_data['student_number'], false);
				$avatar_dir = File::getPath($avatar_path);
				if(!is_dir($avatar_dir))
					File::makeDir($avatar_dir, 0777, true);
				File::rename(self::getAvatarPath($old_data['student_number'], false), $avatar_path);
			}
		}
		
		// Promo
		if(isset($data['promo'])){
			if(!ctype_digit(trim($data['promo'])) || ((int) $data['promo'] < 2000))
					throw new FormException('promo');
			$student_data['promo'] = (int) trim($data['promo']);
		}
		
		// Cesure
		if(isset($data['cesure']))
			$student_data['cesure'] = $data['cesure'] ? 1 : 0;
		
		// Avatar
		if(isset($data['avatar_path']) && isset($data['student_number']) && File::exists($data['avatar_path'])){
			$avatar_path = self::getAvatarPath((int) $data['student_number'], true);
			$avatar_dir = File::getPath($avatar_path);
			if(!is_dir($avatar_dir))
				File::makeDir($avatar_dir, 0777, true);
			File::rename($data['avatar_path'], $avatar_path);
		}
		if(isset($data['avatar_big_path']) && isset($data['student_number']) && File::exists($data['avatar_big_path'])){
			$avatar_path = self::getAvatarPath((int) $data['student_number'], false);
			$avatar_dir = File::getPath($avatar_path);
			if(!is_dir($avatar_dir))
				File::makeDir($avatar_dir, 0777, true);
			File::rename($data['avatar_big_path'], $avatar_path);
		}
		
		// Update the DB
		$this->createQuery()
			->set($student_data)
			->where(array('username' => $username))
			->update();
		
	}
	
	
	/**
	 * Returns the path of an avatar
	 *
	 * @param int $student_number	Student number
	 * @param boolean $thumb		Thumb's path if true, big photo otherwise
	 * @return string
	 */
	public static function getAvatarPath($student_number, $thumb=false){
		$student_number = (string) ((int) $student_number);
		return DATA_DIR.Config::DIR_DATA_STORAGE.'avatars/'.substr($student_number, 0, -2).'/'.$student_number.($thumb ? '_thumb' : '').'.jpg';
	}
	
	/**
	 * Returns the absolute URL of an avatar
	 *
	 * @param int $student_number	Student number
	 * @param boolean $thumb		Thumb's path if true, big photo otherwise
	 * @return string
	 */
	public static function getAvatarURL($student_number, $thumb=false){
		$student_number = (string) ((int) $student_number);
		return Config::URL_STORAGE.'avatars/'.substr($student_number, 0, -2).'/'.$student_number.($thumb ? '_thumb' : '').'.jpg';
	}
	
}
