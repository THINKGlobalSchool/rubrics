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
	'rubrics:group' => 'Group rubrics',
	'rubric:none' => 'No rubrics',
	'rubrics:friends' => 'Friends\' Rubrics',
	'rubrics:enablegroup' => 'Enable group rubrics',
	'river:comment:object:rubric' => '%s commented on the rubric %s',
	
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
	'rubrics:fork' => 'Copy',
	'rubrics:restore' => 'Restore this version',
	
	// Action labels
	'rubrics:deleted' => 'Rubric successfully deleted',
	'rubrics:success'	=> 'Your action was successful',
	'rubrics:posted' => 'Rubric successfully created',
	'rubrics:saved' => 'Rubric successfully saved',
	'rubrics:edited' => 'Rubric updated',
	'rubrics:restored' => 'Rubric successfully restored',

	// Confirmations
	'rubrics:fork_confirm' => 'Are you sure you want to duplicate this rubric?',
	'rubrics:restore_confirm' => 'Are you sure you want to restore this rubric?',
	
	// Errors
	'rubrics:not_deleted' => 'There was an error deleting the rubric',
	'rubrics:error' => 'There was an error saving the rubric',
	'rubrics:blank' => 'Rubric title cannot be blank',
	'rubrics:failure'	=> 'Your action failed',
	'rubrics:error:unknown_username' => 'Unknown User',
	'rubrics:cannot_delete_column' => 'You must have one column.',
	'rubrics:cannot_delete_row' => 'You must have one row.',
	'rubrics:cannot_load' => 'Error loading rubric matrix. Using defaults',
	'rubrics:error:missing_data' => 'One or more required fields are missing',
	'rubrics:error:invalid' => 'Invalid Rubric',
	'rubrics:error:autosave' => 'WARNING: There was an error attempting to autosave this rubric. You should save the rubric as soon as possible to prevent data loss.',

	// Titles/Label
	'rubrics:add' => 'Add Rubric',
	'rubrics:edit' => 'Edit Rubric',
	'rubrics:revision' => 'Revision: ',
	'rubrics:history' => 'History',
	'rubrics:revision_history' => 'Revision History',
	'rubrics:viewing_revision' => 'Viewing Revision: ',
	'rubrics:revision_author' => 'Revision Author',
	'rubrics:revision_created_by' => 'Revision created %s by %s',
	'rubrics:forked_title' => "Copy of %s",
	'rubrics:save_status' => 'Last Saved: ',
	'rubrics:never' => 'Never',
	'rubrics:status:unsaved_draft' => 'Unsaved Draft',
	
	// River terms
	'river:create:object:rubric' => '%s published a Rubric titled %s',
	'river:update:object:rubric' => '%s updated a Rubric titled %s',
	'river:fork:object:rubric' => '%s copied a Rubric titled %s',

	// Notifications
	'rubrics:notification:subject' => 'New Rubric',
	'rubrics:notification:body' => "%s created a rubric titled: %s\n\n%s\n\nTo view the rubric click here:\n%s
",

	// Widget
	'rubrics:num' => 'Number of rubrics to display',
	'rubrics:widget:description' => 'A list of your rubrics',
	'rubrics:more' => 'More rubrics',
	
	// Other
	'rubrics:comments:allow' => 'Allow Comments',
	'rubrics:previous' => '<< Previous',
	'rubrics:next' => 'Next >>',
);

add_translation('en', $english);