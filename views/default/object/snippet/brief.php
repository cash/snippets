<?php
/**
 * Brief view for a list
 */

$body = elgg_view('object/elements/summary', $vars);

echo elgg_view_image_block($vars['icon'], $body);
