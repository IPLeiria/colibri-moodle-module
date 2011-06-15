<?php
/**
 * Capabilities for the 'local_colibri' component
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

defined('MOODLE_INTERNAL') || die();

// Capability to configure the plugin
$capabilities = array(
    'local/colibri:configureplugin' => array(
	'captype' => 'write',
	'contextlevel' => CONTEXT_MODULE,
	'archetypes' => array(
	    'guest' => CAP_PROHIBIT,
	    'user' => CAP_PROHIBIT,
	    'manager' => CAP_PROHIBIT
	)
    ),
);