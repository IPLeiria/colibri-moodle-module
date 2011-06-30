<?php
/**
 * Index file for viewing all the Colibri session in a course
 *
 * @package    	Colibri
 * @subpackage 	mod_colibri
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt> - {@link http://ued.ipleiria.pt | Learning Distance Unit }, Polytechnic Institute of Leiria
 * @license	{@link http://www.gnu.org/copyleft/gpl.html |  GNU GPL v3 or later}
 *
 */

require_once('../../config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = required_param('id', PARAM_INT);   // course

if (! $course = get_record('course', 'id', $id)):
    error('Course ID is incorrect');
endif;

require_course_login($course);

add_to_log($course->id, COLIBRI_PLUGINNAME, 'view all', "index.php?id=$course->id", '');

/// Get all required strings
$strsessions = get_string('modulenameplural', COLIBRI_PLUGINNAME);
$strsession  = get_string('modulename', COLIBRI_PLUGINNAME);


/// Print the header

$navlinks = array();
$navlinks[] = array('name' => $strsessions, 'link' => '', 'type' => 'activity');
$navigation = build_navigation($navlinks);

print_header_simple($strsessions, '', $navigation, '', '', true, '', navmenu($course));

/// Get all the appropriate data

if (! $sessions = get_all_instances_in_course(COLIBRI_PLUGINNAME, $course)):
    notice(get_string('thereareno', 'moodle', $strsessions), "../../course/view.php?id=$course->id");
    die();
endif;

/// Print the list of instances (your module will probably extend this)

$timenow  = time();
$strname  = get_string('name');

$table->head  = array(
    get_string('sessionnames', COLIBRI_PLUGINNAME),
    get_string('sessionstart', COLIBRI_PLUGINNAME),
    get_string('sessionduration', COLIBRI_PLUGINNAME),
    get_string('numberofparticipants', COLIBRI_PLUGINNAME)
);
$table->align = array ('left', 'left', 'left', 'right');


$currentsection = '';
foreach ($sessions as $session):
    $sessionInfo = Colibri::getSessionInfo($session->id);

    $printsection = '';
    if ($session->section !== $currentsection):
	if ($session->section):
	    $printsection = $session->section;
	endif;
	if ($currentsection !== ''):
	    $table->data[] = 'hr';
	endif;
	$currentsection = $session->section;
    endif;
    
    $link = "<a ".(!$session->visible?"class=\"dimmed\" ":'')."href=\"view.php?id=$session->coursemodule\">".format_string($session->name,true)."</a>";
    $startDate = get_string('startdatetime', COLIBRI_PLUGINNAME, usergetdate($sessionInfo->startDateTimeStamp/1000));

    $duration=array();
    $duration['timestamp'] = ($sessionInfo->endDateTimeStamp-$sessionInfo->startDateTimeStamp)/1000;
    $duration['days'] = floor($duration['timestamp']/60/60/24);
    $duration['hours'] = $duration['timestamp']/60/60%24+$duration['days']*24;
    $duration['mins'] = $duration['timestamp']/60%60;
    $duration['secs'] = $duration['timestamp']%60;
    $duration = get_string('durationtime', COLIBRI_PLUGINNAME, $duration);
    $participants=($sessionInfo->sessionStatus!=sessionResult::SESSION_STATUS_SCHEDULED?count($sessionInfo->users):'-');
    
    $table->data[] = array($link, $startDate, $duration, $participants);
endforeach;

echo '<br />';

print_table($table);

/// Finish the page

print_footer($course);