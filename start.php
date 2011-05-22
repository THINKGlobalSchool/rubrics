<?php
/**
 * Rubrics start.php
 * 
 * @package Rubrics
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.com/
 *
 * @todo The storage approach can be simplified by making the following changes. These would
 * require either backword compatibility in the models or an upgrade.
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
 *	delete views/default/rubrics/profile_link.php
 *	delete metatags view
 *	work out sticky forms for column info.
 *
 *	deprecate the river view using the old rubricbuilder name
 *	Is the rubric content running through elgg_echo() on purpose?
 *	Remove the edit option when viewing a revision
 *	Anything extending rubric/options should now extend the entity menu for rubric entities. (See
 *	how the fork menu is added.)
 *	Need a fork icon
 *	Better CSS padding
 *	Weird spaces in css in IE.
 *	Revision link on entity
 *	History page
 *	Language strings
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

	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'rubrics_owner_block_menu');

	// Profile menu hook is user_hover now
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'rubrics_user_hover_menu');
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'rubrics_add_fork_menu_item');
	
	// Extend options for favorites
	// @todo not supported in current 1.8
	//elgg_extend_view('rubric/options', 'favorites/form');
	
	elgg_register_page_handler('rubrics', 'rubrics_page_handler');
	elgg_register_entity_url_handler('object', 'rubric', 'rubrics_url_handler');

	$actions_root = "$plugin_root/actions/rubrics";
	elgg_register_action('rubrics/save', "$actions_root/save.php");
	elgg_register_action('rubrics/delete', "$actions_root/delete.php");
	elgg_register_action('rubrics/fork', "$actions_root/fork.php");
	elgg_register_action('rubrics/restore', "$actions_root/restore.php");

	// Add widget 
	add_widget_type('rubric', elgg_echo('rubrics'), elgg_echo('rubrics:widget:description'));
	
	// Register plugin hook to extend permissions checking to include write access
	register_plugin_hook('permissions_check', 'object', 'rubric_write_permission_check');
		
	// for search
	register_entity_type('object', 'rubric');
}

/**
 * Dispatcher for rubrics.
 *
 * URLs take the form of
 *  All rubrics:      rubrics/all
 *  User's rubrics:   rubrics/owner/<username>
 *  Friends' rubrics: rubrics/friends/<username>
 *  View rubric:      rubrics/view/<guid>/<title><?rev=<annotation_id>> Optional history
 *  New rubric:       rubrics/add/<container_guid> (container: user, group, parent)
 *  Edit rubric:      rubrics/edit/<guid>
 *  Group rubrics:    rubrics/group/<guid>/owner
 *
 * Titles are ignored
 *
 * @param array $page
 */
function rubrics_page_handler($page) {
	gatekeeper();
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
 * Populates the ->getUrl() method for rubrics
 *
 * @param ElggEntity entity
 * @return string rubric url
 */
function rubrics_url_handler($entity) {
	$user = get_entity($entity->owner_guid);

	// BP: this should never happen...?
	if (!$user) {
		// default to a standard view if no owner.
		return FALSE;
	}

	$title = elgg_get_friendly_title($entity->title);
	
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
 * Adds a rubrics entry to the user hover and profile menus.
 *
 * @param type $hook
 * @param type $type
 * @param type $return
 * @param type $params
 * @return array
 */
function rubrics_user_hover_menu($hook, $type, $return, $params) {
	$url = "rubrics/owner/{$params['entity']->username}";
	$item = new ElggMenuItem('rubrics', elgg_echo('rubrics'), $url);
	$return[] = $item;

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
	);

	if ($entity) {
		foreach (array_keys($values) as $field) {
			if (isset($entity->$field)) {
				$values[$field] = $entity->$field;
			}
		}
	}

	if ($revision_id) {
		$revision = get_annotation($revision_id);

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
		$sticky_values = elgg_get_sticky_values('file');
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
	if (elgg_instanceof($entity, 'object', 'rubric')) {
		$text = elgg_echo('rubrics:fork');
		$url = "action/rubrics/fork";
		$url = elgg_http_add_url_query_elements($url, array('guid' => $entity->getGUID()));
		$url = elgg_add_action_tokens_to_url($url);
		$item = ElggMenuItem::factory(array(
			'href' => $url,
			'name' => 'fork',
			'text' => $text,
			'link_class' => 'elgg-requires-confirmation'
		));

		$return[] = $item;
		return $return;
	}
}