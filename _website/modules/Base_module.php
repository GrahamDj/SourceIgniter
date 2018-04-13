<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Simple HMVC Base module
 * 
 * This class is the base for Simple HMVC modules. It is auto loaded when requesting a 
 * Simple HMVC module. Extend your own module controller from this base the have access
 * to the base functions that you add your self
 *
 * @author  Martin Langenberg
 */

class Base_module {
	
	public function __construct() {
		
		log_message('debug', "Class ".get_class($this)." Initialized.");
	
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
	
}