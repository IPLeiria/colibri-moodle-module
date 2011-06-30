<?php
if(!class_exists('accessCredentials')):

    /**
     * Provides the Access Credentials to use the webservice
     *
     * @package    	Colibri
     * @subpackage 	mod_colibri
     * @version		2011.0
     * @author		Cláudio Esperança <claudio.esperanca@ipleiria.pt> - {@link http://ued.ipleiria.pt | Learning Distance Unit }, Polytechnic Institute of Leiria
     * @license		http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
     */
    class accessCredentials{
	/**
	 * @var string
	 */
	public $installId;
	/**
	 * @var string
	 */
	public $password;
	
	function __construct($installId='', $password=''){
	    $this->installId = $installId;
	    $this->password = $password;
	}
    }
endif;
