<?php
/**
 * Snippets widget content view
 */

$options = array(
	'type' => 'object',
	'subtype' => 'snippet',
	'container_guid' => $vars['entity']->owner_guid,
	'limit' => (int) $vars['entity']->num_display,
	'full_view' => false,
	'pagination' => false,
);
$content = elgg_list_entities($options);

echo $content;
if ($content) {
	$url = "snippets/owner/" . elgg_get_page_owner_entity()->username;
	$more_link = elgg_view('output/url', array(
		'href' => $url,
		'text' => elgg_echo('snippets:text:more'),
		'is_trusted' => true,
	));
	echo "<span class=\"elgg-widget-more\">$more_link</span>";
}
