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
 * @uses $vars['revision'] - Current Revision or null
 * @uses $vars['rubric_guid'] - Rubric guid
 * @uses $vars['local_revisions'] - Array of revision_guid => local_id (1, 2, 3 etc..)
 * @uses $vars['current_local_revision'] - Current local revision from above
 */

$rubric_guid = elgg_extract('rubric_guid', $vars);

$rubric = get_entity($rubric_guid);
$user = get_entity($rubric->owner_guid);
$local_revisions = elgg_extract('local_revisions', $vars);
$current_local_revision = elgg_extract('current_local_revision', $vars);
$revision = elgg_extract('revision', $vars, null);

$count = count($local_revisions);

// Create the dropdown array, sorting in reverse order
$revisions_dropdown = $local_revisions;
arsort($revisions_dropdown);

$counter = 0;
foreach ($revisions_dropdown as $key => $value) {
	if ($counter == 1) {
		break;
	}
	
	$revisions_dropdown[$key] = $value . " (Latest)";
	$counter++;
}

// Set up previous and next buttons
$flipped_revisions = array_flip($local_revisions);
$previous_button = "";
$next_button = "";
$class = '';
if ($current_local_revision > 1) {
	$prev = $flipped_revisions[$current_local_revision - 1];
	$url = elgg_http_add_url_query_elements($rubric->getURL(), array('rev_id' => $prev));
	$previous_button = elgg_view('output/url', array(
		'text' => elgg_echo('rubrics:previous'),
		'href' => $url,
		'class' => 'elgg-button-action mhl'
	));
}

if ($current_local_revision < $count){
	$next = $flipped_revisions[$current_local_revision + 1];
	$url = elgg_http_add_url_query_elements($rubric->getURL(), array('rev_id' => $next));
	$next_button = elgg_view('output/url', array(
		'text' => elgg_echo('rubrics:next'),
		'href' => $url,
		'class' => 'elgg-button-action mhl'
	));
}

if ($current_local_revision != $count) {
	$url = 'action/rubrics/restore';
	$url = elgg_http_add_url_query_elements($url, array(
		'guid' => $rubric_guid,
		'rev_id' => $revision->id
	));
	
	$restore_link = elgg_view("output/confirmlink", array(
		'href' => $url,
		'text' => elgg_echo('rubrics:restore'),
		'confirm' => elgg_echo('rubrics:restore_confirm'),
	));
}

// Get revision author
$revision_author = get_entity(get_annotation($flipped_revisions[$vars['current_local_revision']])->owner_guid);
$author_content = "<a href='{$vars['url']}pg/rubric/{$revision_author->username}'>{$revision_author->name}</a>";

$history_toggler = elgg_view('output/url', array(
	'text' => elgg_echo("rubrics:revision_history"),
	'href' => '#elgg-rubric-history',
	'class' => 'elgg-toggler'
));

$viewing_revision = elgg_echo("rubrics:viewing_revision");
$author_content = elgg_echo("rubrics:revision_author");

$history_link = elgg_view('output/url', array(
	'text' => $count,
	'href' => $rubric->getHistoryURL()
));

$history_dropdown = elgg_echo('rubrics:revision') . elgg_view("input/dropdown", array(
	'options_values' => $revisions_dropdown,
	'name' => 'rev_id',
	'class' => 'elgg-rubrics-revision',
	'value' => $revision->id
));

$hidden_guid = elgg_view('input/hidden', array(
	'name' => 'guid',
	'value' => $rubric_guid
));

// wrap this in a form so we can jQuery at it.
$history_select = elgg_view('input/form', array(
	'body' => $history_dropdown . $hidden_guid
));

$class = $current_local_revision == $count ? 'class = "hidden"' : '';

$content = <<<___HTML
	$history_toggler

	<div id="elgg-rubric-history" $class>
		<table>
			<tr>
				<td colspan="3" class='rubric-revision-description center pbl'>
					$viewing_revision
					$current_local_revision/$history_link<br />
					$author_content<br />
					$restore_link
				</td>
			</tr>

			<tr>
				<td class='rubric-revision-previous middle pbm'>
					$previous_button
				</td>

				<td class='rubric-revision-select middle pbm'>
					$history_select $history_rev
				</td>

				<td class='rubric-revision-next middle pbm'>
					$next_button
				</td>
			</tr>
		</table>
	</div>
___HTML;

echo $content;
