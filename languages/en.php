<?php
/**
 * Rubric language file
 * 
 * @package RubricBuilder
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

$english = array(
	
	// Entity/Object Related
	'rubrics' => 'Rubrics',
	'item:object:rubric' => 'Rubrics',
	'rubrics:title' => 'Rubric',
	'rubrics:owned_rubrics' => '%s\'s Rubrics',
	
	// Default Content
	'rubrics:criteria:default' => 'Criteria',
	'rubrics:level1' => '1',
	'rubrics:level2' => '2',
	'rubrics:level3' => '3',
	'rubrics:level4' => '4',
	'rubrics:level5' => '5',
	
	// Menu
	'rubrics:myrubrics' => 'Your Rubrics',
	'rubrics:friendsrubrics' => 'Friends\' Rubrics',
	'rubrics:all' => 'All Rubrics',
	'rubrics:label:history' => 'Rubric History',
	'rubrics:label:view' => 'View Rubric',
	
	// Actions
	'rubrics:fork' => 'Make Copy',
	'rubrics:restore' => 'Restore this version',
	
	// Action labels
	'rubrics:deleted' => 'Rubric successfully deleted',
	'rubrics:success'	=> 'Your action was successful',
	'rubrics:posted' => 'Rubric successfully created',
	'rubrics:edited' => 'Rubric updated',
	'rubrics:restored' => 'Rubric successfully restored',

	// Confirmations
	'rubrics:forkconfirm' => 'Are you sure you want to duplicate this rubric?',
	'rubrics:restoreconfirm' => 'Are you sure you want to restore this rubric?',
	
	// Errors
	'rubrics:notdeleted' => 'There was an error deleting the rubric',
	'rubrics:error' => 'There was an error saving the rubric',
	'rubrics:blank' => 'Rubric title cannot be blank',
	'rubrics:failure'	=> 'Your action failed',
	'rubrics:error:unknown_username' => 'Unknown User',

	// Titles/Label
	'rubrics:add' => 'Add Rubric',
	'rubrics:edit' => 'Edit Rubric',
	'rubrics:revision' => 'Revision: ',
	'rubrics:history' => 'History',
	'rubrics:revisionhistory' => 'Revision History',
	'rubrics:viewingrevision' => 'Viewing Revision: ',
	'rubrics:revisionauthor' => 'Revision Author',
	'rubrics:revisioncreatedby' => 'Revision created %s by %s',
	
	// River terms
	'rubrics:river:created' => '%s created',
	'rubrics:river:updated' => '%s updated',
	'rubrics:river:posted' => '%s posted',

	// River links
	'rubrics:river:create' => 'a new rubric titled',
	'rubrics:river:update' => 'a rubric titled',
	'rubrics:river:annotate' => 'a comment on a rubric titled',
	'rubric:river:annotate' => 'a comment on a rubric titled',
	
	// Widget
	'rubrics:num' => 'Number of rubrics to display',
	'rubrics:widget:description' => 'A list of your rubrics',
	'rubrics:more' => 'More rubrics',
	
	// Other
	'rubrics:comments:allow' => 'Allow Comments',

);

add_translation('en', $english);
