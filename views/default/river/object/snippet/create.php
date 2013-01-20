<?php
/**
 * River view for snippet creation
 */

$object = $vars['item']->getObjectEntity();

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
));
