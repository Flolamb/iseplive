<?php

class Student_Model extends Model {
	
	/**
	 * Returns an associative array of the information of a student,
	 * including user info (from the users table)
	 *
	 * @param string $username	User name
	 * @return array
	 */
	public function getInfo($username){
		$students = DB::select('
			SELECT u.*, s.firstname, s.lastname, s.student_number, s.promo
			FROM students s
			LEFT JOIN users u ON u.username = s.username
			WHERE s.username = ?
		', array($username));
		
		if(!isset($students[0]))
			throw new Exception('Student not found');
		
		$student = $students[0];
		
		// Avatar
		$student['avatar_url'] = User_Model::getAvatarURL($student['student_number'], true);
		$student['avatar_big_url'] = User_Model::getAvatarURL($student['student_number'], false);
		
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
	
}
