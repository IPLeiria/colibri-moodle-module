<?php
/**
 * The main Colibri activity configuration form
 *
 * @package    	Colibri
 * @subpackage 	mod_colibri
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt> - {@link http://ued.ipleiria.pt | Learning Distance Unit }, Polytechnic Institute of Leiria
 * @license	{@link http://www.gnu.org/copyleft/gpl.html |  GNU GPL v3 or later}
 *
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/moodleform_mod.php');
require_once($CFG->libdir . '/filelib.php');
require_once(dirname(__FILE__) . '/locallib.php');
require_once(dirname(__FILE__) . '/form/selectfromto.php');

class mod_colibri_mod_form extends moodleform_mod {

    /**
     * Defines the colibri instance configuration form
     *
     * @return void
     */
    function definition() {

        global $CFG, $COURSE;
        $colibriconfig = get_config('colibri');
        $mform = $this->_form;

	if(!$postData = &data_submitted()){
	    //$postData = $this->externalData;
        }

        // General --------------------------------------------------------------------
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Colibri name
        $label = get_string('sessionname', 'colibri');
        $mform->addElement('text', 'name', $label, array('size'=>'64'));
	$mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumsessionnamechars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('sessionname', 'sessionname', 'colibri');

        // Introduction
        $label = get_string('introeditor', 'colibri');
        $this->add_intro_editor(false,$label);
        $mform->addHelpButton('introeditor', 'introeditor', 'colibri');

	// Start date time
	$label = get_string('startdatetime', 'colibri');
        $mform->addElement('date_time_selector', 'startdatetime', $label);
        $mform->addRule('startdatetime', null, 'required', null, 'client');
        $mform->addHelpButton('startdatetime', 'startdatetime', 'colibri');

	// End date time
	$label = get_string('enddatetime', 'colibri');
        $mform->addElement('date_time_selector', 'enddatetime', $label);
        $mform->addRule('enddatetime', null, 'required', null, 'client');
        $mform->addHelpButton('enddatetime', 'enddatetime', 'colibri');

	$mform->addElement('text', 'sessionpin', get_string('sessionpin', 'colibri'), array('size'=>'4'));
	$mform->setType('sessionpin', PARAM_INT);
        $mform->addRule('sessionpin', null, 'required', null, 'client');
        $mform->addRule('sessionpin', get_string('invalidsessionpin', 'colibri'), 'regex', '/^\d{4}$/', 'client');
        $mform->addHelpButton('sessionpin', 'sessionpin', 'colibri');
	$mform->setDefault('sessionpin', ColibriService::generatePin());

	$mform->addElement('passwordunmask', 'moderationpin', get_string('moderationpin', 'colibri'), array('size'=>'4'));
	$mform->setType('moderationpin', PARAM_INT);
        $mform->addRule('moderationpin', null, 'required', null, 'client');
        $mform->addRule('moderationpin', get_string('invalidmoderationpin', 'colibri'), 'regex', '/^\d{4}$/', 'client');
        $mform->addHelpButton('moderationpin', 'moderationpin', 'colibri');
	$mform->setDefault('moderationpin', ColibriService::generatePin());

	$mform->addElement('selectyesno', 'publicsession', get_string('publicsession', 'colibri'));
        $mform->addHelpButton('publicsession', 'publicsession', 'colibri');
	


        $mform->addElement('header', 'sessionusers', get_string('sessionusers', 'colibri'));
	
	// Session users
        $label = get_string('maxsessionusers', 'colibri');
        $maxsessionusers = $mform->addElement('text', 'maxsessionusers', $label, array('size'=>'3'));
	$mform->setType('maxsessionusers', PARAM_INT);
        $mform->addRule('maxsessionusers', null, 'numeric', null, 'client');
        $mform->addRule('maxsessionusers', null, 'required', null, 'client');
        $mform->addRule('maxsessionusers', get_string('maximumsessionusersnumber', 'colibri', 5), 'maxlength', 5, 'client');
        $mform->addHelpButton('maxsessionusers', 'maxsessionusers', 'colibri');
	
	$labels=array(
	    'elementLabel'=>get_string('selectusers', 'colibri'),
	    'fromLabel'=> get_string('potencialguests', 'colibri'),
	    'toLabel'=> get_string('reservedseats', 'colibri'),
	    'addTo'=>get_string('reserveseat', 'colibri'),
	    'removeFrom'=>get_string('clearseat', 'colibri')
	);
	
        $users = $mform->addElement('selectfromto', 'authorizedsessionusers', $labels);
	$mform->disabledIf('selectfromto', 'sessionusersradio', 'eq', 0);
	$mform->setAdvanced('authorizedsessionusers');

	// based on the selected users, increment the size of the reserved seats accordingly
	$usersSelected = $users->getSelected();
	if(isset($_POST['maxsessionusers']) && is_array($usersSelected) && count($usersSelected)>$_POST['maxsessionusers']):
	    $_POST['maxsessionusers'] = count($usersSelected);
	    $mform->setDefault('maxsessionusers',  $_POST['maxsessionusers']);
	endif;

	

        $this->standard_coursemodule_elements();

        // Standard buttons, common to all modules ------------------------------------
        $this->add_action_buttons();
    }



    function validation($data, $files) {
        global $CFG;
        $errors= array();

        $errors = parent::validation($data, $files);
	/*
        if(is_null(ColibriService::getSoapClientInstance($data['colibri_wsdl_url']))){
            $errors['colibri_wsdl_url'] = get_string('colibriInvalidWsdlUrl', COLIBRI_PLUGINNAME);
        }
	*/
        return $errors;
    }

    /**
     * Prepares the form before data are set
     *
     * Additional wysiwyg editor are prepared here, the introeditor is prepared automatically by core
     *
     * @param array $data to be set
     * @return void
     */
    function data_preprocessing(&$data) {
	/*
        if ($this->current->instance) {
            // editing an existing colibri - let us prepare the added editor elements (intro done automatically)
            $draftitemid = file_get_submitted_draft_itemid('instructauthors');
            $data['instructauthorseditor']['text'] = file_prepare_draft_area($draftitemid, $this->context->id,
                                'mod_colibri', 'instructauthors', 0,
                                colibri::instruction_editors_options($this->context),
                                $data['instructauthors']);
            $data['instructauthorseditor']['format'] = $data['instructauthorsformat'];
            $data['instructauthorseditor']['itemid'] = $draftitemid;

            $draftitemid = file_get_submitted_draft_itemid('instructreviewers');
            $data['instructreviewerseditor']['text'] = file_prepare_draft_area($draftitemid, $this->context->id,
                                'mod_colibri', 'instructreviewers', 0,
                                colibri::instruction_editors_options($this->context),
                                $data['instructreviewers']);
            $data['instructreviewerseditor']['format'] = $data['instructreviewersformat'];
            $data['instructreviewerseditor']['itemid'] = $draftitemid;
        } else {
            // adding a new colibri instance
            $draftitemid = file_get_submitted_draft_itemid('instructauthors');
            file_prepare_draft_area($draftitemid, null, 'mod_colibri', 'instructauthors', 0);    // no context yet, itemid not used
            $data['instructauthorseditor'] = array('text' => '', 'format' => editors_get_preferred_format(), 'itemid' => $draftitemid);

            $draftitemid = file_get_submitted_draft_itemid('instructreviewers');
            file_prepare_draft_area($draftitemid, null, 'mod_colibri', 'instructreviewers', 0);    // no context yet, itemid not used
            $data['instructreviewerseditor'] = array('text' => '', 'format' => editors_get_preferred_format(), 'itemid' => $draftitemid);
        }
	 */

    }
}
