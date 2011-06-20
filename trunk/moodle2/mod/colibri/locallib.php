<?php
/**
 * The main workshop configuration form
 *
 * @package    	Colibri
 * @subpackage 	mod_colibri
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt> - {@link http://ued.ipleiria.pt | Learning Distance Unit }, Polytechnic Institute of Leiria
 * @license	{@link http://www.gnu.org/copyleft/gpl.html |  GNU GPL v3 or later}
 *
 */

defined('MOODLE_INTERNAL') || die();
global $CFG;
// Load the ColibriService library
require_once($CFG->dirroot.'/local/colibri/lib.php');
ColibriService::load();

if(!class_exists('Colibri')):
    /**
     * Provides the Colibri client to connect with the remote Colibri service
     *
     * @package    	Colibri
     * @subpackage 	local_colibri
     * @version	2011.0
     * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt> - {@link http://ued.ipleiria.pt | Learning Distance Unit }, Polytechnic Institute of Leiria
     * @license	http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
     */
    class Colibri extends UEDbase{
	const INSUFFICIENT_PERMISSIONS = -100;
	const DATABASE_INSERT_FAILED = -101;

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
	    return ColibriService::checkAccess($credentials);
	}

	/**
	 * Creates a session on the system
	 *
	 * @param <int> $userId the moodle user identifier
	 * @param <int> $courseId the moodle course identifier
	 * @param <sessionScheduleInfo> $sessionInfo with the session information
	 * @return <int> with the resource id
	 * @uses global $DB to access the database
	 *
	 * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
	 */
	public static function createSession($userId, $courseId, sessionScheduleInfo $sessionInfo, $users=array(), $intro='', $introformat=0){
	    global $DB;
	    try{
		$coursecontext = get_context_instance(CONTEXT_COURSE, $courseId);
		if( !has_capability('mod/colibri:managesession', $coursecontext, $userId)):
		    return INSUFFICIENT_PERMISSIONS;
		endif;

		$result = ColibriService::createSession($sessionInfo);

		// If the session cannot be created, return the error code
		if(is_integer($result) && $result<0):
		    return $result;
		endif;

		$resource = new stdClass();
		$resource->course = $courseId;
		$resource->sessionuniqueid = $result->sessionUniqueID;
		$resource->sessionnumber = $result->sessionNumber;
		$resource->name = $result->name;
		$resource->moderationpin = $result->moderationPin;
		$resource->sessionpin = $result->sessionPin;
		$resource->startdate = round($result->startDateTimeStamp/1000);
		$resource->enddate = round($result->endDateTimeStamp/1000);
		$resource->maxsessionusers = $result->maxSessionUsers;
		$resource->listpubliclyoncolibri = ($result->listPubliclyInColibri)?1:0;
		$resource->state = 0;
		$resource->creatorid = $userId;
		$resource->timemodified = time();
		$resource->intro = $intro;
		$resource->introformat = $introformat;

		if($resource->id = $DB->insert_record('colibri', $resource)):
		    foreach ($users as $user):
			$dbUser = new stdClass();
			$dbUser->colibrisessionid = $resource->id;
			$dbUser->userid = $user;
			$dbUser->type = 0;
			$dbUser->reservedbyid = $resource->creatorid;
			
			$DB->insert_record('colibri_users', $dbUser);
		
		    endforeach;
		    return $resource->id;
		endif;
		return DATABASE_INSERT_FAILED;
		
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