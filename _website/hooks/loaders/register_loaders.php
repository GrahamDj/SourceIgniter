<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Register loaders
 *
 * This file is loaded by the pre system hook and
 * calls the register_loaders() method
 *
 * The register_loaders() method will then register all declared
 * auto loaders.
 */

if ( ! function_exists('register_loaders') )
{
    function register_loaders()
    {
        // Register the autoloaders
        spl_autoload_register('controllers');
        spl_autoload_register('modules');
        spl_autoload_register('libraries');
    }
}

/*===============================================================================
 * ADD YOUR LAZY/AUTO LOADERS HERE
 *===============================================================================
 */

if ( ! function_exists('controllers') )
{
    /**
     * Controllers
     * Auto loads base controllers
     *
     * @author	Martin Langenberg	langenbergmartin@gmail.com
     *
     * @param 	string 	$class		The class that will be auto loaded
     */
    function controllers($class)
    {
        // Filenames and sub directories must start with uppercase, compliant with CodeIgniter 3 Guidelines
        $classname = ucfirst($class);

        // Does the class exist in a sub directory?
        if( is_dir(APPPATH . "controllers/{$classname}") )
        {
            $classname = $class."/".ucfirst($class);
        }

        $class_path = APPPATH . "controllers/{$classname}.php";
        if( file_exists($class_path) )
        {
            require_once $class_path;
        }
    }
}

//-------------------------------------------------------------------------------

if ( ! function_exists('modules') )
{
    /**
     * Basemodules
     * Auto loads base modules
     *
     * @author	Martin Langenberg	langenbergmartin@gmail.com
     *
     * @param 	string 	$class		The class that will be auto loaded
     */
    function modules($class)
    {
        // Filenames and sub directories must start with uppercase, compliant with CodeIgniter 3 Guidelines
        $classname = ucfirst($class);

        // Does the class exist in a sub directory?
        if( is_dir(APPPATH . "modules/basemodules/{$classname}") )
        {
            $classname = $class."/".ucfirst($class);
        }

        $class_path = APPPATH . "modules/basemodules/{$classname}.php";
        if( file_exists($class_path) )
        {
            require_once $class_path;
        }
    }
}

//-------------------------------------------------------------------------------

if ( ! function_exists('libraries') )
{
    /**
     * baselibraries
     * Auto loads base libraries
     *
     * @author	Martin Langenberg	langenbergmartin@gmail.com
     *
     * @param 	string 	$class		The class that will be auto loaded
     */
    function libraries($class)
    {
        // Filenames and sub directories must start with uppercase, compliant with CodeIgniter 3 Guidelines
        $classname = ucfirst($class);

        // Does the class exist in a sub directory?
        if( is_dir(APPPATH . "libraries/{$classname}") )
        {
            $classname = $class."/".ucfirst($class);
        }

        $class_path = APPPATH . "libraries/{$classname}.php";
        if( file_exists($class_path) )
        {
            require_once $class_path;
        }
    }
}

//-------------------------------------------------------------------------------
