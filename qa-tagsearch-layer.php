<?php

class qa_html_theme_layer extends qa_html_theme_base {

	function head_custom()
	{
		qa_html_theme_base::head_custom();

		$this->output('<style type="text/css">'.qa_opt('tagsearch_plugin_css').'</style>');
	}
	function body_suffix()
	{
		qa_html_theme_base::body_suffix();
		$this->output('<script> var qa_tags_search_examples="";');
		$this->output('if (typeof qa_tags_complete === "undefined") {var qa_tags_complete =\'\';}');
		$template='<a href="#" class="qa-tag-link" onclick="return qa_tag_search_click(this);">^</a>';
		$this->output('var qa_tag_search_template =\''.$template.'\';');
		$this->output('</script>');

		$this->output('<script async type="text/javascript" src="'.QA_HTML_THEME_LAYER_URLTOROOT.'js/tag_search.js?v=2"></script>');
		$this->output(' <script type="text/javascript">
				$(document).ready(function(){

					$("#tag_search").click( function() {

						if(qa_tags_complete == ""){
						$.ajax({
type: "POST",
url: "'.qa_path("qa_tagsearch_ajax_page").'",
data: {ajax:"hello" },
error: function() {
console.log("server: ajax error");
},
success: function(htmldata) {
qa_tags_complete = htmldata;
}
});
						}
						else {
						}
						});

});
</script> 
');

	}
}
