<?php
/**
 * Unit tests for Colibri lib.
 *
 * @package    	Colibri
 * @subpackage 	local_colibri
 * @version	2011.0
 * @copyright 	Learning Distance Unit {@link http://ued.ipleiria.pt} - Polytechnic Institute of Leiria
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt>
 * @license	http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once($CFG->dirroot.'/local/colibri/lib.php');

/**
 * @example run the tests on the 'local/colibri/simpletest' directory
 */
class ColibriService_test extends UnitTestCase {
    public static $includecoverage = array('local/colibri/lib.php');
    public static $excludecoverage = array('local/colibri/simpletest');

    function setUp() {}

    function tearDown() {}
    
    function skip() {
        $instance = ColibriService::getSoapClientInstance();
        $this->skipIf(is_null($instance), __CLASS__.' requires a valid SOAP client instance. Verify the configuration values and try again.');
    }

    function testGetColibriTime(){
        $this->assertNotNull(ColibriService::getColibriTime());
    }

    // TODO remove this method when done
    function testTemporary(){
        echo("<pre>Soap functions: \n".print_r(ColibriService::getSoapFunctions(), true).'</pre>');
        echo("<pre>Soap types: \n".print_r(ColibriService::getSoapTypes(), true).'</pre>');

        //getColibriTime
        echo("getColibriTime: <pre>".print_r(ColibriService::getColibriTime(), true)."</pre>");

        // getSessionInfo
        $result = ColibriService::getSessionInfo(6099);
        echo("getSessionInfo: <pre>".print_r($result, true)."</pre>");

        // createSession
        $result = ColibriService::createSession("teste", time()+3600, time()+2*3600, 10);
        echo("createSession: <pre>".print_r($result, true)."</pre>");



    }
}
