<?php
/**
 * Add rubric action
 *
 * Saves a serialized tabular data as:
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
$comments		= get_input('omments', 'Off');
$headers        = get_input('headers', array());
$data           = get_input('data', array());
$tagarray 		= string_to_tag_array($tags);

// die early
if (!$headers || !$data) {
	register_error(elgg_echo('rubrics:error:missing_data'));
}

// we know the # of headers so we can use
// that to generate offsets and limits
$cols = count($headers);
$content = array($headers);

// Get rubric content
$i = 1;
$row = array();
foreach($data as $item) {
	$row[] = $item;

	if ($i == $cols) {
		$content[] = $row;
		$row = array();
		$i = 1;
	} else {
		$i++;
	}
}

if (empty($title)) {
	register_error(elgg_echo("rubricbuilder:blank"));
	forward(REFERER);
}

$rubric = new Rubric();
$rubric->contents			= serialize($content);
$rubric->title 				= $title;
$rubric->description 		= $description;
$rubric->container_guid 	= $container_guid;
$rubric->access_id			= $access_id;
$rubric->write_access_id	= $write_access;
$rubric->tags 				= $tagarray;
$rubric->comments_on		= $comments;

if (!$rubric->save()) {
	register_error(elgg_echo("rubricbuilder:error"));		
	forward($_SERVER['HTTP_REFERER']);
}

elgg_clear_sticky_form('rubrics');

system_message(elgg_echo("rubrics:posted"));
add_to_river('river/object/rubrics/create', 'create', get_loggedin_userid(), $rubric->guid);

$revision = array(
	"contents" => $rubric->contents,
	"title" => $rubric->title,
	"description" => $rubric->description
);

// Annotate for revision history
$rubric->annotate('rubric', serialize($revision), $rubric->access_id);

$user = get_loggedin_user();

//forward("pg/rubric/{$user->username}/view/" . $rubric->getGUID());
forward($rubric->getURL());
