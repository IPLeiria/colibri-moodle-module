<?php
/**
 * Strings for the 'local_colibri' component, english
 *
 * @package    	Colibri
 * @subpackage 	local_colibri
 * @copyright 	{@link http://ued.ipleiria.pt | Learning Distance Unit } - Polytechnic Institute of Leiria
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt>
 * @license	{@link http://www.gnu.org/copyleft/gpl.html |  GNU GPL v3 or later}
 *
 * SVN:
 * $Author$
 * $Date$
 * $Rev$
 */

$string['pluginname'] = 'EU4ALL';


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

$string['invalidStartDate'] = 'The session start date is invalid';
$string['invalidEndDate'] = 'The session end date is invalid';
$string['invalidNumberOfParticipants'] = 'The of participants provided is invalid (the session must have participants!)';
$string['createSessionFailed'] = 'Unable to create the session on the Colibri server';

$string['getSessionInfoFailed'] = 'Unable to get the time from the Colibri server';



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
$string['instanceSettings'] = 'Moodle Instance Settings';
$string['colibriInstallationIdentifier'] = 'Moodle Installation ID';
$string['colibriEmptyInstallationIdentifier'] = 'The installation identifier must be set';
$string['colibriInstallationIdentifier_help'] = 'The installation identifier is the unique installation identifier provided by the Colibri services to use the web services interface';
$string['colibriInstallationPassword'] = 'Moodle Installation Key';
$string['colibriEmptyInstallationPassword'] = 'The installation key must be set';
$string['colibriInstallationPassword_help'] = 'The installation key is the password provided by the Colibri services to use the web services interface';
$string['save'] = 'Save';
$string['colibriInvalidWsdlUrl'] = 'Cannot connect with the web service. Please verify the provided URL and try again.';
