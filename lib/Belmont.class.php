<?php

require('BelmontRequest.class.php');
require('BelmontResponse.class.php');

class Belmont {

  CONST DEFAULT_METHODS = 'GET|POST|HEAD|PUT|DELETE';
  CONST DEFAULT_ROUTE_MATCH = '[^/]+';
  CONST DEFAULT_CONTROLLER = 'HomeController';

  private $_config = array(
    'debug'     => false, // Enable debug console
    'tracking'  => false, // Enable link tracking
    'stream'    => true   // Stream HTML as we generate it otherwise buffer
  );

  private $routes = null;

  public function __construct (array $routes, array $config = array()) {

    $config = array_merge($this->_config, $config);

    if ($config['debug']) {
      // TODO enable debug logging
    }
    if ($config['tracking']) {
      // TODO Modify links with tracking info
    }

    $this->addRoute($routes);

  }

  public function addRoute(array $routes) {
    foreach ($routes as $key => $route) {
      // Make sure there is always a leading '/'
      if (substr($key, 0, 1) !== '/') {
        $key = '/' . $key;
      }

      $this->_routes[$key] = $route;
    }

    return true;
  }

  // Takes a URL and finds the appropriate handler
  public function matchRoute ($request_url) {

    $methods = self::DEFAULT_METHODS;
    $controller =  self::DEFAULT_CONTROLLER;
    
    foreach ($this->_routes as $route_url => $route) {
      $route_url = str_replace('/', '\/', $route_url);
      if (!preg_match_all("/{$route_url}/", $request_url, $matches)) {
        continue;
      } else {
        if (is_string($route) || is_callable($route)) {
          $controller = $route;
        } else if (is_array($route)) {
          $controller = $route['controller'];
          $methods = $route['methods'];
        }
        break;
      }
    }

    return array(
      'controller' => $controller,
      'methods' => $methods
    ); 
  }

  public function handleRequest ($request = null) {
    // Lets make sure we're always working with a valid request
    if (!$request) {
      $request = new BelmontRequest();  
    }

    $status_code = 200;
    $request_uri = $request->getUri();
    $method      = $request->getMethod();
    //$response = new BelmontResponse($status_code);

    // Look for the route handler
    $route_config = $this->matchRoute($request_uri);
    if (!$route_config) {
      // $response->setStatusCode(500);
      $response->send("Error, No router for {$request_ur}");
      return $response;
    }

    // Check the method
    $controller = $route_config['controller'];
    $supported_methods = explode('|', $route_config['methods']);
    if (!in_array($method, $supported_methods)) {
      // error_log('unsupported method');
      return new BelmontResponse("Invalid Method supplied for {$controller}", 405);
    }

    if (is_callable($controller)) {
      $response = new BelmontResponse(call_user_func($controller, $request));
    } else {
      $response = $this->runController($controller, $method, $request);
    }

    return $response;
  }

  public function log ($content) {
    error_log('[Belmont] ' . print_r($content));
  }

  public function runController($controller_name, $method) {

    echo $method . ' Run Controller for ' . $controller_name;


    $response = new BelmontResponse();
    return $response;
  }
  
}