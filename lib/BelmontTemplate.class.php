<?php

class BelmontTemplate {

  // Key/value store
  protected $data = array();

  public function __construct ($data) {
    $this->data = $data;
  }

  public function get ($key, $default = null) {
    $ret = $this->getRaw($key, $default);
    if (!is_array($ret)) {
      $ret = htmlspecialchars($ret, ENT_QUOTES, 'UTF-8', true);
    }
    
    return $ret;
  }

  public function getRaw ($key, $default = null) {
    $ret = $default;
    if (isset($this->data[$key])) {
      $ret = $this->data[$key];
    }

    return $ret;
  }

}