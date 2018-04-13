<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * My Loader
 *
 * This class extends the Crossfire core Loader. It adds the methods for
 * using Simple HMVC in your CodeIgniter 3.x projects. It also adds 
 * a method for using views stored in your libraries directory
 *
 * @author  Martin Langenberg
 */

class MY_Loader extends CI_Loader {
	
	public function __construct() {
		log_message('debug', "Class ".get_class($this)." Initialized.");
	
		parent::__construct();

		$this->helper('path');
	}
	
	/**
	 * Set the view path for a library, or module library
	 *
	 * Prepends a library view path to the view paths array.
	 * Enables using views from within a library. This helps keep
	 * related files in the same parent directory
	 *
	 * @param	string	name of the library
	 */
	public function library_view($library, $view_cascade = TRUE, $module = NULL) {		
		// Are we requesting a module library and it's own views?
		if( ! is_null($module) ) {
			$application = $this->_load_module($module);
			if( array_key_exists('dir_path', $application) ) {
				$path = $application['dir_path'].'libraries/'.$library.'/';
			}
		} else {
			$path = APPPATH.'libraries/'.$library.'/';
		}
		
		$this->_ci_view_paths = array($path.'views/' => $view_cascade) + $this->_ci_view_paths;
	}
	
	###################################################################################
	# HMVC MODULE METHODS
	###################################################################################
	
	/**
	 * Load module and execute the called method
	 * 
	 * @param string $module
	 * @param string $method
	 * @param array $params
	 * @return mixed, the output of the called controller/method combination
	 */
	public function module($module, $method = NULL, $params = array() ) {
		// Define the base path to the modules directory
		if ( ! defined('MOD_BASE_PATH') ) {
			define('MOD_BASE_PATH', APPPATH.'modules/');
		}
		
		require_once MOD_BASE_PATH.'base_module.php';
		
		$dir = NULL;
		$dir_path = NULL;
		$controller = NULL;
		$controller_path = NULL;
	
		// Get the CI Super object
		$CI =& get_instance();
	
		// Load main Controller or sub controller?
		$application = $this->_load_module($module);
		$application['method'] = $method;
		$application['params'] = $params;
	
		if( ! array_key_exists('error', $application) ) {
			foreach ($application as $key => $value) {
				$$key = $value;
			}
		} else {
			return $this->_module_error($module, $application);
		}
	
		// Has the Controller already been loaded? No, then load it
		if( ! property_exists($CI, $controller) ) {
			$this->_ci_module_paths($dir_path, TRUE);
			
			// Include the file
			log_message('debug', "The module {$controller} has been loaded.");
			require_once $controller_path;
	
			// Auto load config file for the loaded controller
			$config = NULL;
			if( $config_path = set_realpath($dir_path.'/config/'.$controller.'.php', TRUE) ) {
				require_once $config_path;
			}
	
			// Create the new Object of the required module and register as loaded
			$object = ucfirst($controller).'_Controller';
			$app = new $object($config);
			
			// Add a reference to the CI super object for easy use later on
			$CI->{$controller} = $app;
				
		} else {
			log_message('debug', "The controller {$controller} of module {$dir} has already been loaded. Second attempt ignored");
		}
	
		// Execute called method if set
		if( ! is_null($method) ) {
			// Check if called method exists
			if( ! method_exists($app, $method) ) {
				$application['error_type'] = "Unknow method {$method}()";
				$application['error'] = "The method <strong>\"{$method}\"</strong> does not exist in the called controller <strong>\"{$controller}\"</strong> of the requested module <strong>\"{$dir}\"</strong>";
				return $this->_module_error($module, $application);
			}
			// Run and return
			return $CI->{$controller}->$method($params);
		}
		
		// Return the controller object;
		return $CI->{$controller};
	}
	
	/**
	 * Add module paths to the corresponding paths arrays
	 *
	 * This method only loads the paths of a given module.
	 * Makes it possible to use any module resource in a second HMVC module
	 * without the overhead of the complete module controller
	 * 
	 * @param string $module
	 */
	public function module_paths($module) {
		// Get the CI Super object
		$CI =& get_instance();
		
		// Load main Controller or sub controller?
		$application = $this->_load_module($module);
		
		if( array_key_exists('error', $application) ) {
			log_message('error', "{$application['error_type']}. {$application['error']}");
		}
		
		// Check if module has already been loaded
		if( ! property_exists($CI, $application['controller']) && array_key_exists('dir_path', $application) ) {
			$this->_ci_module_paths($application['dir_path'], TRUE);
		}
		
		return;
	}
	
	/**
	 *
	 * @param string $module
	 * @return string
	 */
	private function _load_module($module) {
		$dir = '';
	
		// Are we calling a sub controller?
		if( ($last_slash = strrpos($module, '/') ) !== FALSE) {
			// The dir is in front of the last slash
			$dir = str_replace('/', '', substr($module, 0, $last_slash + 1) );
	
			// And the module name behind it
			$controller = substr($module, $last_slash + 1);
		} else {
			$dir = $module;
			$controller = $module;
		}
	
		if( $dir_path = set_realpath(MOD_BASE_PATH.$dir, TRUE) ) {
			if($controller_path = set_realpath(MOD_BASE_PATH.$dir.'/controllers/'.$controller.'.php', TRUE) ) {
				return array('dir'=>$dir,'dir_path'=>$dir_path,'controller'=>$controller, 'controller_path'=>$controller_path);
			} else {
				$error_type = "Unknow controller {$controller}";
				$error = "The controller <strong>\"{$controller}\"</strong> for the requested module <strong>\"{$dir}\"</strong> does not exist";
			}
		} else {
			$error_type = "Unknow controller {$dir}";
			$error = "The requested module <strong>\"{$dir}\"</strong> does not exist";
		}
	
		return array('error_type'=>$error_type,'error'=>$error,'dir'=>$dir,'controller'=>$controller);
	}
	
	/**
	 * Add module paths to the corresponding paths arrays
	 *
	 * This method adds the available paths for a requested HMVC module to the already set
	 * paths array used for models, libraries, helpers, views and configs
	 * 
	 * @param string $path
	 * @param boolean $view_cascade
	 */
	private function _ci_module_paths($path, $view_cascade = TRUE) {
		if(set_realpath($path.'libraries', TRUE) ) {
			array_unshift($this->_ci_library_paths, $path);
		}
		if(set_realpath($path.'models', TRUE) ) {
			array_unshift($this->_ci_model_paths, $path);
		}
		if(set_realpath($path.'helpers', TRUE) ) {
			array_unshift($this->_ci_helper_paths, $path);
		}
		if($viewpath = set_realpath($path.'views', TRUE) ) {
			// Add the path for loading views
			$this->_ci_view_paths = array($viewpath => $view_cascade) + $this->_ci_view_paths;
		}
		
		// Add config file path
		if($viewpath = set_realpath($path.'config', TRUE) ) {
			$config =& $this->_ci_get_component('config');
			array_unshift($config->_config_paths, $path);
		}
	}
	
	/**
	 * _module_error
	 * 
	 * @param string $module
	 * @param array $application
	 * @return string
	 */
	private function _module_error($module, $application) {
		log_message('error', strip_tags($application['error']));
		
		ob_start();
		$this->view('errors/html/error_module', array('module'=>$module,'application'=>$application) );
		$buffer = ob_get_clean();
		return $buffer;
	}
	
}