<?php
/**
 * Rubric revision history navigation menu
 * 
 * @package RubricBuilder
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 * @uses $vars['rev_guid'] - Current Revision Guide
 * @uses $vars['rubric_guid'] - Rubric guid
 * @uses $vars['local_revisions'] - Array of revision_guid => local_id (1, 2, 3 etc..)
 * @uses $vars['current_local_revision'] - Current local revision from above
 */

$rubric_guid = elgg_extract('rubric_guid', $vars);

$rubric = get_entity($rubric_guid);
$user = get_entity($rubric->owner_guid);
$revisions_local = $vars['local_revisions'];
$current_revision = $vars['current_local_revision'];

$count = count($revisions_local);

// Create the dropdown array, sorting in reverse order
$revisions_pulldown = $revisions_local;
arsort($revisions_pulldown);

$counter = 0;
foreach ($revisions_pulldown as $key => $value) {
	if ($counter == 1) {
		break;
	}
	
	$revisions_pulldown[$key] = $value . " (Latest)";
	$counter++;
}

// Set up previous and next buttons
$flipped_revisions = array_flip($revisions_local);
$previous_button = "";
$next_button = "";
if ($current_revision > 1) {
	$prev = $flipped_revisions[$current_revision - 1];
	$url = elgg_http_add_url_query_elements($rubric->getURL(), array('rev' => $prev));
	$previous_button = elgg_view('output/url', array(
		'text' => elgg_echo('rubrics:previous'),
		'href' => $url,
		'class' => 'elgg-button-action'
	));
}  

if ($current_revision < $count){
	$next = $flipped_revisions[$current_revision + 1];
	$url = elgg_http_add_url_query_elements($rubric->getURL(), array('rev' => $next));
	$previous_button = elgg_view('output/url', array(
		'text' => elgg_echo('rubrics:next'),
		'href' => $url,
		'class' => 'elgg-button-action'
	));
}

if ($current_revision != $count) {
	$restore_link = elgg_view("output/confirmlink", array(
				'href' => $vars['url'] . "action/rubric/restore?rubric_guid=" . $rubric_guid . "&rev=" . $vars['rev_guid'],
				'text' => elgg_echo('rubricbuilder:restore'),
				'confirm' => elgg_echo('rubricbuilder:restoreconfirm'),
				));
}

$history_url = $CONFIG->url . "pg/rubric/{$user->username}/history/" . $rubric_guid;

// Get revision author
$revision_author = get_entity(get_annotation($flipped_revisions[$vars['current_local_revision']])->owner_guid);
$author_content = "<a href='{$vars['url']}pg/rubric/{$revision_author->username}'>{$revision_author->name}</a>";

$show_div = "";
if ($vars['rev_guid']) {
	$show_div = " style='display: block;' ";
}

$content = "<br /><a href='#' id='show_hide_history' onclick=\"$('#rubric-revision-menu').toggle(200); return false;\">" . elgg_echo("rubricbuilder:revisionhistory") . "</a><br />
			<div id='rubric-revision-menu' $show_div>
				<table id='rubric-revision-menu-table'>
					<tr>
						<td colspan=3 class='rubric-revision-description'>
							" . elgg_echo("rubricbuilder:viewingrevision") . "$current_revision/<a href='$history_url'>$count</a> <br /> " . elgg_echo("rubricbuilder:revisionauthor") . ": $author_content <br /> $restore_link  
						</td>
					</tr>
					<tr>
						<td class='rubric-revision-previous'>
							$previous_button
						</td>
						<td class='rubric-revision-select'>
								<form id='revselect' action='{$CONFIG->url}pg/rubric/{$user->username}/view/$rubric_guid/'>
									" . elgg_echo("rubricbuilder:revision") 
										. elgg_view("input/pulldown", array('options_values' => $revisions_pulldown, 'internalname' => 'rev', 'internalid' => 'rev')) . "											
										<input type='hidden' id='current_revision' value='{$flipped_revisions[$current_revision]}' />
								</form>
						</td>
						<td class='rubric-revision-next'>
							$next_button
						</td>
					</tr>
				</table>
			</div>
";

echo $script . $content;
