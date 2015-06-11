<?php

require('lib/Belmont.class.php');

// Configure the framework
$config = array(
  'tracking' => true,
  'stream' => false,
  'controller_cache' => array(
    'enabled' => true,
    'ttl' => 3600
  )
);

// Set the routes
$routes = array(
  '/explore/([^/])+' => 'ProjectController',
  '/users/([0-9])+' => function ($request) {
    return 'Inline function handler for ' . $request->getUri();
  },
  '/([^/]+)' => 'HomeController'
);

// Initialize the framework
$belmont = new Belmont($routes, $config);

// Handle the request
$response = $belmont->handleRequest();

// Send the response!
$response->send();