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

$annotation = $vars['annotation'];
$entity = get_entity($annotation->entity_guid);

$icon = elgg_view(
	"annotation/icon", array(
	'annotation' => $vars['annotation'],
	'size' => 'small',
  )
);

$owner_guid = $annotation->owner_guid;
$owner = get_entity($owner_guid);
		
$date = sprintf(elgg_echo('rubricbuilder:revisioncreatedby'), 
	friendly_time($annotation->time_created),
	
	"<a href=\"" . $owner->getURL() . "\">" . $owner->name ."</a>"
);

$revision = get_annotation($annotation->id);
$revision = unserialize($revision->value);
$link = $entity->getURL() . "?rev=" . $annotation->id;
$title = $revision['title'];
$linked_title = "<a href=\"$link\" title=\"" . htmlentities($title) . "\">{$title}</a>";




$info = <<<HTML
	<div><a href="$link">{$title}</a></div>
	<div>$rev</div>
HTML;

//echo elgg_view_listing($icon, $info);

echo <<<HTML
	<div class="rubric entity_listing clearfloat">
		<div class="entity_listing_icon">
			$icon
		</div>
		<div class="entity_listing_info">
			<p class="entity_title">$linked_title</p>
			<p class="entity_subtext">
				$date
			</p>
		</div>
	</div>
HTML;
?>