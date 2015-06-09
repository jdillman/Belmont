<?php

require('Belmont.class.php');

$config = array(
	'debug' => true,
	'js' => false,
	'stream' => true
);

$routes = array(
	'/' => 'home',
	'/users/%s' => 'user',
	'/test' => array(
		'controller' => 'test',
		'methods' => 'POST'
	),
	'/404' => function ($request) {
		return 'Page not found';
  }
);

// Initialize
$belmont = new Belmont($routes, $config);

// Run
$response = $belmont->handleRequest();

$response->send();


//$request = $belmont->getRequest();
//$response = $belmont->getResponse($request);


