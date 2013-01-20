
//<script>
elgg.provide('elgg.snippets');

elgg.snippets.init = function() {
	prettyPrint();
};

elgg.register_hook_handler('init', 'system', elgg.snippets.init);
