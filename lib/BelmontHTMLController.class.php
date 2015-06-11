<?php

require_once 'lib/BelmontController.class.php';

class BelmontHTMLController extends BelmontController {

  CONST TEMPLATE_PATH = '/templates/';
  CONST JS_PATH = '/js/';
  CONST CSS_PATH = '/css/';

  // Page parameters (title, metatags)
  protected $_page_params = array(
    'title' => 'Belmont web framework',
    'meta' => array(
      'description' => 'Meta description'
    )
  );

  // Regions are special sections of the page that can contain 
  // 1 or many templates, scripts and styles grouped together
  protected $_regions = null;

  // Scripts to be included
  protected $_scripts = null;

  // Stylesheets to be included
  protected $_styles = null;

  // Have we started the page already?
  private $_started = false;

  public function start ($page_params) {

    $title = isset($page_params['title'])
      ? $page_params['title']
      : ''; // self::DEFAULT_TITLE;

    $meta = array();
    if (isset($page_params['meta'])) {
      foreach ($page_params['meta'] as $tag => $content) {
//<meta name="description" content="test">
      }
    }
    
    $meta = '';
    $stylesheets = '';
    $head_scripts = '';
    
    // Begin the page (TODO put markup in a .tpl file)
    $page = <<< HTML
<html>
  <head>
    <title>{$title}</title>
    {$meta}
    {$this->addCSS('reset.css')}
    {$this->addCSS('common.css')}
    {$stylesheets}
    {$this->addJS('belmont.js')}
    {$head_scripts}
  </head>
  <body>
HTML;

    $this->_response->send($page);
    $this->_started = true;

    return $this->_started;
  }

  public function addJS ($script_name, $defer = false, $region_id = null) {

    $script_html = '<script type="text/javascript" src="' . $script_name . '"></script>';

    // Do we want to include this script now or wait and do it at page end
    $included = true;
    if ($this->_started) {
      if ($defer) {
        $included = false;
      } else {
        $this->_response->send($script_html);
      }
    }

    $this->_scripts[$script_name] = array(
      'region' => $region_id || 'body',
      'included' => $included
    );

    return $script_html;

  }

  public function addCSS ($stylesheet_name, $region_id = null) {

    $stylesheet_html = '<link type="text/css" rel="stylesheet" href="' . $stylesheet_name . '">';

    /*if ($smart_load && $this->_started) {
      $this->response->send('');
    }*/

    return $stylesheet_html;

  }

  public function addTpl($template_name, $region_id = null) {
    // TODO validate template_name

    $html = null; // TODO html tags class
    $model = null; //$this->_model;

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
        if (isset($region['css'])) {
          $this->addCSS($region['css']);
        }
        if (isset($region['tpl'])) {
          $this->addTpl($region['tpl'], $region_id);  
        }
        if (isset($region['js'])) {
          $this->addJS($region['js']);
        }

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