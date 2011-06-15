<?php

/**
 * Unit tests for Colibri lib.
 *
 * @package    	Colibri
 * @subpackage 	local_colibri
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt> - {@link http://ued.ipleiria.pt | Learning Distance Unit }, Polytechnic Institute of Leiria
 * @license	{@link http://www.gnu.org/copyleft/gpl.html |  GNU GPL v3 or later}
 *
 * SVN:
 * $Author$
 * $Date$
 * $Rev$
 */
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once($CFG->dirroot . '/local/colibri/lib.php');

/**
 * @example run the tests on the 'local/colibri/simpletest' directory
 */
class ColibriService_test extends UnitTestCase {

    public static $includecoverage = array('local/colibri/lib.php');
    public static $excludecoverage = array('local/colibri/simpletest');

    function setUp() {

    }

    function tearDown() {

    }

    function skip() {
	$instance = ColibriService::getSoapClientInstance();
	$this->skipIf(is_null($instance), __CLASS__ . ' requires a valid SOAP client instance. Verify the configuration values and try again.');
    }

    /**
     * Test the getColibriTime method
     */
    function testGetColibriTime() {
	$this->assertNotNull(ColibriService::getColibriTime());
    }

    /**
     * Test the checkAccess method
     */
    function testCheckAccess() {
	$acc = new accessCredentials();
	$acc->installId = 'xpto';
	$acc->password = 'e uma senha que não deve existir'.uniqid();
	
	$this->assertFalse(ColibriService::checkAccess($acc)===true);
	$this->assertTrue(ColibriService::checkAccess()===true);
	
    }

    // TODO remove this method when done
    function testTemporary() {

	echo("<pre>Soap functions: \n" . print_r(ColibriService::getSoapFunctions(), true) . '</pre>');
	echo("<pre>Soap types: \n" . print_r(ColibriService::getSoapTypes(), true) . '</pre>');
	
	// createSession
	$result = ColibriService::createSession("teste", time() + 60, time() + 2 * 3600, 10, 1234, 4321, true);
	if(is_integer($result) && $result<0){
	    echo( "<pre>createSession: ".ColibriService::getErrorString($result)."</pre>");
	}else{
	    echo("createSession: <pre>" . print_r($result, true) . "</pre>");
	}

	// getSessionInfo
	$result =ColibriService::getSessionInfo(6099);
	if(is_integer($result) && $result<0){
	    echo( "<pre>getSessionInfo: ".ColibriService::getErrorString($result)."</pre>");
	}else{
	    echo("getSessionInfo: <pre>" . print_r($result, true) . "</pre>");
	}

	/*
	// getSessionInfo
	$result = ColibriService::getSessionInfo(6099);
	echo("getSessionInfo: <pre>" . print_r($result, true) . "</pre>");

	// modifySession
	$result = ColibriService::modifySession(10, "teste2", time() + 3600, time() + 2 * 3600, 10);
	echo("modifySession: <pre>" . print_r($result, true) . "</pre>");

	/*
	  // removeSession
	  $result = ColibriService::removeSession(10);
	  echo("removeSession: <pre>".print_r($result, true)."</pre>");
	 */
    }

}
