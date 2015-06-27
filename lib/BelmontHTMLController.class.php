<?php

require_once 'lib/BelmontController.class.php';
require_once 'lib/BelmontTemplate.class.php';

class BelmontHTMLController extends BelmontController {

  CONST TEMPLATE_PATH = '/templates/';
  CONST JS_PATH       = '/js/';
  CONST CSS_PATH      = '/css/';

  // Page parameters (title, metatags)
  protected $_page_params = array(
    'title' => 'Belmont web framework',
    'meta' => array(
      'description' => 'Meta description'
    ),
  );

  // Scripts to be included
  protected $_scripts = null;

  // Stylesheets to be included
  protected $_styles = null;

  // Have we started the page already? (meaining we've sent <html><head>)
  private $_started = false;

  // Regions are special sections of the page that can contain 
  // 1 or many templates, scripts and styles grouped together
  protected $_regions = array();

  public function beforeStart () {
    // Implement in your child class 
  }

  public function beforeEnd () {
   // Implement in your child class 
  }

  public function handleGET () {
    $this->beforeStart();
    $this->start($this->_page_params);

    // If there is a buildPage method lets assume they wan
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

  // Let's begin the page! Create the HTML, HEAD and open the BODY
  public function start ($page_params) {

    // Validate the page parameters
    $title = isset($page_params['title'])
      ? $page_params['title']
      : '';

    $meta_array = isset($page_params['meta'])
      ? $page_params['meta']
      : array();

    $this->addJS('utils.js');
    $this->addJS('core.js');
    $this->addCSS('reset.css');
    $this->addCSS('common.css');

    // Grab assets for all the regions
    foreach ($this->_regions as $region_id => $region) {
      $this->_addRegionAssets($region_id, $region);
    }

    // Start the page!!
    $page = $this->addTpl('page_start', array(
      'css' => $this->_getCSSIncludes(),
      'js' => $this->_getJSIncludes(),
      'title' => $title,
      'meta' => $meta_array
    ));

    $this->_response->send($page);
    $this->_started = true;

    return $this->_started;
  }

  private function _addRegionAssets ($region_id, $region) {
    if (isset($region['js'])) {
      if (!is_array($region['js'])) {
        $region['js'] = array($region['js']);
      }
      foreach ($region['js'] as $script) {
        $this->addJS($script, $region_id);
      }
    }
    if (isset($region['css'])) {
      if (!is_array($region['css'])) {
        $region['css'] = array($region['css']);
      }
      foreach ($region['css'] as $script) {
        $this->addCSS($script);
      }
    }

    return true;
  }

  // Returns any CSS files that haven't been included yet
  private function _getCSSIncludes () {
    $ret = array();
    foreach ($this->_styles as $stylesheet => $included) {
      if (!$included) {
        $this->_styles[$stylesheet] = true;
        array_push($ret, self::CSS_PATH . $stylesheet);
      }
    }
    return $ret;
  }

  private function _getJSIncludes () {
    $ret = array();
    foreach ($this->_scripts as $script => $script_data) {
      if (!$script_data['included']) {
        $this->_scripts[$script]['included'] = true;
        array_push($ret, array(self::JS_PATH . $script => $script_data['region_id']));
      }
    }
    return $ret;
  }

  public function addJS ($script_name, $region_id = null, $include_now = false) {
    /* Whitelist JS possibly?
    if (!$this->checkWhiteList($script_name)) {
      return false;
    }*/

    if ($include_now) {
      $script_html = '<script></script>';
      $this->response->send($script_html);
    }
    
    $this->_scripts[$script_name] = array(
      'region_id' => $region_id || 'body',
      'included' => $include_now
    );

    return true;
  }

  public function addCSS ($stylesheet_name, $region_id = null) {

    $loaded = false;
    if ($this->_started) {
      // not recommended but possible
      $loaded = true;
    }

    $this->_styles[$stylesheet_name] = $loaded;

    return true;
  }

  public function addTpl(
    $template,
    $data = array(),
    $region_id = null
  ) {

    // TODO validate template

    $tpl = new BelmontTemplate($data);

    ob_start();
    require self::TEMPLATE_PATH . $template . '.html.tpl';
    $ret = ob_get_contents();
    ob_end_clean();

    // Wrap this template in a region div
    if ($region_id) {
      // Check schema to create conf settings
      $ret = div($ret, array(
        'data-region' => $region_id
      ));
    }

    $this->_response->send($ret);
  }

  // Include
  public function end () {
    foreach ($this->_scripts as $script => $script_) {
      //$this->addTpl($script);
    }
    //$this->addTpl('tracking');
    $this->_response->send('</body></html>');
  }

  public function setModel ($data) {

  }

 
}