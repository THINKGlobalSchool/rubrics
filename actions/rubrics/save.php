<?php
/**
 * Save rubric
 *
 * Used for add and edit
 *
 * Saves serialized tabular data as:
 * array(
 *	array( // tr
 *		1, 2, 3 // td
 *	),
 *	array( // tr
 *		4, 5, 6 //td
 *	)
 * )
 * 
 * @package RubricBuilder
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

elgg_make_sticky_form('rubrics');

$title 			= get_input('title');
$description 	= get_input('description');
$tags			= get_input('tags');
$container_guid = (int)get_input('container_guid');
$access_id		= (int)get_input('access_id', ACCESS_PRIVATE);
$write_access	= (int)get_input('write_access_id', ACCESS_PRIVATE);
$comments_on	= get_input('comments_on', 'Off');
$headers        = get_input('headers', array());
$data           = get_input('data', array());
$tagarray 		= string_to_tag_array($tags);
$guid           = get_input('guid');

// die early
if (!$headers || !$data || !$title) {
	register_error(elgg_echo('rubrics:error:missing_data'));
	forward(REFERER);
}

$info = rubrics_get_matrix_info_from_input($headers, $data);

// new or existing entity?
if ($guid) {
	$rubric = get_entity($guid);

	if (!elgg_instanceof($rubric, 'object', 'rubric') || !$rubric->canEdit()) {
		register_error(elgg_echo('rubrics:errors:invalid_entity'));
		forward(REFERER);
	}

	$success_msg = elgg_echo('rubrics:edited');
	$river_view = 'river/object/rubrics/update';
	$river_action = 'update';
} else {
	$rubric = new Rubric();

	$success_msg = elgg_echo('rubrics:posted');
	$river_view = 'river/object/rubrics/create';
	$river_action = 'create';
}

$rubric->contents			= serialize($info['contents']);
$rubric->title 				= $title;
$rubric->description 		= $description;
$rubric->container_guid 	= $container_guid;
$rubric->access_id			= $access_id;
$rubric->write_access_id	= $write_access;
$rubric->tags 				= $tagarray;
$rubric->comments_on		= $comments_on;
$rubric->num_cols           = $info['num_cols'];
$rubric->num_rows           = $info['num_rows'];

if (!$rubric->save()) {
	register_error(elgg_echo("rubricbuilder:error"));		
	forward($_SERVER['HTTP_REFERER']);
}

elgg_clear_sticky_form('rubrics');

system_message($success_msg);
// Hacked in for now
$river_view = 'river/object/rubrics/create';
add_to_river($river_view, $river_action, elgg_get_logged_in_user_guid(), $rubric->getGUID());

$revision = array(
	"contents" => $rubric->contents,
	"title" => $rubric->title,
	"description" => $rubric->description,
	'cols' => $info['num_cols'],
	'rows' => $info['num_rows']
);

// Annotate for revision history
$rubric->annotate('rubric', serialize($revision), $rubric->access_id);

forward($rubric->getURL());