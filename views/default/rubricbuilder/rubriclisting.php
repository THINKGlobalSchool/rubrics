<?php
/**
 * Rubric listing view
 * 
 * @package RubricBuilder
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 * 
 */

if (isset($vars['entity']) && $vars['entity'] instanceof Rubric) {
					
	$url = $vars['entity']->getURL();
	$owner = $vars['entity']->getOwnerEntity();
	$canedit = $vars['entity']->canEdit();
	//sort out the access level for display
	$object_acl = get_readable_access_level($vars['entity']->access_id);
	//pages with these access level don't need an icon
	$general_access = array('Public', 'Logged in users', 'Friends');
	//set the right class for access level display - need it to set on groups and shared access only
	$is_group = get_entity($vars['entity']->container_guid);
	if($is_group instanceof ElggGroup){
		//get the membership type open/closed
		$membership = $is_group->membership;
		//we decided to show that the item is in a group, rather than its actual access level
		$object_acl = "Group: " . $is_group->name;
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
		
	$can_delete = false;
		
	if (($vars['user']->getGUID() == $vars['entity']->owner_guid) || ($vars['user']->admin || $vars['user']->isAdmin())) {
		$can_delete = true;
	}
		
	$icon = elgg_view(
			"graphics/icon", array(
			'entity' => $vars['entity'],
			'size' => 'small',
		  )
		);
		
	$comments_on = $vars['entity']->comments_on;
		
	if ($comments_on){
        //get the number of comments
    	$num_comments = elgg_count_comments($vars['entity']);
	} 
		
	$info = "<div class='entity_metadata'><span {$access_level}>" . $object_acl . "</span>";
	// view for plugins to extend	
	$info .= elgg_view('rubric/options', array('entity' => $vars['entity']));
	$info .= elgg_view_likes($vars['entity']);			
	// include edit and delete options
	if ($vars['entity']->canEdit()) {
		$info .= "<span class='entity_edit'><a href=\"{$vars['url']}pg/rubric/edit/{$vars['entity']->getGUID()}\">" . elgg_echo('edit') . "</a></span>";
		$info .= "<span class='delete_button'>" . elgg_view('output/confirmlink',array('href' => $vars['url'] . "action/rubric/delete?rubric_guid=" . $vars['entity']->getGUID(), 'text' => elgg_echo("delete"),'confirm' => elgg_echo("rubricbuilder:deleteconfirm"))). "</span>";  
	}
		
	$info .= "</div>";
		
	$info .= "<p class='entity_title'><a href=\"{$vars['entity']->getURL()}\">{$vars['entity']->title}</a></p>";
	
	$info .= "<p class='entity_subtext'>" . elgg_echo('pages:updatedby') . " <a href=\"{$vars['url']}pg/pages/owned/{$owner->username}\">{$owner->name}</a> {$friendlytime}";
	
	// get the number of comments
	$numcomments = elgg_count_comments($vars['entity']);
	if ($numcomments) {
		$info .= ", <a href=\"{$vars['entity']->getURL()}\">" . sprintf(elgg_echo("comments")) . " (" . $numcomments . ")</a>";
	}
	$info .= "</p>";
	$icon = elgg_view("profile/icon",array('entity' => $owner, 'size' => 'tiny'));
	//display
	echo elgg_view_listing($icon, $info);
}else{
	echo "<p class='margin_top'>".elgg_echo('rubric:none')."</p>";
}