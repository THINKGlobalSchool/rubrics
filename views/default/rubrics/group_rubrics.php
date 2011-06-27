<?php
/**
 * Rubrics group module
 * 
 * @package Rubrics
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 */

$group = elgg_get_page_owner_entity();

if ($group->rubrics_enable == "no") {
	return true;
}

$all_link = elgg_view('output/url', array(
	'href' => "rubrics/group/$group->guid/all",
	'text' => elgg_echo('link:view:all'),
));

elgg_push_context('widgets');
$options = array(
	'type' => 'object',
	'subtype' => 'rubric',
	'container_guid' => elgg_get_page_owner_guid(),
	'limit' => 6,
	'full_view' => false,
	'pagination' => false,
);
$content = elgg_list_entities($options);
elgg_pop_context();

if (!$content) {
	$content = '<p>' . elgg_echo('rubric:none') . '</p>';
}

$new_link = elgg_view('output/url', array(
	'href' => "rubrics/add/$group->guid",
	'text' => elgg_echo('rubrics:add'),
));

echo elgg_view('groups/profile/module', array(
	'title' => elgg_echo('rubrics:group'),
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
));

