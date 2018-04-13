<?php
/**
 * Crossfire CMS
 *
 * @package   Crossfire CMS
 * @author    Martin Langenberg
 * @copyright Copyright (c) 2011 - 2015, Martin Langenberg
 * @since     Version 2.0
 * @filesource
 */

/**
 * Form Validation
 *
 * This class replaces the CodeIgniter path_helper method set_realpath().
 * It now returns FALSE if existance check fails instead of throwing an
 * error. Futher the $check_existance bool now defaults to TRUE instead of FALSE
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Path Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/path_helper.html
 */

// ------------------------------------------------------------------------

if ( ! function_exists('set_realpath'))
{
	/**
	 * Set Realpath
	 *
	 * @param	string
	 * @param	bool	checks to see if the path exists
	 * @return	string
	 */
	function set_realpath($path, $check_existance = TRUE)
	{
		// Security check to make sure the path is NOT a URL. No remote file inclusion!
		if (preg_match('#^(http:\/\/|https:\/\/|www\.|ftp)#i', $path) OR filter_var($path, FILTER_VALIDATE_IP) === $path )
		{
			return FALSE;
		}

		// Resolve the path
		if (realpath($path) !== FALSE)
		{
			$path = realpath($path);
		}
		elseif ($check_existance && ! is_dir($path) && ! is_file($path))
		{
			return FALSE;
		}

		// Add a trailing slash, if this is a directory
		return is_dir($path) ? rtrim($path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR : $path;
	}
}
