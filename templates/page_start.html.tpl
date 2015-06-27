<?php

require_once 'lib/HTMLfunctions.inc.php';

$title = $tpl->get('title');
$meta = $tpl->get('meta');
$js = $tpl->get('js');
$css = $tpl->get('css');

$meta_tags = '';
foreach ($meta as $name => $content) {
  //$meta_tags .= meta()
}

$scripts = '';
foreach ($js as $script => $props) {
  $scripts .= '<script></script>';
}

$stylesheets = '';
foreach ($css as $styesheet) {
  $stylesheets .= '<link></link>';
}

echo <<< HTML
<html>
  <head>
    <title>{$title}</title>
    {$meta_tags}
    {$stylesheets}
    {$scripts}
  </head>
  <body>
HTML;

