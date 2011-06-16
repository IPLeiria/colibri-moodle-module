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
	$this->assertNotNull(ColibriService::getColibriTime(), 'Invalid time returned');
    }

    /**
     * Test the checkAccess method
     */
    function testCheckAccess() {
	$acc = new accessCredentials();
	$acc->installId = 'xpto';
	$acc->password = 'e uma senha que não deve existir'.uniqid();
	
	$this->assertFalse(ColibriService::checkAccess($acc)===true, 'The access was autorized with invalid credentials');
	$this->assertTrue(ColibriService::checkAccess()===true, 'Access denied with the suplied credentials');
    }

    /**
     * Test the createSession method
     */
    function testCreateSession() {

	//try to create a session with a unique name, 60 seconds from now, with a duration of 3 seconds, with one participant, not public available
	$result = ColibriService::createSession("test-".uniqid(), time() + 60, time() + 63, 1, 4941, 4942, false);
	$this->assertFalse(is_integer($result) && $result<0, 'An error ocorred while creating the session: '.ColibriService::getErrorString($result));
	$this->assertTrue(isset($result->sucess) && $result->sucess, 'The session was not created');

	$result = ColibriService::removeSession($result->sessionUniqueID);
    }

    /**
     * Test the getSessionInfo method
     */
    function testGetSessionInfo() {
	//try to create a session with a unique name, 60 seconds from now, with a duration of 3 seconds, with one participant, not public available
	$result = ColibriService::createSession("test-".uniqid(), time() + 60, time() + 63, 1, 4941, 4942, false);
	$this->assertFalse(is_integer($result) && $result<0, 'An error ocorred while creating the session: '.ColibriService::getErrorString($result));
	$this->assertTrue(isset($result->sucess) && $result->sucess, 'The session was not created');

	$result = ColibriService::getSessionInfo($result->sessionUniqueID);
	$this->assertFalse(is_integer($result) && $result<0, 'An error ocorred while retrieving the session information: '.ColibriService::getErrorString($result));
	$this->assertTrue(isset($result->sucess) && $result->sucess, 'The session information was not retrieved');

	$result = ColibriService::removeSession($result->sessionUniqueID);

    }

    /**
     * Test the getSessionInfo method
     */
    function testRemoveSession() {
	//try to create a session with a unique name, 60 seconds from now, with a duration of 3 seconds, with one participant, not public available
	$result = ColibriService::createSession("test-".uniqid(), time() + 60, time() + 63, 1, 4941, 4942, false);
	$this->assertFalse(is_integer($result) && $result<0, 'An error ocorred while creating the session: '.ColibriService::getErrorString($result));
	$this->assertTrue(isset($result->sucess) && $result->sucess, 'The session was not created');

	$result = ColibriService::removeSession($result->sessionUniqueID);
	$this->assertFalse(is_integer($result) && $result<0, 'An error ocorred while removing the session: '.ColibriService::getErrorString($result));
	$this->assertTrue($result, 'The session information was not removed');

    }

    // TODO remove this method when done
    function testTemporary() {
	/*
	echo("<pre>Soap functions: \n" . print_r(ColibriService::getSoapFunctions(), true) . '</pre>');
	echo("<pre>Soap types: \n" . print_r(ColibriService::getSoapTypes(), true) . '</pre>');
	*/
	
    }

}
