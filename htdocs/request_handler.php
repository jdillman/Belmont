<?php

require('Belmont.class.php');

$belmont = new Belmont(array(
  '/about' => 'AboutController',
  '/explore/([^/])+' => 'ProjectController',
  '/users/([0-9])+' => 'UserController',
  '/test' => function ($request) {
    return 'Inline Function Handler';
  },
  '/([^/]+)' => 'HomeController',
));

$response = $belmont->handleRequest();
$response->send();
