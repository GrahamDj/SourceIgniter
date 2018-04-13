<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Simple HMVC Autoloader
 * 
 * Simple HMVC does not extend the core autoload function. To autoload modules or any module components
 * you can load them in this library and have CI auto load this library. 
 * It's a simple workaround and like Simple HMVC does not replace or effect core CI code
 * 
 * NOTE: When auto loading, only load the module as an object so it is available through 
 * the CI super object as $this->your_module->some_method(); Calling a method through this
 * library will only complicate your code ;)
 *
 * @author  Martin Langenberg
 */

class Module_loader {
	
	// Set to TRUE to autoload based on route
	private $load_by_route = TRUE;
	
	/* The CI uri segments
	 * You can use this to base auto loading on a specific route
	 */
	private $segments = array();
	
	public function __construct($config = NULL) {
		
		log_message('debug', "Class ".get_class($this)." Initialized.");
		
		if($this->load_by_route) {
			$this->segments = $this->uri->segments;
			// No segments available, set as home
			if( ! array_key_exists(1, $this->segments) ) {
				$this->segments[1] = 'home';
			}
		}
		
		$this->_autoload();
	}
	
	/**
	 * __get
	 *
	 * Enables the use of CI super-global without having to define an extra variable.
	 *
	 * @access	public
	 * @param	$var
	 * @return	mixed
	 */
	public function __get($var) {
		return get_instance()->$var;
	}
	
	/**
	 * _autoload
	 * 
	 * Auto loads any module or module components you want
	 */
	private function _autoload() {
		
		if($this->load_by_route) {
			
			// Load these modules based on route
			switch ( $this->segments[1] ) {
				case 'home':
					// Load a module main controller object
					$this->load->module('your_module');
					
					// Load a module sub controller object
					$this->load->module('your_module/sub_controller');
					
					// Load module component(s)
					$this->load->module_paths('some_module');
					// Load the components you want like loading any available CI resource
					$this->load->library('some_module_library');
					$this->load->helper('some_module_helper');
					$this->load->model('som_module_model');
					
					break;
			}
		}
		
		// Always load these modules
	}
	
}