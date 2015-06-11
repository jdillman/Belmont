<?php

require_once('lib/BelmontResponse.class.php');

class BelmontController {

  protected $_auth_required = false;

  protected $_request = null;
  protected $_response = null;

  public function __construct (
    BelmontRequest &$request,
    BelmontResponse &$response
  ) {
    $this->_request = $request;
    $this->_response = $response;
  }

  // TODO logged in user
  protected function authCheck () {
    return true;
  }

  // TODO is this going to be needed??
  protected function beforeHandleMethod (&$message = '') {
    $ret = true;

    // Auth Check
    if ($this->_auth_required && !$this->authCheck()) {
      $ret = false;
    }

    return $ret;
  }

  // TODO is this going to be needed??
  protected function afterHandleMethod (&$message = '') {
    $ret = true;

    /*if ($this->_request->trackingEnabled()) {
      $this->track();
    }*/

    return $ret;
  }

  public function run ($method) {
    // Pre handler checks
    if (!$this->beforeHandleMethod($message)) {
      $this->_response->setStatusCode(400);
      $this->_response->send($message);
      return $this->_response;
    }

    // Is this method supporterd?
    if (!method_exists($this, "handle{$method}")) {
      $this->_response->setStatusCode(405);
      $this->_response->send(get_class($this) . '::handle' . $method . ' not defined');
      return $this->_response;
    }

    // controllers handle method failed
    if (!call_user_func(array($this, "handle{$method}"), $this->_request)) {
      $this->_response->setStatusCode(500);
      $this->_response->send("Unknown error with handle{$method}");
      return $this->_response;
    }

    if (!$this->afterHandleMethod($message)) {
      $this->_response->send($message);
    }

    return $this->_response;

  }

}