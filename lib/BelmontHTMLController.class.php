<?php

require_once 'lib/BelmontController.class.php';

class BelmontHTMLController extends BelmontController {

  CONST TEMPLATE_PATH = '/templates/';

  public function start () {
    $this->_response->send('<html><head></head><body>');
  }

  public function addTpl($template_name, $region = null) {
    // TODO validate template_name
    ob_start();
    require self::TEMPLATE_PATH . $template_name;
    $ret = ob_get_contents();
    ob_end_clean();
    $this->_response->send($ret);
  }

  public function end () {
    $this->_response->send('</body></html>');
  }

  public function setModel ($data) {

  }

}