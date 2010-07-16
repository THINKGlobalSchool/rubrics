<?php
/**
 * All Site Rubrics
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
	
// set up breadcrumbs
$page_owner = page_owner_entity();
if ($page_owner === false || is_null($page_owner)) {
	$page_owner = $_SESSION['user'];
	set_page_owner($page_owner->getGUID());
}
elgg_push_breadcrumb(elgg_echo('rubric:all'), $CONFIG->wwwroot."mod/rubricbuilder/pages/everyone.php");
elgg_push_breadcrumb(sprintf(elgg_echo("rubric:user"),$page_owner->name));
	
//display the top bar
if(page_owner()== get_loggedin_user()->guid){
	$add_link = $CONFIG->wwwroot . "pg/rubric/add/";
	$all_link = $CONFIG->wwwroot . "pg/rubric/everyone/";
	$friend_link = $CONFIG->wwwroot . "pg/rubric/friends/";
	$area1 .= elgg_view('navigation/breadcrumbs');	
	$area1 .= elgg_view('page_elements/content_header', array('context' => "everyone", 'type' => 'rubric','all_link' => $all_link, 'new_link' => $add_link, 'friend_link' => $friend_link));
}elseif(page_owner_entity() instanceof ElggGroup){
	$area1 .= elgg_view('navigation/breadcrumbs');	
	$area1 .= elgg_view('rubric/group_rubric_header');
}else{
	$area1 .= elgg_view('navigation/breadcrumbs');
	$area1 .= elgg_view('page_elements/content_header_member', array('type' => 'rubric'));
}

$limit = get_input("limit", 10);
$offset = get_input("offset", 0);

// create content for main column
$content = elgg_view_title($title);
	
$context = get_context();
set_context('search');
		
$list = elgg_list_entities(array('types' => 'object', 'subtypes' => 'rubric', 'limit' => $limit, 'offset' => $offset, 'full_view' => FALSE));

set_context($context);
	
if ($list) {
	$content .= $list;
} else {
	$content .= elgg_view('rubricbuilder/noresults');
}

// include a view for plugins to extend
$area3 .= elgg_view("rubric/sidebar", array("object_type" => 'rubric'));

// get the latest comments on all files
$comments = get_annotations(0, "object", "rubric", "generic_comment", "", 0, 4, 0, "desc");
$area3 .= elgg_view('annotation/latest_comments', array('comments' => $comments));

// tag-cloud display
$area3 .= display_tagcloud(0, 50, 'tags', 'object', 'rubric');
	
// layout the sidebar and main column using the default sidebar
$body = elgg_view_layout('one_column_with_sidebar', $area1 . $content, $area3);

// create the complete html page and send to browser
page_draw($title, $body);