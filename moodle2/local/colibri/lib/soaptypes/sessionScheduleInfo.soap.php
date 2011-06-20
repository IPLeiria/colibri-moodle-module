<?php
if(!class_exists('sessionScheduleInfo')):

    /**
     * Provides the Session Schedule Information
     *
     * @package    	Colibri
     * @subpackage 	local_colibri
     * @version		2011.0
     * @author		Cláudio Esperança <claudio.esperanca@ipleiria.pt> - {@link http://ued.ipleiria.pt | Learning Distance Unit }, Polytechnic Institute of Leiria
     * @license		http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
     */
    class sessionScheduleInfo{
	/**
	 * @var long
	 */
	public $endDateTimeStamp;
	/**
	 * @var boolean
	 */
	public $listPubliclyInColibri;
	/**
	 * @var int
	 */
	public $maxSessionUsers;
	/**
	 * @var string
	 */
	public $moderationPin;
	/**
	 * @var string
	 */
	public $name;
	/**
	 * @var string
	 */
	public $sessionPin;
	/**
	 * @var long
	 */
	public $startDateTimeStamp;

	function __construct($name, $startDateTimeStamp, $endDateTimeStamp, $maxSessionUsers=0, $sessionPin=NULL, $moderationPin=NULL, $listPubliclyInColibri=false){
	    // check the name
	    if(empty ($name)):
		throw new Exception(ColibriService::getString('invalidName'), ColibriService::INVALID_NAME);
	    endif;

	    // check the start date value
	    if(!is_numeric($startDateTimeStamp) || (int)$startDateTimeStamp<time()):
		throw new Exception(ColibriService::getString('invalidStartDate'), ColibriService::INVALID_START_DATE);
	    endif;

	    // check end date value
	    if(!is_numeric($endDateTimeStamp) || (int)$endDateTimeStamp<$startDateTimeStamp):
		throw new Exception(ColibriService::getString('invalidEndDate'), ColibriService::INVALID_END_DATE);
	    endif;

	    // Convert the timestamp to miliseconds
	    $startDateTimeStamp *=1000;
	    $endDateTimeStamp *=1000;

	    // check the number of participants
	    if($maxSessionUsers<=0):
		throw new Exception(ColibriService::getString('invalidNumberOfParticipants'), ColibriService::INVALID_NUMBER_OF_PARTICIPANTS);
	    endif;

	    // generate a session pin if needed
	    $sessionPin = is_null($sessionPin)?ColibriService::generatePin():$sessionPin;
	    // generate a moderation pin if needed
	    $moderationPin = is_null($moderationPin)?ColibriService::generatePin():$moderationPin;

	    $this->name = $name;
	    $this->startDateTimeStamp = $startDateTimeStamp;
	    $this->endDateTimeStamp = $endDateTimeStamp;
	    $this->maxSessionUsers = $maxSessionUsers;
	    $this->sessionPin = $sessionPin;
	    $this->moderationPin = $moderationPin;
	    $this->listPubliclyInColibri = $listPubliclyInColibri;
	}
    }
endif;