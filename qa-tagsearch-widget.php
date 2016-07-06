<?php

class qa_tagsearch_widget {

	var $urltoroot;

	function load_module($directory, $urltoroot)
	{
		$this->urltoroot = $urltoroot;
	}

	function allow_template($template)
	{

		return true;
	}

	function allow_region($region)
	{
		return ($region=='side');
	}

	function output_widget($region, $place, $themeobject, $template, $request, $qa_content)
	{

		require_once QA_INCLUDE_DIR.'db/selects.php';


		$out='<div class="qam-tag-search turquoise"> <div class="qa-tag-search">
			<form method="GET" action="../search">
			<input type="hidden" name="t" value="tagsearch" />
			<input type="text" name="q" id="tag_search" value  autocomplete="off" class="qa-tag-search-field"  placeholder="Tag Search" onkeyup="qa_tag_search_hints()"; onmouseup="qa_tag_search_hints()";>
			<input type="submit" value="Search" class="qa-tag-search-button"  >

			<div class="qa-form-tall-note">
			<span id="tag_search_examples_title" style="display:none;"> </span>
			<span id="tag_search_complete_title" style="display:none;"></span>
			<span id="tag_search_hints"></span></div> </form>
			</div> </div>';
		$output = '<div class="tagsearch-widget-container">'.$out.'</div>';

		$themeobject->output(
				$output
				);			
	}
};


/*
   Omit PHP closing tag to help avoid accidental output
 */
