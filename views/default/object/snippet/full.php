<?php
/**
 * Full view of entity
 */

$vars['title'] = false;
$vars['summary'] = elgg_view('object/elements/summary', $vars);

$description = htmlspecialchars($vars['entity']->description, ENT_QUOTES, 'UTF-8');
$vars['body'] = <<<HTML
<div class="mtm">
	<code class="prettyprint">$description</code>
</div>
HTML;

echo elgg_view('object/elements/full', $vars);
