<?php 
/**
 * Library for the Colibri component
 *
 * @package    	Colibri
 * @subpackage 	local_colibri
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt> - {@link http://ued.ipleiria.pt | Learning Distance Unit }, Polytechnic Institute of Leiria
 * @license	{@link http://www.gnu.org/copyleft/gpl.html |  GNU GPL v3 or later}
 *
 * SVN:
 * $Author$
 * $Date$
 * $Rev$
 */

// Terminate on direct file access
defined('MOODLE_INTERNAL') or die(''); // silence is golden

// Set the plugin name
if(!defined('COLIBRI_PLUGINNAME')):
    define('COLIBRI_PLUGINNAME', 'local_colibri');
endif;

// Set the library directory
if(!defined('COLIBRI_LIB_DIR')):
    define('COLIBRI_LIB_DIR', dirname(__FILE__) . '/lib/');
endif;

// set the autoload to load the library classes as needed
set_include_path(get_include_path().PATH_SEPARATOR.COLIBRI_LIB_DIR);
spl_autoload_register(function($className){
    $file = COLIBRI_LIB_DIR."{$className}.php";
    if(is_readable($file)):
        include_once($file);
    endif;
});

// Load the service class of the Colibri module
require_once($CFG->dirroot.'/mod/colibri/locallib.php');

