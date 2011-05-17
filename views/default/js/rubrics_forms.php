<?php
/**
 * Rubrics JS file
 */
?>
//<script>
elgg.provide('elgg.rubrics');

/**
 * Init the JS on the forms
 */
elgg.rubrics.init = function() {
	$('form .elgg-rubric .elgg-rubrics-add-row').live('click', elgg.rubrics.addRow);
	$('form .elgg-rubric .elgg-rubrics-remove-row').live('click', elgg.rubrics.removeRow);

	$('form .elgg-rubric .elgg-rubrics-add-column').live('click', elgg.rubrics.addColumn);
	$('form .elgg-rubric .elgg-rubrics-remove-column').live('click', elgg.rubrics.removeColumn);
}

elgg.rubrics.removeRow = function(e, el, a, b) {
	console.log(e, el, a, b);
}

/**
 * Removes columns
 */
elgg.rubrics.addColumn = function(e) {
	var $this = $(this);
	var $table = $this.parents('table');

	// insert the content of the first TD before the last TD, then remove any input values
	// (the last TD has the control buttons)
	$table.find('tr').each(function(i, e) {
		var $orig = $('td:first', e);
		var newTD = $('td:last', e).before($orig.clone()).prev()

		$(':input', newTD).each(function(i, e) {
			$(e).val('');
		})
	});
}

/**
 * Removes a column
 */
elgg.rubrics.removeColumn = function(e) {
	var $td = $(this).parents('td');
	var index = elgg.rubrics.findTableIndex($td);
	var $table = $td.parents('table');
	var tdCount = $td.parent('tr').find('td').length;

	// don't let them delete all the cols
	if (tdCount <= 2) {
		elgg.register_error(elgg.echo('rubrics:cannot_delete_column'));
		return false;
	}

	$table.find('tr').each(function(i, e) {
		$(e).find('td:eq(' + index + ')').remove();
	});
}

/**
 * Removes a row
 */
elgg.rubrics.removeRow = function(e) {
	var $this = $(this);
	var $table = $(this).parents('table');
	var trCount = $table.find('tr').length;

	// don't let them remove all the rows
	// there are two rows of controls.
	if (trCount <= 3) {
		elgg.register_error(elgg.echo('rubrics:cannot_delete_row'));
		return false;
	}

	$this.parents('tr').remove();
}

/**
 * Adds a row
 */
elgg.rubrics.addRow = function(e) {
	$this = $(this);
	$tr = $this.parents('tr').prev('tr');

	$newTR = $tr.after($tr.clone()).next();
	$(':input', $newTR).each(function(i, e) {
		$(e).val('');
	})
}

/**
 * Find the index of a td element within a table handling colspans.
 *
 * Yoinked from: http://stackoverflow.com/questions/1166452/
 */
elgg.rubrics.findTableIndex = function(td) {
	$td = td;
	
	if (!$td.is('td') && !$td.is('th')) {
		return -1;
	}

	var allCells = $td.parent('tr').children();
	var normalIndex = allCells.index($td);
	var nonColSpanIndex = 0;

	allCells.each(function(i, item) {
		if (i == normalIndex) {
			return false;
		}

		var colspan = $(this).attr('colspan');
		colspan = colspan ? parseInt(colspan) : 1;
		nonColSpanIndex += colspan;
	});

	return nonColSpanIndex;
}

elgg.register_hook_handler('init', 'system', elgg.rubrics.init);