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
		
		if(isset($students[0]))
			return $students[0];
		throw new Exception('Student not found');
	}
	
}
