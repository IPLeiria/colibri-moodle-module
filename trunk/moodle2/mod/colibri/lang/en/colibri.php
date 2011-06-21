<?php
/**
 * Strings for the 'mod_colibri' component, english
 *
 * @package    	Colibri
 * @subpackage 	mod_colibri
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt> - {@link http://ued.ipleiria.pt | Learning Distance Unit }, Polytechnic Institute of Leiria
 * @license	{@link http://www.gnu.org/copyleft/gpl.html |  GNU GPL v3 or later}
 *
 */

$string['name'] = 'Colibri';
$string['modulename'] = 'Colibri Session';
$string['pluginname'] = $string['modulename'];
$string['modulenameplural'] = 'Colibri Sessions';
$string['pluginadministration'] = 'Colibri Administration';


//Capabilities
$string['colibri:managesession'] = 'Manage Colibri sessions';
$string['colibri:attendsession'] = 'Attend Colibri sessions';

//Form
$string['sessionname'] = 'Session name';
$string['sessionname_help'] = 'The name for the session and resource';
$string['maximumsessionnamechars'] = 'The session name cannot be so long';
$string['introeditor'] = 'Activity description';
$string['introeditor_help'] = 'The description to be presented to the users before entering the Colibri session';
$string['startdatetime'] = 'Session start';
$string['startdatetime_help'] = 'The time and date when the session will start';
$string['enddatetime'] = 'Session end';
$string['enddatetime_help'] = 'The time and date when the session will end';
$string['duration'] = 'Session duration';
$string['duration_help'] = 'The time the session will take';
$string['sessionpin'] = 'Session PIN';
$string['sessionpin_help'] = 'The session PIN is the code to be entered by the participants to access the session.';
$string['invalidsessionpin'] = 'Invalid session PIN';
$string['moderationpin'] = 'Moderation PIN';
$string['moderationpin_help'] = 'The moderation PIN is the code to be entered by the moderators to take control of the session.';
$string['invalidmoderationpin'] = 'Invalid moderation PIN';
$string['publicsession'] = 'Public session';
$string['publicsession_help'] = 'Is this session public? If yes, then this session will appear on the public session list on the Colibri website';
$string['authorizedsessionusers_help'] = 'Select users with specific access to the session. Their seats will be reserved and can\'t be occupied by the other users';


$string['sessionusers'] = 'Session Users';
$string['maxsessionusers'] = 'Reserved seats';
$string['maxsessionusers_help'] = 'The maximum number of users allowed in the session';
$string['maximumsessionusersnumber'] = 'The maximum number of session users exceded';
$string['selectusers'] = 'Select users';
$string['potencialguests'] = 'Potencial guests';
$string['reservedseats'] = 'Reserved seats';
$string['reserveseat'] = 'Reserve seat(s)';
$string['reserveseatfortheusers'] = 'Reserve seat(s) for the selected user(s)';
$string['clearseat'] = 'Clear seat(s)';
$string['clearseatoftheusers'] = 'Clear seat(s) of the selected user(s)';
$string['users'] = 'Users';
$string['usersmatching'] = 'Users matching \'{$a}\'';

//validation
$string['emptysessionname'] = 'The session name is empty';
$string['youcannotcreateasessioninthepast'] = 'Unable to create a session in the past';
$string['enddatemustbegreaterthanstartdate'] = 'End date must happen after start date';
$string['sessionpinmustbeavalidnumber'] = 'Session Pin must be a valid number';
$string['moderationpinmustbeavalidnumber'] = 'Moderation Pin must be a valid number';
$string['dontbelikethatandinvitesomeonetothesession'] = 'Don\'t be like that and allow someone in the session';
$string['sessiondurationmustbegreaterthanzero'] = 'Duration must be greater than zero';
$string['sessiondurationmustbeanumber'] = 'Duration must be a number';

$string['DATABASE_INSERT_FAILED'] = 'Database insertion failed';
$string['DATABASE_UPDATE_FAILED'] = 'Database update failed';
$string['DATABASE_DELETE_FAILED'] = 'Database deletion failed';
