<?php
/**
 * 'mod_colibri' settings form
 *
 * @package    	Colibri
 * @subpackage 	mod_colibri
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt> - {@link http://ued.ipleiria.pt | Learning Distance Unit }, Polytechnic Institute of Leiria
 * @license	{@link http://www.gnu.org/copyleft/gpl.html |  GNU GPL v3 or later}
 *
 */

require_once(dirname(__FILE__).'/locallib.php');

$settings->add(
    new admin_setting_heading(
	'generalSettings',
	get_string('generalSettings', COLIBRI_PLUGINNAME),
	''
    )
);

$settings->add(
    new admin_setting_configtext(
	'colibri_wsdl_url',
	get_string('colibriWsdlUrl', COLIBRI_PLUGINNAME),
	get_string('colibriWsdlUrl_help', COLIBRI_PLUGINNAME),
	'https://webconference.fccn.pt/colibri/ColibriService?wsdl',
	PARAM_URL,
	60
    )
);

$settings->add(
    new admin_setting_configtext(
	'colibri_direct_access_url',
	get_string('colibriDirectAccessUrl', COLIBRI_PLUGINNAME),
	get_string('colibriDirectAccessUrl_help', COLIBRI_PLUGINNAME),
	'https://webconference.fccn.pt/colibri/mod/direct_access.jsp',
	PARAM_URL,
	60
    )
);


$settings->add(
    new admin_setting_heading(
	'instanceSettings',
	get_string('instanceSettings', COLIBRI_PLUGINNAME),
	''
    )
);

$settings->add(
    new admin_setting_configtext(
	'colibri_installation_identifier',
	get_string('colibriInstallationIdentifier', COLIBRI_PLUGINNAME),
	get_string('colibriInstallationIdentifier_help', COLIBRI_PLUGINNAME),
	''
    )
);

$settings->add(
    new admin_setting_configpasswordunmask(
	'colibri_installation_password',
	get_string('colibriInstallationPassword', COLIBRI_PLUGINNAME),
	get_string('colibriInstallationPassword_help', COLIBRI_PLUGINNAME),
	''
    )
);

$settings->add(
    new admin_setting_configselect(
	'colibri_session_information_update_method',
	get_string('sessioninformationupdatemethod', COLIBRI_PLUGINNAME),
        get_string('sessioninformationupdatemethod_help', COLIBRI_PLUGINNAME),
	ColibriService::LIVE_INFORMATION_METHOD,
	array(
	    ColibriService::LIVE_INFORMATION_METHOD=>get_string('live', COLIBRI_PLUGINNAME),
	    ColibriService::CRON_INFORMATION_METHOD=>get_string('cron', COLIBRI_PLUGINNAME),
	    ColibriService::LOCAL_INFORMATION_METHOD=>get_string('local', COLIBRI_PLUGINNAME)
	)
    )
);