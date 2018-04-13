<?php

class Simple_modules {
	
	public $config;
	
	public function __construct($config = NULL) {
		$this->config = $config;
	}
	
	public function _return() {
		return $this->config;
	}
}