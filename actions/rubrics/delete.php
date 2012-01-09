<?php
/**
 * Delete rubric action
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

if (elgg_instanceof($rubric, 'object', 'rubric') && $rubric->canEdit()) {
	if ($rubric->delete()) {
		system_message(elgg_echo("rubrics:deleted"));
	} else {
		register_error(elgg_echo("rubrics:notdeleted"));
	}
} else {
	register_error(elgg_echo('rubrics:errors:invalid'));
}

forward(REFERER);