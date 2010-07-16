<?php
	/**
	 * Edit rubric page
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
	
	// if username or owner_guid was not set as input variable, we need to set page owner
	// Get the current page's owner
	$page_owner = page_owner_entity();
	if ($page_owner === false || is_null($page_owner)) {
		$page_owner = $_SESSION['user'];
		set_page_owner($page_owner->getGUID());
	}
	
	$vars['entity'] = get_entity(get_input('rubric_guid'));
	elgg_push_breadcrumb(elgg_echo('rubricbuilder:allrubrics'), $CONFIG->wwwroot."mod/rubricbuilder/pages/everyone.php");
	elgg_push_breadcrumb(sprintf(elgg_echo("rubric:user"),$page_owner->name), $CONFIG->wwwroot."pg/rubric/".$page_owner->username);
	elgg_push_breadcrumb(sprintf(elgg_echo('edit'))); // complete breadcrumb with file title
	$area1 .= elgg_view('navigation/breadcrumbs');	
	// Title
	$title = elgg_echo('rubricbuilder:editrubric');
	
	// create content for main column
	$content = elgg_view_title($title);
	$content .= elgg_view("rubricbuilder/forms/edit", $vars);
	
	
	// layout the sidebar and main column using the default sidebar
	$body = elgg_view_layout('one_column_with_sidebar', $area1 . $content);

	// create the complete html page and send to browser
	page_draw($title, $body);
?>