<?php
/**
 * RSS view required since we have unencoded data for snippet
 */

$title = $vars['entity']->title;
$permalink = htmlspecialchars($vars['entity']->getURL(), ENT_NOQUOTES, 'UTF-8');
$pubdate = date('r', $vars['entity']->getTimeCreated());
$description = htmlspecialchars($vars['entity']->description, ENT_QUOTES, 'UTF-8');
$creator = elgg_view('page/components/creator', $vars);
$georss = elgg_view('page/components/georss', $vars);
$extension = elgg_view('extensions/item', $vars);

$item = <<<__RSS
<item>
	<guid isPermaLink="true">$permalink</guid>
	<pubDate>$pubdate</pubDate>
	<link>$permalink</link>
	<title><![CDATA[$title]]></title>
	<description><![CDATA[<code>$description</code>]]></description>
	$creator$georss$extension
</item>

__RSS;

echo $item;
