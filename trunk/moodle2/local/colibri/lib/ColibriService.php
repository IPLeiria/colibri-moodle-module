<?php
/**
 * Class library for the Colibri webservice communication
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

defined('MOODLE_INTERNAL') || die();

if(!class_exists('ColibriService')):

    /**
     * Provides the Colibri client to connect with the remote Colibri service
     *
     * @package    	Colibri
     * @subpackage 	local_colibri
     * @version	2011.0
     * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt> - {@link http://ued.ipleiria.pt | Learning Distance Unit }, Polytechnic Institute of Leiria
     * @license	http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
     */
    class ColibriService extends UEDbase{
	// constants
	const SOAP_INSTANCE_ERROR = -4;
	const SOAP_FAULT = -5;
	const INVALID_START_DATE = -6;
	const INVALID_END_DATE = -7;
	const INVALID_NUMBER_OF_PARTICIPANTS = -8;
	const ERROR_ON_METHOD_RESPONSE = -9;
	const INVALID_ACCESS_CREDENTIALS = -10;
	const MISSING_ACCESS_CREDENTIALS = -11;
	const COULD_NOT_CREATE_SESSION = -12;
	const MISSING_SESSION_NAME = -13;
	const SESSION_START_TIME_GREATER_THAN_ENDTIME = -14;
	const COULD_NOT_GET_SESSION_INFO = -15;
	const COULD_NOT_MODIFY_SESSION = -16;
	const COULD_NOT_REMOVE_SESSION = -17;
	const INVALID_NAME = -18;

	/**
	 * @var <string> with the class name
	 */
	protected static $_className = __CLASS__;

	/**
	 * @var <SoapClient> with the client instance to (re)use
	 */
	private static $_soapClient = NULL;

	/**
	 * @var <String> with the webservice URL
	 */
	private static $_url = NULL;

	/**
	 * @var <accessCredentials> to use for the authentication with the webservice
	 */
	private static $_accessCredentials;

	/**
	 * We will be using a private constructor for single SoapClient instance (re)use
	 *
	 * @param $wsdl with the service URL to use on the SoapClient link
	 * @uses global $CFG for the proxy settings
	 * @uses get_config() to load configurations from Moodle
	 * @uses get_string() to get localized strings
	 *
	 * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
	 */
	private function __construct($wsdl=NULL){
	    global $CFG;
	    try{
		self::load();
		
		self::$_url = $wsdl;
		self::$_soapClient = NULL;
		self::$_accessCredentials = new accessCredentials();

		// Get the WSDL url from the module configuration
		if(is_null($wsdl)):
		    if(!($wsdl = get_config(COLIBRI_PLUGINNAME, 'colibri_wsdl_url'))):
			throw new Exception(get_string('webServiceLinkFailed', COLIBRI_PLUGINNAME));
		    endif;
		endif;

		// cache the credentials
		self::$_accessCredentials = new accessCredentials(get_config(COLIBRI_PLUGINNAME, 'colibri_installation_identifier'), get_config(COLIBRI_PLUGINNAME, 'colibri_installation_password'));

		// Enable trace
		$options = array('trace' => true);

		// Map the remote classes with the PHP classes
		$options['classmap'] = array(
		    'getColibriTime'=>'getColibriTime',
		    'getColibriTimeResponse'=>'getColibriTimeResponse',
		    'removeSession'=>'removeSession',
		    'accessCredentials'=>'accessCredentials',
		    'sessionScheduleInfo'=>'sessionScheduleInfo',
		    'removeSessionResponse'=>'removeSessionResponse',
		    'sessionResult'=>'sessionResult',
		    'recordingInfo'=>'recordingInfo',
		    'getSessionInfo'=>'getSessionInfo',
		    'getSessionInfoResponse'=>'getSessionInfoResponse',
		    'getSessionsInfo'=>'getSessionsInfo',
		    'getSessionsInfoResponse'=>'getSessionsInfoResponse',
		    'createSession'=>'createSession',
		    'createSessionResponse'=>'createSessionResponse',
		    'modifySession'=>'modifySession',
		    'modifySessionResponse'=>'modifySessionResponse'
		);

		// If we have proxy settings, use them to connect to the client
		if(!empty($CFG->proxyhost)):
		    $options['proxy_host']=$CFG->proxyhost;
		endif;
		if(!empty($CFG->proxyport)):
		    $options['proxy_port']=$CFG->proxyport;
		endif;
		if(!empty($CFG->proxyuser)):
		    $options['proxy_login']=$CFG->proxyuser;
		endif;
		if(!empty($CFG->proxypassword)):
		    $options['proxy_password']=$CFG->proxypassword;
		endif;

		// Verify if we have a connection to the server
		if(!@file_get_contents($wsdl)):
		    throw new SoapFault('-1', get_string('unableToConnectToTheServer', COLIBRI_PLUGINNAME));
		endif;
		// Define the client
		self::$_soapClient = new SoapClient($wsdl, $options);

	    }catch(Exception $ex){
		self::_log(get_string('webServiceLinkFailed', COLIBRI_PLUGINNAME));
	    }
	}

	/**
	 * Magic method to call remote SOAP methods that aren't specified on this class
	 *
	 * @param String $name with the name of the function
	 * @param array $arguments with the function arguments
	 * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
	 */
	public function __call($name, $arguments) {
	    return self::__callStatic($name, $arguments);
	}

	/**
	 * Magic method to call remote SOAP methods that aren't specified on this class
	 *
	 * @param String $name with the name of the function
	 * @param array $arguments with the function arguments or negative integer with the error code
	 * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
	 */
	public static function __callStatic($name, $arguments) {
	    try{
		$client = self::getSoapClientInstance();

		// checks for a valid client
		if(is_null($client)):
		    self::_log(self::getString('unableToCreateSoapClientInstance'), __FUNCTION__);
		    return self::SOAP_INSTANCE_ERROR;
		endif;

		if(isset($arguments[0])):
		    return $client->$name($arguments[0]);
		else:
		    return $client->$name();
		endif;
	    }catch(SoapFault $fault){
		$extra = (isset($fault->detail->ServiceFault->MessageError)?$fault->detail->ServiceFault->MessageError:'');
		self::_log(self::getString('remoteMethodExecutionFailed')." ({$fault->getMessage()}".(!empty($extra)?": $extra":'').")", $name."@".$fault->getLine());
		return self::SOAP_FAULT;
	    }catch(Exception $ex){
		self::_log(self::getString('remoteMethodExecutionFailed')." (".$ex->getMessage().")", $name."@".$ex->getLine());
		return self::EXCEPTION;
	    }
	    return self::GENERIC_ERROR;
	}

	/**
	 * Get a single SoapClient instance to use
	 *
	 * @param $url string with the optional url to use on the instance (if this parameter is diferent from the used on the current instance, a new instance is created)
	 * @return SoapClient instance to use
	 * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
	 */
	public static function getSoapClientInstance($url=NULL){
	    if(is_null(self::$_soapClient) || $url!=self::$_url):
		new self($url);
	    endif;
	    return self::$_soapClient;
	}

	/**
	 * Autoload static method for loading classes and interfaces.
	 *
	 * @param string $className The name of the class or interface.
	 * @return void
	 * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
	 */
	public static function autoload($className){
	    $file = COLIBRI_SOAPTYPES_DIR."{$className}.soap.php";
	    if(is_readable($file)):
		include_once($file);
	    endif;
	}

	/**
	 * Initialize the service
	 */
	public static function load(){
	    // Set the soap types directory
	    if(!defined('COLIBRI_SOAPTYPES_DIR')):
		define('COLIBRI_SOAPTYPES_DIR', dirname(__FILE__) . '/soaptypes/');
	    endif;

	    // set the autoload to load the SOAP module classes as needed
	    set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__) . '/soaptypes/');
	    spl_autoload_extensions(spl_autoload_extensions().',.soap.php');
	    spl_autoload_register(array(__CLASS__, 'autoload'));
	}

	/**
	 * Get the credentials to use
	 *
	 * @return accessCredentials object instance to use
	 * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
	 */
	private static function getCredentials(){
	    self::getSoapClientInstance(); // Just in case, to define the $_accessCredentials
	    return self::$_accessCredentials;
	}

	/**
	 * Get a list of functions from the SOAP server
	 *
	 * @return Array instance with a list of remote SOAP methods, negative integer with the error code
	 * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
	 */
	public static function getSoapFunctions(){
	    try{
		$client = self::getSoapClientInstance();

		// checks for a valid client
		if(is_null($client)):
		    self::_log(self::getString('unableToCreateSoapClientInstance'), __FUNCTION__);
		    return self::SOAP_INSTANCE_ERROR;
		endif;

		return $client->__getFunctions();

	    }catch(Exception $ex){
		self::_log(self::getString('getSoapFunctionsFailed')." (".$ex->getMessage().")", __FUNCTION__."@".$ex->getLine());
		return self::EXCEPTION;
	    }
	    return self::GENERIC_ERROR;
	}

	/**
	 * Get a list of types from the SOAP server
	 *
	 * @return Array instance with a list of SOAP data structures, negative integer with the error code
	 * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
	 */
	public static function getSoapTypes(){
	    try{
		$client = self::getSoapClientInstance();

		// checks for a valid client
		if(is_null($client)):
		    self::_log(self::getString('unableToCreateSoapClientInstance'), __FUNCTION__);
		    return self::SOAP_INSTANCE_ERROR;
		endif;

		return $client->__getTypes();

	    }catch(Exception $ex){
		self::_log(self::getString('getSoapTypesFailed')." (".$ex->getMessage().")", __FUNCTION__."@".$ex->getLine());
		return self::EXCEPTION;
	    }
	    return self::GENERIC_ERROR;
	}


// Remote SOAP methods definition
	
	/**
	 * Verify if the credentials have access to the service
	 *
	 * @param $credentials optional accessCredentials with the credentials to check
	 * @return String from the language pack
	 * @uses get_string to get a localized string
	 *
	 * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
	 */
	public static function checkAccess($credentials=NULL){
	    try{
		$client = self::getSoapClientInstance();

		// checks for a valid client
		if(is_null($client)):
		    self::_log(self::getString('unableToCreateSoapClientInstance'), __FUNCTION__);
		    return self::SOAP_INSTANCE_ERROR;
		endif;

		// method parameters
		$params = array(
		    'accessCredentials'=>(empty($credentials)?ColibriService::getCredentials():$credentials)
		);

		// call the method
		$result = $client->checkAccess($params);

		if(isset($result->return) && isset($result->return->sucess)):
		    if($result->return->sucess):
			return true;
		    else:
			self::_log(self::getString('errorReturned', self::getString($result->return->resultMessage, $result->return->resultMessage)), __FUNCTION__."@".__LINE__);
			return self::getErrorCode($result->return->resultMessage);
		    endif;
		else:
		    throw new Exception(self::getString('invalidMethodResponse').(isset($result->return)?" {$result->return->resultMessage}":''));
		    return ERROR_ON_METHOD_RESPONSE;
		endif;
	    }catch(SoapFault $fault){
		$extra = (isset($fault->detail->ServiceFault->MessageError)?$fault->detail->ServiceFault->MessageError:'');
		self::_log(self::getString('checkAccessFailed')." ({$fault->getMessage()}".(!empty($extra)?": $extra":'').")", __FUNCTION__."@".$fault->getLine());
		return self::SOAP_FAULT;
	    }catch(Exception $ex){
		self::_log(self::getString('checkAccessFailed')." (".$ex->getMessage().")", __FUNCTION__."@".$ex->getLine());
		return self::EXCEPTION;
	    }
	    return self::GENERIC_ERROR;
	}

	/**
	 * Generates a four digits PIN
	 *
	 * @return <int>
	 *
	 * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
	 */
	public static function generatePin(){
	    return substr(mt_rand(11111,19999),1);
	}

	/**
	 * Creates a session on the system
	 *
	 * @param <sessionScheduleInfo> $sessionInfo
	 * @return <sessionResult>
	 *
	 * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
	 */
	public static function createSession(sessionScheduleInfo $sessionInfo){
	    try{
		$client = self::getSoapClientInstance();

		// check for a valid client
		if(is_null($client)):
		    self::_log(self::getString('unableToCreateSoapClientInstance'), __FUNCTION__);
		    return self::SOAP_INSTANCE_ERROR;
		endif;

		// method parameters
		$params = array(
		    'accessCredentials'=>ColibriService::getCredentials(),
		    'sessionInfo'=>$sessionInfo
		);

		// call the method
		$result = $client->createSession($params);

		if(isset($result->return) && isset($result->return->sucess)):
		    if($result->return->sucess):
			return $result->return;
		    else:
			self::_log(self::getString('errorReturned', self::getString($result->return->resultMessage, $result->return->resultMessage)), __FUNCTION__."@".__LINE__);
			return self::getErrorCode($result->return->resultMessage);
		    endif;
		else:
		    throw new Exception(self::getString('invalidMethodResponse').(isset($result->return)?" {$result->return->resultMessage}":''));
		    return ERROR_ON_METHOD_RESPONSE;
		endif;

	    }catch(SoapFault $fault){
		$extra = (isset($fault->detail->ServiceFault->MessageError)?$fault->detail->ServiceFault->MessageError:'');
		self::_log(self::getString('createSessionFailed')." ({$fault->getMessage()}".(!empty($extra)?": $extra":'').")", __FUNCTION__."@".$fault->getLine());
		return self::SOAP_FAULT;
	    }catch(Exception $ex){
		self::_log(self::getString('createSessionFailed')." (".$ex->getMessage().")", __FUNCTION__."@".$ex->getLine());
		return self::EXCEPTION;
	    }
	    return self::GENERIC_ERROR;
	}

	/**
	 * Modifies a session on the system
	 *
	 * @param <String> $sessionUniqueID
	 * @param <sessionScheduleInfo> $sessionInfo
	 * @return <sessionResult>
	 *
	 * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
	 */// @TODO add the sessionId to modify
	public static function modifySession($sessionUniqueID, sessionScheduleInfo $sessionInfo){
	    try{
		$client = self::getSoapClientInstance();

		// checks for a valid client
		if(is_null($client)):
		    self::_log(self::getString('unableToCreateSoapClientInstance'), __FUNCTION__);
		    return self::SOAP_INSTANCE_ERROR;
		endif;

		// method parameters
		$params = array(
		    'accessCredentials'=>ColibriService::getCredentials(),
		    'sessionUniqueID'=>$sessionUniqueID,
		    'sessionInfo'=>$sessionInfo
		);

		// call the method
		$result = $client->modifySession($params);

		// check the result
		if(isset($result->return) && isset($result->return->sucess)):
		    if($result->return->sucess):
			return $result->return;
		    else:
			self::_log(self::getString('errorReturned', self::getString($result->return->resultMessage, $result->return->resultMessage)), __FUNCTION__."@".__LINE__);
			return self::getErrorCode($result->return->resultMessage);
		    endif;
		else:
		    throw new Exception(self::getString('invalidMethodResponse').(isset($result->return)?" {$result->return->resultMessage}":''));
		    return ERROR_ON_METHOD_RESPONSE;
		endif;

	    }catch(SoapFault $fault){
		$extra = (isset($fault->detail->ServiceFault->MessageError)?$fault->detail->ServiceFault->MessageError:'');
		self::_log(self::getString('modifySessionFailed', $sessionId)." ({$fault->getMessage()}".(!empty($extra)?": $extra":'').")", __FUNCTION__."@".$fault->getLine());
		return self::SOAP_FAULT;
	    }catch(Exception $ex){
		self::_log(self::getString('modifySessionFailed', $sessionId)." (".$ex->getMessage().")", __FUNCTION__."@".$ex->getLine());
		return self::EXCEPTION;
	    }
	    return self::GENERIC_ERROR;
	}

	/**
	 * Removes a session from the Colibri system
	 *
	 * @param <String> $sessionUniqueID
	 * @return <sessionResult>
	 *
	 * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
	 */
	public static function removeSession($sessionUniqueID){
	    try{
		$client = self::getSoapClientInstance();

		// checks for a valid client
		if(is_null($client)):
		    self::_log(self::getString('unableToCreateSoapClientInstance'), __FUNCTION__);
		    return self::SOAP_INSTANCE_ERROR;
		endif;

		// method parameters
		$params = array(
		    'accessCredentials'=>ColibriService::getCredentials(),
		    'sessionUniqueID'=>$sessionUniqueID
		);

		// call the method
		$result = $client->removeSession($params);

		if(isset($result->return) && isset($result->return->sucess)):
		    if($result->return->sucess):
			return true;
		    else:
			self::_log(self::getString('errorReturned', self::getString($result->return->resultMessage, $result->return->resultMessage)), __FUNCTION__."@".__LINE__);
			return self::getErrorCode($result->return->resultMessage);
		    endif;
		else:
		    throw new Exception(self::getString('invalidMethodResponse').(isset($result->return)?" {$result->return->resultMessage}":''));
		    return ERROR_ON_METHOD_RESPONSE;
		endif;

	    }catch(SoapFault $fault){
		$extra = (isset($fault->detail->ServiceFault->MessageError)?$fault->detail->ServiceFault->MessageError:'');
		self::_log(self::getString('removeSessionFailed', $sessionUniqueID)." ({$fault->getMessage()}".(!empty($extra)?": $extra":'').")", __FUNCTION__."@".$fault->getLine());
		return self::SOAP_FAULT;
	    }catch(Exception $ex){
		self::_log(self::getString('removeSessionFailed', $sessionUniqueID)." (".$ex->getMessage().")", __FUNCTION__."@".$ex->getLine());
		return self::EXCEPTION;
	    }
	    return self::GENERIC_ERROR;
	}

	/**
	 * Returns information about a session
	 *
	 * @param <String> $sessionUniqueID with the session unique identifier
	 * @return <sessionResult> with the result, negative integer with the error code on error
	 * 
	 * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
	 */
	public static function getSessionInfo($sessionUniqueID){
	    try{
		$client = self::getSoapClientInstance();

		// checks for a valid client
		if(is_null($client)):
		    self::_log(self::getString('unableToCreateSoapClientInstance'), __FUNCTION__);
		    return self::SOAP_INSTANCE_ERROR;
		endif;

		$params = array(
		    'accessCredentials'=>ColibriService::getCredentials(),
		    'sessionUniqueID'=>$sessionUniqueID
		);

		$result = $client->getSessionInfo($params);
		if(isset($result->return) && isset($result->return->sucess)):
		    if($result->return->sucess):
			return $result->return;
		    else:
			self::_log(self::getString('errorReturned', self::getString($result->return->resultMessage, $result->return->resultMessage)), __FUNCTION__."@".__LINE__);
			return self::getErrorCode($result->return->resultMessage);
		    endif;
		else:
		    throw new Exception(self::getString('invalidMethodResponse').(isset($result->return)?" {$result->return->resultMessage}":''));
		    return ERROR_ON_METHOD_RESPONSE;
		endif;

	    }catch(SoapFault $fault){
		$extra = (isset($fault->detail->ServiceFault->MessageError)?$fault->detail->ServiceFault->MessageError:'');
		self::_log(self::getString('getSessionInfoFailed')." ({$fault->getMessage()}".(!empty($extra)?": $extra":'').")", __FUNCTION__."@".$fault->getLine());
		return self::SOAP_FAULT;
	    }catch(Exception $ex){
		self::_log(self::getString('getSessionInfoFailed')." (".$ex->getMessage().")", __FUNCTION__."@".$ex->getLine());
		return self::EXCEPTION;
	    }
	    return self::GENERIC_ERROR;
	}
	
	/**
	 * Returns information about a session
	 *
	 * @param <array> $sessionUniqueIDs with the session unique identifiers
	 * @return <array> with the result, negative integer with the error code on error
	 *
	 * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
	 */
	public static function getSessionsInfo($sessionUniqueIDs){
	    try{
		$client = self::getSoapClientInstance();

		// checks for a valid client
		if(is_null($client)):
		    self::_log(self::getString('unableToCreateSoapClientInstance'), __FUNCTION__);
		    return self::SOAP_INSTANCE_ERROR;
		endif;

		$params = array(
		    'accessCredentials'=>ColibriService::getCredentials(),
		    'sessionsUniqueID'=>$sessionUniqueIDs
		);

		$result = $client->getSessionsInfo($params);
		
		if(isset($result->return)):
		    return $result->return;
		else:
		    throw new Exception(self::getString('invalidMethodResponse').(isset($result->return)?" {$result->return->resultMessage}":''));
		    return ERROR_ON_METHOD_RESPONSE;
		endif;

	    }catch(SoapFault $fault){
		$extra = (isset($fault->detail->ServiceFault->MessageError)?$fault->detail->ServiceFault->MessageError:'');
		self::_log(self::getString('getSessionsInfoFailed')." ({$fault->getMessage()}".(!empty($extra)?": $extra":'').")", __FUNCTION__."@".$fault->getLine());
		return self::SOAP_FAULT;
	    }catch(Exception $ex){
		self::_log(self::getString('getSessionsInfoFailed')." (".$ex->getMessage().")", __FUNCTION__."@".$ex->getLine());
		return self::EXCEPTION;
	    }
	    return self::GENERIC_ERROR;
	}
	
	/**
	 * The time from the Colibri remote system
	 *
	 * @return getColibriTimeResponse with the timestamp, negative integer with the error code on error
	 * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
	 */
	public static function getColibriTime(){
	    try{
		$client = self::getSoapClientInstance();

		// checks for a valid client
		if(is_null($client)):
		    self::_log(self::getString('unableToCreateSoapClientInstance'), __FUNCTION__);
		    return self::SOAP_INSTANCE_ERROR;
		endif;

		$params = array(
		    'getColibriTime'=>new getColibriTime()
		);
		$result = $client->getColibriTime($params);

		return $result->return;

	    }catch(SoapFault $fault){
		$extra = (isset($fault->detail->ServiceFault->MessageError)?$fault->detail->ServiceFault->MessageError:'');
		self::_log(self::getString('getColibriTimeFailed')." ({$fault->getMessage()}".(!empty($extra)?": $extra":'').")", __FUNCTION__."@".$fault->getLine());
		return self::SOAP_FAULT;
	    }catch(Exception $ex){
		self::_log(self::getString('getColibriTimeFailed')." (".$ex->getMessage().")", __FUNCTION__."@".$ex->getLine());
		return self::EXCEPTION;
	    }
	    return self::GENERIC_ERROR;
	}
    }
endif;