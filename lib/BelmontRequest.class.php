<?php

// TODO extend from data class that fixes PHPs isset() madness
class BelmontRequest {

	// GET and POST params
	private $_params = array();

	private $_uri = null;

	private $_method = null;

	public function __construct () {

		// We filter the GET and POST params in the get() method
		$get = $_GET;
		$post = $_POST;

		if (is_array($get)) {
			$this->_params = array_merge($this->_params, $get);
		}
		if (is_array($post)) {
			$this->_params = array_merge($this->_params, $post);
		}

		$this->_uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRIPPED);

		$this->_method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRIPPED);
		
	}

	public function getMethod () {
		return $this->_method;
	}

	public function getUri () {
		return $this->_uri;
	}

	public function getRawValue (string $key, $default_value = null) {
		$ret = $default_value;
		if (isset($this->params[$key])) {
			$ret = $this->params[$key];
		}
		return $ret;
	}

	public function get (string $key, $default_value = null) {
		return htmlspecialchars($this->getRawValue($key, $default_value), ENT_QUOTES);
	}

	public function set (string $key, $value) {
		$this->params[$key] = $value;
		return $value;
	}


}