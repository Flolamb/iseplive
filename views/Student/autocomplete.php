<?php
foreach($students as &$student){
	$student['value'] = $student['firstname'].' '.$student['lastname'];
	$student['url'] = Config::URL_ROOT.Routes::getPage('student', array('username' => $student['username']));
}

echo json_encode($students);
