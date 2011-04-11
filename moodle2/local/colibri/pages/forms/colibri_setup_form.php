<?php
/**
 * Global setup form for the Colibri module definitions
 *
 * @package    	Colibri
 * @subpackage 	local_colibri
 * @version	2011.0
 * @copyright 	Learning Distance Unit {@link http://ued.ipleiria.pt} - Polytechnic Institute of Leiria
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt>
 * @license	http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot.'/local/colibri/lib.php');

class colibri_setup_form extends moodleform {

    function definition() {
        $mform = $this->_form;

        $config = get_config(COLIBRI_PLUGINNAME);
        $mform->addElement('header', 'about', get_string('about', COLIBRI_PLUGINNAME));
        $mform->addElement('html', '<div>'.get_string('usingPluginVersion', COLIBRI_PLUGINNAME, $config->version).'</div>');

        // General settings
        $mform->addElement('header', 'generalsettings', get_string('generalSettings', COLIBRI_PLUGINNAME));
        
        $mform->addElement('text', 'colibri_wsdl_url', get_string('colibriWsdlUrl', COLIBRI_PLUGINNAME), array('size'=>'250'));
        $mform->setType('colibri_wsdl_url', PARAM_URL);
        $mform->addRule('colibri_wsdl_url', get_string('colibriEmptyWsdlURL', COLIBRI_PLUGINNAME), 'required', null);
        $mform->setDefault('colibri_wsdl_url', (isset($config->colibri_wsdl_url)?$config->colibri_wsdl_url:'https://webconference.fccn.pt/colibri/ColibriService?wsdl'));
        $mform->addHelpButton('colibri_wsdl_url', 'colibriWsdlUrl', COLIBRI_PLUGINNAME);

        // Instance settings
        $mform->addElement('header', 'instancesettings', get_string('instanceSettings', COLIBRI_PLUGINNAME));

        $mform->addElement('text', 'colibri_installation_identifier', get_string('colibriInstallationIdentifier', COLIBRI_PLUGINNAME));
        $mform->addRule('colibri_installation_identifier', get_string('colibriEmptyInstallationIdentifier', COLIBRI_PLUGINNAME), 'required', null);
        $mform->setDefault('colibri_installation_identifier', (isset($config->colibri_installation_identifier)?$config->colibri_installation_identifier:''));
        $mform->addHelpButton('colibri_installation_identifier', 'colibriInstallationIdentifier', COLIBRI_PLUGINNAME);

        $mform->addElement('passwordunmask', 'colibri_installation_password', get_string('colibriInstallationPassword', COLIBRI_PLUGINNAME));
        $mform->addRule('colibri_installation_password', get_string('colibriEmptyInstallationPassword', COLIBRI_PLUGINNAME), 'required', null);
        $mform->setDefault('colibri_installation_password', (isset($config->colibri_installation_password)?$config->colibri_installation_password:''));
        $mform->addHelpButton('colibri_installation_password', 'colibriInstallationPassword', COLIBRI_PLUGINNAME);

        $this->add_action_buttons(false, get_string('save', COLIBRI_PLUGINNAME));
    }

    function validation($data, $files) {
        global $CFG;
        $errors= array();

        $errors = parent::validation($data, $files);

        if(is_null(ColibriService::getSoapClientInstance($data['colibri_wsdl_url']))){
            $errors['colibri_wsdl_url'] = get_string('colibriInvalidWsdlUrl', COLIBRI_PLUGINNAME);
        }
        return $errors;
    }
}
