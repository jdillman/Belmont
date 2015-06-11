<?php

require_once 'lib/BelmontController.class.php';

class BelmontHTMLController extends BelmontController {

  CONST TEMPLATE_PATH = '/templates/';
  CONST JS_PATH = '/js/';
  CONST CSS_PATH = '/css/';

  protected $_model = null;

  protected $_regions = null;

  protected $_page_params = null;

  public function start ($page_params) {
    $this->addJS('belmont.js');
    $this->addCSS('reset.css');
    $this->addCSS('common.css');
    $this->_response->send('<html><head></head><body>');
  }

  public function addJS ($script_name) {

  }

  public function addCSS ($stylesheet_name) {

  }

  public function addTpl($template_name, $region_id = null) {
    // TODO validate template_name

    $html = null; // TODO html tags class
    $model = $this->_model;

    ob_start();
    require self::TEMPLATE_PATH . $template_name;
    $ret = ob_get_contents();
    ob_end_clean();

    if ($region_id) {
      // Check schema to create conf settings
      $ret = '<div data-region="' . $region_id . '">' . $ret . '</div>';
    }

    $this->_response->send($ret);
  }

  public function end () {
    $this->_response->send('</body></html>');
  }

  public function setModel ($data) {

  }

  public function handleGET () {
    $this->beforeStart();
    $this->start($this->_page_params);

    if (method_exists($this, 'buildPage')) {
      $this->buildPage();
    } else if (!empty($this->_regions)) {
      foreach ($this->_regions as $region_id => $region) {
        $this->addTpl($region['tpl'], $region_id);
      }
    }
    
    $this->beforeEnd();
    $this->end();

    return true;
  }

  public function beforeStart () {
    // Implement in your child class 
  }

  public function beforeEnd () {
   // Implement in your child class 
  }

}