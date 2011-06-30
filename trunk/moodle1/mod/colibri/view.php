<?php
/**
 * The view for the Colibri activity
 *
 * @package    	Colibri
 * @subpackage 	mod_colibri
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt> - {@link http://ued.ipleiria.pt | Learning Distance Unit }, Polytechnic Institute of Leiria
 * @license	{@link http://www.gnu.org/copyleft/gpl.html |  GNU GPL v3 or later}
 */

require_once('../../config.php');
global $USER, $CFG;
require_once($CFG->libdir.'/blocklib.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/pagelib.php');

$id = required_param('id', PARAM_INT);
$edit = optional_param('edit', -1, PARAM_BOOL);

if (! $cm = get_coursemodule_from_id(COLIBRI_PLUGINNAME, $id)):
    print_error('invalidcoursemodule');
endif;
if (! $course = get_record("course", "id", $cm->course)):
    print_error('coursemisconf');
endif;
if (! $colibri = get_record("colibri", "id", $cm->instance)):
    print_error('invalidcolibriid', COLIBRI_PLUGINNAME);
endif;
$url = new moodle_url('/mod/colibri/view.php', array('id' => $id));

// set the context
$context = get_context_instance(CONTEXT_MODULE, $cm->id);

// permissions
require_login($course, true, $cm);
require_capability('mod/colibri:attendsession', $context);
if (empty($cm->visible) and !has_capability('moodle/course:viewhiddenactivities', $context)):
    notice(get_string("activityiscurrentlyhidden"));
endif;

// log
add_to_log($course->id, COLIBRI_PLUGINNAME, 'view', "view.php?id={$id}", $cm->instance, $id);

$session = Colibri::getSessionInfo($cm->instance);
if(!empty($session) && isset($session->sessionStatus)):
    // If we are going enter the session, skip the page layout
    if($session->sessionStatus == sessionResult::SESSION_STATUS_INSESSION):
	if(Colibri::reserveSeatForUserInSession($colibri->id, $USER->id)):
	    if(Colibri::hasUserSessionAdminPriviligies($colibri->id, $USER->id)):
		redirect(Colibri::getSessionUrl($colibri->id, $USER), get_string('redirectingtothesessionasmoderator', COLIBRI_PLUGINNAME, $session->moderationPin), 15);
	    else:
		redirect(Colibri::getSessionUrl($colibri->id, $USER), get_string('redirectingtothesession', COLIBRI_PLUGINNAME), 3);
	    endif;
	else:
	    echo(get_string('unabletoaccessthesession_allseatstaken', COLIBRI_PLUGINNAME));
	endif;
    else:
	$PAGE = page_create_instance($cm->instance);
	$pageblocks = blocks_setup($PAGE);
	$blocks_preferred_width = bounded_number(180, blocks_preferred_width($pageblocks[BLOCK_POS_LEFT]), 210);

	if (($edit != -1) and $PAGE->user_allowed_editing()):
	    $USER->editing = $edit;
	endif;

	$PAGE->print_header($course->shortname.': %fullname%');
	/// If we have blocks, then print the left side here
	if (!empty($CFG->showblocksonmodpages)) :
	    echo '<table id="layout-table"><tr>';
	    if ((blocks_have_content($pageblocks, BLOCK_POS_LEFT) || $PAGE->user_is_editing())):
		echo '<td style="width: '.$blocks_preferred_width.'px;" id="left-column">';
		print_container_start();
		blocks_print_group($PAGE, $pageblocks, BLOCK_POS_LEFT);
		print_container_end();
		echo '</td>';
	    endif;
	    echo '<td id="middle-column">';
	    print_container_start();
	endif;

	print_heading(format_string($session->name));

	switch($session->sessionStatus):
	    case sessionResult::SESSION_STATUS_SCHEDULED:
		print_box(get_string('sessionscheduletostart_title', COLIBRI_PLUGINNAME, usergetdate($session->startDateTimeStamp/1000)));
		break;
	    case sessionResult::SESSION_STATUS_FINISHED:
		print_box(get_string('thesessionhasended', COLIBRI_PLUGINNAME));
		//@TODO: show the list of recordings

		break;

	    case sessionResult::SESSION_STATUS_INSESSION:
		break;
	endswitch;

	/// If we have blocks, then print the left side here
        if (!empty($CFG->showblocksonmodpages)):
            print_container_end();
            echo '</td>';   // Middle column
            if ((blocks_have_content($pageblocks, BLOCK_POS_RIGHT) || $PAGE->user_is_editing())):
                echo '<td style="width: '.$blocks_preferred_width.'px;" id="right-column">';
                print_container_start();
                blocks_print_group($PAGE, $pageblocks, BLOCK_POS_RIGHT);
                print_container_end();
                echo '</td>';
            endif;
            echo '</tr></table>';
        endif;

	print_footer($course);
    endif;
else:
    notify(get_string('unabletogetthesessionstate', COLIBRI_PLUGINNAME), 'notifyproblem');
    //print_error('unabletogetthesessionstate', COLIBRI_PLUGINNAME, new moodle_url('/course/view.php', array('id' => $course->id)));
endif;