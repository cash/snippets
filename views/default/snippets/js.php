
//<script>
elgg.provide('elgg.snippets');

elgg.snippets.init = function() {
	if ($(".prettyprint").length !== 0) {
		prettyPrint();
	}
};

elgg.register_hook_handler('init', 'system', elgg.snippets.init);
