<?php
/**
 * Base class library
 *
 * @package    	Colibri
 * @subpackage 	local_colibri
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt> - {@link http://ued.ipleiria.pt | Learning Distance Unit }, Polytechnic Institute of Leiria
 * @license	{@link http://www.gnu.org/copyleft/gpl.html |  GNU GPL v3 or later}
 *
 */

defined('MOODLE_INTERNAL') || die();

if(!class_exists('UEDbase')):

    /**
     * Provides the base classe functionalities, like error reporting, etc
     *
     * @package    	Colibri
     * @subpackage 	local_colibri
     * @version		2011.0
     * @author		Cláudio Esperança <claudio.esperanca@ipleiria.pt> - {@link http://ued.ipleiria.pt | Learning Distance Unit }, Polytechnic Institute of Leiria
     * @license		http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
     */
    class UEDbase{
	// constants
	const GENERIC_ERROR = -1;
	const EXCEPTION = -2;
	const UNKOWN_ERROR = -3;

	/**
	 * @var <string> with the class name
	 */
	protected static $_className = __CLASS__;

	/**
	 * @var <array> with the class constants names
	 */
	protected static $_classConstants = NULL;

	/**
	 * Logs a error message to the PHP logger
	 *
	 * @param $message string with the string message
	 * @param $title string with the prefix for the message
	 * @param $forceOutput boolean to force the log entry even if the global debug flag is disabled
	 * @uses global $CFG for the debug current settings
	 * @uses global $COLIBRI for the module name
	 * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
	 */
	protected static function _log($message=NULL, $title='', $forceOutput=false){
	    global $CFG;

	    if(!empty($message) && ($forceOutput || !empty($CFG->debug))):
		error_log('['.static::$_className.(!empty($title)?" - $title":'')."] $message");
	    endif;
	}
	
	/**
	 * Returns a localized string from the module language pack
	 *
	 * @param $message string with the string message
	 * @param string|object|array $a An object, string or number that can be used within translation strings
	 * @param $module string with the module name
	 * @return String from the language pack
	 * @uses get_string to get a localized string
	 *
	 * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
	 */
	protected static function getString($message=NULL, $a = NULL, $module=COLIBRI_PLUGINNAME){
	    if(!empty($message)):
		return get_string($message, $module, $a);
	    endif;
	    return '';
	}

	/**
	 * Get the class constants names
	 *
	 * @return <array>
	 */
	protected static function getClassConstants(){
	    if(is_null(self::$_classConstants)){
		$oClass = new ReflectionClass(static::$_className);
		self::$_classConstants = $oClass->getConstants();
	    }
	    return self::$_classConstants;
	}

	/*
	 * Returns a numeric error code from the given string
	 *
	 * @param $errorString string with the original remote error message
	 * @return int with the error code if found
	 *
	 * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
	 */
	public static function getErrorCode($errorString){
	    self::getClassConstants();
	    return (!empty(self::$_classConstants[$errorString])?self::$_classConstants[$errorString]:self::UNKOWN_ERROR);
	}

	/*
	 * Returns the constant name string with the specified code
	 *
	 * @param $errorCode int error code
	 * @return String with the constant name, false if the errorcode wasn't found
	 *
	 * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
	 */
	public static function getErrorConstantName($errorCode){
	    $_classConstants = self::getClassConstants();
	    foreach($_classConstants as $constant=>$value):
		if($value==$errorCode):
		    return $constant;
		endif;
	    endforeach;
	    return false;
	}

	/*
	 * Returns the error string for the specified code
	 *
	 * @param $errorCode int error code
	 * @return String with the error string
	 * @uses get_string() to get localized strings
	 *
	 * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
	 */
	public static function getErrorString($errorCode){
	    if(!is_numeric($errorCode)):
		$errorCode = self::UNKOWN_ERROR;
	    endif;
	    $string = self::getErrorConstantName($errorCode);
	    if(empty ($string)):
		$string = self::getErrorConstantName(self::UNKOWN_ERROR);
	    endif;
	    return self::getString($string, $errorCode);
	}

	/**
	 * Merge user defined arguments into defaults array.
	 * 
	 * @param <array>|<object> $args Value to merge with $defaults
	 * @param <array> $defaults Array that serves as the defaults.
	 * @return <type> Merged user defined values with defaults.
	 * @example:
	 * $args = UEDbase::parseArgs( array('key1'=>'value1', 'key2'=>'value2'), array('key2'=>'value2', 'key3'=>'value3') );
	 * $args = (object) $args;
	 */
	public function parseArgs( $args, $defaults = '' ) {
	    if ( is_object( $args ) ):
		$r = get_object_vars( $args );
	    elseif ( is_array( $args ) ):
		$r =& $args;
	    else:
		return false;
	    endif;

	    if ( is_array( $defaults ) ):
		return array_merge( $defaults, $r );
	    endif;
	    return $r;
	}
    }
endif;
