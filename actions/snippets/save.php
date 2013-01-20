<?php
/**
 * Save a snippet
 */

elgg_make_sticky_form('snippets');

$title = get_input('title');
$code = get_input('description', '', false);
$access_id = get_input('access_id');
$container_guid = get_input('container_guid');
$guid = get_input('guid');
if (!$title || !$code) {
	register_error(elgg_echo('snippets:error:empty'));
	forward(REFERER);
}

$container = get_entity($container_guid);
if (!$container || !$container->canEdit()) {
	register_error(elgg_echo('snippets:error:permissions'));
	forward(REFERER);
}

$new = true;
if ($guid) {
	$entity = get_entity($guid);
	if (!$entity || !$entity->canEdit()) {
		register_error(elgg_echo('snippets:error:permissions'));
		forward(REFERER);
	}
	$new = false;
}

if ($new) {
	$entity = new ElggObject();
	$entity->subtype = 'snippet';
}
$entity->title = $title;
$entity->description = $code;
$entity->access_id = $access_id;
$guid = $entity->save();
if (!$guid) {
	register_error(elgg_echo('snippets:error:save'));
	forward(REFERER);
}

elgg_clear_sticky_form('snippets');

if ($new) {
	add_to_river('river/object/snippet/create', 'create', $entity->getOwnerGUID(), $entity->getGUID());
}

system_message(elgg_echo('snippets:success:save'));
forward($entity->getURL());
