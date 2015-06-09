<?php

// TODO extend from data class that fixes PHPs isset() madness
class BelmontResponse {

  CONST MIN_RESPONSE_SIZE = 512;

  // Supported status codes
  private $_status_codes = array(
    200 => 'OK',
    405 => 'Method Not Allowed',
    500 => 'Internal Server Error'
  );

  // Actual response content
  private $_content = '';

  // Array of headers to send
  private $_headers = array();

  public function __construct ($content = '', $status_code = 200) {

    $this->_content = $content;

    $this->setHeader('Content-Type', 'text/html; charset=UTF-8');

    $this->setHeader('Content-Encoding', 'gzip');

    $this->setHeader('HTTP/1.1', "{$status_code} {$this->_status_codes[$status_code]}");

  }

  public function setHeader ($key, $value) {
    if ($this->_headers) {
      $this->_headers[$key] = $value;  
    } else {
      error_log('Headers already sent!!');
    }
  }

  private function _sendHeaders () {
    foreach ($this->_headers as $key => $value) {
      header("{$key}: {$value}");
    }
    $this->_headers = null;
    
    // Send cookies
  }

  public function send () {
    if (!headers_sent()) {
      $this->_sendHeaders();
    }
    echo $this->_content;
  }
}
