<?php
/**
 * View a snippet
 */

$full_view = elgg_extract('full_view', $vars, false);
$entity = elgg_extract('entity', $vars, null);
$owner = $entity->getOwnerEntity();

$vars['metadata'] = elgg_view_menu('entity', array(
	'entity' => $entity,
	'handler' => 'snippets',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

$owner_link = elgg_view('output/url', array(
	'href' => "snippets/owner/$owner->username",
	'text' => $owner->name,
	'is_trusted' => true,
));
$byline = elgg_echo('byline', array($owner_link));
$date = elgg_view_friendly_time($entity->time_created);

$comments_count = $entity->countComments();
if ($comments_count != 0) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', array(
		'href' => $entity->getURL() . '#comments',
		'text' => $text,
		'is_trusted' => true,
	));
} else {
	$comments_link = '';
}

$vars['subtitle'] = "$byline $date $comments_link";

$vars['icon'] = elgg_view_entity_icon($owner, 'tiny');

if ($full_view) {
	echo elgg_view('object/snippet/full', $vars);
} else {
	echo elgg_view('object/snippet/brief', $vars);
}
