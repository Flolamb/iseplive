<?php
$data = array();
$data[] = array(
	'value'	=> '<img src="'.Config::URL_STATIC.'images/icons/search.png" alt="" class="icon" /> '.$query,
	'url'	=> Config::URL_ROOT.Routes::getPage('search').'?q='.urlencode($query)
);

foreach($results as &$result){
	switch($result['_type']){
		
		case 'student':
			$data[] = array(
				'value'	=> '<img src="'.Config::URL_STATIC.'images/icons/user.png" alt="" class="icon" /> '. htmlspecialchars($result['_source']['firstname'].' '.$result['_source']['lastname']),
				'url'	=> Config::URL_ROOT.Routes::getPage('student', array('username' => $result['_id']))
			);
			break;
		
		case 'group':
			$data[] = array(
				'value'	=> '<img src="'.Config::URL_STATIC.'images/icons/group.png" alt="" class="icon" /> '. htmlspecialchars($result['_source']['name']),
				'url'	=> Config::URL_ROOT.Routes::getPage('group', array('group' => $result['_source']['url_name']))
			);
			break;
		
		case 'post':
			$data[] = array(
				'value'	=> '<img src="'.Config::URL_STATIC.'images/icons/post.png" alt="" class="icon" /> '. htmlspecialchars(Text::summary($result['_source']['message'], 140, '...', $query)),
				'url'	=> Config::URL_ROOT.Routes::getPage('post', array('id' => $result['_id']))
			);
			break;
	
	}
}

echo json_encode($data);
