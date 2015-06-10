<?php

require_once 'lib/BelmontHTMLController.class.php';

class HomeController extends BelmontHTMLController {

  public function handleGET () {
    
    $this->setModel(array(
      'name' => '',
      'foo' => '',
    ));

    $this->start(array(
      'title' => 'Home',
      'keywords' => 'Keywords list, foo, bar',
      'css' => array(
        'header.css',
        'home.css'
      ),
      'js' => array(
        'home.js'
      )
    ));

    $this->addTpl('home.html.tpl');

    if (true /* testing something*/) {
      //$this->bindTpl('jsbindings.html.tpl', array('name', 'foo'));
    }

    $this->end();
    
    return true;
  }

}