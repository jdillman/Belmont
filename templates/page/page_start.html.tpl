<?php

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
  $attrs = array(
    'src' => $script,
    'type' => 'text/javascript'
  );
  if (!empty($props['region_id'])) {
    $attrs['data-region'] = $props['region_id'];
  }
  $scripts .= $tags->script('', $attrs);
}

$stylesheets = '';
foreach ($css as $stylesheet) {
  $stylesheets .= $tags->link('', array(
    'rel' => 'stylesheet',
    'href' => $stylesheet
  ));
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

