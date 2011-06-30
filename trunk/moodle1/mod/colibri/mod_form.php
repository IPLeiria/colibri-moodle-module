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
//require_once(dirname(__FILE__) . '/form/selectfromto.php');

class mod_colibri_mod_form extends moodleform_mod {
    private $usersData = NULL;

    public function __construct(&$data, $section, $cm){
	    $this->usersData = &$data;
	    //self::getData($data->instance, $this->usersData);

	    parent::__construct($data, $section, $cm);
    }

    /**
     * Defines the colibri instance configuration form
     *
     * @return void
     */
    function definition() {

        global $CFG, $COURSE;
        $mform = $this->_form;
	/*
	if(!$postData = &data_submitted()){
	    $postData = $this->usersData;
        }
	*/
        // General --------------------------------------------------------------------
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Colibri name
        $label = get_string('sessionname', COLIBRI_PLUGINNAME);
        $mform->addElement('text', 'name', $label, array('size'=>'64'));
	$mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumsessionnamechars', '', 255), 'maxlength', 255, 'client');
//        $mform->addHelpButton('sessionname', 'sessionname', COLIBRI_PLUGINNAME);

        // Introduction
	$mform->addElement('htmleditor', 'intro', get_string('introeditor', COLIBRI_PLUGINNAME));
        $mform->setType('intro', PARAM_RAW);
        $mform->setHelpButton('intro', array('writing', 'questions', 'richtext'), false, 'editorhelpbutton');

	// Start date time
	$label = get_string('startdate', COLIBRI_PLUGINNAME);
        $mform->addElement('date_time_selector', 'startdate', $label);
        $mform->addRule('startdate', null, 'required', null, 'client');
//        $mform->addHelpButton('startdate', 'startdate', COLIBRI_PLUGINNAME);
	$mform->setDefault('startdate', time()+3600);

	
	// End date time
	$label = get_string('enddate', COLIBRI_PLUGINNAME);
        $mform->addElement('date_time_selector', 'enddate', $label);
        $mform->addRule('enddate', null, 'required', null, 'client');
//        $mform->addHelpButton('enddate', 'enddate', COLIBRI_PLUGINNAME);
	$mform->setDefault('enddate', time()+2*3600);
	
	$mform->addElement('text', 'sessionpin', get_string('sessionpin', COLIBRI_PLUGINNAME), array('size'=>'4'));
	$mform->setType('sessionpin', PARAM_INT);
        $mform->addRule('sessionpin', null, 'required', null, 'client');
        $mform->addRule('sessionpin', get_string('invalidsessionpin', COLIBRI_PLUGINNAME), 'regex', '/^\d{4}$/', 'client');
//        $mform->addHelpButton('sessionpin', 'sessionpin', COLIBRI_PLUGINNAME);
	$mform->setDefault('sessionpin', ColibriService::generatePin());

	$mform->addElement('passwordunmask', 'moderationpin', get_string('moderationpin', COLIBRI_PLUGINNAME), array('size'=>'4'));
	$mform->setType('moderationpin', PARAM_INT);
        $mform->addRule('moderationpin', null, 'required', null, 'client');
        $mform->addRule('moderationpin', get_string('invalidmoderationpin', COLIBRI_PLUGINNAME), 'regex', '/^\d{4}$/', 'client');
//        $mform->addHelpButton('moderationpin', 'moderationpin', COLIBRI_PLUGINNAME);
	$mform->setDefault('moderationpin', ColibriService::generatePin());

	$mform->addElement('selectyesno', 'publicsession', get_string('publicsession', COLIBRI_PLUGINNAME));
//        $mform->addHelpButton('publicsession', 'publicsession', COLIBRI_PLUGINNAME);
	


        $mform->addElement('header', 'sessionusers', get_string('sessionusers', COLIBRI_PLUGINNAME));
	
	// Session users
        $label = get_string('maxsessionusers', COLIBRI_PLUGINNAME);
        $maxsessionusers = $mform->addElement('text', 'maxsessionusers', $label, array('size'=>'3'));
	$mform->setType('maxsessionusers', PARAM_INT);
        $mform->addRule('maxsessionusers', null, 'numeric', null, 'client');
        $mform->addRule('maxsessionusers', null, 'required', null, 'client');
        $mform->addRule('maxsessionusers', get_string('maximumsessionusersnumber', COLIBRI_PLUGINNAME, 5), 'maxlength', 5, 'client');
//        $mform->addHelpButton('maxsessionusers', 'maxsessionusers', COLIBRI_PLUGINNAME);
	/*
	$labels=array(
	    'elementLabel'=>get_string('selectusers', COLIBRI_PLUGINNAME),
	    'fromLabel'=> get_string('potencialguests', COLIBRI_PLUGINNAME),
	    'toLabel'=> get_string('reservedseats', COLIBRI_PLUGINNAME),
	    'addTo'=>get_string('reserveseat', COLIBRI_PLUGINNAME),
	    'removeFrom'=>get_string('clearseat', COLIBRI_PLUGINNAME)
	);
	
        $users = $mform->addElement('selectfromto', 'authorizedsessionusers', $labels);
	$mform->disabledIf('selectfromto', 'sessionusersradio', 'eq', 0);
	$mform->setAdvanced('authorizedsessionusers');
        $mform->addHelpButton('authorizedsessionusers', 'authorizedsessionusers', COLIBRI_PLUGINNAME);
	
	if(isset($postData->users)):
	    $users->setValues($postData->users);
	endif;

	// based on the selected users, increment the size of the reserved seats accordingly
	$usersSelected = $users->getSelected();
	if(isset($_POST['maxsessionusers']) && is_array($usersSelected) && count($usersSelected)>$_POST['maxsessionusers']):
	    $_POST['maxsessionusers'] = count($usersSelected);
	    $mform->setDefault('maxsessionusers',  $_POST['maxsessionusers']);
	endif;
	*/
	

        $this->standard_coursemodule_elements();

        // Standard buttons, common to all modules ------------------------------------
        $this->add_action_buttons();
    }
    /**
     * Get the form data and return null if the submit button was just a refresh (like the one's to add or remove an form element
     */
    function get_data() {
	if ($this->is_submitted() and $this->is_validated() and $postData = &data_submitted()):
	    /*
	    if(isset($postData->authorizedsessionusers_selectuser) || isset($postData->authorizedsessionusers_clearuser)):
		return null;
	    endif;
	     */
	endif;
	return parent::get_data();
    }

    /**
     * Validate the submited form data
     */
    function validation($data, $files) {
        global $CFG;
        $errors= array();

        $errors = parent::validation($data, $files);

	// session name
	$var = 'name';
	if(empty($data[$var])):
	    $errors[$var] = get_string('emptysessionname', COLIBRI_PLUGINNAME);
	endif;

	// session dates
	$currentdate = usergetdate(time());
	$currentdate = make_timestamp($currentdate['year'], $currentdate['mon'], $currentdate['mday'], $currentdate['hours'], $currentdate['minutes']);
	$startdate = usergetdate($data['startdate']);
	$startdate = make_timestamp($startdate['year'], $startdate['mon'], $startdate['mday'], $startdate['hours'], $startdate['minutes']);
	if($startdate<=$currentdate):
	    $errors['startdate'] = get_string('youcannotcreateasessioninthepast', COLIBRI_PLUGINNAME);
	endif;
	
	if($data['enddate']<=$data['startdate']):
	    $errors['enddate'] = get_string('enddatemustbegreaterthanstartdate', COLIBRI_PLUGINNAME);
	endif;

	// pin
	if(!is_numeric($data['sessionpin']) || $data['sessionpin']<0 || $data['sessionpin']>9999):
	    $errors['sessionpin'] = get_string('sessionpinmustbeavalidnumber', COLIBRI_PLUGINNAME).$data['sessionpin'];
	endif;
	if(!is_numeric($data['moderationpin']) || $data['moderationpin']<0 || $data['moderationpin']>9999):
	    $errors['moderationpin'] = get_string('moderationpinmustbeavalidnumber', COLIBRI_PLUGINNAME);
	endif;
	
	// seats
	if(!is_numeric($data['maxsessionusers']) || $data['maxsessionusers']<=0):
	    $errors['maxsessionusers'] = get_string('dontbelikethatandinvitesomeonetothesession', COLIBRI_PLUGINNAME);
	endif;
	
        return $errors;
    }

    /**
     * Load the adaptable_relations data
     *
     * @param mixed $default_values object or array of default values
     */
    function set_data($default_values) {
	/*
        if (is_object($default_values)):
	    $this->usersData = self::getData($default_values->instance, $default_values);
        endif;
	*/
        parent::set_data($default_values);
    }


    /**
     * Load the users data for the instance id
     *
     * @param int with the id of the module
     */
    private static function getData($id, &$defaultValues=NULL) {
    	if (!is_object($defaultValues)):
    		$defaultValues = new stdClass();
    		$defaultValues->instance = $id;
    	endif;
        if($users = Colibri::getSessionUsers($id)):
	    $defaultValues->users = array();
	    
	    foreach($users as $user):
		$defaultValues->users[] = $user->userid;
	   endforeach;
	   
        endif;
        return $defaultValues;
    }
}
