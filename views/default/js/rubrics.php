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
	// use the same selector as the CSS to keep 0 or 1 based indexes straight.
	$('table.elgg-rubric tr:nth-child(odd)').addClass('elgg-rubrics-odd');
	$('table.elgg-rubric tr:nth-child(even)').addClass('elgg-rubrics-even');
}

	elgg.register_hook_handler('init', 'system', elgg.rubrics.init);