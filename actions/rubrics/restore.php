<?php
/**
 * Restore rubric action
 * 
 * @package RubricBuilder
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

// Get input data
$guid = (int)get_input('guid');
$rev_id = (int)get_input('rev_id');
$revision = elgg_get_annotation_from_id($rev_id);
$rubric = get_entity($guid);
$rubric_info = rubrics_get_rubric_info($rubric, $revision);

$is_rubric = (elgg_instanceof($rubric, 'object', 'rubric') && $rubric->canEdit());
$is_revision = ($revision instanceof ElggAnnotation && $revision->entity_guid == $guid);
if ($rubric_info && $is_rubric && $is_revision) {
	$rubric->title       = $rubric_info['title'];
	$rubric->description = $rubric_info['description'];
	$rubric->contents    = serialize($rubric_info['contents']);
	$rubric->num_rows    = $rubric_info['num_rows'];
	$rubric->num_cols    = $rubric_info['num_cols'];
			
	$revision = array(
		"title" => $rubric_info['title'],
		"description" => $rubric_info['description'],
		"contents" => serialize($rubric_info['contents']),
		"rows" => $rubric_info['num_rows'],
		"cols" => $rubric_info['num_cols']
	);

	if ($rubric->save()) {
		// Annotate for revision history
		$rubric->annotate('rubric', serialize($revision), $rubric->access_id);
		system_message(elgg_echo("rubrics:restored"));
	} else {
		register_error(elgg_echo("rubrics:error"));
	}
	forward($rubric->getURL());
} else {
	register_error(elgg_echo("rubrics:error"));
	forward(REFERER);
}
