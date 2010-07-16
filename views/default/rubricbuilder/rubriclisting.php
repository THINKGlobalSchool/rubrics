<?php

	/**
	 * Rubric full view
	 * 
	 * @package RubricBuilder
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Jeff Tilson
	 * @copyright THINK Global School 2010
	 * @link http://www.thinkglobalschool.com/
	 * 
	 * @uses $vars['entity'] Optionally, the rubric to view
	 */
	
		if (isset($vars['entity']) && $vars['entity'] instanceof Rubric) {
			
			$url 		= $vars['entity']->getURL();
			$owner 		= $vars['entity']->getOwnerEntity();
			$canedit 	= $vars['entity']->canEdit();
			$rubric 	= $vars['entity'];
		
			$description 	= $vars['entity']->description;

			// Are we allowed to delete?
			$can_delete = false;
			if (($vars['user']->getGUID() == $rubric->owner_guid) || ($vars['user']->admin || $vars['user']->siteadmin)) {
				$can_delete = true;
			}
			
			// Views/Content
			$icon = elgg_view(
					"graphics/icon", array(
					'entity' => $vars['entity'],
					'size' => 'small',
				  )
				);
			$linked_title = "<a href=\"{$rubric->getURL()}\" title=\"" . htmlentities($rubric->title) . "\">{$rubric->title}</a>";
			$owner_link = "<a href='{$vars['url']}pg/rubric/{$owner->username}/'>{$owner->name}</a>";
			$author_text = sprintf(elgg_echo('blog:author_by_line'), $owner_link);
			$tags = elgg_view('output/tags', array('tags' => $vars['entity']->tags));
		
			$date = friendly_time($rubric->time_created);

			// If comments on build link
			if ($rubric->comments_on != 'Off') {
				$comments_count = elgg_count_comments($rubric);
				$comments_link = "<a href=\"{$rubric->getURL()}#annotations\">" . sprintf(elgg_echo("comments"), $comments_count) . '</a>';
			} else {
				$comments_link = '';
			}

			// Options menu
			$options = "";
			// Only show editing options if we're not viewing a revision
			if (!$rev || $current_revision == $count) {
				if ($canedit) {
					$revision = $rev ? "?rev=$rev"  : "";
					$options .= "<span class='entity_edit'><a href={$vars['url']}pg/rubric/{$vars['user']->username}/edit/{$vars['entity']->getGUID()}$revision>" . elgg_echo("edit") . "</a></span>";
				} 
				
				$options .= "<span class='entity_edit'>" . elgg_view("output/confirmlink", array(
							'href' => $vars['url'] . "action/rubric/fork?rubric_guid=" . $vars['entity']->getGUID(),
							'text' => elgg_echo('rubricbuilder:fork'),
							'confirm' => elgg_echo('rubricbuilder:forkconfirm'),
							)) . "</span>";
					
				if ($canedit && $can_delete) {
					if ($can_delete) {								
						$delete_url = "{$vars['url']}action/rubric/delete?rubric_guid={$rubric->getGUID()}";
						$delete_link = "<span class='delete_button'>" . elgg_view('output/confirmlink', array(
							'href' => $delete_url,
							'text' => elgg_echo('delete'),
							'confirm' => elgg_echo('deleteconfirm')
						)) . "</span>";
						
						$options .= $delete_link;
					}
					// include a view for plugins to extend
					$options = elgg_view("rubric/options", array("object_type" => 'rubric', 'entity' => $rubric)) . elgg_view_likes($rubric) . $options;
				}
			} 
			
			echo <<<EOT
				<div class="rubric entity_listing clearfloat">
					<div class="entity_listing_icon">
						$icon
					</div>
					<div class="entity_listing_info">
						<div class="entity_metadata">$options</div>
						<p class="entity_title">$linked_title</p>
						<p class="entity_subtext">
							$author_text
							$date
							$comments_link
						</p>
						<p class="tags">$tags</p>
					</div>
				</div>
EOT;

		} else {
			
			$url = 'javascript:history.go(-1);';
			$owner = $vars['user'];
			$canedit = false;
			
		}
?>