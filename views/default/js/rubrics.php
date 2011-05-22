<?php
/**
 * The JS for displaying rubrics.
 */
?>

//<script>
elgg.provide('elgg.rubrics');

/**
 * bind JS events
 */
elgg.rubrics.init = function() {
	// add specific classes to help IE since it doesn't support nth-child
	// use the same selector as the CSS to keep 0 and 1 based indexes straight.
	$('table.elgg-rubric tr:nth-child(odd)').addClass('elgg-rubrics-odd');
	$('table.elgg-rubric tr:nth-child(even)').addClass('elgg-rubrics-even');

	// dropdown to select revision
	$('select.elgg-rubrics-revision').live('change', elgg.rubrics.revisionPulldown);
}

elgg.rubrics.revisionPulldown = function(e) {
	$this = $(this);
	var guid = $this.parents('form').find('input[name=guid]').val();
	elgg.forward('rubrics/view/' + guid + '?rev_id=' + $(this).val());
}

elgg.register_hook_handler('init', 'system', elgg.rubrics.init);