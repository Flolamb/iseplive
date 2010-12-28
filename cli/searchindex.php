<?php
/**
 * Indexes the data of users, groups, and posts for the search engine
 *
 * @example /usr/bin/php -f searchindex.php
 */

define('CLI_MODE', true);
define('APP_DIR', realpath(dirname(__FILE__).'/../').'/');
define('CF_DIR', realpath(dirname(__FILE__).'/../../confeature/').'/');
define('DATA_DIR', realpath(dirname(__FILE__).'/../../data/').'/');

try{
	
	// Loading Confeature
	require_once CF_DIR.'init.php';
	
	$search_model = new Search_Model();
	
	// Students indexing
	
	$search_model->delete('student');
	
	$students = DB::createQuery('students')
		->fields('username', 'firstname', 'lastname')
		->select();
	foreach($students as $student){
		$search_model->index(array(
			'username'	=> $student['username'],
			'firstname'	=> Search_Model::sanitize($student['firstname']),
			'lastname'	=> Search_Model::sanitize($student['lastname'])
		), 'student', $student['username']);
	}
	
	
	// Posts indexing
	
	$search_model->delete('post');
	
	$posts = DB::createQuery('posts')
		->fields('id', 'message', 'private', 'official')
		->select();
	foreach($posts as $post){
		$search_model->index(array(
			'message'	=> Search_Model::sanitize($post['message']),
			'official'	=> $post['official'] == '1',
			'private'	=> $post['private'] == '1'
		), 'post', $post['id']);
	}
	
	
	// Groups indexing
	
	$search_model->delete('group');
	
	$groups = DB::createQuery('groups')
		->fields('id', 'name', 'url_name', 'description')
		->select();
	foreach($groups as $group){
		$search_model->index(array(
			'name'			=> Search_Model::sanitize($group['name']),
			'url_name'		=> $group['url_name'],
			'description'	=> Search_Model::sanitize($group['description'])
		), 'group', $group['id']);
	}
	
	
}catch(Exception $e){
	echo $e->getMessage();
}
