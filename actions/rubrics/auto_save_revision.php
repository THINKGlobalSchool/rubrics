<?php
/**
 * Auto save rubric, similar to blogs
 * 
 * @package RubricBuilder
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$guid = get_input('guid');
$user = elgg_get_logged_in_user_entity();
$title = get_input('title');
$description = get_input('description');
$headers = get_input('headers', array());
$data = get_input('data', array());
$error = FALSE;

if ($title && $headers && $data) {
	// Rubric content
	$info = rubrics_get_matrix_info_from_input($headers, $data);
	
	if ($guid) {
		$entity = get_entity($guid);
		if (elgg_instanceof($entity, 'object', 'rubric') && $entity->canEdit()) {
			$rubric = $entity;

			// Create a revision of the last 
			if (!$rubric->new_auto_revision) {
				
			} 
			
		} else {
			$error = elgg_echo('rubrics:error:invalid');
		}
	} else {
		$rubric = new Rubric();

		// force draft and private for autosaves.
		$rubric->status = 'unsaved_draft';
		$rubric->access_id = ACCESS_PRIVATE;
		$rubric->write_access_id = ACCESS_PRIVATE;

		// mark this as a brand new post so we can work out the
		// river / revision logic in the real save action.
		$rubric->new_rubric = TRUE;
	}

	if (!$error) {
		// Set rubric data
		$rubric->title = $title;
		$rubric->description = $description;
		$rubric->contents = serialize($info['contents']);
		$rubric->num_cols = $info['num_cols'];
		$rubric->num_rows = $info['num_rows'];

		if (!$rubric->save()) {
			$error = elgg_echo('rubrics:error');
		}
	}
} else {
	$error = elgg_echo('rubrics:error:missing_data');
}


if ($error) {
	$json = array('success' => FALSE, 'message' => $error);
	echo json_encode($json);
} else {
	$msg = elgg_echo('rubrics:saved');
	$json = array('success' => TRUE, 'message' => $msg, 'guid' => $rubric->getGUID());
	echo json_encode($json);
}
exit;

