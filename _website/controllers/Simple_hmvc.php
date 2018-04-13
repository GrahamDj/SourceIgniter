<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Simple_hmvc extends CI_Controller {

	public function index() {
		$params = array();
		$params['arg1'] = 'My name is';
		$params['arg2'] = 'Your name is';
		$params['arg3'] = 'His name is';
		$output = $this->load->module('content', 'test', $params);
		
		echo $output;
	}
}
