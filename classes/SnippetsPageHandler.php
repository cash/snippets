<?php
/**
 * Page handler for Snippets plugin
 *
 * Routes:
 * /snippets/all
 * /snippets/owner/<username>
 * /snippets/friends/<username>
 * /snippets/add/<guid>
 * /snippets/edit/<guid>
 * /snippets/view/<guid>
 */
class SnippetsPageHandler {

	/**
	 * Route the request to the appropiate handler method
	 *
	 * @param array $segments URL segments
	 * @return bool
	 */
	public function route(array $segments) {
		$page = array_shift($segments);
		if (!$page) {
			$page = 'all';
		}

		$method = 'serve' . ucfirst($page);
		if (method_exists($this, $method)) {
			$params = $this->$method($segments);
			if ($params) {
				$this->render($params);
				return true;
			}
		}

		return false;
	}

	/**
	 * Render the page
	 *
	 * @param array $params See the content layout for parameters
	 * @return void
	 */
	protected function render($params) {
		$body = elgg_view_layout('content', $params);
		echo elgg_view_page($params['title'], $body);
	}

	protected function serveAdd($segments) {
		$guid = array_shift($segments);
		if (!$guid) {
			return false;
		}
		elgg_set_page_owner_guid($guid);

		$title = elgg_echo('snippets:add');
		elgg_push_breadcrumb(elgg_echo('snippets'), 'snippets');
		elgg_push_breadcrumb($title);

		$params = $this->prepareFormVars();
		return array(
			'title' => $title,
			'filter' => '',
			'content' => elgg_view_form('snippets/save', array(), $params),
		);
	}

	protected function serveAll() {
		$title = elgg_echo('snippets:all');

		elgg_push_breadcrumb(elgg_echo('snippets'));
		elgg_register_title_button();

		$content = elgg_list_entities(array(
			'types' => 'object',
			'subtype' => 'snippet',
			'full_view' => false,
		));
		return array(
			'title' => $title,
			'filter_context' => 'all',
			'content' => $content,
		);
	}

	protected function serveEdit($segments) {
		$guid = array_shift($segments);
		$entity = get_entity($guid);
		if (!$entity) {
			return false;
		}
		elgg_set_page_owner_guid($entity->getContainerGUID());

		$title = elgg_echo('snippets:title:edit');
		elgg_push_breadcrumb(elgg_echo('snippets'), 'snippets');
		elgg_push_breadcrumb($title);

		$params = $this->prepareFormVars($entity);
		return array(
			'title' => $title,
			'filter' => '',
			'content' => elgg_view_form('snippets/save', array(), $params),
		);
	}

	protected function serveFriends($segments) {
		$username = array_shift($segments);
		$user = get_user_by_username($username);
		if (!$user) {
			return false;
		}

		elgg_push_breadcrumb(elgg_echo('snippets'), 'snippets');
		elgg_push_breadcrumb($user->name, "snippets/owner/$username");
		elgg_push_breadcrumb(elgg_echo('friends'));

		elgg_register_title_button();

		$title = elgg_echo('snippets:title:friends');
		$content = list_user_friends_objects($user->guid, 'snippet', 10, false);

		return array(
			'title' => $title,
			'filter_context' => 'friends',
			'content' => $content,
		);
	}

	protected function serveOwner($segments) {
		$username = array_shift($segments);
		$user = get_user_by_username($username);
		if (!$user) {
			return false;
		}

		elgg_push_breadcrumb(elgg_echo('snippets'), 'snippets');
		elgg_push_breadcrumb($user->name);

		elgg_register_title_button();

		$title = elgg_echo('snippets:title:owner', array($user->name));
		$content .= elgg_list_entities(array(
			'type' => 'object',
			'subtype' => 'snippet',
			'container_guid' => $user->guid,
			'full_view' => false,
		));

		return array(
			'title' => $title,
			'filter_context' => 'mine',
			'content' => $content,
		);
	}

	protected function serveView($segments) {
		$guid = array_shift($segments);
		$entity = get_entity($guid);
		if (!$entity) {
			return false;
		}

		elgg_load_js('google:code:prettify');
		elgg_load_css('google:code:prettify');

		$container = $entity->getContainerEntity();
		$title = $entity->title;

		elgg_push_breadcrumb(elgg_echo('snippets'), 'snippets');
		if (elgg_instanceof($container, 'group')) {
			elgg_push_breadcrumb($container->name, "snippets/group/$container->guid/all");
		} else {
			elgg_push_breadcrumb($container->name, "snippets/owner/$container->username");
		}
		elgg_push_breadcrumb($title);

		$content = elgg_view_entity($entity, array('full_view' => true));
		$content .= elgg_view_comments($entity);
		return array(
			'title' => $title,
			'filter' => '',
			'content' => $content,
		);
	}

	protected function prepareFormVars($entity = null) {
		// input names => defaults
		$values = array(
			'title' => '',
			'description' => '',
			'access_id' => ACCESS_DEFAULT,
			'container_guid' => elgg_get_page_owner_guid(),
			'entity' => $entity,
		);

		if ($entity) {
			foreach (array_keys($values) as $field) {
				if (isset($entity->$field)) {
					$values[$field] = $entity->$field;
				}
			}
		}

		if (elgg_is_sticky_form('snippets')) {
			$sticky_values = elgg_get_sticky_values('snippets');
			foreach ($sticky_values as $key => $value) {
				$values[$key] = $value;
			}
		}

		elgg_clear_sticky_form('snippets');

		return $values;
	}
}
