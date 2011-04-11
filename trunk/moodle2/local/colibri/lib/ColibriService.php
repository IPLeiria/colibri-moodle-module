<?php
/**
 * Class library for the Colibri webservice communication
 *
 * @package    	Colibri
 * @subpackage 	local_colibri
 * @copyright 	{@link http://ued.ipleiria.pt | Learning Distance Unit } - Polytechnic Institute of Leiria
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt>
 * @license	{@link http://www.gnu.org/copyleft/gpl.html |  GNU GPL v3 or later}
 *
 * SVN:
 * $Author$
 * $Date$
 * $Rev$
 */

// Set the soap types directory
if(!defined('COLIBRI_SOAPTYPES_DIR')):
    define('COLIBRI_SOAPTYPES_DIR', dirname(__FILE__) . '/soaptypes/');
endif;

// set the autoload to load the SOAP module classes as needed
set_include_path(get_include_path().PATH_SEPARATOR.COLIBRI_SOAPTYPES_DIR);
spl_autoload_extensions(spl_autoload_extensions().',.soap.php');
spl_autoload_register(array('ColibriService', 'autoload'));


/**
 * Provides the Colibri client to connect with the remote Colibri service
 *
 * @package    	Colibri
 * @subpackage 	local_colibri
 * @version	2011.0
 * @copyright 	Learning Distance Unit {@link http://ued.ipleiria.pt} - Polytechnic Institute of Leiria
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt>
 * @license	http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class ColibriService{
    private static $_soapClient = NULL;
    private static $_url = NULL;
    private static $_accessCredentials;

    // constants
    const GENERIC_ERROR = -1;
    const SOAP_INSTANCE_ERROR = -2;
    const SOAP_FAULT = -3;
    const EXCEPTION = -4;
    const INVALID_START_DATE = -5;
    const INVALID_END_DATE = -6;
    const INVALID_NUMBER_OF_PARTICIPANTS = -7;
    const ERROR_ON_METHOD_RESPONSE = -8;

    /**
     * We will be using a private constructor for single SoapClient instance reuse
     *
     * @param $wsdl with the service URL to use on the SoapClient link
     * @uses global $CFG for the proxy settings
     * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
     */
    private function __construct($wsdl=NULL){
        global $CFG;
        try{
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
            self::$_accessCredentials->installId = get_config(COLIBRI_PLUGINNAME, 'colibri_installation_identifier');
            self::$_accessCredentials->password = get_config(COLIBRI_PLUGINNAME, 'colibri_installation_password');

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
     * Logs a error message to the PHP logger
     *
     * @param $message string with the string message
     * @param $title string with the prefix for the message
     * @param $forceOutput boolean to force the log entry even if the global debug flag is disabled
     * @uses global $CFG for the debug current settings
     * @uses global $COLIBRI for the module name
     * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
     */
    private static function _log($message=NULL, $title='', $forceOutput=false){
        global $CFG;

        if(!is_null($message) && ($forceOutput || !empty($CFG->debug))):
            error_log('['.__CLASS__.(!empty($title)?" - $title":'')."] $message");
        endif;
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
     * Get the credentials to use
     *
     * @return accessCredentials object instance to use
     * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
     */
    private static function getCredentials(){
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
    public static function getString($message=NULL, $a = NULL, $module=COLIBRI_PLUGINNAME){
        return get_string($message, $module, $a);
    }


    // Remote methods definition

    /**
     * Creates a session on the system
     *
     * @param <String> $name
     * @param <int> $startDateTimeStamp
     * @param <int> $endDateTimeStamp
     * @param <int> $maxSessionUsers
     * @param <int> $sessionPin
     * @param <int> $moderationPin
     * @param <Boolean> $listPubliclyInColibri
     * @return <sessionResult>
     *
     * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
     */
    public static function createSession($name, $startDateTimeStamp, $endDateTimeStamp, $maxSessionUsers=0, $sessionPin=NULL, $moderationPin=NULL, $listPubliclyInColibri=false){
        try{
            $client = self::getSoapClientInstance();

            // checks for a valid client
            if(is_null($client)):
                self::_log(self::getString('unableToCreateSoapClientInstance'), __FUNCTION__);
                return self::SOAP_INSTANCE_ERROR;
            endif;

            // check the start date value
            if(!is_numeric($startDateTimeStamp) || (int)$startDateTimeStamp<time()):
                self::_log(self::getString('invalidStartDate'), __FUNCTION__);
                return self::INVALID_START_DATE;
            endif;

            // check end date value
            if(!is_numeric($endDateTimeStamp) || (int)$endDateTimeStamp<$startDateTimeStamp):
                self::_log(self::getString('invalidEndDate'), __FUNCTION__);
                return self::INVALID_END_DATE;
            endif;

            // check the number of participants
            if($maxSessionUsers<=0):
                self::_log(self::getString('invalidNumberOfParticipants'), __FUNCTION__);
                return self::INVALID_NUMBER_OF_PARTICIPANTS;
            endif;

            // generate a session pin if needed
            $sessionPin = is_null($sessionPin)?substr(mt_rand(10000,19999),1):$sessionPin;
            // generate a moderation pin if needed
            $moderationPin = is_null($moderationPin)?substr(mt_rand(10000,19999),1):$moderationPin;

            // method parameters
            $params = array(
                'accessCredentials'=>ColibriService::getCredentials(),
                'sessionInfo'=>array(
                    'name'=>$name,
                    'startDateTimeStamp'=>$startDateTimeStamp,
                    'endDateTimeStamp'=>$endDateTimeStamp,
                    'maxSessionUsers'=>$maxSessionUsers,
                    'listPubliclyInColibri'=>$listPubliclyInColibri,
                    'moderationPin'=>$moderationPin,
                    'sessionPin'=>$sessionPin
                )
            );

            // call the method
            $result = $client->createSession($params);

            if(isset($result->return) && $result->return->sucess):
                return $result->return;
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
     * @param <int> $sessionId
     * @param <String> $name
     * @param <int> $startDateTimeStamp
     * @param <int> $endDateTimeStamp
     * @param <int> $maxSessionUsers
     * @param <int> $sessionPin
     * @param <int> $moderationPin
     * @param <Boolean> $listPubliclyInColibri
     * @return <sessionResult>
     *
     * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
     */// @TODO add the sessionId to modify
    public static function modifySession($sessionId, $name, $startDateTimeStamp, $endDateTimeStamp, $maxSessionUsers=0, $sessionPin=NULL, $moderationPin=NULL, $listPubliclyInColibri=false){
        try{
            $client = self::getSoapClientInstance();

            // checks for a valid client
            if(is_null($client)):
                self::_log(self::getString('unableToCreateSoapClientInstance'), __FUNCTION__);
                return self::SOAP_INSTANCE_ERROR;
            endif;

            // check the start date value
            if(!is_numeric($startDateTimeStamp) || (int)$startDateTimeStamp<time()):
                self::_log(self::getString('invalidStartDate'), __FUNCTION__);
                return self::INVALID_START_DATE;
            endif;

            // check end date value
            if(!is_numeric($endDateTimeStamp) || (int)$endDateTimeStamp<$startDateTimeStamp):
                self::_log(self::getString('invalidEndDate'), __FUNCTION__);
                return self::INVALID_END_DATE;
            endif;

            // check the number of participants
            if($maxSessionUsers<=0):
                self::_log(self::getString('invalidNumberOfParticipants'), __FUNCTION__);
                return self::INVALID_NUMBER_OF_PARTICIPANTS;
            endif;

            // generate a session pin if needed
            $sessionPin = is_null($sessionPin)?substr(mt_rand(10000,19999),1):$sessionPin;
            // generate a moderation pin if needed
            $moderationPin = is_null($moderationPin)?substr(mt_rand(10000,19999),1):$moderationPin;

            // method parameters
            $params = array(
                'accessCredentials'=>ColibriService::getCredentials(),
                'sessionInfo'=>array(
                    'sessionId'=>$sessionId,
                    'name'=>$name,
                    'startDateTimeStamp'=>$startDateTimeStamp,
                    'endDateTimeStamp'=>$endDateTimeStamp,
                    'maxSessionUsers'=>$maxSessionUsers,
                    'listPubliclyInColibri'=>$listPubliclyInColibri,
                    'moderationPin'=>$moderationPin,
                    'sessionPin'=>$sessionPin
                )
            );

            // call the method
            $result = $client->modifySession($params);

            if(isset($result->return) && $result->return->sucess):
                return $result->return;
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
     * Removes a session on the system
     *
     * @param <int> $sessionId
     * @param <String> $name
     * @param <int> $startDateTimeStamp
     * @param <int> $endDateTimeStamp
     * @param <int> $maxSessionUsers
     * @param <int> $sessionPin
     * @param <int> $moderationPin
     * @param <Boolean> $listPubliclyInColibri
     * @return <sessionResult>
     *
     * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
     */// @TODO remove all the junk and use only the sessionId to remove
    public static function removeSession($sessionId){
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
                'sessionInfo'=>array(
                    'sessionId'=>$sessionId
                )
            );

            // call the method
            $result = $client->removeSession($params);

            if(isset($result->return) && $result->return->sucess):
                return $result->return;
            else:
                throw new Exception(self::getString('invalidMethodResponse').(isset($result->return)?" {$result->return->resultMessage}":''));
                return ERROR_ON_METHOD_RESPONSE;
            endif;

        }catch(SoapFault $fault){
            $extra = (isset($fault->detail->ServiceFault->MessageError)?$fault->detail->ServiceFault->MessageError:'');
            self::_log(self::getString('removeSessionFailed', $sessionId)." ({$fault->getMessage()}".(!empty($extra)?": $extra":'').")", __FUNCTION__."@".$fault->getLine());
            return self::SOAP_FAULT;
        }catch(Exception $ex){
            self::_log(self::getString('removeSessionFailed', $sessionId)." (".$ex->getMessage().")", __FUNCTION__."@".$ex->getLine());
            return self::EXCEPTION;
        }
        return self::GENERIC_ERROR;
    }

    /**
     * Returns the session information about a session
     *
     * @param integer $sessionId with the session identifier
     * @return sessionResult with the result, negative integer with the error code on error
     * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
     */
    public static function getSessionInfo($sessionId){
        try{
            $client = self::getSoapClientInstance();

            // checks for a valid client
            if(is_null($client)):
                self::_log(self::getString('unableToCreateSoapClientInstance'), __FUNCTION__);
                return self::SOAP_INSTANCE_ERROR;
            endif;

            $params = array(
                'accessCredentials'=>ColibriService::getCredentials(),
                'sessionInfo'=>$sessionId
            );

            $result = $client->getSessionInfo($params);
            return $result->return;

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