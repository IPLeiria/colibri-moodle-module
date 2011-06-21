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
	const DATABASE_UPDATE_FAILED = -102;
	const DATABASE_DELETE_FAILED = -103;

	/**
	 * @var <string> with the class name
	 */
	protected static $_className = __CLASS__;

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
	 * Returns the session information
	 *
	 * @param <integer> $resourceId with the session identifier
	 * @return <object> with the result, null on error
	 * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
	 */
	public static function getSessionInfo($resourceId){
	    global $DB;

	    if ($session = $DB->get_record('colibri', array('id'=>$resourceId), 'id, course, creatorid, sessionuniqueid, timemodified, intro, introformat', MUST_EXIST)):
		$session->users = $DB->get_records('colibri_users', array('colibrisessionid'=>$session->id),'', 'userid, type, accessed');
		$remoteInfo = ColibriService::getSessionInfo($session->sessionuniqueid);
		if(is_numeric($remoteInfo)):
		    return null;
		endif;
		foreach($remoteInfo as $key=>$value):
		    $session->$key = $value;
		endforeach;
		return $session;
	    endif;
	    return null;
	}
	
	/**
	 * Return the users associated with a session
	 *
	 * @param <integer> $resourceId with the session identifier
	 * @return <object> with the result, negative integer with the error code on error
	 * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
	 */
	public static function getSessionUsers($resourceId){
	    global $DB;
	    if($users = $DB->get_records('colibri_users', array('colibrisessionid'=>$resourceId))):
		return $users;
	    endif;
	    return null;
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
	    //$resource->maxsessionusers = $result->maxSessionUsers; // @TODO bug on the webservice side: the maxSessionUsers return always 0
	    $resource->maxsessionusers = $sessionInfo->maxSessionUsers;
	    $resource->listpubliclyoncolibri = ($result->listPubliclyInColibri)?1:0;
	    $resource->state = $result->sessionStatus;
	    $resource->creatorid = $userId;
	    $resource->timemodified = time();
	    $resource->intro = $intro;
	    $resource->introformat = $introformat;

	    if($resource->id = $DB->insert_record('colibri', $resource)):
		if(!empty ($users)):
		    foreach ($users as $user):
			$dbUser = new stdClass();
			$dbUser->colibrisessionid = $resource->id;
			$dbUser->userid = $user;
			$dbUser->type = 0;
			$dbUser->reservedbyid = $resource->creatorid;

			$DB->insert_record('colibri_users', $dbUser);

		    endforeach;
		endif;
		return $resource->id;
	    endif;
	    return DATABASE_INSERT_FAILED;
	}

	/**
	 * Modify a session on the system
	 *
	 * @param <int> $userId the moodle user identifier
	 * @param <int> $instanceId the module instance identifier
	 * @param <sessionScheduleInfo> $sessionInfo with the session information
	 * @return <boolean> true if the session was successful updated, false otherwise
	 * @uses global $DB to access the database
	 *
	 * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
	 */
	public static function modifySession($userId, $instanceId, sessionScheduleInfo $sessionInfo, $users=array(), $intro='', $introformat=0){
	    global $DB;

	    $dbSession = self::getSessionInfo($instanceId);

	    if(!empty($dbSession)):
		$coursecontext = get_context_instance(CONTEXT_COURSE, $dbSession->course);
		if( !has_capability('mod/colibri:managesession', $coursecontext, $userId)):
		    //return INSUFFICIENT_PERMISSIONS;
		    return false;
		endif;
		$result = ColibriService::modifySession($dbSession->sessionuniqueid, $sessionInfo);

		// If the session cannot be update, return false
		if(is_integer($result) && $result<0):
		    return false;
		endif;

		$resource = new stdClass();
		$resource->id = $instanceId;
		$resource->course = $dbSession->course;
		$resource->sessionuniqueid = $result->sessionUniqueID;
		$resource->sessionnumber = $result->sessionNumber;
		$resource->name = $result->name;
		$resource->moderationpin = $result->moderationPin;
		$resource->sessionpin = $result->sessionPin;
		$resource->startdate = round($result->startDateTimeStamp/1000);
		$resource->enddate = round($result->endDateTimeStamp/1000);
		//$resource->maxsessionusers = $result->maxSessionUsers; // @TODO bug on the webservice side: the maxSessionUsers return always 0
		$resource->maxsessionusers = $sessionInfo->maxSessionUsers;
		$resource->listpubliclyoncolibri = ($result->listPubliclyInColibri)?1:0;
		$resource->state = $result->sessionStatus;
		$resource->creatorid = $userId;
		$resource->timemodified = time();
		$resource->intro = $intro;
		$resource->introformat = $introformat;

		if($DB->update_record('colibri', $resource)):
		    if(!empty ($users)):
			// remove the old records
			$DB->delete_records('colibri_users', array('colibrisessionid'=>$resource->id));
			foreach ($users as $user):
			    $dbUser = new stdClass();
			    $dbUser->colibrisessionid = $resource->id;
			    $dbUser->userid = $user;
			    $dbUser->type = 0;
			    $dbUser->reservedbyid = $resource->creatorid;

			    $DB->insert_record('colibri_users', $dbUser);

			endforeach;
		    endif;
		    return true;
		endif;
	    endif;
	    return false;
	}

	/**
	 * Removes a session from the system
	 *
	 * @param <int> $userId the moodle user identifier
	 * @param <int> $instanceId the module instance identifier
	 * @return <boolean> true if the session was successful removed, false otherwise
	 * @uses global $DB to access the database
	 *
	 * @author Cláudio Esperança <claudio.esperanca@ipleiria.pt>
	 */
	public static function removeSession($userId, $instanceId){
	    global $DB;

	    $dbSession = self::getSessionInfo($instanceId);
	    if(!empty($dbSession)):
		$coursecontext = get_context_instance(CONTEXT_COURSE, $dbSession->course);
		if(has_capability('mod/colibri:managesession', $coursecontext, $userId) && ColibriService::removeSession($dbSession->sessionuniqueid)===true):
		    return ($DB->delete_records('colibri_users', array('colibrisessionid'=>$instanceId)) && $DB->delete_records('colibri', array('id'=>$instanceId)));
		endif;
	    endif;
	    return false;
	}
    }
endif;