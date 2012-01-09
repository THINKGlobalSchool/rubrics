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

	// Autosave
	setInterval(elgg.rubrics.saveDraft, 60000);
}

/**
 * Forward to the correct history when selected.
 */
elgg.rubrics.revisionPulldown = function(e) {
	$this = $(this);
	var guid = $this.parents('form').find('input[name=guid]').val();
	elgg.forward('rubrics/view/' + guid + '?rev_id=' + $(this).val());
}

/*
 * Attempt to save and update the input with the guid.
 */
elgg.rubrics.saveDraftCallback = function(data, textStatus, XHR) {
	if (textStatus == 'success' && data.success == true) {
		var form = $('form[name=rubrics]');

		// update the guid input element for new posts that now have a guid
		form.find('input[name=guid]').val(data.guid);

		var d = new Date();
		var mins = d.getMinutes() + '';
		if (mins.length == 1) {
			mins = '0' + mins;
		}
		$(".rubric-save-status-time").html(d.toLocaleDateString() + " @ " + d.getHours() + ":" + mins);
	} else {
		$(".rubric-save-status-time").html(elgg.echo('error'));
	}
}

elgg.rubrics.saveDraft = function() {
	// Make sure tinymce input has content
	if (typeof(tinyMCE) != 'undefined') {
		tinyMCE.triggerSave();
	}

	var form = $('form[name=rubrics]');
	var title = form.find('input[name=title]').val();

	if (!(title)) {
		return false;
	}

	var draftURL = elgg.config.wwwroot + "action/rubrics/auto_save_revision";
	var postData = form.serializeArray();

	// force draft status
	$(postData).each(function(i, e) {
		if (e.name == 'status') {
			e.value = 'draft';
		}
	});

	$.post(draftURL, postData, elgg.rubrics.saveDraftCallback, 'json');
}


elgg.register_hook_handler('init', 'system', elgg.rubrics.init);