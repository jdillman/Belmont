<?php

class BelmontResponse {

  CONST HTTP_HEADER = 'HTTP/1.1';

  CONST MIN_RESPONSE_SIZE = 512;

  // Supported status codes
  private $_status_codes = array(
    200 => 'OK',
    405 => 'Method Not Allowed',
    500 => 'Internal Server Error'
  );

  // Array of headers to send
  private $_headers = array();

  // Actual response content
  private $_content = '';

  public function __construct ($content = '', $code = 200) {

    $this->_content = $content;

    $this->setStatusCode($code);
    $this->setHeader('Content-Type', 'text/html; charset=UTF-8');
    // $this->setHeader('Content-Encoding', 'gzip');
  }

  public function setStatusCode ($code) {
    http_response_code($code);
    $this->setHeader(self::HTTP_HEADER, "{$code} {$this->_status_codes[$code]}");
  }

  public function setHeader ($key, $value) {
    if (!is_null($this->_headers)) {
      $this->_headers[$key] = $value;
    } else {
      echo('Headers already sent!!');
      echo('Cant set ' . $key);
    }
  }

  private function _sendHeaders () {
    if ($this->_headers) {
      foreach ($this->_headers as $key => $value) {
        header("{$key}: {$value}");
      }
      $this->_headers = null;
    }
    
    // Send cookies
  }

  public function send ($content = '', $flush = false) {
    if (!headers_sent()) {
      $this->_sendHeaders();
    }

    echo $content;
  }
}
