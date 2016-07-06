<?php
class qa_tagsearch_admin {

	function allow_template($template)
	{
		return ($template!='admin');
	}

	function option_default($option) {

		switch($option) {
			case 'tagsearch_plugin_css':
				return '.qam-tag-search  {
background :#1abc9c;
				}
				.qa-tag-search {
padding: 8px;
	 margin-bottom: 5px;
				}
				.qa-tag-search-field {
margin: 0 -40px 0 0;
padding: 0 40px 0 5px;
	 vertical-align: bottom;
width: 100%;
height: 36px;
	border-width: 1px;
	border-style: solid;
	border-color: transparent;
				}

				.qa-tag-search-button {
width: 36px;
height: 36px;
margin: 0!important;
	text-indent: -9999px;
background: #bdc3c7 url("'.$this->getMyPath(__DIR__).'/images/search-icon-white.png") center no-repeat;
border: none;
outline: none;
background-color: #117964;
				}

				';
			default:
				return null;

		}
	}
	function admin_form(&$qa_content)
	{

		//	Process form input

		$ok = null;
		if (qa_clicked('tagsearch_save_button')) {

			qa_opt('tagsearch_plugin_css',qa_post_text('tagsearch_plugin_css'));


			$ok = qa_lang('admin/options_saved');
		}
		else if (qa_clicked('tagsearch_reset_button')) {
			foreach($_POST as $i => $v) {
				$def = $this->option_default($i);
				if($def !== null) qa_opt($i,$def);
			}
			$ok = qa_lang('admin/options_reset');
		}			
		//	Create the form for display


		$fields = array();


		$fields[] = array(
				'label' => 'Tag Search custom css',
				'tags' => 'NAME="tagsearch_plugin_css"',
				'value' => qa_opt('tagsearch_plugin_css'),
				'type' => 'textarea',
				'rows' => 20
				);


		return array(
				'ok' => ($ok && !isset($error)) ? $ok : null,

				'fields' => $fields,

				'buttons' => array(
					array(
						'label' => qa_lang_html('main/save_button'),
						'tags' => 'NAME="tagsearch_save_button"',
					     ),
					array(
						'label' => qa_lang_html('admin/reset_options_button'),
						'tags' => 'NAME="tagsearch_reset_button"',
					     ),
					),
			    );
	}
	function getMyPath($location) { 
		$getMyPath = str_replace($_SERVER['DOCUMENT_ROOT'],'',$location); 
		return $getMyPath; 
	} 


}
