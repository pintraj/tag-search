<?php


	class qa_tagsearch_ajax_page {
		
		var $directory;
		var $urltoroot;
		
		function load_module($directory, $urltoroot)
		{
			$this->directory = $directory;
			$this->urltoroot = $urltoroot;
		}
		
		function suggest_requests() 
		{
			return null;	
		}
		
		function match_request($request)
		{
			if ($request=='qa_tagsearch_ajax_page') {
				return true;
			}
			return false;
		}

		function process_request($request)
		{
		
			$transferString = qa_post_text('ajax');
			if($transferString !== null) {
				
				
				require_once QA_INCLUDE_DIR.'db/selects.php';
                		$ctags=array_keys(qa_db_single_select(qa_db_popular_tags_selectspec(0, QA_DB_RETRIEVE_COMPLETE_TAGS)));	
				$output = qa_html(implode(',',$ctags));
				header('Access-Control-Allow-Origin: '.qa_path(null));
				echo $output;
				
				exit(); 
			} // END AJAX RETURN
			else {
			}
			
			
		} // end process_request
		
	}; // end class
	
/*
	Omit PHP closing tag to help avoid accidental output
*/
