<?php
/**
* Rubric renderer
* 
* @package Rubrics
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
* @author Jeff Tilson
* @copyright THINK Global School 2010
* @link http://www.thinkglobalschool.com/
* 
* @uses $rubric
*/

$full = elgg_extract('full_view', $vars, false);
$rubric = elgg_extract('entity', $vars, false);
$rev_id = elgg_extract('rev_id', $vars, false);
$rubric_info = elgg_extract('rubric_info', $vars, array());

if (!$rubric) {
	return true;
}

// put all the array keys into local scope as vars
extract($rubric_info);

$owner = $rubric->getOwnerEntity();
$container = $rubric->getContainerEntity();
$categories = elgg_view('output/categories', $vars);
$excerpt = elgg_get_excerpt($rubric_info['description']);

$body = elgg_view('output/longtext', array(
	'value' => $rubric_info['description'],
	'class' => 'pbl'
));

$owner_link = elgg_view('output/url', array(
	'href' => "file/owner/$owner->username",
	'text' => $owner->name,
));
$author_text = elgg_echo('byline', array($owner_link));

$icon = elgg_view_entity_icon($owner, 'small');

$tags = elgg_view('output/tags', array('tags' => $rubric->tags));
$date = elgg_view_friendly_time($rubric->time_created);

$comments_link = '';

if ($rubric->comments_on != 'Off') {
	$comments_count = $rubric->countComments();
	if ($comments_count != 0) {
		$text = elgg_echo("comments") . " ($comments_count)";
		$comments_link = elgg_view('output/url', array(
			'href' => $rubric->getURL() . '#file-comments',
			'text' => $text,
		));
	}
}

$metadata = elgg_view_menu('entity', array(
	'entity' => $rubric,
	'handler' => 'rubrics',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

$subtitle = "$author_text $date $categories $comments_link";

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

if ($full) {
	// Build an array of 'local' revisions
	// ie: map the annotation id to a local number
	// annotations 87, 88, 89, 92, 98 becomes 1, 2, 3, 4, 5
	$revisions = $rubric->getAnnotations('rubric', 0);

	$count = count($revisions);
	$local_revisions = array();

	for ($i = 0; $i < $count; $i++) {
		$local_revisions[$revisions[$i]->id] = $i + 1;
	}

	if ($rev_id) {
		$current_revision = $local_revisions[$rev_id];
	} else {
		$current_revision = $count;
	}
	
	$revisions_output = elgg_view('rubrics/revision_menu', array(
		'revision' => $revision,
		'rubric_guid' => $rubric->getGUID(), 
		'local_revisions' => $local_revisions,
		'current_local_revision' => $current_revision
	));

	// comments
	if ($rubric->comments_on != 'Off') {
		$comments = elgg_view_comments($rubric);
	}

	// Build rubric table
	$rubric_table = "<table class='elgg-rubric elgg-rubric-display'>";
	for ($i = 0; $i < $num_rows; $i++) {
		$rubric_table .= "<tr>";
		for ($j = 0; $j < $num_cols; $j++) {
			if ($i == 0) {
				// these are headers
				$rubric_table .= "<td class=\"center middle\"><p class=\"elgg-rubrics-header\">";
				$rubric_table .= elgg_view('output/text', array(
					'value' => elgg_echo($contents[$i][$j])
				));
			} else {
				$rubric_table .= "<td class=\"middle\"><p>";
		    	$rubric_table .=  elgg_view('output/text', array(
					'value' => elgg_echo($contents[$i][$j])
				));
			}

			$rubric_table .= "&nbsp;</p></td>";
		}
		$rubric_table .= "</tr>";
	}
	$rubric_table .= "</table>";

	$header = elgg_view_title($rubric_info['title']);

	$params = array(
		'entity' => $rubric,
		'title' => $rubric_info['title'],
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);
	$list_body = elgg_view('page/components/summary', $params);

	$rubric_info = elgg_view_image_block($icon, $list_body);

	echo <<<HTML
$header
$rubric_info
<div class="elgg-content">
	$body
	$revisions_output
	$rubric_table
	$comments
</div>
HTML;

} else {
	// brief view

	$params = array(
		'entity' => $rubric,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
		'content' => $excerpt,
	);
	$list_body = elgg_view('page/components/summary', $params);

	echo elgg_view_image_block($rubric_icon, $list_body);
}