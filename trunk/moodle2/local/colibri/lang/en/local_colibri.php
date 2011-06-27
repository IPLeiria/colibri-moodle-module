<?php
/**
 * Strings for the 'local_colibri' component, english
 *
 * @package    	Colibri
 * @subpackage 	local_colibri
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt> - {@link http://ued.ipleiria.pt | Learning Distance Unit }, Polytechnic Institute of Leiria
 * @license	{@link http://www.gnu.org/copyleft/gpl.html |  GNU GPL v3 or later}
 *
 * SVN:
 * $Author$
 * $Date$
 * $Rev$
 */

$string['pluginname'] = 'Colibri';
$string['local_colibri'] = $string['pluginname'];


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
