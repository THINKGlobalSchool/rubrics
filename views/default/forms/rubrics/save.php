<?php
/**
 * Edit rubric form
 * 
 * @package Rubrics
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 * 
 */

elgg_load_js('rubrics:forms');

$entity = elgg_extract('entity', $vars);
$title = elgg_extract('title', $vars, '');
$description = elgg_extract('description', $vars, '');
$tags = elgg_extract('tags', $vars, '');
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
$container_guid = elgg_extract('container_guid', $vars, null);
$guid = elgg_extract('guid', $vars, null);
$write_access_id = elgg_extract('write_access_id', $vars, ACCESS_PRIVATE);

// sticky forms
$headers = elgg_extract('headers', $vars);
$data = elgg_extract('data', $vars);

// If entity exists, we're editing existing
if ($entity instanceof Rubric) {
	$contents = $entity->getContents();
} else {
	// use the builder to create default content
	$contents = Rubric::getDefaultContents();
}

// if the headers and data sticky values are set ned to override whatever is sent as content.
if ($headers && $data) {
	if ($info = rubrics_get_matrix_info_from_input($headers, $data)) {
		$contents = $info['contents'];
	}
}

// if this is still empty something is wrong. use defaults.
if (!$contents) {
	register_error(elgg_echo('rubrics:cannot_load'));
	$contents = Rubric::getDefaultContents();
}

// Get views for inputs
$title_label 			= elgg_echo('title');
$title_textbox 			= elgg_view('input/text', array('name' => 'title', 'value' => $title));
$description_label 		= elgg_echo('description');
$description_textbox 	= elgg_view('input/longtext', array('name' => 'description', 'value' => $description));
$rubric_label 			= elgg_echo('rubrics:title');
$tag_label 				= elgg_echo('tags');
$tag_input 				= elgg_view('input/tags', array('name' => 'tags', 'value' => $tags));
$write_access_label		= elgg_echo('Write Access');
$access_label 			= elgg_echo('access');

$write_access_input	= elgg_view('input/access', array('name' => 'write_access_id', 'value' => $write_access_id));
$access_input 		= elgg_view('input/access', array('name' => 'access_id', 'value' => $access_id));
$submit_input 		= elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('publish')));
$entity_hidden      = elgg_view('input/hidden', array('name' => 'guid', 'value' => $guid));
$container          = elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));

$publish 			= elgg_echo('publish');
$privacy 			= elgg_echo('access');
$allowcomments 		= elgg_echo('rubrics:comments:allow');
  

// num_rows is the number of trs / top level arrays
$num_rows = count($contents);

// num_cols is the number of tds in the first element (headers)
$num_cols = count($contents[0]);

$rubric_input = "<table class='elgg-rubric'>";
for ($i = 0; $i < $num_rows; $i++) {
	$rubric_input .= "<tr>";
	for ($j = 0; $j < $num_cols; $j++) {
		if ($i == 0) {
			// this is a header.
			$rubric_input .= "<td class=\"middle center elgg-rubrics-header\">";

			$icon = '<span class="mhm elgg-rubrics-icon elgg-rubrics-icon-minus"></span>';
			$rubric_input .= '<a class="elgg-rubrics-remove-column">' . $icon . '</a>';
			$rubric_input .= elgg_view('input/text', array(
				'name' => 'headers[]',
				'value' => elgg_echo($contents[$i][$j]),
				'class' => 'elgg-rubrics-header'
			));
		} else {
			// this is data
			$rubric_input .= "<td>";
	    	$rubric_input .=  elgg_view('input/plaintext', array(
				'name' => 'data[]',
				'value' => elgg_echo($contents[$i][$j]),
			));
		}

		$rubric_input .= "</td>";
	}

	// add controls
	if ($i == 0) {
		// if a header, add a new column button
		$icon = '<span class="mhm elgg-rubrics-icon elgg-rubrics-icon-plus"></span>';
		$link = '<a class="elgg-rubrics-add-column">' . $icon . '</a>';
		$rubric_input .= "<td class=\"center top elgg-rubrics-control\">$link</td>";
	} else {
		// if a row, add a delete row button
		$icon = '<span class="mhm elgg-rubrics-icon elgg-rubrics-icon-minus"></span>';
		$link = '<a class="elgg-rubrics-remove-row">' . $icon . '</a>';
		$rubric_input .= "<td class=\"center middle elgg-rubrics-control\">$link</td>";
	}

	$rubric_input .= "</tr>";
}

// the add row control
$rubric_input .= '<tr>';
for ($i = 0; $i < $num_cols; $i++) {
	$rubric_input .= '<td>&nbsp;</td>';
}

$icon = '<span class="mhm elgg-rubrics-icon elgg-rubrics-icon-plus"></span>';
$link = '<a class="elgg-rubrics-add-row">' . $icon . '</a>';
$rubric_input .= "<td class=\"center top\">$link</td>";

$rubric_input .= "</tr></table>";

$save_status = elgg_echo('rubrics:save_status');
if ($entity) {
	$saved = date('F j, Y @ H:i', $entity->time_updated);
} else {
	$saved = elgg_echo('rubric:never');
}

// Build form body
$form_body = <<<HTML
		<div class='margin_top'>
			<label>$title_label</label><br />
            $title_textbox
		</div>
		<div>
			<label>$description_label</label><br />
            $description_textbox
		</div>
		<div>
			<label>$rubric_label</label><br />
			$rubric_input
		</div>
		<div>
			<label>$tag_label</label><br />
			$tag_input
		</div>
		<div>
			<label>$access_label</label><br />
			$access_input
		</div>
		<div>
			<label>$write_access_label</label><br />
			$write_access_input
		</div>
		<div class="elgg-foot">
			<div class="elgg-subtext mbm">
			$save_status <span class="rubric-save-status-time">$saved</span>
			</div>
			$entity_hidden
			$container
			$submit_input
		</div>
HTML;

echo $form_body;