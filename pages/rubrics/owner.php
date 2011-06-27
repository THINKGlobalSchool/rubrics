<?php
/**
 * Rubric Index for single user
 * 
 * @package RubricBuilder
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

// access check for closed groups
group_gatekeeper();

$owner = elgg_get_page_owner_entity();
elgg_push_breadcrumb($owner->name);

$params = array();

$title = elgg_echo('rubrics:owned_rubrics', array($owner->name));

if ($owner->guid == elgg_get_logged_in_user_guid()) {
	// user's rubrics
	$params['filter_context'] = 'mine';
	elgg_register_add_button();
} elseif (elgg_instanceof($owner, 'user')) {
	// someone else's rubrics
	// do not show button or select a tab when viewing someone else's rubrics
	$params['filter_context'] = 'none';
} else {
	// group rubrics
	$params['filter'] = '';
	elgg_register_add_button();
}

$content = elgg_list_entities(array(
	'types' => 'object',
	'subtypes' => 'rubric',
	'container_guid' => $owner->guid,
	'limit' => 10,
	'full_view' => false,
));

if (!$content) {
	$content = elgg_echo("rubric:none");
}

$params['content'] = $content;
$params['title'] = $title;
$params['sidebar'] = $sidebar;

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);