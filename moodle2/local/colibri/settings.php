<?php
/**
 * Settings definitions for the Colibri component
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

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) :
	$ADMIN->add('root', new admin_category('colibri', get_string('colibri', 'local_colibri')));
	$ADMIN->add('colibri', new admin_externalpage('colibri_config', get_string('configuration', 'local_colibri'), new moodle_url('/local/colibri/pages/setup.php')), 'moodle/site:config');
endif;