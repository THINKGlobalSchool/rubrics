<?php
/**
* This file displays the access level, edit/delete buttons and other options on an individual rubric
**/
	 
global $CONFIG;
	
//grab some values
$rubric_acl = $vars['rubric_acl'];
$rubric = $vars['entity'];
$rubric_owner = get_user($rubric->owner_guid)->guid;
$owner = get_entity($rubric_owner)->name;
$latest = $rubric->getAnnotations('rubric', 1, 0, 'desc');
if ($latest) $latest = $latest[0];

//sort out the access level for display
$object_acl = get_readable_access_level($rubric->access_id);
//files with these access level don't need an icon
$general_access = array('Public', 'Logged in users', 'Friends');
//set the right class for access level display - need it to set on groups and shared access only
$is_group = get_entity($rubric->container_guid);
if($is_group instanceof ElggGroup){
	//get the membership type open/closed
	$membership = $is_group->membership;
	if($membership == 2)
		$access_level = "class='group_open'";
	else
		$access_level = "class='group_closed'";
}elseif($object_acl == 'Private'){
	$access_level = "class='private'";
}else{
	if(!in_array($object_acl, $general_access))
		$access_level = "class='shared_collection'";
	else
		$access_level = "class='generic_access'";
}

$get_annotations = get_annotations($rubric->guid, "object", "rubric", "rubric","", 0, 999);
$authors = array();//declare the array which will hold unique authors
//loop through all rubric authors
foreach($get_annotations as $ga){
	//only add authors once
	if(!in_array($ga->owner_guid, $authors))
		$authors[] = $ga->owner_guid;
}

//display all authors avatars
if($authors) {
	echo "<h3>Contributors</h3><div class='clearfloat'>";
	foreach($authors as $a){
		echo "<div class='contributor_icon'>".elgg_view("profile/icon",array('entity' => get_entity($a), 'size' => 'tiny'))."</div>";
	}
	echo "</div>";
} else { // if no authors to display

}
				
$time_updated = $latest->time_created;
$owner_guid = $latest->owner_guid;
$edit_owner = get_entity($owner_guid);
		
echo "<div class='latest_contribution clearfloat'>" . sprintf(elgg_echo("rubricbuilder:strapline"),
		friendly_time($time_updated),
		"<a href=\"" . $edit_owner->getURL() . "\">" . $edit_owner->name ."</a>"
) . "</div>";

$url = $vars['url'] . "pg/rubric/history/" . $rubric->guid;
echo " <a href=\"{$url}\">" . elgg_echo('rubricbuilder:history') . "</a> ";
echo "<a class='print_rubric' href=\"{$vars['url']}mod/rubricbuilder/endpoint/print.php?rubric={$rubric->guid}\" target='_blank'>" . elgg_echo('rubricbuilder:print') . "</a>";
	
//display the access level
echo "<div class='divider margin_top'><p class='margin_top'>".elgg_echo('rubric:access')."<br /><span {$access_level}><b>{$object_acl}</b></span></p></div>";
