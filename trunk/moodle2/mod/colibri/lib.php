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
require_once(dirname(__FILE__).'/locallib.php');     // we extend this library here

/**
 * List of features supported in Colibri module
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed True if module supports feature, false if not, null if doesn't know
 */
function colibri_supports($feature) {
    switch($feature) {
        case FEATURE_GROUPS:                  return false;
        case FEATURE_GROUPINGS:               return false;
        case FEATURE_GROUPMEMBERSONLY:        return false;
        case FEATURE_MOD_INTRO:               return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS: return false;
        case FEATURE_GRADE_HAS_GRADE:         return false;
        case FEATURE_GRADE_OUTCOMES:          return false;
        case FEATURE_BACKUP_MOODLE2:          return false;

        default: return null;
    }
}

/**
 * Returns all other caps used in module
 * @return array
 */
function colibri_get_extra_capabilities() {
    return array('local/colibri:attendsession');
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
 * Add colibri instance.
 * 
 * @param object $data
 * @param object $mform
 * @return int new resoruce instance id
 */
function colibri_add_instance($data) {
    global $CFG, $DB, $USER;
    
    $data->id = Colibri::createSession(
	$USER->id,
	$data->course,
	new sessionScheduleInfo(
	    $data->name,
	    $data->startdatetime,
	    $data->enddatetime,
	    $data->maxsessionusers,
	    $data->sessionpin,
	    $data->moderationpin,
	    $data->publicsession==0?false:true,
	    $data
	),
	$data->intro,
	$data->introformat
    );
    if($data->id>0):
	// we need to use context now, so we need to make sure all needed info is already in db
	//$DB->set_field('course_modules', 'instance', $data->id, array('id'=>$data->coursemodule));
	echo('<pre>'.print_r($data, true).'</pre>');
	
    endif;
    return $data->id;
}

/**
 * Update colibri instance.
 * @param object $data
 * @param object $mform
 * @return bool true
 */
function colibri_update_instance($data, $mform) {
    global $CFG, $DB;
    
    if(isset($data->submitbutton) || isset($data->submitbutton2)){
    	
    	require_once("$CFG->libdir/resourcelib.php");
	    $data->timemodified = time();
	    $data->id           = $data->instance;
	    $data->revision++;
	
	    $displayoptions = array();
	    if (isset($data->display) && $data->display == RESOURCELIB_DISPLAY_POPUP) {
	        $displayoptions['popupwidth']  = $data->popupwidth;
	        $displayoptions['popupheight'] = $data->popupheight;
	    }
	    if (isset($data->display) && in_array($data->display, array(RESOURCELIB_DISPLAY_AUTO, RESOURCELIB_DISPLAY_EMBED, RESOURCELIB_DISPLAY_FRAME))) {
	        $displayoptions['printheading'] = (int)!empty($data->printheading);
	        $displayoptions['printintro']   = (int)!empty($data->printintro);
	    }
	    $data->displayoptions = serialize($displayoptions);
	    
	    
	    $eventdata = new stdClass();
	    
	    // cache and delete the old records
	    $previousColibriRelations = $DB->get_records('colibri_relations', array('colibri_id'=>$data->instance));
	    if($DB->delete_records('colibri_relations', array('colibri_id'=>$data->instance))){
	    	$eventdata->previousColibriRelations = $previousColibriRelations;
	    	if($DB->update_record('colibri', $data)){
		    	if(colibri_add_relations_to_db($data, $eventdata)){
			    	events_trigger('colibri_updated', $eventdata);
			    	colibri_set_mainfile($data);
	    			
	    			return true;
			    }
	    	}
	    }
    	return false;
    }
    return true;
}

/**
 * Delete colibri instance.
 * @param int $id
 * @return boolean true on success, false otherwise
 */
function colibri_delete_instance($id) {
    global $DB;
    
    $eventdata = new stdClass();
    if(($eventdata->previousColibriRelations = $DB->get_records('colibri_relations', array('colibri_id'=>$id))) && $DB->delete_records('colibri_relations', array('colibri_id'=>$id))){
    	if($DB->delete_records('colibri', array('id'=>$id))){
    		events_trigger('colibri_deleted', $eventdata);
    		return true;
    	}
    }
    return false;
}

/**
 * Handles the associated resources deletion triggering the update on the central repository
 * 
 * @param object $eventdata with the module data
 * @return boolean with the result of the operation
 */
function colibri_mod_deleted_handler($eventdata){
	global $DB;
	
	if(($previousColibriRelations = $DB->get_records('colibri_relations', array('resource_id'=>$eventdata->cmid))) && $DB->delete_records('colibri_relations', array('resource_id'=>$eventdata->cmid))){
		$eventdata = new stdClass();
		$eventdata->previousColibriRelations = $previousColibriRelations;
		events_trigger('colibri_deleted', $eventdata);
	}
	return true;
}

/**
 * Return use outline
 * @param object $course
 * @param object $user
 * @param object $mod
 * @param object $resource
 * @return object|null
 */
function colibri_user_outline($course, $user, $mod, $resource) {
    global $DB;

    if ($logs = $DB->get_records('log', array('userid'=>$user->id, 'module'=>'colibri',
                                              'action'=>'view', 'info'=>$resource->id), 'time ASC')) {

        $numviews = count($logs);
        $lastlog = array_pop($logs);

        $result = new object();
        $result->info = get_string('numviews', '', $numviews);
        $result->time = $lastlog->time;

        return $result;
    }
    return NULL;
}

/**
 * Return use complete
 * @param object $course
 * @param object $user
 * @param object $mod
 * @param object $resource
 */
function colibri_user_complete($course, $user, $mod, $resource) {
    global $CFG, $DB;

    if ($logs = $DB->get_records('log', array('userid'=>$user->id, 'module'=>'colibri',
                                              'action'=>'view', 'info'=>$resource->id), 'time ASC')) {
        $numviews = count($logs);
        $lastlog = array_pop($logs);

        $strmostrecently = get_string('mostrecently');
        $strnumviews = get_string('numviews', '', $numviews);

        echo "$strnumviews - $strmostrecently ".userdate($lastlog->time);

    } else {
        print_string('neverseen', 'colibri');
    }
}

/**
 * Returns the users with data in one colibri
 *
 * @param int $resourceid
 * @return bool false
 */
function colibri_get_participants($resourceid) {
    return false;
}

/**
 * Given a course_module object, this function returns any
 * "extra" information that may be needed when printing
 * this activity in a course listing.
 *
 * See {@link get_array_of_activities()} in course/lib.php
 *
 * @param object $coursemodule
 * @return object info
 */
function colibri_get_coursemodule_info($coursemodule) {
    global $CFG, $DB;
    require_once("$CFG->libdir/resourcelib.php");

    if (!$page = $DB->get_record('colibri', array('id'=>$coursemodule->instance), 'id, name, display ,displayoptions')) {
        return NULL;
    }

    $info = new object();
    $info->name = $page->name;

    if ($page->display != RESOURCELIB_DISPLAY_POPUP) {
        return $info;
    }

    $fullurl = "$CFG->wwwroot/mod/colibri/view.php?id=$coursemodule->id";
    $options = empty($page->displayoptions) ? array() : unserialize($page->displayoptions);
    $width  = empty($options['popupwidth'])  ? 620 : $options['popupwidth'];
    $height = empty($options['popupheight']) ? 450 : $options['popupheight'];
    $wh = "width=$width,height=$height,toolbar=no,location=no,menubar=no,copyhistory=no,status=no,directories=no,scrollbars=yes,resievents_trigger('colibri_deleted', $eventdata);zable=yes";
    $info->extra = "onclick=\"window.open('$fullurl', '', '$wh'); return false;\"";

    return $info;
}