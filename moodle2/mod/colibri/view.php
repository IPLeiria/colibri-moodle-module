<?php
/**
 * The view for the Colibri activity
 *
 * @package    	Colibri
 * @subpackage 	mod_colibri
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt> - {@link http://ued.ipleiria.pt | Learning Distance Unit }, Polytechnic Institute of Leiria
 * @license	{@link http://www.gnu.org/copyleft/gpl.html |  GNU GPL v3 or later}
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');
require_once($CFG->libdir.'/completionlib.php');

global $PAGE, $USER;

$id = required_param('id', PARAM_INT);
if (! $cm = get_coursemodule_from_id('colibri', $id, 0, false, MUST_EXIST)):
    print_error('invalidcoursemodule');
endif;
if (! $course = $DB->get_record("course", array("id" => $cm->course), '*', MUST_EXIST)):
    print_error('coursemisconf');
endif;
if (! $colibri = $DB->get_record("colibri", array("id" => $cm->instance), '*', MUST_EXIST)):
    print_error('invalidcolibriid', 'colibri');
endif;
$url = new moodle_url('/mod/colibri/view.php', array('id' => $id));
$PAGE->set_url($url);

// set the context
$context = get_context_instance(CONTEXT_MODULE, $cm->id);
$PAGE->set_context($context);

// permissions
require_login($course, true, $cm);
require_capability('mod/colibri:attendsession', $PAGE->context);
if (empty($cm->visible) and !has_capability('moodle/course:viewhiddenactivities', $context)):
    notice(get_string("activityiscurrentlyhidden"));
endif;

// log
add_to_log($course->id, 'colibri', 'view', "view.php?id={$id}", $cm->instance, $id);

// define the headings
$PAGE->set_title(format_string($colibri->name));
$PAGE->set_heading(format_string($course->fullname));

$session = Colibri::getSessionInfo($cm->instance);
if(!empty($session) && isset($session->sessionStatus)):
    switch($session->sessionStatus):
	case sessionResult::SESSION_STATUS_SCHEDULED:
	    echo($OUTPUT->header());

	    $title = get_string('sessionscheduletostart_title', 'colibri', usergetdate($session->startDateTimeStamp/1000));
	    echo($title);

	    echo($OUTPUT->footer($course));
	    break;
	case sessionResult::SESSION_STATUS_INSESSION:
	    if(Colibri::reserveSeatForUserInSession($colibri->id, $USER->id)):
		$completion=new completion_info($course);
		$completion->set_module_viewed($cm);

		if(Colibri::hasUserSessionAdminPriviligies($colibri->id, $USER->id)):
		    redirect(Colibri::getSessionUrl($colibri->id, $USER), get_string('redirectingtothesessionasmoderator', 'colibri', $session->moderationPin), 15);
		else:
		    redirect(Colibri::getSessionUrl($colibri->id, $USER), get_string('redirectingtothesession', 'colibri'), 3);
		endif;
	    else:
		echo(get_string('unabletoaccessthesession_allseatstaken', 'colibri'));
	    endif;
	    
	    break;
	case sessionResult::SESSION_STATUS_FINISHED:
	    echo($OUTPUT->header());
	    
	    echo(get_string('thesessionhasended', 'colibri'));
	    //@TODO: show the list of recordings

	    echo($OUTPUT->footer($course));
	    break;
    endswitch;
else:
    print_error('unabletogetthesessionstate', 'colibri', new moodle_url('/course/view.php', array('id' => $course->id)));
endif;