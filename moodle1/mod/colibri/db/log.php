<?php
/**
 * Logs definitions for the 'mod_colibri' component
 *
 * @package    	Colibri
 * @subpackage 	mod_colibri
 * @copyright 	{@link http://ued.ipleiria.pt | Learning Distance Unit } - Polytechnic Institute of Leiria
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt>
 * @license	{@link http://www.gnu.org/copyleft/gpl.html |  GNU GPL v3 or later}
 *
 */

defined('MOODLE_INTERNAL') || die();

$logs = array(
    // session instance log actions
    array('module'=>'mod_colibri', 'action'=>'add', 'mtable'=>'colibri_sessions', 'field'=>'name'),
    array('module'=>'mod_colibri', 'action'=>'update', 'mtable'=>'colibri_sessions', 'field'=>'name'),
    array('module'=>'mod_colibri', 'action'=>'view', 'mtable'=>'colibri_sessions', 'field'=>'name'),
    array('module'=>'mod_colibri', 'action'=>'view all', 'mtable'=>'colibri_sessions', 'field'=>'name'),
    array('module'=>'mod_colibri', 'action'=>'record delete', 'mtable'=>'colibri_sessions', 'field'=>'name'),
    // session user log actions
    array('module'=>'mod_colibri', 'action'=>'add session user', 'mtable'=>'colibri_sessions_users', 'field'=>'colibrisessionid'),
    array('module'=>'mod_colibri', 'action'=>'update session user', 'mtable'=>'colibri_sessions_users', 'field'=>'colibrisessionid'),
    array('module'=>'mod_colibri', 'action'=>'view session user', 'mtable'=>'colibri_sessions_users', 'field'=>'colibrisessionid'),
);