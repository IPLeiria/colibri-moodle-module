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
    private $usersData = NULL;

    public function __construct(&$data, $section, $cm, $course){
	    global $DB;

	    $this->usersData = &$data;
	    self::getData($data->instance, $this->usersData);

	    parent::__construct($data, $section, $cm, $course);
    }

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
	    $postData = $this->usersData;
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
	$mform->setDefault('startdatetime', time()+3600);

	/*
	// End date time
	$label = get_string('enddatetime', 'colibri');
        $mform->addElement('date_time_selector', 'enddatetime', $label);
        $mform->addRule('enddatetime', null, 'required', null, 'client');
        $mform->addHelpButton('enddatetime', 'enddatetime', 'colibri');
	$mform->setDefault('enddatetime', time()+2*3600);
	*/

	//duration
	$mform->addElement('duration', 'duration', get_string('duration', 'colibri'));
        $mform->addHelpButton('duration', 'duration', 'colibri');
        $mform->addRule('duration', null, 'required', null, 'client');
        $mform->setDefault('duration', 3600);

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
        $mform->addHelpButton('authorizedsessionusers', 'authorizedsessionusers', 'colibri');
	
	if(isset($postData->users)):
	    $users->setValues($postData->users);
	endif;

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
    /**
     * Get the form data and return null if the submit button was just a refresh (like the one's to add or remove an form element
     */
    function get_data() {
	if ($this->is_submitted() and $this->is_validated() and $postData = &data_submitted()):
	    if(isset($postData->authorizedsessionusers_selectuser) || isset($postData->authorizedsessionusers_clearuser)):
		return null;
	    endif;
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
	    $errors[$var] = get_string('emptysessionname', 'colibri');
	endif;

	// session dates
	$currentdate = usergetdate(time());
	$currentdate = make_timestamp($currentdate['year'], $currentdate['mon'], $currentdate['mday'], $currentdate['hours'], $currentdate['minutes']);
	$startdatetime = usergetdate($data['startdatetime']);
	$startdatetime = make_timestamp($startdatetime['year'], $startdatetime['mon'], $startdatetime['mday'], $startdatetime['hours'], $startdatetime['minutes']);
	if($startdatetime<=$currentdate):
	    $errors['startdatetime'] = get_string('youcannotcreateasessioninthepast', 'colibri');
	endif;
	/*
	if($data['enddatetime']<=$data['startdatetime']):
	    $errors['enddatetime'] = get_string('enddatemustbegreaterthanstartdate', 'colibri');
	endif;
	 */
	if(!is_numeric($data['duration'])):
	    $errors['duration'] = get_string('sessiondurationmustbeanumber', 'colibri');
	elseif($data['duration']<=0):
	    $errors['duration'] = get_string('sessiondurationmustbegreaterthanzero', 'colibri');
	endif;
	

	// pin
	if(!is_numeric($data['sessionpin']) || $data['sessionpin']<0 || $data['sessionpin']>9999):
	    $errors['sessionpin'] = get_string('sessionpinmustbeavalidnumber', 'colibri').$data['sessionpin'];
	endif;
	if(!is_numeric($data['moderationpin']) || $data['moderationpin']<0 || $data['moderationpin']>9999):
	    $errors['moderationpin'] = get_string('moderationpinmustbeavalidnumber', 'colibri');
	endif;
	
	// seats
	if(!is_numeric($data['maxsessionusers']) || $data['maxsessionusers']<=0):
	    $errors['maxsessionusers'] = get_string('dontbelikethatandinvitesomeonetothesession', 'colibri');
	endif;
	
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
        if ($this->current->instance):
            // editing an existing colibri session - let us prepare the added editor elements (intro done automatically)
	    $data['duration'] = $data['enddate']-$data['startdate'];
	    $data['startdatetime'] = $data['startdate'];
	    $data['enddatetime'] = $data['enddate'];
        else:
            // adding a new colibri session instance
        endif;
    }


    /**
     * Load the adaptable_relations data
     *
     * @param mixed $default_values object or array of default values
     */
    function set_data($default_values) {
    	global $DB;

        if (is_object($default_values)):
	    $this->usersData = self::getData($default_values->instance, $default_values);
        endif;

        parent::set_data($default_values);
    }


    /**
     * Load the users data for the instance id
     *
     * @param int with the id of the module
     */
    private static function getData($id, &$defaultValues=NULL) {
    	global $DB;
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
