<?php
/**
 * Capabilities for the 'mod_colibri' component
 *
 * @package    	Colibri
 * @subpackage 	local_colibri
 * @copyright 	{@link http://ued.ipleiria.pt | Learning Distance Unit } - Polytechnic Institute of Leiria
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt>
 * @license	{@link http://www.gnu.org/copyleft/gpl.html |  GNU GPL v3 or later}
 *
 */

defined('MOODLE_INTERNAL') || die();

// Capability to manage sessions with the plugin
$capabilities = array(
    'mod/colibri:managesession' => array(
	'captype' => 'write',
	'contextlevel' => CONTEXT_MODULE,
	'archetypes' => array(
	    'guest' => CAP_PROHIBIT,
	    'user' => CAP_PROHIBIT,
	    'student' => CAP_PROHIBIT,
	    'teacher' => CAP_PROHIBIT,
	    'editingteacher' => CAP_ALLOW,
	    'manager' => CAP_ALLOW
	)
    ),

    'mod/colibri:attendsession' => array(
	'captype' => 'read',
	'contextlevel' => CONTEXT_MODULE,
	'archetypes' => array(
	    'guest' => CAP_ALLOW,
	    'user' => CAP_ALLOW,
	    'student' => CAP_ALLOW,
	    'teacher' => CAP_ALLOW,
	    'editingteacher' => CAP_ALLOW,
	    'manager' => CAP_ALLOW
	)
    ),
);