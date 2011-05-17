<?php
/**
 * View single rubric page
 * 
 * @package RubricBuilder
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$guid = get_input('guid');
$rubric = get_entity($guid);

if (!elgg_instanceof($rubric, 'object', 'rubric')) {
	$content = elgg_echo('rubrics:not_found');
} else {
	$page_owner = elgg_get_page_owner_entity();
	$crumbs_title = $page_owner->name;

	if (elgg_instanceof($page_owner, 'group')) {
		elgg_push_breadcrumb($crumbs_title, "rubrics/group/$page_owner->guid/owner");
	} else {
		elgg_push_breadcrumb($crumbs_title, "rubrics/owner/$page_owner->username");
	}

	$title = $rubric->title;
	elgg_push_breadcrumb($title);
	$content = elgg_view_entity($rubric, true);
}

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
	'header' => '',
));

echo elgg_view_page($title, $body);
