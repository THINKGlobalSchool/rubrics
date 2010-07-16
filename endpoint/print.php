<?php

    /**
	 * Elgg print rubrics
	 */

    // Load Elgg engine will not include plugins
    require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
    
    //grab the rubric set over
    $rubric = get_input('rubric');
    $rubric_contents = get_entity($rubric);
    $title = $rubric_content->title;
	$description = $rubric_contents->description;
	$contents = $rubric_contents->getContents();
	$num_rows = $rubric_contents->getNumRows();
	$num_cols = $rubric_contents->getNumCols();	
    // Build rubric table
	$rubric_table = "<table class='rubric_table'>";
	for ($i = 0; $i < $num_rows; $i++) {
		$rubric_table .= "<tr>";
		for ($j = 0; $j < $num_cols; $j++) {
				$input_class = 'rubric_td';
				// Zebra stripes
				if ($i % 2 == 0)
					$input_class .= " alt";

				if ($i == 0) {
					$rubric_table .= "<td valign='top' style='height: 15px;' class='$input_class rubric_header'><p>";
					$rubric_table .= elgg_view('output/text', array('internalname' => $i . '|' . $j, 'value' => elgg_echo($contents[$i][$j])));
				} else {
					$rubric_table .= "<td valign='top' class='$input_class'><p>";
			    	$rubric_table .=  elgg_view('output/text', array('internalname' => $i . '|' . $j, 'value' => elgg_echo($contents[$i][$j])));
				}
				$rubric_table .= "&nbsp;</p></td>";
			}
		$rubric_table .= "</tr>";
	} 
	$rubric_table .= "</table><br />";
  
?>
<style>
body {
	font-size: 12pt;
	width: auto;
	margin: 0 5%;
	padding: 0;
	border: 0;
	float: none;
	color: black;
	background: white;
	font-family:'Lucida Grande', 'Lucida Sans Unicode', Lucida, Arial, Helvetica, sans-serif;
}
a:link, a:visited {
	background: transparent;
	font-weight: bold;
	text-decoration: underline;
}
#content a:link:after, #content a:visited:after {
	content: " (" attr(href) ") ";
	font-size: 90%;
}
h1 {
	margin-top:10px;
	padding-top: 0.5em;
	padding-bottom: 0.5em;
	border-top: 1px solid #cccccc;
	border-bottom: 1px solid #cccccc;
}

.rubric_div {
	color: #000000;
}

.singleview {
	margin-top:10px;
}

.rubric_icon {
	float:left;
	margin:3px 0 0 0;
	padding:0;
}

.rubric_description img[align="left"] {
	margin: 10px 10px 10px 0;
	float:left;
}

textarea.rubric_input, input.rubric_input {
	width: 119px;
	height: 80px;
	padding: 5px;
	border: 1px solid #dddddd;
	font-family: Tahoma, sans-serif;
	-moz-border-radius:5px 5px 5px 5px;
	-webkit-border-radius: 5px 5px 5px 5px;
	border-radius: 5px;
	font-size: 90%;
}

td.rubric_td {
	width: 119px;
	height: 100;
	padding: 5px;
	margin-top: 10px;
	//border: 1px solid #eeeeee;
	font-family: Tahoma, sans-serif;
	border-radius: 5px;
	-moz-border-radius:5px 5px 5px 5px;
	-webkit-border-radius: 5px 5px 5px 5px;
}

textarea.alt, td.alt {
	background: #eeeeee;
}

input.rubric_header, td.rubric_header {
	font-weight: bold;
	text-align: center;
	background: #bbdaf7;
	border: 1px solid #bbdaf7;
	width: 119px;
	-moz-border-radius:5px 5px 5px 5px;
	-webkit-border-radius: 5px 5px 5px 5px;
	border-radius: 5px;
	height: 15px;
}

td.rubric_col {
	border-top: 1px solid red;
	border-left: 1px solid blue;
}

td.rubric_col_last {
	border-bottom: 1px solid red;
	border-right: 1px solid blue;
}

table.rubric_table {
	width: 98%;	
	border-spacing: 1px;
}


table.rubric_table td {
	height: 80px;
	valign:top;
}

#rubric .tags {
    padding:0 0 0 16px;
    margin:10px 0 4px 0;
	background:transparent url(<?php echo $vars['url']; ?>_graphics/icon_tag.gif) no-repeat scroll left 2px;
}

#rubric .strapline {
    text-align:right;
    border-top:1px solid #efefef;
    margin:10px 0 10px 0;
    color:#666666;
}
#rubric .categories {
    border:none !important;
    padding:0 !important;
}


.rubric_icon {
	float:left;
	margin:3px 0 0 0;
	padding:0;
}

.rubric h3 {
	font-size: 150%;
	margin:0 0 10px 0;
	padding:0;
}

.rubric h3 a {
	text-decoration: none;
}

.rubric p {
	margin: 0 0 5px 0;
}

.rubric .strapline {
	margin: 0 0 0 35px;
	padding:0;
	color: #aaa;
	line-height:1em;
}

.rubric .listingstrapline {
	margin: 0 0 0 0px;
	padding:0;
	color: #aaa;
	line-height:1em;
}

.rubric p.tags {
	background:transparent url(<?php echo $vars['url']; ?>_graphics/icon_tag.gif) no-repeat scroll left 2px;
	margin:0 0 7px 35px;
	padding:0pt 0pt 0pt 16px;
	min-height:22px;
}

.rubric p.listingtags {
	background:transparent url(<?php echo $vars['url']; ?>_graphics/icon_tag.gif) no-repeat scroll left 2px;
	margin:0 0 0 0;
	padding:0pt 0pt 0pt 16px;
	min-height:22px;
}

.rubric p.gallerytags {
	background:transparent url(<?php echo $vars['url']; ?>_graphics/icon_tag.gif) no-repeat scroll left 2px;
	margin:0 0 0 0;
	padding:0pt 0pt 0pt 16px;
	min-height:22px;
	text-align: left;
}

.rubric .controls {
	margin-top: 5px;
}

.rubric .options {
	margin:0;
	padding:0;
}

.rubric_body img[align="left"] {
	margin: 10px 10px 10px 0;
	float:left;
}
.rubric_body img[align="right"] {
	margin: 10px 0 10px 10px;
	float:right;
}
.rubric_body img {
	margin: 10px !important;
}

</style>
 	
<?php 
	echo "<h1>{$rubric_contents->title}</h1>";
	echo "<p>{$rubric_contents->description}</p>";
	echo $rubric_table;
?>