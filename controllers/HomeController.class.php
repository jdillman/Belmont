<?php

require_once 'lib/BelmontHTMLController.class.php';

class HomeController extends BelmontHTMLController {

  protected $_page_params = array(
    'title' => 'Home',
    'keywords' => 'Keywords list, foo, bar',
  );

  protected $_regions = array(
    'main' => array(
      'tpl' => 'home.html.tpl',
      'css' => 'main.css',
      'js' => 'main.js',
      'schema' => array(
        'polling' => 30, // 30 sec refresh
      )
    ),
    'sidebar' => array(
      'tpl' => 'sidebar.html.tpl',
      'css' => 'sidebar.css',
    ),
  );

  public function beforeStart () {
    // Modify the $_page_params or $_regions before we start the page
  }

  public function regionSchema ($region_id) {
    switch ($region_id) {
      case 'main':
        break;
    }
  }

  

}