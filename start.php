<?php
/**
 * Share code snippets
 */

elgg_register_event_handler('init', 'system', 'snippets_init');

function snippets_init() {
	$item = new ElggMenuItem('snippets', elgg_echo('snippets'), 'snippets');
	elgg_register_menu_item('site', $item);
	elgg_register_page_handler('snippets', 'snippets_page_handler');

	elgg_register_entity_url_handler('object', 'snippet', 'snippets_get_url');
	elgg_register_entity_type('object', 'snippet');

	elgg_register_widget_type('snippets', elgg_echo('snippets:widget:title'), elgg_echo('snippets:widget:descr'));

	elgg_extend_view('js/elgg', 'snippets/js');
	elgg_extend_view('css/elgg', 'snippets/css');
	elgg_register_js('google:code:prettify', 'mod/snippets/vendors/google-code-prettify/prettify.js', 'footer');
	elgg_register_css('google:code:prettify', 'mod/snippets/vendors/google-code-prettify/prettify.css');

	$action_base = elgg_get_plugins_path() . 'snippets/actions/snippets';
	elgg_register_action('snippets/save', "$action_base/save.php");
	elgg_register_action('snippets/delete', "$action_base/delete.php");
}

function snippets_page_handler($segments) {
	$handler = new SnippetsPageHandler();
	return $handler->route($segments);
}

function snippets_get_url($entity) {
	$title = elgg_get_friendly_title($entity->title);
	$guid = $entity->getGUID();
	return elgg_normalize_url("snippets/view/$guid/$title");
}
