<?php

	if(empty($_SERVER['APP_PATH']) or
		@chdir($_SERVER['DOCUMENT_ROOT'].$_SERVER['APP_PATH']) == false)
	{
		exit('app init error : (');
	}

	include_once('index.php');

	$main = new main();

	switch($main->app->request)
	{
		case '/index.css':
			$main->css('index.css');
			break;
	
		case '/index.js':
			$main->js('index.js');
			break;

		case '/worker.js':
			$main->js('worker.js');
			break;

		default:
			$main->doc();
	}

?>
