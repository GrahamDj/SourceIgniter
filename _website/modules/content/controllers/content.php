<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');

class Content_Controller extends Base_module {
	
	public function __construct() {
		log_message('debug', "Class ".get_class($this)." Initialized.");
	
		parent::__construct();
		
	}
	
	public function index() {
		$this->load->library('simple_modules');
		
		$config['config'] = $this->simple_modules->_return();
		
		ob_start();
		$this->load->view('example', $config );
		$buffer = ob_get_clean();
		return $buffer;
	}
	
}