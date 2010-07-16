<?php
	/**
	 * List rubric history
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
	$vars['entity'] = get_entity($rubric_guid);

	// if username or owner_guid was not set as input variable, we need to set page owner
	// Get the current page's owner
	$page_owner = page_owner_entity();
	if ($page_owner === false || is_null($page_owner)) {
		$page_owner = $_SESSION['user'];
		set_page_owner($page_owner->getGUID());
	}	
	elgg_push_breadcrumb(elgg_echo('rubricbuilder:allrubrics'), $CONFIG->wwwroot."mod/rubricbuilder/pages/everyone.php");
	elgg_push_breadcrumb(sprintf(elgg_echo("rubric:user"),$page_owner->name), $CONFIG->wwwroot."pg/rubric/".$page_owner->username);
	elgg_push_breadcrumb(sprintf($vars['entity']->title), $CONFIG->wwwroot."pg/rubric/view/".$vars['entity']->guid."/");
	elgg_push_breadcrumb(sprintf(elgg_echo('rubricbuilder:history')));
	$area1 .= elgg_view('navigation/breadcrumbs');	
	
	
	$limit = get_input("limit", 10);
	$offset = get_input("offset", 0);
	
	// Title
	$title = elgg_echo('rubricbuilder:history');
	$title .= ': ' . $vars['entity']->title;
	 
	// create content for main column
	$content = elgg_view_title($title);
	
	$context = get_context();
	set_context('search');
		
	$content .= list_annotations($rubric_guid, 'rubric', $limit, false);
	
	
	set_context($context);
	
	// layout the sidebar and main column using the default sidebar
	$body = elgg_view_layout('one_column_with_sidebar', $area1 . $content);

	// create the complete html page and send to browser
	page_draw($title, $body);
?>