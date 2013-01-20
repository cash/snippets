<?php
/**
 * Snippets form body
 */

$title = $description = $access_id = $container_guid = '';
extract($vars, EXTR_IF_EXISTS);

$title_label = elgg_echo('snippets:label:title');
$title_input = elgg_view('input/text', array(
	'name' => 'title',
	'value' => $title,
));

$code_label = elgg_echo('snippets:label:code');
$code_input = elgg_view('input/plaintext', array(
	'name' => 'description',
	'value' => $description,
));

$access_label = elgg_echo('access');
$access_input = elgg_view('input/access', array(
	'name' => 'access_id',
	'value' => $access_id,
));

$container_input = elgg_view('input/hidden', array(
	'name' => 'container_guid',
	'value' => $container_guid,
));
if ($vars['entity']) {
	$guid_input = elgg_view('input/hidden', array(
		'name' => 'guid',
		'value' => $vars['entity']->guid,
	));
} else {
	$guid_input = '';
}
$save_button = elgg_view('input/submit', array(
	'value' => elgg_echo('save'),
));

echo <<<HTML
<div>
	<label>$title_label</label>
	$title_input
</div>
<div>
	<label>$code_label</label>
	$code_input
</div>
<div>
	<label>$access_label</label><br/>
	$access_input
</div>
<div class="elgg-foot">
	$container_input
	$guid_input
	$save_button
</div>
HTML;
