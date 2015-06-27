<?php

require_once 'lib/BelmontHTMLController.class.php';

class HomeController extends BelmontHTMLController {

  protected $_page_params = array(
    'title' => 'Home',
    'keywords' => 'Keywords, foo, bar',
  );

  protected $_regions = array(
    'main' => array(
      'tpl' => 'home',
      'css' => 'main.css',
      'js' => 'main.js',
      'events' => array(
        'sidebar::select' => 'refresh'
      )
    ),
    'sidebar' => array(
      'tpl' => 'sidebar',
      'css' => 'sidebar.css',
      'events' => array(
        'js:onSelect' => 'sidebar::select'
      )
    ),
  );

}