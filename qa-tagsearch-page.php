<?php

class qa_tagsearch_page{

	private $directory;
	private $urltoroot;


	public function load_module($directory, $urltoroot)
	{
		$this->directory=$directory;
		$this->urltoroot=$urltoroot;
	}


	public function suggest_requests() // for display in admin interface
	{
		return array(
				array(
					'title' => 'Tag Search Page',
					'request' => 'tag-search-page',
					'nav' => 'null', // 'M'=main, 'F'=footer, 'B'=before main, 'O'=opposite main, null=none
				     ),
			    );
	}


	public function match_request($request)
	{
		return $request == 'tag-search-page';
	}


	public function process_request($request)
	{

		require_once QA_INCLUDE_DIR.'app/format.php';
		require_once QA_INCLUDE_DIR.'app/options.php';
		require_once QA_INCLUDE_DIR.'app/search.php';


		//	Perform the search if appropriate

		if (strlen(qa_get('q'))) {

			//	Pull in input parameters

			$inquery=trim(qa_get('q'));
			$num = explode('+', $inquery);
		//	if(count($num) == 1) //just one query
				
			$userid=qa_get_logged_in_userid();
			$start=qa_get_start();

			$display=qa_opt_if_loaded('page_size_search');
			$count=2*(isset($display) ? $display : QA_DB_RETRIEVE_QS_AS)+1;
			// get enough results to be able to give some idea of how many pages of search results there are

			//	Perform the search using appropriate module

			$results=$this->qa_get_tag_search_results($inquery, $start, $count, $userid, false, false);

			//	Count and truncate results

			$pagesize=qa_opt('page_size_search');
			$gotcount=count($results);
			$results=array_slice($results, 0, $pagesize);

			//	Retrieve extra information on users

			$fullquestions=array();

			foreach ($results as $result)
				if (isset($result['question']))
					$fullquestions[]=$result['question'];

			$usershtml=qa_userids_handles_html($fullquestions);

			//	Report the search event

			qa_report_event('tagsearch', $userid, qa_get_logged_in_handle(), qa_cookie_get(), array(
						'query' => $inquery,
						'start' => $start,
						));
		}


		//	Prepare content for theme

		$qa_content=qa_content_prepare(true);

		if (strlen(qa_get('q'))) {
			$qa_content['search']['value']=qa_html($inquery);

			if (count($results))
				$qa_content['title']=qa_lang_html_sub('main/results_for_x', qa_html($inquery));
			else
				$qa_content['title']=qa_lang_html_sub('main/no_results_for_x', qa_html($inquery));

			$qa_content['q_list']['form']=array(
					'tags' => 'method="post" action="'.qa_self_html().'"',

					'hidden' => array(
						'code' => qa_get_form_security_code('vote'),
						),
					);

			$qa_content['q_list']['qs']=array();

			$qdefaults=qa_post_html_defaults('Q');

			foreach ($results as $result)
				if (!isset($result['question'])) { // if we have any non-question results, display with less statistics
					$qdefaults['voteview']=false;
					$qdefaults['answersview']=false;
					$qdefaults['viewsview']=false;
					break;
				}

			foreach ($results as $result) {
				if (isset($result['question']))
					$fields=qa_post_html_fields($result['question'], $userid, qa_cookie_get(),
							$usershtml, null, qa_post_html_options($result['question'], $qdefaults));

				elseif (isset($result['url']))
					$fields=array(
							'what' => qa_html($result['url']),
							'meta_order' => qa_lang_html('main/meta_order'),
						     );

				else
					continue; // nothing to show here

				if (isset($qdefaults['blockwordspreg']))
					$result['title']=qa_block_words_replace($result['title'], $qdefaults['blockwordspreg']);

				$fields['title']=qa_html($result['title']);
				$fields['url']=qa_html($result['url']);

				$qa_content['q_list']['qs'][]=$fields;
			}

			$qa_content['page_links']=qa_html_page_links(qa_request(), $start, $pagesize, $start+$gotcount,
					qa_opt('pages_prev_next'), array('q' => $inquery), $gotcount>=$count);

			if (qa_opt('feed_for_search'))
				$qa_content['feed']=array(
						'url' => qa_path_html(qa_feed_request('search/'.$inquery)),
						'label' => qa_lang_html_sub('main/results_for_x', qa_html($inquery)),
						);

			if (empty($qa_content['page_links']))
				$qa_content['suggest_next']=qa_html_suggest_qs_tags(qa_using_tags());

		} else
			$qa_content['error']=qa_lang_html('main/search_explanation');



		return $qa_content;

	}



	function qa_get_tag_search_results($query, $start, $count, $userid, $absoluteurls, $fullcontent)
	{


		$results=$this->process_tag_search($query, $start, $count, $userid, $absoluteurls, $fullcontent);

		//      Work out what additional information (if any) we need to retrieve for the results

		$keypostidgetfull=array();
		$keypostidgettype=array();
		$keypostidgetquestion=array();
		$keypageidgetpage=array();

		foreach ($results as $result) {
			if (isset($result['question_postid']) && !isset($result['question']))
				$keypostidgetfull[$result['question_postid']]=true;

			if (isset($result['match_postid'])) {
				if (!( (isset($result['question_postid'])) || (isset($result['question'])) ))
					$keypostidgetquestion[$result['match_postid']]=true; // we can also get $result['match_type'] from this

				elseif (!isset($result['match_type']))
					$keypostidgettype[$result['match_postid']]=true;
			}

			if (isset($result['page_pageid']) && !isset($result['page']))
				$keypageidgetpage[$result['page_pageid']]=true;
		}

		//      Perform the appropriate database queries
		list($postidfull, $postidtype, $postidquestion, $pageidpage)=qa_db_select_with_pending(
				count($keypostidgetfull) ? qa_db_posts_selectspec($userid, array_keys($keypostidgetfull), $fullcontent) : null,
				count($keypostidgettype) ? qa_db_posts_basetype_selectspec(array_keys($keypostidgettype)) : null,
				count($keypostidgetquestion) ? qa_db_posts_to_qs_selectspec($userid, array_keys($keypostidgetquestion), $fullcontent) : null,
				count($keypageidgetpage) ? qa_db_pages_selectspec(null, array_keys($keypageidgetpage)) : null
				);

		//      Supplement the results as appropriate

		foreach ($results as $key => $result) {
			if (isset($result['question_postid']) && !isset($result['question']))
				if (@$postidfull[$result['question_postid']]['basetype']=='Q')
					$result['question']=@$postidfull[$result['question_postid']];

			if (isset($result['match_postid'])) {
				if (!( (isset($result['question_postid'])) || (isset($result['question'])) )) {
					$result['question']=@$postidquestion[$result['match_postid']];

					if (!isset($result['match_type']))
						$result['match_type']=@$result['question']['obasetype'];

				} elseif (!isset($result['match_type']))
				$result['match_type']=@$postidtype[$result['match_postid']];
			}

			if (isset($result['question']) && !isset($result['question_postid']))
				$result['question_postid']=$result['question']['postid'];

			if (isset($result['page_pageid']) && !isset($result['page']))
				$result['page']=@$pageidpage[$result['page_pageid']];

			if (!isset($result['title'])) {
				if (isset($result['question']))
					$result['title']=$result['question']['title'];
				elseif (isset($result['page']))
					$result['title']=$result['page']['heading'];
			}

			if (!isset($result['url'])) {
				if (isset($result['question']))
					$result['url']=qa_q_path($result['question']['postid'], $result['question']['title'],
							$absoluteurls, @$result['match_type'], @$result['match_postid']);
				elseif (isset($result['page']))
					$result['url']=qa_path($result['page']['tags'], null, qa_opt('site_url'));
			}

			$results[$key]=$result;
		}

		//      Return the results

		return $results;
	}
	function process_tag_search($query, $start, $count, $userid, $absoluteurls, $fullcontent)
	{
		require_once QA_INCLUDE_DIR.'qa-db-selects.php';
		require_once QA_INCLUDE_DIR.'qa-util-string.php';

		$words=qa_string_to_words($query);

		$questions=qa_db_select_with_pending(
				$this->qa_db_tag_search_posts_selectspec($userid, $words, $words, $words, $words, trim($query), $start, $fullcontent, $count)
				);

		$results=array();

		foreach ($questions as $question) {
			qa_search_set_max_match($question, $type, $postid); // to link straight to best part

			$results[]=array(
					'question' => $question,
					'match_type' => $type,
					'match_postid' => $postid,
					);
		}

		return $results;
	}
	function qa_db_tag_search_posts_selectspec($voteuserid, $titlewords, $contentwords, $tagwords, $handlewords, $handle, $start, $full=false, $count=null)
	//Modified from qa_db_search_posts_selectspec to do a logical and for all selected TAGS
	{
		$count=isset($count) ? min($count, QA_DB_RETRIEVE_QS_AS) : QA_DB_RETRIEVE_QS_AS;


		$selectspec=qa_db_posts_basic_selectspec($voteuserid, $full);

		$selectspec['columns'][]='score';
		$selectspec['columns'][]='matchparts';
		$selectspec['source'].=" JOIN (SELECT questionid, SUM(score)+2*(LOG(#)*(^posts.hotness-(SELECT MIN(hotness) FROM ^posts WHERE type='Q'))/((SELECT MAX(hotness) FROM ^posts WHERE type='Q')-(SELECT MIN(hotness) FROM ^posts WHERE type='Q')))+LOG(questionid)/1000000 AS score, GROUP_CONCAT(CONCAT_WS(':', matchposttype, matchpostid, ROUND(score,3))) AS matchparts FROM ((";
		$selectspec['sortdesc']='score';
		array_push($selectspec['arguments'], QA_IGNORED_WORDS_FREQ);

		$selectparts=0;

		if (!empty($tagwords)) {

			$selectspec['source'].=($selectparts++ ? " UNION ALL " : "").
				"(SELECT postid AS questionid, 2*LOG(#/tagwordcount) AS score, 'Q' AS matchposttype, postid AS matchpostid FROM ^tagwords JOIN ^words ON ^tagwords.wordid=^words.wordid WHERE word IN ($) AND tagwordcount<#)";

			array_push($selectspec['arguments'], QA_IGNORED_WORDS_FREQ, $tagwords, QA_IGNORED_WORDS_FREQ);
		}


		if ($selectparts==0)
			$selectspec['source'].='(SELECT NULL as questionid, 0 AS score, NULL AS matchposttype, NULL AS matchpostid FROM ^posts WHERE postid IS NULL)';


		$selectspec['source'].="))x  LEFT JOIN ^posts ON ^posts.postid=questionid GROUP BY questionid ORDER BY score DESC LIMIT #,#) y ON ^posts.postid=y.questionid";
		if(!empty($tagwords)){
			$selectspec['source'].=" where ^posts.postid  in (select postid AS questionid from ^tagwords JOIN ^words on ^tagwords.wordid=^words.wordid where word IN  ('".implode("','",$tagwords)."') group by questionid having count(*) = ".count($tagwords)."  )";
		}
		array_push($selectspec['arguments'], $start, $count);

		return $selectspec;
	}

}



	/*
	   Omit PHP closing tag to help avoid accidental output
	 */
