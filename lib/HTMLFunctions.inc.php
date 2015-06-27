<?php

// Just some helper functions to create markup.

// $args == $content, $attributes
function html_tag ($tag, $args) {
  $content = isset($args[0]) ? $args[0] : '';
  $attributes = !empty($args[1]) ? $args[1] : array();
  $no_closing = isset($args[2]) ? $args[2] : false;

  $tag_string = "<$tag";

  if (is_string($attributes)) {
    $attributes = array('class' => $attributes);
  }

  foreach ($attributes as $key => $value) {
    $tag_string .= " $key=\"$val\"";
  }

  $tag_string .= ">{$content}";

  if (!$no_closing) {
    $tag_string .= "</$tag>";
  }

  return $tag_string;
}
function html_single_tag ($tag, $args) {
  $args[2] = true;
  return html_tag($tag, $args);
}

function html() { return html_tag('html', func_get_args()); }
function head() { return html_tag('head', func_get_args()); }
function title() { return html_tag('title', func_get_args()); }
function body() { return html_tag('body', func_get_args()); }
function h1() { return html_tag('h1', func_get_args()); }
function h2() { return html_tag('h2', func_get_args()); }
function h3() { return html_tag('h3', func_get_args()); }
function h4() { return html_tag('h4', func_get_args()); }
function h5() { return html_tag('h5', func_get_args()); }
function h6() { return html_tag('h6', func_get_args()); }
function a() { return html_tag('a', func_get_args()); }
function pre() { return html_tag('pre', func_get_args()); }
function span() { return html_tag('span', func_get_args()); }
function strong() { return html_tag('strong', func_get_args()); }
function table() { return html_tag('table', func_get_args()); }
function td() { return html_tag('td', func_get_args()); }
function tr() { return html_tag('tr', func_get_args()); }
function p() { return html_tag('p', func_get_args()); }
function form() { return html_tag('form', func_get_args()); }

function img() { return html_single_tag('img', func_get_args()); }
function br() { return html_single_tag('br', func_get_args()); }
function input() { return html_single_tag('input', func_get_args()); }