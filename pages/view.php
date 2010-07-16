<?php
/**
 * View rubric page
 * 
 * @package RubricBuilder
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

// include the Elgg engine
include_once dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php"; 

// logged in users only
gatekeeper();
	
// get any input
$rubric_guid = get_input('rubric_guid');
$rubric = get_entity($rubric_guid);
$container = $rubric->container_guid;
if ($container) {
	set_page_owner($container);
} else {
	set_page_owner($rubric->owner_guid);
}

elgg_push_breadcrumb(elgg_echo('rubric:all'), $CONFIG->wwwroot."mod/rubricbuilder/pages/everyone.php");
elgg_push_breadcrumb(sprintf(elgg_echo("rubric:user"),page_owner_entity()->name), $CONFIG->wwwroot."pg/rubric/".page_owner_entity()->username);
elgg_push_breadcrumb(sprintf($rubric->title)); // complete breadcrumb with file title
$area1 .= elgg_view('navigation/breadcrumbs');	

// Title
$title = $rubric->title;
	
// create content for main column
$content .= elgg_view_entity($rubric, true);
	
//access
$page_acl = get_readable_access_level($rubric->access_id);
$sidebar .= elgg_view('rubricbuilder/read_sidebar', array('page_acl' => $page_acl, 'entity' => $rubric));

// layout the sidebar and main column using the default sidebar
$body = elgg_view_layout('one_column_with_sidebar', $area1 . $content, $sidebar);

// create the complete html page and send to browser
page_draw($title, $body);