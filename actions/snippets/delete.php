<?php
/**
 * Delete snippet action
 */

$guid = get_input('guid');
$entity = get_entity($guid);

if (elgg_instanceof($entity, 'object', 'snippet') && $entity->canEdit()) {
	$container = $entity->getContainerEntity();
	if ($entity->delete()) {
		system_message(elgg_echo('snippet:success:delete'));
		if (elgg_instanceof($container, 'group')) {
			forward("snippets/group/$container->guid/all");
		} else {
			forward("snippets/owner/$container->username");
		}
	}
}

register_error(elgg_echo('snippets:error:delete'));
forward(REFERER);
