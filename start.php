<?php
	/**
	 * RubricBuilder start.php
	 * 
	 * @package RubricBuilder
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Jeff Tilson
	 * @copyright THINK Global School 2010
	 * @link http://www.thinkglobalschool.com/
	 * 
	 */

	/** Rubric builder initialisation **/
	function rubricbuilder_init() {
		
		
		global $CONFIG;
		include_once('lib/rubric.php');

		// Extend CSS
		elgg_extend_view('css','rubricbuilder/css');
		
		// Extend Metatags (for js)
		elgg_extend_view('metatags','rubricbuilder/metatags'); 
		
		// Extend profile_ownerblock
		elgg_extend_view('profile_ownerblock/extend', 'rubricbuilder/profile_link');
		
		// Register page handler
		register_page_handler('rubric','rubricbuilder_page_handler');
		
		// Set up url handler
		register_entity_url_handler('rubric_url','object', 'rubric');

		// Add rubrics to main menu
		add_menu('Rubrics', $CONFIG->wwwroot . 'pg/rubric/');

		// Event handler for submenus
		register_elgg_event_handler('pagesetup','system','rubricbuilder_submenus');

		// Register actions
		register_action('rubric/add', false, $CONFIG->pluginspath . 'rubricbuilder/actions/add.php');
		register_action('rubric/edit', false, $CONFIG->pluginspath . 'rubricbuilder/actions/edit.php');
		register_action('rubric/delete', false, $CONFIG->pluginspath . 'rubricbuilder/actions/delete.php');
		register_action('rubric/fork', false, $CONFIG->pluginspath . 'rubricbuilder/actions/fork.php');
		register_action('rubric/restore', false, $CONFIG->pluginspath . 'rubricbuilder/actions/restore.php');
				
		// Add widget 
		add_widget_type('rubric',elgg_echo('Rubrics'),elgg_echo('rubricbuilder:widget:description'));
		
		// Register an annotation handler for comments etc
		register_plugin_hook('entity:annotate', 'object', 'rubric_annotate_comments');
		
		// Register plugin hook to extend permissions checking to include write access
		register_plugin_hook('permissions_check', 'object', 'rubric_write_permission_check');
		
		// Profile hook	
		register_plugin_hook('profile_menu', 'profile', 'rubric_profile_menu');
		
	    // This operation only affects the db on the first call for this subtype
	    // If you change the class name, you'll have to hand-edit the db
		run_function_once("rubricbuilder_run_once");
		register_entity_type('object', 'rubric');	
	}
	
	
	/**
	* Rubricbuilder's Page Handler
	* 
	* @param array $page From the page_handler function
	* @return true|false Depending on success
	*
	*/
	function rubricbuilder_page_handler($page) {
		global $CONFIG;
		
		if (isset($page[0]) && !empty($page[0])) {
			$username = $page[0];

			// push breadcrumb
			elgg_push_breadcrumb(elgg_echo('rubricbuilder:allrubrics'), "{$CONFIG->site->url}pg/rubric");

			// forward away if invalid user.
			if (!$user = get_user_by_username($username)) {
				register_error(elgg_echo('rubricbuilder:error:unknown_username'));
				forward($_SERVER['HTTP_REFERER']);
			}

			set_page_owner($user->getGUID());
			$crumbs_title = sprintf(elgg_echo('rubricbuilder:owned_rubrics'), $user->name);
			$crumbs_url = "{$CONFIG->site->url}pg/rubric/$username";
			elgg_push_breadcrumb($crumbs_title, $crumbs_url);

			$action = isset($page[1]) ? $page[1] : FALSE;
			$page2 = isset($page[2]) ? $page[2] : FALSE;
			$page3 = isset($page[3]) ? $page[3] : FALSE;
			
			switch ($action) {
				case 'history':
					if ($page2) {
						set_input('rubric_guid', $page2);
						add_submenu_item(elgg_echo('rubricbuilder:label:view'), $CONFIG->url . "pg/rubric/{$user->username}/view/{$page2}", 'rubriclinks');
						include $CONFIG->pluginspath . 'rubricbuilder/pages/history.php';
					}
					break;
				case 'view':
					if ($page2) {
						set_input('rubric_guid', $page2);				
						if ($page3)
							set_input('rubric_revision', $page3);
							include($CONFIG->pluginspath . "rubricbuilder/pages/view.php");
					}
					break;
				case 'edit':
					if ($page2) {
						set_input('rubric_guid', $page2);
						include $CONFIG->pluginspath . 'rubricbuilder/pages/edit.php';
					}
					break;
				case 'new':
					include $CONFIG->pluginspath . 'rubricbuilder/pages/add.php';
					break;
				case 'friends':
					include $CONFIG->pluginspath . 'rubricbuilder/pages/friends.php';
					break;
				default:
					include $CONFIG->pluginspath . 'rubricbuilder/pages/index.php';
					break;
					
			}
			
		} else {
			include $CONFIG->pluginspath . 'rubricbuilder/pages/everyone.php';
		}
		
		return true;
	}
	
	/**
	 * Extend permissions checking to extend can-edit for write users.
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 */
	function rubric_write_permission_check($hook, $entity_type, $returnvalue, $params)
	{
		if ($params['entity']->getSubtype() == 'rubric') {
		
			$write_permission = $params['entity']->write_access_id;
			$user = $params['user'];
			
			if (($write_permission !== null) && ($user)) {
				$list = get_access_array($user->guid); // get_access_list($user->guid);
				if (($write_permission != 0) && (in_array($write_permission,$list))) {
					return true;
				} else if ($write_permission == -2 && ($user)) {
					if ($user->isFriendOf($params['entity']->getOwner())) {
						return true;
					}
				}
			}
		}
	}

	function rubricbuilder_submenus() {
		global $CONFIG;
		
		if (get_context() == 'rubric') {

		}
		
	}
	
	/**
	 * Hook into the framework and provide comments on rubric entities.
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 * @return unknown
	 */
	function rubric_annotate_comments($hook, $entity_type, $returnvalue, $params)
	{
		$entity = $params['entity'];
		$full = $params['full'];
		
		if (
			($entity instanceof ElggEntity) &&	// Is the right type 
			($entity->getSubtype() == 'rubric') &&  // Is the right subtype
			($entity->comments_on!='Off') && // Comments are enabled
			($full) // This is the full view
		)
		{
			// Display comments
			return elgg_view_comments($entity);
		}
		
	}
	
	/**
	 * Plugin hook to add rubrics to users profile block
	 * 	
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 * @return unknown
	 */
	function rubric_profile_menu($hook, $entity_type, $return_value, $params) {
		global $CONFIG;

		$return_value[] = array(
			'text' => elgg_echo('rubric'),
			'href' => "{$CONFIG->url}pg/rubric/{$params['owner']->username}",
		);

		return $return_value;
	}
	
	
	/**
	 * Populates the ->getUrl() method for rubrics
	 *
	 * @param ElggEntity entity
	 * @return string rubric url
	 */
	function rubric_url($entity) {
		global $CONFIG;
		
		if (!$user = get_entity($entity->owner_guid)) {
			// default to a standard view if no owner.
			return FALSE;
		}
		
		return $CONFIG->url . "pg/rubric/{$user->username}/view/{$entity->guid}/";
	}

	/** 
	* Runonce for rubrics
	* 
	* Registers the rubrics subtype
	*
	*/
	function rubricbuilder_run_once() {
		add_subtype('object', 'rubric', 'Rubric');
	}
	
	// Helpful debug function
	function print_r_html ($arr) {
	        ?><pre><?
	        print_r($arr);
	        ?></pre><?
	}


	
	register_elgg_event_handler('init', 'system', 'rubric_init');
	register_elgg_event_handler('init', 'system', 'rubricbuilder_init');
?>