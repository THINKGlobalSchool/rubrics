<?php
/**
 * Rubric revision view
 * 
 * @package RubricBuilder
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 * Some code borrowed from Pages plugin
 */

$revision = $vars['annotation'];
$rubric = get_entity($revision->entity_guid);
$rubric_info = rubrics_get_rubric_info($rubric, $revision);

$owner = get_entity($revision->owner_guid);
$owner_url = elgg_view('output/url', array(
	'text' => $owner->name,
	'href' => $owner->getURL()
));
$icon = elgg_view_entity_icon($owner, 'small');
		
$metadata = elgg_echo('rubrics:revision_created_by', array(
	elgg_get_friendly_time($revision->time_created),
	$owner_url
));

$link = elgg_http_add_url_query_elements($rubric->getURL(), array('rev_id' => $revision->id));
$title = $rubric_info['title'];
$linked_title = "<a href=\"$link\" title=\"" . htmlentities($title) . "\">{$title}</a>";

// view: object/elements/summary requires an entity to be passed because
// it builds its own links. We don't want that, so write the html manually
$html = <<<HTML
	$metadata
	<h3>$title_link</h3>
	<div class="elgg-list-content">$linked_title</div>
HTML;

echo elgg_view_image_block($icon, $html);