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
$revision = elgg_extract('revision', $vars, false);

if (!$rubric) {
	return true;
}

$owner = $rubric->getOwnerEntity();
$container = $rubric->getContainerEntity();
$categories = elgg_view('output/categories', $vars);
$excerpt = elgg_get_excerpt($rubric->description);

$body = elgg_view('output/longtext', array('value' => $rubric->description));

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
	$revisions = elgg_get_annotations(array(
		'type' => 'object',
		'subtype' => 'rubric',
		'annotation_name' => 'rubric',
		'limit' => 0
	));

	$count = count($revisions);
	$revisions_local = array();

	for ($i = 0; $i < $count; $i++) {
		$revisions_local[$revisions[$i]->id] = $i + 1;
	}

	if ($revision) {
		$revision = get_annotation($rev);
		
		// Make sure we have an annotation object, and that it belongs to this rubric
		if ($revision && $revision->entity_guid == $rubric->getGUID()) {
			$revision = unserialize($revision->value);

			$title       = $revision['title'];
			$description = $revision['description'];
			$contents    = unserialize($revision['contents']);
			$num_rows    = $revision['rows'];
			$num_cols    = $revision['cols'];

			$current_revision = $revisions_local[$rev];

		} else {
			// Something funny is going on...
			forward();
		}
	} else {
		$title 			  = $rubric->title;
		$description 	  = $rubric->description;
		$contents		  = $rubric->getContents();
		$num_rows		  = $rubric->getNumRows();
		$num_cols		  = $rubric->getNumCols();
		$current_revision = $count;
	}


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

	$header = elgg_view_title($rubric->title);

	$params = array(
		'entity' => $rubric,
		'title' => $rubric->title,
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





return true;








if (isset($rubric) && $rubric instanceof Rubric) {
	$url 		= $rubric->getURL();
	$owner 		= $rubric->getOwnerEntity();
	$canedit 	= $rubric->canEdit();
	$rubric 	= $rubric;
	
	

	$count = count($revisions);	

	// Build an array of 'local' revisions
	// ie: map the annotation id to a local number
	// annotations 87, 88, 89, 92, 98 becomes 1, 2, 3, 4, 5
	$revisions_local = array();

	for ($i = 0; $i < $count; $i++) {
		$revisions_local[$revisions[$i]->id] = $i + 1;
	}
		
	//	$current_revision = $count;
	
	// If we're looking at a revision, grab the content for that
	$rev = (int)get_input('rev',0);
	if ($rev) {
		
		$revision = get_annotation($rev);
		// Make sure we have an annotation object, and that it belongs to this rubric
		if ($revision && $revision->entity_guid == $rubric->getGUID()) {
			$revision = unserialize($revision->value);

			$title 			= $revision['title'];
			$description 	= $revision['description'];
			$contents		= unserialize($revision['contents']);
			$num_rows		= $revision['rows'];
			$num_cols		= $revision['cols'];
			
			$current_revision = $revisions_local[$rev];
			
			if ($current_revision == $count)
				$current_revision = $count;
			
		} else {
			// Something funny is going on...
			forward();
		}
	} else {
		$title 			= $rubric->title;
		$description 	= $rubric->description;
		$contents		= $rubric->getContents();
		$num_rows		= $rubric->getNumRows();
		$num_cols		= $rubric->getNumCols();
		$current_revision = $count;			
	}

	// display comments link?
	if ($rubric->comments_on == 'Off') {
		$comments_on = false;
	} else {
		$comments_on = true;
	}
	
	// Are we allowed to delete?
	$can_delete = false;
	if (($vars['user']->getGUID() == $rubric->owner_guid) || $vars['user']->isAdmin()) {
		$can_delete = true;
	}
	
	// Build rubric table
	$rubric_table = "<table class='rubric-table' cellpadding='10px' cellspacing='10px'>";
	for ($i = 0; $i < $num_rows; $i++) {
		$rubric_table .= "<tr>";
		for ($j = 0; $j < $num_cols; $j++) {

			$input_class = 'rubric-cell';

			// Zebra stripes
			if ($i % 2 == 0 && $i != 0)
				$input_class .= " alt";

			if ($i == 0) {
				$rubric_table .= "<td style='height: 15px;' class='$input_class rubric-header'><p>";
				$rubric_table .= elgg_view('output/text', array('internalname' => $i . '|' . $j, 'value' => elgg_echo($contents[$i][$j])));
			} else {
				$rubric_table .= "<td class='$input_class'><p>";
		    	$rubric_table .=  elgg_view('output/text', array('internalname' => $i . '|' . $j, 'value' => elgg_echo($contents[$i][$j])));
			}

			$rubric_table .= "&nbsp;</p></td>";
		}
		$rubric_table .= "</tr>";
	} 
	$rubric_table .= "</table><br />";
	
	// Views/Content
	$user_icon = elgg_view("profile/icon",array('entity' => $owner, 'size' => 'tiny'));
	$tags_output = elgg_view('output/tags', array('tags' => $rubric->tags));
	$description_output = elgg_view('output/longtext',array('value' => $description));
	$revisions_output = elgg_view('rubricbuilder/revisionmenu', array('rev_guid' => $rev, 'rubric_guid' => $rubric->getGUID(), 'local_revisions' => $revisions_local, 'current_local_revision' => $current_revision));
	
	$date = friendly_time($rubric->time_created);

	// If comments on build link
	if ($rubric->comments_on != 'Off') {
		$comments_count = elgg_count_comments($rubric);
		$comments_link = "<a href=\"{$rubric->getURL()}#annotations\">" . sprintf(elgg_echo("comments"), $comments_count) . '</a>';
	} else {
		$comments_link = '';
	}

	// Options menu
	$options = "";
	// Only show editing options if we're not viewing a revision
	if (!$rev || $current_revision == $count) {
		if ($canedit) {
			$revision = $rev ? "?rev=$rev"  : "";
			$options .= "<span class='entity_edit'><a href={$vars['url']}pg/rubric/{$vars['user']->username}/edit/{$rubric->getGUID()}$revision>" . elgg_echo("edit") . "</a></span>";
		} 
		
		$options .= "<span class='entity_edit'>" . elgg_view("output/confirmlink", array(
					'href' => $vars['url'] . "action/rubric/fork?rubric_guid=" . $rubric->getGUID(),
					'text' => elgg_echo('rubricbuilder:fork'),
					'confirm' => elgg_echo('rubricbuilder:forkconfirm'),
					)) . "</span>";
			
		if ($canedit && $can_delete) {
			if ($can_delete) {								
				$delete_url = "{$vars['url']}action/rubric/delete?rubric_guid={$rubric->getGUID()}";
				$delete_link = "<span class='delete_button'>" . elgg_view('output/confirmlink', array(
					'href' => $delete_url,
					'text' => elgg_echo('delete'),
					'confirm' => elgg_echo('deleteconfirm')
				)) . "</span>";
				
				$options .= $delete_link;
			}
		}
		
		// include a view for plugins to extend
		$options = elgg_view("rubric/options", array("object_type" => 'rubric', 'entity' => $rubric)) . elgg_view_likes($rubric) . $options;
	}
	
	echo <<<HTML
	<div id="rubric clearfloat">
			<div id="content_header" class="clearfloat">
				<div class="content_header_title"><h2>{$title}</h2></div>
			</div>
			<div class="entity_listing_icon">
				$user_icon
			</div>
			<div class="entity_listing_info">
				<div class="entity_metadata">$options</div>
				<p class="entity_subtext">
					$date
					$comments_link
				</p>
				<p class="tags">$tags_output</p>
				<span class="body"><br />$description_output<br /></span>
			</div>
				$revisions_output
			<div class="clearfloat"></div>
			<div class="rubric_body">
				$rubric_table
			</div>
			<div class="clearfloat"></div>				
	</div>
HTML;
} else {
	forward();
}
