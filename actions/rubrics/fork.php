<?php
/**
 * Fork rubric action
 * 
 * @package RubricBuilder
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$guid = (int)get_input('guid');
$rubric = get_entity($guid);
$user_guid = elgg_get_logged_in_user_guid();

if (!elgg_instanceof($rubric, 'object', 'rubric')) {
	register_error(elgg_echo("rubrics:cannot_fork"));
	forward(REFERER);
}

$new_rubric                 = clone $rubric;
$new_rubric->title          = elgg_echo('rubrics:forked_title', array($rubric->title));
$new_rubric->owner_guid     = $user_guid;
$new_rubric->container_guid = (int)get_input($rubric->container_guid, $user_guid);

if (!$new_rubric->save()) {
	register_error(elgg_echo("rubrics:cannot_fork2"));
	forward($_SERVER['HTTP_REFERER']);
}

forward($new_rubric->getURL());