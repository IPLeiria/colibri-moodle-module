<?php
/**
 * Class to dynamically create an HTML SELECT with all options grouped in optgroups
 *
 * @package    	Colibri
 * @subpackage 	mod_colibri
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt> - {@link http://ued.ipleiria.pt | Learning Distance Unit }, Polytechnic Institute of Leiria
 * @license	{@link http://www.gnu.org/copyleft/gpl.html |  GNU GPL v3 or later}
 * @version     1.0
 * @access      public
 *
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once("{$CFG->libdir}/formslib.php");
require_once("{$CFG->dirroot}/user/selector/lib.php");
require_once('HTML/QuickForm/element.php');

// Register the selectfromto form element type
MoodleQuickForm::registerElementType('selectfromto', __FILE__, 'MoodleQuickForm_selectfromto');

class MoodleQuickForm_selectfromto extends HTML_QuickForm_element {

    // {{{ properties

    /**
     * Contains the labels for the element
     *
     * @var       array
     * @since     1.0
     * @access    private
     */
    private $_labels = array();
    
    /**
     * Contains the select from Options
     *
     * @var       array
     * @since     1.0
     * @access    private
     */
    private $_fromOptions = array();
    
    /**
     * Contains the select to Options
     *
     * @var       array
     * @since     1.0
     * @access    private
     */
    private $_toOptions = array();

    /**
     * html for help button, if empty then no help
     *
     * @var string
     */
    var $_helpbutton='';
    var $_hiddenLabel=false;
    var $_name='';

    /**
     * Class constructor
     *
     * @param     string    Select name attribute
     * @param     array     Labels for the element
     *  			- 'elementLabel' string with the label for the element
     *				- 'fromLabel' optional string with the label for the 'from' element
     *				- 'toLabel' optional string with the label for the 'to' element
     *				- 'addTo' string with the label to the add button
     *				- 'removeFrom' string with the label to the remove button
     *
     * @param     array     The options for the 'to' element. Should have defined the following idexes
     *				- 'label' optional string with label for the select element
     *				- 'courseid' required integer with the course ID
     *				- 'values' optional array with the values of the select
     * 
     * @param     array     The options for the 'from' element. Should have defined the following idexes
     *				- 'label' optional string with label for the select element
     *				- 'courseid' required integer with the course ID
     *				- 'values' optional array with the values of the select
     *				- 'exclude' optional array with the ids of the elements to exclude from the select
     * @param     array     The extra attributes for the element
     * @since     1.0
     * @access    public
     * @return    void
     */
    function MoodleQuickForm_selectfromto(
	    $elementName=null,
	    $labels=array(
		'elementLabel'=>null,
		'fromLabel'=>'',
		'toLabel'=>'',
		'addTo'=>'',
		'removeFrom'=>''
	    ),
	    $toOptions=array(
		'values'=>array()
	    ),
	    $fromOptions=array(
		'values'=>array()
	    ),
	    $attributes=array()
	){
	global $COURSE;
	
        $this->_type = 'selectfromto';

        $this->_fromOptions = $fromOptions;
        $this->_toOptions = $toOptions;
	$this->_labels = $labels;
	
	// Default the courseid
	if(!isset($this->_toOptions['courseid']) && isset($COURSE->id)):
	    $this->_toOptions['courseid'] = $COURSE->id;
	endif;
	if(!isset($this->_fromOptions['courseid']) && isset($COURSE->id)):
	    $this->_fromOptions['courseid'] = $COURSE->id;
	endif;

	// The elements of the 'to' element should not appear on the 'from' element
	if(!isset($this->_fromOptions['exclude']) && isset($this->_toOptions['values'])):
	    $this->_fromOptions['exclude'] = $this->_toOptions['values'];
	endif;
	
	parent::HTML_QuickForm_element($elementName, $this->_labels['elementLabel'], $attributes);
	
    } //end constructor

    // }}}
    // {{{ toHtml()
    /**
     * Returns the SELECT in HTML
     *
     * @since     1.0
     * @access    public
     * @return    string
     */
    function toHtml(){
        global $OUTPUT;
	
        $str = parent::toHtml();
	
        $user_selectfrom = new ColibriSelectFromDbUsers($this->getName().'_select_from', $this->_fromOptions);
        $user_selectto = new ColibriSelectFromUsers($this->getName().'_select_to', $this->_toOptions);

	$str .=
	'<table id="'.$this->getAttribute('id').'" class="generaltable generalbox groupmanagementtable boxaligncenter" summary="">
	    <tr>
		<td id="existingcell">';
		    if(!empty ($this->_labels['toLabel'])):
			$str .= '<p><label for="removeselect">'.$this->_labels['toLabel'].'</label></p>';
		    endif;
		    $str .= $user_selectto->display(true).'
		</td>
		<td id="buttonscell">
		    <p class="arrow_button">
			<input name="'.$this->getName().'_selectuser" id="add" type="submit" value="'.$OUTPUT->larrow().'&nbsp;'.$this->_labels['addTo'].'" title="'.get_string('reserveseatfortheusers', 'colibri').'" /><br />
			<input name="'.$this->getName().'_clearuser" id="remove" type="submit" value="'.$this->_labels['removeFrom'].'&nbsp;'.$OUTPUT->rarrow().'" title="'.get_string('clearseatoftheusers', 'colibri').'" />
		    </p>
		</td>
		<td id="potentialcell">';
		    if(!empty ($this->_labels['fromLabel'])):
			$str .= '<p><label for="addselect">'.$this->_labels['fromLabel'].'</label></p>';
		    endif;
		    $str .= $user_selectfrom->display(true).'
		</td>
	    </tr>
	</table>';

	if(isset($this->_toOptions['values'])):
	    foreach($this->_toOptions['values'] as $userid):
		$str .= "<input name=\"_selecteduser_{$userid}\" type=\"hidden\" value=\"$userid\" />";
	    endforeach;
	endif;
	
        return $str;
    } //end func toHtml

    // }}}
    // {{{ exportValue()

   /**
    * We check the options and return only the values that _could_ have been
    * selected. We also return a scalar value if select is not "multiple"
    */
    function exportValue(&$submitValues=null, $assoc = false) {
	if(empty($submitValues) && ($values = &data_submitted())):
	    $submitValues = (array) $values;
	endif;
        if(!empty($submitValues)):
	    $this->_toOptions['values'] = array();
	    foreach($submitValues as $key=>$value):
		if(preg_match("/_selecteduser_\d+$/", $key)):
		    $this->_toOptions['values'][] = $submitValues[$key];
		endif;
	    endforeach;

	    // reserve the seats
	    if(isset($submitValues[$this->getName().'_selectuser']) && !empty ($submitValues[$this->getName().'_select_from'])):
		foreach($submitValues[$this->getName().'_select_from'] as $userid):
		    $this->_toOptions['values'][] = $userid;
		endforeach;
	    endif;

	    // clear the seats
	    if(isset($submitValues[$this->getName().'_clearuser']) && !empty($submitValues[$this->getName().'_select_to'])):
		foreach($submitValues[$this->getName().'_select_to'] as $userid):
		    if(($key = array_search($userid, $this->_toOptions['values']))!==false):
			unset($this->_toOptions['values'][$key]);
		    endif;
		endforeach;
	    endif;

	    // sync the excluded values with the values
	    $this->_fromOptions['exclude'] = $this->_toOptions['values'];
	endif;
	$values = array($this->getName().'_values'=>$this->_toOptions['values']);
	return $values;
    }
    
    // }}}
    function getSelected(){
	if(!isset($this->_toOptions['values'])):
	    $this->exportValue();
	endif;
	return $this->_toOptions['values'];
    }
    // {{{ onQuickFormEvent()

    function onQuickFormEvent($event, $arg, &$caller){
	switch ($event):
	    case 'createElement':
		$this->exportValue();
		return parent::onQuickFormEvent($event, $arg, $caller);
	/*
	    case 'updateValue':
		if ($caller->isSubmitted()):
		    if(!empty($caller->_submitValues)):
			$this->exportValue($caller->_submitValues);
		    endif;

		endif;
		return parent::onQuickFormEvent($event, $arg, $caller);
	*/
	endswitch;

	return parent::onQuickFormEvent($event, $arg, $caller);
    }

   /**
    * Automatically generates and assigns an 'id' attribute for the element.
    *
    * Currently used to ensure that labels work on radio buttons and
    * checkboxes. Per idea of Alexander Radivanovich.
    * Overriden in moodleforms to remove qf_ prefix.
    *
    * @access private
    * @return void
    */
    function _generateId(){
        static $idx = 1;

        if (!$this->getAttribute('id')) {
            $this->updateAttributes(array('id' => 'id_'. substr(md5(microtime() . $idx++), 0, 6)));
        }
    } // end func _generateId

    function setName($name){
	$this->_name = $name;
    }

    function getName(){
	return $this->_name;
    }

    /**
     * Slightly different container template when frozen. Don't want to use a label tag
     * with a for attribute in that case for the element label but instead use a div.
     * Templates are defined in renderer constructor.
     *
     * @return string
     */
    function getElementTemplateType(){
        if ($this->_flagFrozen){
            return 'nodisplay';
        } else {
            return 'default';
        }
    }
}

/**
 * Class to list users from the database
 *
 * @package    	Colibri
 * @subpackage 	mod_colibri
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt> - {@link http://ued.ipleiria.pt | Learning Distance Unit }, Polytechnic Institute of Leiria
 * @license	{@link http://www.gnu.org/copyleft/gpl.html |  GNU GPL v3 or later}
 * @version     1.0
 * @access      public
 *
 * SVN:
 * $Author: cesperanc $
 * $Date: 2011-06-09 17:53:14 +0100 (Qui, 09 Jun 2011) $
 * $Rev: 13 $
 */
if(!class_exists('ColibriSelectFromDbUsers')):

    class ColibriSelectFromDbUsers extends user_selector_base {
	protected $courseid;

	public function __construct($name, $options) {

	    $this->courseid = $options['courseid'];
	    if(!empty($options['exclude'])):
		$this->exclude = $options['exclude'];
	    endif;
	    parent::__construct($name, $options);
	}

	/**
	 * Candidate users
	 * @param <type> $search
	 * @return array
	 */
	public function find_users($search) {
	    global $DB;
	    $context = get_context_instance(CONTEXT_COURSE, $this->courseid);

	    // we are looking for all users with this role assigned in this context or higher
	    $listofcontexts = get_related_contexts_string($context);

	    list($esql, $params) = get_enrolled_sql($context);
	    list($wherecondition, $params2) = $this->search_sql($search, 'u');
	    $params = array_merge($params, $params2);

	    $availableusers = $DB->get_records_sql('SELECT '.$this->required_fields_sql('u'). '
		FROM {user} u
		JOIN ('.$esql.') e ON e.id = u.id
		WHERE '.$wherecondition . ' ORDER BY u.firstname ASC, u.lastname ASC ', $params);

	    if (empty($availableusers)) :
		return array();
	    endif;

	    if (!empty($search)) :
		$groupname = get_string('usersmatching', 'colibri', $search);
	    else:
		$groupname = get_string('users', 'colibri');
	    endif;
	    return array($groupname=>$availableusers);
	}

	protected function get_options() {
	    $options = parent::get_options();
	    $options['courseid'] = $this->courseid;
	    $options['file']    = 'mod/colibri/form/selectfromto.php';
	    return $options;
	}

    }
endif;


/**
 * Class to list users
 *
 * @package    	Colibri
 * @subpackage 	mod_colibri
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt> - {@link http://ued.ipleiria.pt | Learning Distance Unit }, Polytechnic Institute of Leiria
 * @license	{@link http://www.gnu.org/copyleft/gpl.html |  GNU GPL v3 or later}
 * @version     1.0
 * @access      public
 *
 * SVN:
 * $Author: cesperanc $
 * $Date: 2011-06-09 17:53:14 +0100 (Qui, 09 Jun 2011) $
 * $Rev: 13 $
 */
if(!class_exists('ColibriSelectFromUsers')):

    class ColibriSelectFromUsers extends ColibriSelectFromDbUsers {
	protected $_optValues=array();

	public function __construct($name, $options=array()) {
	    //$this->enrolid  = $options['enrolid'];
	    parent::__construct($name, $options);
	    $this->_optValues = $options['values'];
	}

	/**
	 * Candidate users
	 * @param <type> $search
	 * @return array
	 */
	public function find_users($search) {
	    global $DB;

	    if(empty ($this->_optValues)):
		return array();
	    endif;

	    $this->validatinguserids = $this->_optValues;

	    $availableusers = parent::find_users(false);

	    if(!empty ($search)):
		$label = get_string('usersmatching', 'colibri', $search);
		$results = array($label=>array());
		foreach ($availableusers as $group):
		    foreach ($group as $id=>$item):
			$itemarray = (array)$item;
			if(in_array($search, $itemarray)):
			    if(!isset ($results[$label][$id])):
				$results[$label][$id] = $item;
			    endif;
			endif;
		    endforeach;
		endforeach;
		return $results;

	    endif;
	    return $availableusers;
	}

	protected function get_options() {
	    $options = parent::get_options();
	    $options['courseid'] = $this->courseid;
	    $options['values'] = $this->_optValues;
	    $options['file']    = 'mod/colibri/form/selectfromto.php';
	    return $options;
	}

    }
endif;
