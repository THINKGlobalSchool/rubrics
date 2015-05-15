<?php
/**
 * Fork rubric action
 * 
 * @package RubricBuilder
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 * 
 */

$guid = (int)get_input('guid', get_input('entity_guid'));
$group_guid = get_input('group_guid', NULL);

// Check for valid group and that user is a member (or is admin)
if (elgg_instanceof($group, 'group') &&  (!$group->isMember()) && !elgg_is_admin_logged_in()) {
	register_error(elgg_echo("rubrics:cannot_fork"));
	forward(REFERER);
}


$rubric = get_entity($guid);
$user_guid = elgg_get_logged_in_user_guid();

if (!elgg_instanceof($rubric, 'object', 'rubric')) {
	register_error(elgg_echo("rubrics:cannot_fork"));
	forward(REFERER);
}

$new_rubric                 = clone $rubric;
$new_rubric->title          = elgg_echo('rubrics:forked_title', array($rubric->title));
$new_rubric->owner_guid     = $user_guid;
$new_rubric->container_guid = $group_guid ? $group_guid : (int)get_input($rubric->container_guid, $user_guid);

if (!$new_rubric->save()) {
	register_error(elgg_echo("rubrics:cannot_fork"));
	forward(REFERER);
}

$revision = array(
	"contents" => $new_rubric->contents,
	"title" => $new_rubric->title,
	"description" => $new_rubric->description,
	'cols' => $new_rubric->num_cols,
	'rows' => $new_rubric->num_rows
);

// Annotate for revision history
$new_rubric->annotate('rubric', serialize($revision), $new_rubric->access_id);

system_message(elgg_echo('rubrics:fork_success'));
forward($new_rubric->getURL());