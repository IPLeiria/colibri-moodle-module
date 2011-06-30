<?php // $Id: pagelib.php,v 1.10 2007/07/05 04:41:07 mattc-catalyst Exp $

require_once($CFG->libdir.'/pagelib.php');
require_once($CFG->dirroot.'/course/lib.php'); // needed for some blocks
require_once(dirname(__FILE__).'/locallib.php');

define('PAGE_COLIBRI_VIEW', 'mod-colibri-view');

page_map_class(PAGE_COLIBRI_VIEW, 'page_colibri');

$DEFINEDPAGES = array(PAGE_COLIBRI_VIEW);
/*
*/

/**
 * Class that models the behavior of a data
 *
 * @author Jon Papaioannou
 * @package pages
 */

class page_colibri extends page_generic_activity {

    function init_quick($data) {
        if(empty($data->pageid)) {
            error('Cannot quickly initialize page: empty course id');
        }
        $this->activityname = 'colibri';
        parent::init_quick($data);
    }

    function print_header($title, $morenavlinks = NULL, $meta = '') {
        parent::print_header($title, $morenavlinks, '', $meta);
    }

    function get_type() {
        return PAGE_COLIBRI_VIEW;
    }
}