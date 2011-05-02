<?php
/**
 * Rubric object view
 * 
 * @package RubricBuilder
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 * Code borrowed from Pages plugin
 * 
 */
if ($vars['full']) {
	echo elgg_view("rubricbuilder/rubricview",$vars);
} else {
	echo elgg_view("rubricbuilder/rubriclisting",$vars);
}			
