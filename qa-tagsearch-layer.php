<?php

class qa_html_theme_layer extends qa_html_theme_base {

	function head_custom()
	{
		qa_html_theme_base::head_custom();

		require_once QA_INCLUDE_DIR.'db/selects.php';
		$this->output('<style type="text/css">'.qa_opt('tagsearch_plugin_css').'</style>');
		$this->output('<script> var qa_tags_search_examples="";');
		$ctags=array_keys(qa_db_single_select(qa_db_popular_tags_selectspec(0, QA_DB_RETRIEVE_COMPLETE_TAGS)));
		$this->output('var qa_tags_search_complete =\''.qa_html(implode(',',$ctags)).'\';');
		$template='<a href="#" class="qa-tag-link" onclick="return qa_tag_search_click(this);">^</a>';
		$this->output('var qa_tag_search_template =\''.$template.'\';');
		$this->output('</script>');

		$this->output('<script async type="text/javascript" src="'.QA_HTML_THEME_LAYER_URLTOROOT.'js/tag_search.js?v=1"></script>');
	}

                       // $results=qa_get_search_results($inquery, $start, $count, $userid, false, false);


}

