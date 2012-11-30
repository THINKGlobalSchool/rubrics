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
$rev_id = get_input('rev_id', null);

// remove the edit menu item if we're looking at a revision
if ($rev_id) {
	elgg_unregister_menu_item('entity', 'edit');
}

$rubric_info = rubrics_get_rubric_info($rubric, $rev_id);

if (!elgg_instanceof($rubric, 'object', 'rubric') || !$rubric_info) {
	register_error(elgg_echo('noaccess'));
	$_SESSION['last_forward_from'] = current_page_url();
	forward('');
} else {
	$page_owner = elgg_get_page_owner_entity();
	$crumbs_title = $page_owner->name;

	if (elgg_instanceof($page_owner, 'group')) {
		elgg_push_breadcrumb($crumbs_title, "rubrics/group/$page_owner->guid/owner");
	} else {
		elgg_push_breadcrumb($crumbs_title, "rubrics/owner/$page_owner->username");
	}

	$title = $rubric_info['title'];
	elgg_push_breadcrumb($title);
	$content = elgg_view_entity($rubric, array(
		'full_view' => true,
		'rev_id' => $rev_id,
		'rubric_info' => $rubric_info
	));
}

$body = elgg_view_layout('content', array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
	'header' => '',
));

echo elgg_view_page($title, $body);
