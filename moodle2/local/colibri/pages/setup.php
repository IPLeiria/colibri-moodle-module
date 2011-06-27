<?php
/**
 * Global configuration page for the Colibri module definitions
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

require_once(dirname(__FILE__) . '/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once(dirname(__FILE__) . '/forms/colibri_setup_form.php');
require_once($CFG->dirroot.'/local/colibri/lib.php');

admin_externalpage_setup('colibri_config');

global $USER;

// verify the user capabilities
$systemcontext = get_context_instance(CONTEXT_SYSTEM);
if(!has_capability('local/colibri:configureplugin', $systemcontext, $USER) && !is_siteadmin($USER)):
    print_error('insuficientPermissionsToConfigureTheColibriPlugin', EU4ALLMODULENAME);
endif;

// instanciate the form
$form = new colibri_setup_form();

// Display the settings form.
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('colibriConfiguration', COLIBRI_PLUGINNAME));

$data = $form->get_data();
if ($data):
    try{
        if(isset($data->colibri_wsdl_url)):
            set_config('colibri_wsdl_url', $data->colibri_wsdl_url, COLIBRI_PLUGINNAME);
        endif;
        if(isset($data->colibri_direct_access_url)):
            set_config('colibri_direct_access_url', $data->colibri_direct_access_url, COLIBRI_PLUGINNAME);
        endif;
        if(isset($data->colibri_installation_identifier)):
            set_config('colibri_installation_identifier', $data->colibri_installation_identifier, COLIBRI_PLUGINNAME);
        endif;
        if(isset($data->colibri_installation_password)):
            set_config('colibri_installation_password', $data->colibri_installation_password, COLIBRI_PLUGINNAME);
        endif;
        if(isset($data->session_information_update_method)):
            set_config('session_information_update_method', $data->session_information_update_method, COLIBRI_PLUGINNAME);
        endif;

	
        echo($OUTPUT->notification(get_string('colibriSettingsSaved', COLIBRI_PLUGINNAME), 'notifysuccess'));
    }catch(Exception $ex){
        echo($OUTPUT->notification(get_string('colibriErrorSavingSettings', COLIBRI_PLUGINNAME, $ex->getMessage()), 'notifyproblem'));
    }
endif;

$form->display();

echo $OUTPUT->footer();