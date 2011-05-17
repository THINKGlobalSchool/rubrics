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

if ($owner->guid == elgg_get_logged_in_user_guid()) {
	// user looking at own files
	$title = elgg_echo('rubrics:mine');
	$params['filter_context'] = 'mine';
} elseif (elgg_instanceof($owner, 'user')) {
	// someone else's files
	$title = elgg_echo("rubrics:user", array($owner->name));
	// do not show button or select a tab when viewing someone else's posts
	$params['filter_context'] = 'none';
	$params['buttons'] = '';
} else {
	// group files
	$title = elgg_echo("rubrics:user", array($owner->name));
	$params['filter'] = '';
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