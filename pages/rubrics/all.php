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

$title = elgg_echo('rubrics:all');

$content .= elgg_list_entities(array(
	'type' => 'object',
	'subtypes' => 'rubric',
	'full_view' => false
));

elgg_register_title_button();

$body = elgg_view_layout('content', array(
	'filter_context' => 'all',
	'content' => $content,
	'title' => $title
));

echo elgg_view_page($title, $body);