<?php
/**
 * Settings definitions for the Colibri component
 *
 * @package    	Colibri
 * @subpackage 	local_colibri
 * @version	2011.0
 * @copyright 	Learning Distance Unit {@link http://ued.ipleiria.pt} - Polytechnic Institute of Leiria
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt>
 * @license	http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) :
	$ADMIN->add('root', new admin_category('colibri', get_string('colibri', 'local_colibri')));
	$ADMIN->add('colibri', new admin_externalpage('colibri_config', get_string('configuration', 'local_colibri'), new moodle_url('/local/colibri/pages/setup.php')), 'moodle/site:config');
endif;