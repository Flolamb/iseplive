<?php
// No cache
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

define('APP_DIR', realpath('./').'/');
define('CF_DIR', realpath('../confeature/').'/');
define('DATA_DIR', realpath('../data/').'/');

try{
	
	// Loading Confeature
	require_once CF_DIR.'init.php';
	
	// Loading the controllers and actions from url info
	Routes::dispatch();
	
}catch(Exception $e){
	if(Config::DEBUG)
		echo $e->getMessage();
}
