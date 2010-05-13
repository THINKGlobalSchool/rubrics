<?php
	/**
	 * Friends' Rubrics listing
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
	if (!$page_owner) {
		$page_owner_guid = get_loggedin_userid();
		if ($page_owner_guid)
			set_page_owner($page_owner_guid);
	}	

	$limit = get_input("limit", 10);
	$offset = get_input("offset", 0);

	// Title
	$title = elgg_echo('rubricbuilder:friendsrubrics');
	
	elgg_push_breadcrumb(elgg_echo('friends'));
	
	// create content for main column
	$content = elgg_view('navigation/breadcrumbs');
	$content .= elgg_view('page_elements/content_header', array(
		'context' => 'friends',
		'type' => 'rubric',
		'all_link' => "{$CONFIG->site->url}pg/rubric"
	));
	
	$context = get_context();
	set_context('search');
		
	$content .= list_user_friends_objects(page_owner(),'rubric',10,false,false);
	
	set_context($context);
	
	// layout the sidebar and main column using the default sidebar
	$body = elgg_view_layout('one_column_with_sidebar', $content, '');

	// create the complete html page and send to browser
	page_draw($title, $body);
?>