<?php
/*
 * Css for rubric
 */

$rubrics_url = basename(dirname(dirname(dirname(dirname(__FILE__)))));
$rubrics_url = elgg_normalize_url("mod/$rubrics_url");
$light_grey = '#f9f9f9';
$dark_grey = '#f3f3f3';

?>

.middle {
	vertical-align: middle;
}

.elgg-rubrics-icon {
	opacity:0.2;
	filter: alpha(opacity=20);
	width: 20px;
	height: 20px;
	background-color: #000000;
	display: inline-block;
}

.elgg-rubrics-icon:hover {
	cursor: pointer;
	opacity: 1;
	filter: alpha(opacity=100);
}

.elgg-rubrics-icon-minus {
	background-image: url("<?php echo "$rubrics_url/images/minus.gif"; ?>");
}

.elgg-rubrics-icon-plus {
	background-image: url("<?php echo "$rubrics_url/images/plus.gif"; ?>");
}

table.elgg-rubric {
	width: 100%;
	table-layout: fixed;
}

table.elgg-rubric textarea,
table.elgg-rubric input,
table.elgg-rubric p {
	width: 100%;
	font-family: Tahoma, sans-serif;
	font-size: 90%;

	border: 1px solid <?php echo $light_grey; ?>;
	border-radius: 5px;
	-moz-border-radius:5px;
	-webkit-border-radius: 5px;
}

table.elgg-rubric textarea:focus, table.elgg-rubric input:focus {
	border: solid 1px #4690D6;
	background-color: #E4ECF5;
}

table.elgg-rubric textarea,
table.elgg-rubric p {
	min-height: 100px;
	vertical-align: top;
}

table.elgg-rubric p.elgg-rubrics-header {
	height: auto;
}

input.elgg-rubrics-header,
p.elgg-rubrics-header {
	font-weight: bold;
	text-align: center;
	background: #cacaca;
	border: 1px solid white;
}

table.elgg-rubric td {
	padding: 5px;
	margin: 3px;
	border-right: 2px solid #fff;
	border-bottom: 2px solid #fff;
}

td.elgg-rubrics-control {
	width: 30px;
}



/* ie doesn't support nth-child and freaks if we combine the rules. */
table.elgg-rubric tr.elgg-rubrics-odd textarea,
table.elgg-rubric tr.elgg-rubrics-odd p {
	background-color: <?php echo $dark_grey; ?>;
}

table.elgg-rubric tr:nth-child(even) textarea,
table.elgg-rubric tr:nth-child(even) p {
	background-color: <?php echo $light_grey; ?>;
}

table.elgg-rubric tr:nth-child(odd) textarea:focus {
	background-color: #E4ECF5;
}


/* display overrides */
/* ie doesn't support nth-child and freaks if we combine the rules. */
table.elgg-rubric-display tr.elgg-rubrics-even {
	background-color: <?php echo $light_grey; ?>;
}
table.elgg-rubric-display tr:nth-child(odd) {
	background-color: <?php echo $dark_grey; ?>;
}

table.elgg-rubric-display td {
	padding: 1px;
}

table.elgg-rubric-display p {
	border: 0;
	border-radius: 0;
	-moz-border-radius: 0;
	-webkit-border-radius: 0;
}

table.elgg-rubric-display p.elgg-rubrics-header {
	border-radius: 3px;
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	min-height: 25px;
}

#elgg-rubric-history table {
	margin: auto;
}



<?php
return true;
?>







.singleview {
	margin-top:10px;
}

textarea.rubric-input, input.rubric-input {
	width: 127px;
	height: 80px;
	padding: 5px;
	border: 1px solid #dddddd;
	font-family: Tahoma, sans-serif;
	-moz-border-radius:5px 5px 5px 5px;
	-webkit-border-radius: 5px 5px 5px 5px;
	border-radius: 5px;
	font-size: 90%;
}

td.rubric-cell {
	height: 80px;
	padding: 5px;
	margin-top: 10px;
	font-family: Tahoma, sans-serif;
	border: 1px solid white;
	border-radius: 5px;
	-moz-border-radius:5px 5px 5px 5px;
	-webkit-border-radius: 5px 5px 5px 5px;
}

textarea.alt, td.alt {
	background: #eeeeee;
}

input.rubric-header, td.rubric-header {
	font-weight: bold;
	text-align: center;
	background: #bbbbbb;
	border: 1px solid white;
	width: 127px;
	height: 15px;
	font: 100%;
}


table.rubric-table {
	width: 100%;	
}

#rubric {
	border-bottom: 1px solid #cccccc;
	margin-bottom: 10px;
}

.rubric .entity_listing_icon img {
	width: 40px;
	height: 40px;
}

.remove-button {
	opacity:0.2;
	filter:alpha(opacity=20);
	width: 20px;
	height: 20px;
	background-color: #000000;
	background-image: url("<?php echo $vars['url'] . "mod/rubricbuilder/images/minus.gif"; ?>");
}

.remove-button:hover {
	cursor: pointer;
	opacity:1;
	filter:alpha(opacity=100);
	width: 20px;
	height: 20px;
	background-color: #000000;
	background-image: url("<?php echo $vars['url'] . "mod/rubricbuilder/images/minus.gif"; ?>");
}

div#rubric-revision-menu {
	display: none;
	width: 100%;
}

div#rubric-revision-menu table#rubric-revision-menu-table {
	width: 100%;
}

table#rubric-revision-menu-table td.rubric-revision-description {
	text-align: center;
	width: auto;
	vertical-align: middle;
}

table#rubric-revision-menu-table td.rubric-revision-previous {
	text-align: right;
	width: 35%;
}


table#rubric-revision-menu-table td.rubric-revision-select {
	text-align: center;
	width: auto;
	margin-left: auto;
	margin-right: auto;
	vertical-align: middle;
}


table#rubric-revision-menu-table td.rubric-revision-next {
	text-align: left;
	width: 35%;
}

/* For the river! (Not sure if this still works) */

.river_object_rubric_create {
	background: url(<?php echo $vars['url']; ?>mod/rubricbuilder/images/rubric_river.gif) no-repeat left -1px;
}
.river_object_rubric_comment {
	background: url(<?php echo $vars['url']; ?>mod/rubricbuilder/images/rubric_river.gif) no-repeat left -1px;
}
.river_object_rubric_update {
	background: url(<?php echo $vars['url']; ?>mod/rubricbuilder/images/rubric_river.gif) no-repeat left -1px;
}

