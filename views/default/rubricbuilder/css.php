<?php
/*
 * Css for rubric
 */
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

