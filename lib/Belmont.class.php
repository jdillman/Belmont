<?php

/**
 * Belmont web framework.
 *  
 * Route a sanitized request to a controller to generate a response.
 * 
 * @see 'request_handler.php'
 * @see 'lib/Belmont.class.php'
 * @see 'lib/BelmontRequest.class.php'
 * @see 'lib/BelmontResponse.class.php'
 * @see 'lib/BelmontController.class.php'
 * @see 'lib/BelmontHTMLController.class.php'
 * @see 'lib/BelmontModel.class.php'
 */

require 'lib/BelmontRequest.class.php';
require 'lib/BelmontResponse.class.php';

class Belmont {

  // TODO INI settings
  CONST DEFAULT_METHODS = 'GET|POST|HEAD|PUT|DELETE';
  CONST DEFAULT_ROUTE_MATCH = '[^/]+';
  CONST DEFAULT_CONTROLLER = 'HomeController';

  private $_config = array(
    'debug'     => false, // Enable debug console
    'tracking'  => false, // Enable link tracking
    'stream'    => true   // Stream HTML as we generate it otherwise buffer
  );

  // Current routes being handled.
  // Set with addRoutes(array($route => $handler));
  private $routes = null;

  public function __construct (array $routes = array(), array $config = array()) {

    $config = array_merge($this->_config, $config);

    if ($config['debug']) {
      // TODO enable debug logging
    }
    if ($config['tracking']) {
      // TODO Modify links with tracking info
    }

    if (!empty($routes)) {
      $this->addRoutes($routes);  
    }
    
  }

  public function addRoutes(array $routes) {
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
  private function _matchRoute ($request_url) {

    $methods = self::DEFAULT_METHODS;
    $controller = self::DEFAULT_CONTROLLER;
    
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

    $request_uri = $request->getUri();
    $method = $request->getMethod();
    $response = new BelmontResponse();

    // Look for the route handler
    $route_config = $this->_matchRoute($request_uri);
    if (!$route_config) {
      $response->setStatusCode(400);
      return $response;
    }

    // Check the method
    $controller = $route_config['controller'];
    $supported_methods = explode('|', $route_config['methods']);
    if (!in_array($method, $supported_methods)) {
      $response->setStatusCode(405);
      return $response;
    }

    // See if the handler is a callback instead of controller
    if (is_callable($controller)) {
      $response->send(call_user_func($controller, $request));
    } else {
      $response = $this->_runController($controller, $method, $request, $response);
    }

    return $response;
  }

  public function log ($content) {
    error_log('[Belmont] ' . print_r($content, true));
  }

  private function _runController(
    $controller_name,
    $method = 'GET',
    &$request = null,
    &$response = null
  ) {

    // TODO validate controller_name
    $controller_path = "\\controllers\\{$controller_name}.class.php";
    require_once($controller_path);

    // Ensure we always have a proper request and response
    if (!$request) {
      $request = new BelmontRequest();
    }
    if (!$response) {
      $response = new BelmontResponse();
    }

    // Instantiate the controller
    $controller = new $controller_name($request, $response);

    $response = $controller->run($method);

    return $response;
  }
  
}