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

$owner = elgg_get_page_owner_entity();

elgg_push_breadcrumb($owner->name, "rubrics/owner/$owner->username");
elgg_push_breadcrumb(elgg_echo('friends'));

$title = elgg_echo("rubrics:friends", array($owner->name));

// offset is grabbed in list_user_friends_objects
$content = list_user_friends_objects($owner->guid, 'rubric', 10, false);

if (!$content) {
	$content = elgg_echo("rubric:none");
}

elgg_register_add_button();

$body = elgg_view_layout('content', array(
	'filter_context' => 'friends',
	'content' => $content,
	'title' => $title
));

echo elgg_view_page($title, $body);