<?php

require('BelmontRequest.class.php');
require('BelmontResponse.class.php');

class Belmont {

  CONST DEFAULT_METHODS = 'GET|HEAD|POST|PUT';


  private $config = array(
    'debug'     => false, // Enable debug console
    'tracking'  => false, // Enable link tracking
    'stream'    => true   // Stream HTML as we generate it
  );

  // TODO BelmontRoute class if we feel like we need more abstractions :)
  private $default_route = array(
    'controller'  => 'NotFound',
    'methods'     => self::DEFAULT_METHODS
  );

  // Valid urls to route
  private $routes = null;

  public function __construct (array $routes, array $config = null) {

    $config = array_merge($this->config, $config);

    if ($config['debug']) {
      // TODO enable debug logging
    }
    if ($config['js']) {
      // TODO Noscript
    }
    if ($config['tracking']) {
      // TODO Modify links with tracking info
    }

    $this->routes = $routes;
  }

  // Takes a URI and finds the appropriate handler
  public function matchRoute ($url) {
    
    $route_handler = $this->default_route;

    // TODO regex route match
    $matched_route = isset($this->routes[$url])
      ? $this->routes[$url]
      : null;

    // Route can be an array, string or function. Handler accordingly
    if (is_array($matched_route)) {
      $route_handler = array_merge($route_handler, $matched_route);
    } else if (is_string($matched_route)) {
      $route_handler['controller'] = $route_handler;
    } else if (is_callable($matched_route)) {
      $route_handler = $matched_route;
    }

    return $route_handler;
  }

  public function handleRequest ($request = null) {

    // Lets make sure we're always working with a valid request
    if (!$request) {
      $request = new BelmontRequest();  
    }

    // Match the requests uri to a handler
    $route_config = $this->matchRoute($request->getUri());

    $output = null;
    $status_code = 200;
    if (!$route_config) {
      $output = 'Error, No router for' . $request->getUri();
    } else if (is_callable($route_config)) {
      $output = call_user_func($route_config, $request);
    }

    if ($output) {
      return new BelmontResponse($output, $status_code);
    }

    // Lets start validating the request
    $valid_methods = explode('|', $route_config['methods']);
    if (in_array($request->getMethod(), $valid_methods)) {
      echo 'found a valid method and handler for ' . $request->getMethod();
    } else {
      return new BelmontResponse($output, 405);
    }

    // We've found a valid route, lets execute it
    $response = $this->runController($route_config);
  
    return $response;
  }

  public function runController(array $controller) {

    $response = new BelmontResponse();
    return $response;

  }
  
}