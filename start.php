<?php
/**
 * Rubrics start.php
 * 
 * @package Rubrics
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.com/
 *
 * @todo The storage approach can be simplified by making the following changes. These would
 * require either backword compatibility in the models or an upgrade script.
 *
 * Old version (Rubric entity):
 *	->num_rows and ->num_cols stored dimension
 *	->contents stored headers and data
 *
 * Old version (revision annotation):
 *	->rows and ->cols stored dimension
 *  ->contents stored headers and data
 *
 * New version (Rubric entity):
 *	->headers stores the header information
 *  ->data stores the actual rubric information
 *
 * New version (revision annotation):
 *	->headers stores the header information
 *	->data stores the rubric data
 *
 *
 * @todo
 *	icon overrides
 *
 *	deprecate the river view using the old rubricbuilder name
 *	Is the rubric content running through elgg_echo() on purpose?
 *	Anything extending rubric/options should now extend the entity menu for rubric entities. (See
 *	how the fork menu is added.)
 *	Need a fork icon
 *	Better CSS padding
 *	Weird spaces in css in IE.
 *	Widgets?
 */

elgg_register_event_handler('init', 'system', 'rubrics_init');

/**
 * Rubric builder initialisation
 */
function rubrics_init() {
	$plugin_root = dirname(__FILE__);

	// js for forms is only needed on those pages.
	$url = elgg_get_simplecache_url('js', 'rubrics_forms');
	elgg_register_js('rubrics:forms', $url);

	// js for viewing.
	elgg_extend_view('js/elgg', 'js/rubrics');
	elgg_extend_view('css/elgg', 'rubrics/css');

	// menus
	elgg_register_menu_item('site', array(
		'name' => 'rubrics',
		'text' => elgg_echo('rubrics'),
		'href' => 'rubrics'
	));

	// menus
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'rubrics_owner_block_menu');
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'rubrics_add_fork_menu_item');
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'rubrics_add_draft_menu_item');
	elgg_register_plugin_hook_handler('prepare', 'menu:entity', 'rubrics_remove_edit_menu_item');
	elgg_register_plugin_hook_handler('entity:icon:url', 'object', 'rubrics_icon_url_override');

	// Register rubrics as a group copyable subtype
	elgg_register_plugin_hook_handler('cangroupcopy', 'entity', 'rubrics_can_group_copy_handler');
	elgg_register_plugin_hook_handler('allowedgroupcopy', 'entity', 'rubrics_allowed_group_copy_handler');
	elgg_register_plugin_hook_handler('groupcopyaction', 'entity', 'rubrics_group_copy_action_handler');
	
	elgg_register_page_handler('rubrics', 'rubrics_page_handler');

	// Url handler 
	elgg_register_plugin_hook_handler('entity:url', 'object', 'rubrics_url_handler');

	$actions_root = "$plugin_root/actions/rubrics";
	elgg_register_action('rubrics/save', "$actions_root/save.php");
	elgg_register_action('rubrics/auto_save_revision', "$actions_root/auto_save_revision.php");
	elgg_register_action('rubrics/delete', "$actions_root/delete.php");
	elgg_register_action('rubrics/fork', "$actions_root/fork.php");
	elgg_register_action('rubrics/restore', "$actions_root/restore.php");

	// notifications
	elgg_register_notification_event('object', 'rubric', array('create'));
	elgg_register_plugin_hook_handler('prepare', 'notification:create:object:rubric', 'rubrics_prepare_notification');

	// Groups support
	add_group_tool_option('rubrics', elgg_echo('rubrics:enablegroup'), true);
	elgg_extend_view('groups/tool_latest', 'rubrics/group_rubrics');

	// Register plugin hook to extend permissions checking to include write access
	elgg_register_plugin_hook_handler('permissions_check', 'object', 'rubric_write_permission_check');
		
	// for search
	elgg_register_entity_type('object', 'rubric');
}

/**
 * Dispatcher for rubrics.
 *
 * URLs take the form of
 *  All rubrics:      rubrics/all
 *  User's rubrics:   rubrics/owner/<username>
 *  Friends' rubrics: rubrics/friends/<username>
 *  View rubric:      rubrics/view/<guid>/<title><?rev=<annotation_id>> Optional revision
 *  New rubric:       rubrics/add/<container_guid> (container: user, group, parent)
 *  Edit rubric:      rubrics/edit/<guid>
 *  Group rubrics:    rubrics/group/<guid>/owner
 *	Rubric history:   rubrics/history/<guid>/<title>
 *
 * Titles are ignored
 *
 * @param array $page
 */
function rubrics_page_handler($page) {
	elgg_push_context('rubrics');
	elgg_push_breadcrumb(elgg_echo('rubrics'), 'rubrics');

	$pages = dirname(__FILE__) . '/pages/rubrics';

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	switch ($page[0]) {
		case "all":
			include "$pages/all.php";
			break;

		case "owner":
			include "$pages/owner.php";
			break;

		case "friends":
			include "$pages/friends.php";
			break;

		case "view":
			set_input('guid', elgg_extract(1, $page, null));
			include "$pages/view.php";
			break;

		case "add":
			include "$pages/add.php";
			break;

		case "edit":
			set_input('guid', elgg_extract(1, $page, null));
			include "$pages/edit.php";
			break;

		case 'group':
			group_gatekeeper();
			include "$pages/owner.php";
			break;

		case "history":
			set_input('guid', elgg_extract(1, $page, null));
			include "$pages/history.php";
			break;

		default:
			return false;
	}

	elgg_pop_context();

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
function rubric_write_permission_check($hook, $entity_type, $returnvalue, $params) {
	if ($params['entity']->getSubtype() == 'rubric') {
	
		$write_permission = $params['entity']->write_access_id;
		$user = $params['user'];
		
		if (($write_permission !== null) && ($user)) {
			$list = get_access_array($user->guid);
			if (($write_permission != 0) && (in_array($write_permission, $list))) {
				return true;
			} else if ($write_permission == -2 && ($user)) {
				if ($user->isFriendOf($params['entity']->getOwnerGUID())) {
					return true;
				}
			}
		}
	}
}

/**
 * Returns the URL from a rubric entity
 *
 * @param string $hook   'entity:url'
 * @param string $type   'object'
 * @param string $url    The current URL
 * @param array  $params Hook parameters
 * @return string
 */
function rubrics_url_handler($hook, $type, $url, $params) {
	$entity = $params['entity'];

	// Check that the entity is a rubric object
	if (!elgg_instanceof($entity, 'object', 'rubric')) {
		return;
	}

	return "rubrics/view/{$entity->guid}/$title";
}

/**
 * Add a menu item to an ownerblock
 *
 * @param string $hook
 * @param string $type
 * @param array  $return
 * @param array  $params
 * @return array
 */
function rubrics_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user')) {
		$url = "rubrics/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('rubrics', elgg_echo('rubrics'), $url);
		$return[] = $item;
	} else {
		if ($params['entity']->rubrics_enable != 'no') {
			$url = "rubrics/group/{$params['entity']->guid}/owner";
			$item = new ElggMenuItem('rubrics', elgg_echo('rubrics:group'), $url);
			$return[] = $item;
		}
	}

	return $return;
}

/**
 * Prepares form values for the rubrics form.
 *
 * @param null|ElggEntity $entity      The entity to base values on.
 * @param null|int        $revision_id The id of a revision to load
 *
 * @return array
 */
function rubrics_prepare_form_vars($entity = null, $revision_id = null) {

	// input names => defaults
	$values = array(
		'title' => '',
		'description' => '',
		'access_id' => ACCESS_DEFAULT,
		'tags' => '',
		'container_guid' => elgg_get_page_owner_guid(),
		'guid' => null,
		'entity' => $entity,
		'comments' => 'On',
		'headers' => array(),
		'data' => array(),
		'write_access_id' => ACCESS_PRIVATE
	);

	if ($entity) {
		foreach (array_keys($values) as $field) {
			if (isset($entity->$field)) {
				$values[$field] = $entity->$field;
			}
		}
	}

	if ($revision_id) {
		$revision = elgg_get_annotation_from_id($revision_id);

		if ($revision) {
			$rev_values = unserialize($revision->value);
			if ($rev_values) {
				foreach ($values as $field => $value) {
					if (isset($rev_values->$field)) {
						$values[$field] = $rev_values->$field;
					}
				}
			}
		}
	}

	if (elgg_is_sticky_form('rubrics')) {
		$sticky_values = elgg_get_sticky_values('rubrics');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('rubrics');

	return $values;
}

/**
 * Adds the fork menu entry
 *
 * @param type $hook
 * @param type $type
 * @param ElggMenuItem $return
 * @param type $options
 * @return ElggMenuItem
 */
function rubrics_add_fork_menu_item($hook, $type, $return, $options) {
	$entity = elgg_extract('entity', $options);
	if (elgg_instanceof($entity, 'object', 'rubric') && !elgg_is_active_plugin('group-extender')) {
		$text = elgg_echo('rubrics:fork');
		$url = "action/rubrics/fork";
		$url = elgg_http_add_url_query_elements($url, array('guid' => $entity->getGUID()));
		$url = elgg_add_action_tokens_to_url($url);
		$item = ElggMenuItem::factory(array(
			'href' => $url,
			'name' => 'fork',
			'text' => $text,
			'data-confirm' => TRUE,
			'section' => 'core'
		));

		$return[] = $item;
	}

	return $return;
}

/**
 * Add a menu item to display rubric status (for auto saved drafts)
 *
 * @param type $hook
 * @param type $type
 * @param ElggMenuItem $return
 * @param type $options
 * @return ElggMenuItem
 */
function rubrics_add_draft_menu_item($hook, $type, $return, $options) {
	$entity = elgg_extract('entity', $options);
	if (elgg_instanceof($entity, 'object', 'rubric') && $entity->canEdit() && $entity->status == 'unsaved_draft') {
		$status_text = elgg_echo("rubrics:status:unsaved_draft");
		$options = array(
			'name' => 'rubric_draft_status',
			'text' => "<span>$status_text</span>",
			'href' => false,
			'priority' => 150,
			'section' => 'info'
		);
		$return[] = ElggMenuItem::factory($options);
	}

	return $return;
}

/**
 * Returns information about a rubric taking into consideration any revisions requested.
 *
 * @param mixed $rubric The Rubric object or a guid
 * @param mixed $rev_id The revision ElggAnnotation object or an annotation id
 *
 * @return array An array of the rubric's info.
 */
function rubrics_get_rubric_info($rubric, $revision = null) {
	if (is_numeric($rubric)) {
		$rubric = get_entity($rubric);
	}

	if (!elgg_instanceof($rubric, 'object', 'rubric')) {
		return false;
	}

	$info = array(
		'rubric' => $rubric
	);

	if (!$revision) {
		$info['title']       = $rubric->title;
		$info['description'] = $rubric->description;
		$info['contents']    = unserialize($rubric->contents);
		$info['num_rows']    = $rubric->num_rows;
		$info['num_cols']    = $rubric->num_cols;
		$info['revison']     = null;
	} else {
		if (is_numeric($revision)) {
			$revision = elgg_get_annotation_from_id($revision);
		}

		// Make sure we have an annotation object, and that it belongs to this rubric
		if (!$revision instanceof ElggAnnotation && $revision->entity_guid == $rubric->getGUID()) {
			return false;
		}

		$revision_info = unserialize($revision->value);

		$info['title']       = $revision_info['title'];
		$info['description'] = $revision_info['description'];
		$info['contents']    = unserialize($revision_info['contents']);
		$info['num_rows']    = $revision_info['rows'];
		$info['num_cols']    = $revision_info['cols'];
		$info['revision']    = $revision;
	}

	return $info;
}

/**
 * Override icon for rubrics
 *
 * @return string Relative URL
 */
function rubrics_icon_url_override($hook, $type, $value, $params) {
	$rubric = $params['entity'];
	$size = $params['size'];

	if (elgg_instanceof($rubric, 'object', 'rubric')) {
		switch ($size) {
			case 'large':
				$url = "mod/rubrics/images/rubric_lrg.gif";
				break;

			case 'medium':
			case 'small':
			default:
				$url = "mod/rubrics/images/rubric.gif";
				break;

			case 'tiny':
				$url = "mod/rubrics/images/rubric_river.gif";
				break;
		}

		return $url;
	}
}

/**
 * Uses headers and flat data arrays to build a matrix like:
 *
 * In: $headers = array(h1, h2, h3)
 * In: $data = array(v1, v2, v3, v4, v5, v6)
 *
 * Out:
 *	array(
 *		'num_rows' => int,
 *		'num_cols' => int,
 *		'contents' => array(
 *			array(h1, h2, h3),
 *			array(v1, v2, v3),
 *			array(v4, v5, v6)
 *		)
 *	)
 *
 * @param array $headers
 * @param array $data
 * @return array The above array
 */
function rubrics_get_matrix_info_from_input(array $headers, array $data) {
	// we know the # of headers so use that to generate offsets and limits
	$cols = count($headers);
	$contents = array($headers);

	$i = 0;
	$row = array();
	foreach($data as $item) {
		$row[] = $item;

		// count is 1-indexed
		if ($i == $cols - 1) {
			$contents[] = $row;
			$row = array();
			$i = 0;
		} else {
			$i++;
		}
	}

	return array(
		'num_cols' => count($headers),
		'num_rows' => count($contents),
		'contents' => $contents
	);
}

/**
 * Removes the edit entity menu item if we're looking at a revision.
 *
 * @param type $hook
 * @param type $type
 * @param type $value
 * @param type $params
 * @return array
 */
function rubrics_remove_edit_menu_item($hook, $type, $value, $params) {
	// only if we're on a revision
	$is_revision = elgg_extract('is_revision', $params, false);
	if (!$is_revision) {
		return null;
	}
	
	$entity = elgg_extract('entity', $params);
	$menu = $value;

	if (elgg_instanceof($entity, 'object', 'rubric')) {
		foreach ($value as $i => $menu) {
			foreach ($menu as $j => $item) {
				if ($item->getName() == 'edit') {
					unset($value[$i][$j]);
				}
			}
		}
		return $value;
	}
}

/**
 * Prepare a notification message about a new rubric
 *
 * @param string                          $hook         Hook name
 * @param string                          $type         Hook type
 * @param Elgg_Notifications_Notification $notification The notification to prepare
 * @param array                           $params       Hook parameters
 * @return Elgg_Notifications_Notification
 */
function rubrics_prepare_notification($hook, $type, $notification, $params) {
	$entity = $params['event']->getObject();
	$owner = $params['event']->getActor();
	$recipient = $params['recipient'];
	$language = $params['language'];
	$method = $params['method'];

	// Title for the notification
	$notification->subject = elgg_echo('rubrics:notification:subject');

    // Message body for the notification
	$notification->body = elgg_echo('rubrics:notification:body', array(
		$owner->name,
		$entity->title,
		$entity->description,
		$entity->getURL()
	), $language);

    // The summary text is used e.g. by the site_notifications plugin
    $notification->summary = elgg_echo('rubrics:notification:summary', array($entity->title), $language);

    return $notification;
}

/**
 * Register rubrics as a group copyable subtype
 *
 * @param string $hook
 * @param string $type
 * @param array  $value
 * @param array  $params
 * @return array
 */
function rubrics_can_group_copy_handler($hook, $type, $value, $params) {
	$value[] = 'rubric';
	return $value;
}


/**
 * Register rubrics group copy action
 *
 * @param string $hook
 * @param string $type
 * @param array  $value
 * @param array  $params
 * @return array
 */
function rubrics_group_copy_action_handler($hook, $type, $value, $params) {
	if ($params['entity']->getSubtype() == 'rubric') {
		$value = elgg_normalize_url('action/rubrics/fork');	
	}
	return $value;
}

/**
 * Register rubrics to be copied by anyone
 *
 * @param string $hook
 * @param string $type
 * @param array  $value
 * @param array  $params
 * @return array
 */
function rubrics_allowed_group_copy_handler($hook, $type, $value, $params) {
	if ($params['entity']->getSubtype() == 'rubric') {
		$value = elgg_is_logged_in() ? TRUE : FALSE;	
	}
	return $value;
}
