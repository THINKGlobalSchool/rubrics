<?php
/**
 * Add rubric page
 * 
 * @package RubricBuilder
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$title = elgg_echo('rubrics:add');
elgg_push_breadcrumb($title);

$vars = rubrics_prepare_form_vars();
$content = elgg_view_form("rubrics/save", array('name' => 'rubrics', 'class' => 'elgg-form-alt'), $vars);
$body = elgg_view_layout('content', array(
	'filter' => '',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);