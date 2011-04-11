<?php
/**
 * Capabilities for the 'local_colibri' component
 *
 * @package    	Colibri
 * @subpackage 	local_colibri
 * @version	2011.0
 * @copyright 	Learning Distance Unit {@link http://ued.ipleiria.pt} - Polytechnic Institute of Leiria
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt>
 * @license	http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

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