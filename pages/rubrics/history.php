<?php
/**
 * List rubric history
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

elgg_push_breadcrumb($vars['entity']->title, $vars['entity']->getURL());
elgg_push_breadcrumb(elgg_echo('rubricbuilder:history'));

$title = elgg_echo('rubricbuilder:history', array($rubric->title));

$content .= list_annotations($rubric_guid, 'rubric', $limit, false);
$content = elgg_list_annotations(array(
	'entity_guid' => $rubric->getGUID(),
	'annotation_name' => 'rubric'
));

$params['content'] = $content;
$params['title'] = $title;

$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);