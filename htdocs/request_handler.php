<?php

require('lib/Belmont.class.php');

// Configure the framework
$config = array(
  'tracking' => true,
  'stream' => false,
  'cache' => 3600
);

// Set the routes
$routes = array(
  '/about' => 'AboutController',
  '/explore/([^/])+' => 'ProjectController',
  '/users/([0-9])+' => 'UserController',
  '/([^/]+)' => 'HomeController'
);

// Initialize the framework
$belmont = new Belmont($routes, $config);

// Handle the request
$response = $belmont->handleRequest();

// Send the response!
$response->send();