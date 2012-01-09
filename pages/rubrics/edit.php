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

$rubric = get_entity(get_input('guid'));
$title = elgg_echo('rubrics:edit');

if (!elgg_instanceof($rubric, 'object', 'rubric')) {
	$content = elgg_echo('rubrics:not_found');
} else {
	elgg_push_breadcrumb($rubric->title, $rubric->getURL());
	elgg_push_breadcrumb(elgg_echo('edit'));
	$vars = rubrics_prepare_form_vars($rubric);
	$content = elgg_view_form("rubrics/save", array('name' => 'rubrics', 'class' => 'elgg-form-alt'), $vars);
}

$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
