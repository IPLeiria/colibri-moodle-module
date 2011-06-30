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
$string['startdate'] = 'Session start';
$string['startdate_help'] = 'The time and date when the session will start';
$string['enddate'] = 'Session end';
$string['enddate_help'] = 'The time and date when the session will end';
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

$string['DATABASE_INSERT_FAILED'] = 'Database insertion failed';
$string['DATABASE_UPDATE_FAILED'] = 'Database update failed';
$string['DATABASE_DELETE_FAILED'] = 'Database deletion failed';

$string['sessionscheduletostart'] = ' (scheduled)';
$string['sessionscheduletostart_title'] = 'Schedule to start at {$a[\'weekday\']}, {$a[\'month\']} {$a[\'mday\']}, {$a[\'year\']} at {$a[\'hours\']}:{$a[\'minutes\']}';
$string['sessionstartedxparticipantsinsession'] = ' (started)';
$string['sessionstartedxparticipantsinsession_title'] = '{$a} participants in the session';
$string['sessionfinished'] = '';
$string['sessionfinished_title'] = '';

$string['invalidcolibriid'] = 'The Colibri session resource identifier is invalid';
$string['unabletogetthesessionstate'] = 'Unable to get the session state';

$string['thesessionhasended'] = 'The session has ended';
$string['unabletoaccessthesession_allseatstaken'] = 'Unable to access the session: all the seats are taken.';
$string['redirectingtothesession'] = 'Redirecting to the Colibri session...';
$string['redirectingtothesessionasmoderator'] = 'Redirecting to the Colibri session. You can moderate this session by using the moderator PIN {$a} inside the session.';




//Capabilities
$string['colibri:configureplugin'] = 'Configure the Colibri plugin settings';


// library strings
$string['webServiceLinkFailed'] = 'Could not establish a connection to the web service';
$string['unableToConnectToTheServer'] = 'Unable to connect to the remote server';
$string['unableToCreateSoapClientInstance'] = 'Unable to create a SOAP client instance for the plugin';
$string['remoteMethodExecutionFailed'] = 'Unable to execute the remote SOAP method';
$string['invalidMethodResponse'] = 'Invalid method response';

$string['getSoapTypesFailed'] = 'Unable to get the SOAP types from the Colibri server';
$string['getSoapFunctionsFailed'] = 'Unable to get the SOAP methods from the Colibri server';
$string['getColibriTimeFailed'] = 'Unable to get the time from the Colibri server';

$string['checkAccessFailed'] = 'Unable to create the check the credentials on the Colibri server';

$string['invalidName'] = 'The session name is invalid';
$string['invalidStartDate'] = 'The session start date is invalid';
$string['invalidEndDate'] = 'The session end date is invalid';
$string['invalidNumberOfParticipants'] = 'The of participants provided is invalid (the session must have participants!)';
$string['createSessionFailed'] = 'Unable to create the session on the Colibri server';
$string['modifySessionFailed'] = 'Unable to modify the session {$a} on the Colibri server';
$string['removeSessionFailed'] = 'Unable to remove the session {$a} from the Colibri server';
$string['getSessionInfoFailed'] = 'Unable to get the session information from the Colibri server';
$string['getSessionsInfoFailed'] = 'Unable to get the sessions information from the Colibri server';

$string['errorReturned'] = 'An error occurred ({$a})';
$string['GENERIC_ERROR'] = 'Error: {$a}';
$string['SOAP_INSTANCE_ERROR'] = 'Error on the SOAP client instance';
$string['SOAP_FAULT'] = 'SOAP error';
$string['EXCEPTION'] = 'Exception occurred';
$string['INVALID_START_DATE'] = 'Invalid start date';
$string['INVALID_END_DATE'] = 'Invalid end date';
$string['INVALID_NUMBER_OF_PARTICIPANTS'] = 'Invalid number of participants';
$string['ERROR_ON_METHOD_RESPONSE'] = 'Error on the remote method response';
$string['INVALID_ACCESS_CREDENTIALS'] = 'The credentials are invalid';
$string['MISSING_ACCESS_CREDENTIALS'] = 'The credentials are missing on the request';
$string['UNKOWN_ERROR'] = 'Unkown error: {$a}';
$string['COULD_NOT_CREATE_SESSION'] = 'Unable to create the session';
$string['MISSING_SESSION_NAME'] = 'Session name is not defined';
$string['SESSION_START_TIME_GREATER_THAN_ENDTIME'] = 'Start time is greater than end time';
$string['COULD_NOT_GET_SESSION_INFO'] = 'Unable to get the session info';
$string['INSUFFICIENT_PERMISSIONS'] = 'Insufficient permissions';
$string['COULD_NOT_MODIFY_SESSION'] = 'Unable to modify the session';
$string['COULD_NOT_REMOVE_SESSION'] = 'Unable to remove the session';
$string['INVALID_NAME'] = 'Invalid name';


// settings menu
$string['colibri'] = 'Colibri';
$string['configuration'] = 'Configuration';
// configuration page
$string['colibriConfiguration'] = 'Colibri Configuration';
$string['insuficientPermissionsToConfigureTheColibriPlugin'] = 'Insuficient permissions to configure the Colibri plugin';
$string['colibriSettingsSaved'] = 'Settings saved';
$string['colibriErrorSavingSettings'] = 'An error occurred while saving settings ({$a})';
// configuration form
$string['about'] = 'About';
$string['usingPluginVersion'] = 'Local plugin version: {$a}';
$string['generalSettings'] = 'General Settings';
$string['colibriWsdlUrl'] = 'WSDL URL';
$string['colibriEmptyWsdlURL'] = 'The URL for web service WSDL must be set';
$string['colibriWsdlUrl_help'] = 'WSDL URL is the URL with the web service descriptor document to use the web service';
$string['colibriDirectAccessUrl'] = 'Direct Access URL';
$string['emptyColibriDirectAccessUrl'] = 'The direct access URL cannot be empty';
$string['colibriDirectAccessUrl_help'] = 'The base URL that allows the direct access by an user to a session';
$string['instanceSettings'] = 'Moodle Instance Settings';
$string['colibriInstallationIdentifier'] = 'Moodle Installation ID';
$string['colibriEmptyInstallationIdentifier'] = 'The installation identifier must be set';
$string['colibriInstallationIdentifier_help'] = 'The installation identifier is the unique installation identifier provided by the Colibri services to use the web services interface';
$string['colibriInstallationPassword'] = 'Moodle Installation Key';
$string['colibriEmptyInstallationPassword'] = 'The installation key must be set';
$string['colibriInstallationPassword_help'] = 'The installation key is the password provided by the Colibri services to use the web services interface';
$string['sessioninformationupdatemethod'] = 'Session Information';
$string['sessioninformationupdatemethod_help'] = 'The method used to retrieve the sessions information. The options are \'live\', to get the information directly from the Colibri Service, \'cron\' to retrieve the information from the database and sync the information on cron updates or \'local\' to use only the local database information.';
$string['live'] = 'live';
$string['cron'] = 'cron';
$string['local'] = 'local';
$string['save'] = 'Save';
$string['colibriInvalidWsdlUrl'] = 'Cannot connect with the web service. Please verify the provided URL and try again.';


$string['sessionnames'] = 'Session name';
$string['sessionstart'] = 'Session start';
$string['sessionduration'] = 'Session duration';
$string['numberofparticipants'] = 'Number of participants';
$string['startdatetime'] = '{$a[\'weekday\']}, {$a[\'month\']} {$a[\'mday\']}, {$a[\'year\']} at {$a[\'hours\']}:{$a[\'minutes\']}';
$string['durationtime'] = '{$a[\'hours\']} hour(s), {$a[\'mins\']} minute(s)';
