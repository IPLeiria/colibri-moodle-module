<?php
/**
 * Library for the Colibri mod
 *
 * @package    	Colibri
 * @subpackage 	mod_colibri
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt> - {@link http://ued.ipleiria.pt | Learning Distance Unit }, Polytechnic Institute of Leiria
 * @license	{@link http://www.gnu.org/copyleft/gpl.html |  GNU GPL v3 or later}
 *
 */

defined('MOODLE_INTERNAL') || die();

// we extend this library here
require_once(dirname(__FILE__).'/locallib.php'); 

/**
 * List of features supported in Colibri module
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed True if module supports feature, false if not, null if doesn't know
 */
function colibri_supports($feature) {
    switch($feature) {
        case FEATURE_GROUPS:			return true;
        case FEATURE_GROUPINGS:			return true;
        case FEATURE_GROUPMEMBERSONLY:		return true;
        case FEATURE_MOD_INTRO:			return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS:	return true;
        case FEATURE_GRADE_HAS_GRADE:		return true;
        case FEATURE_GRADE_OUTCOMES:		return false;
        case FEATURE_BACKUP_MOODLE2:		return false;

        default: return null;
    }
}

/**
 * Returns all other caps used in module
 * @return array
 */
function colibri_get_extra_capabilities() {
    return array('mod/colibri:attendsession');
}

/**
 * This function is used by the reset_course_userdata function in moodlelib.
 * @param $data the data submitted from the reset course.
 * @return array status array
 */
function colibri_reset_userdata($data) {
    return array();
}

/**
 * List of view style log actions
 * @return array
 */
function colibri_get_view_actions() {
    return array('view','view all');
}

/**
 * List of update style log actions
 * @return array
 */
function colibri_get_post_actions() {
    return array('update', 'add');
}

/**
 * Insert a colibri instance.
 * 
 * @param object $data
 * @param object $mform
 * @return int new resoruce instance id
 */
function colibri_add_instance($data) {
    global $USER;

    $data->id = Colibri::createSession(
	$USER->id,
	$data->course,
	new sessionScheduleInfo(
	    $data->name,
	    $data->startdate,
	    $data->enddate,
	    $data->maxsessionusers,
	    $data->sessionpin,
	    $data->moderationpin,
	    $data->publicsession==0?false:true
	),
	array(), //$data->authorizedsessionusers_values,
	$data->intro//,
	//$data->introformat
    );
    if($data->id>0):
	return $data->id;
    else:
	return false;
    endif;
}

/**
 * Update a colibri instance.
 * @param object $data
 * @param object $mform
 * @return bool true
 */
function colibri_update_instance($data) {
    global $USER;

    return Colibri::modifySession(
	$USER->id,
	$data->instance,
	new sessionScheduleInfo(
	    $data->name,
	    $data->startdate,
	    $data->enddate,
	    $data->maxsessionusers,
	    $data->sessionpin,
	    $data->moderationpin,
	    $data->publicsession==0?false:true,
	    $data
	),
	array(), //$data->authorizedsessionusers_values,
	$data->intro//,
	//$data->introformat
    );
}

/**
 * Delete colibri instance.
 * @param int $id
 * @return boolean true on success, false otherwise
 */
function colibri_delete_instance($id) {
    global $USER;
    
    return Colibri::removeSession($USER->id, $id);
}


/**
 * Returns the users with data in one resource
 *
 * @param int $sessionId
 */
function colibri_get_participants($sessionId) {
    $users = array();
    $usersTmp = Colibri::getSessionUsers($sessionId);
    foreach ($usersTmp as $user) {
        if (empty($users[$user->userid])) {
            $users[$user->userid] = $user->userid;
        }
    }

    return $users;
}

/**
 * Adds information about unread messages, that is only required for the course view page (and similar), to the course-module object.
 * @param cm_info $cm Course-module object
 */
function colibri_cm_info_view(cm_info $cm) {
    global $CFG;
    $session = Colibri::getSessionInfo($cm->instance);
    $status = '';
    $title = '';
    switch($session->sessionStatus):
	case sessionResult::SESSION_STATUS_SCHEDULED:
	    $status = get_string('sessionscheduletostart', COLIBRI_PLUGINNAME);
	    $title = get_string('sessionscheduletostart_title', COLIBRI_PLUGINNAME, usergetdate($session->startDateTimeStamp/1000));
	    break;
	case sessionResult::SESSION_STATUS_INSESSION:
	    $session = Colibri::getSessionInfo($cm->instance, true);
	    $status = get_string('sessionstartedxparticipantsinsession', COLIBRI_PLUGINNAME);
	    $title = get_string('sessionstartedxparticipantsinsession_title', COLIBRI_PLUGINNAME, $session->currentUsersInSession);
	    break;
	case sessionResult::SESSION_STATUS_FINISHED:
	    $status = get_string('sessionfinished', COLIBRI_PLUGINNAME);
	    $title = get_string('sessionfinished_title', COLIBRI_PLUGINNAME);
	    break;
    endswitch;
    $out = "<span class=\"colibri-session-info\" title=\"{$title}\">{$status}</span>";
    $cm->set_after_link($out);
}


function colibri_cron(){
    if(Colibri::syncSessionsInformation()):
	mtrace('Colibri session information successfuly synced');
    endif;
    
    return true;
}