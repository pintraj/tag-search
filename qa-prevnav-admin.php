<?php
	class qa_prevnav_admin {
		
		function allow_template($template)
		{
			return ($template!='admin');
		}

		function option_default($option) {

			switch($option) {
				case 'prevnav_plugin_widget_title':
					return 'Previous questions';
				case 'prevnav_plugin_css':
					return '#qa-prevnav-buttons-container {
	background: none repeat scroll 0 0 #DDDDDD;
	font-size: 125%;
	font-weight: bold;
	margin: 20px 0;
	padding: 20px;
	text-align: center;

}
.prevnav-widget-container {
	display:inline-block;
	position:relative;
}';
			}
			
		}

		function admin_form(&$qa_content)
		{

		//	Process form input

			$ok = null;
			if (qa_clicked('prevnav_save_button')) {
				
				qa_opt('prevnav_plugin_css',qa_post_text('prevnav_plugin_css'));
				qa_opt('prevnav_plugin_widget_only',(bool)qa_post_text('prevnav_plugin_widget_only'));
				qa_opt('prevnav_plugin_widget_title',qa_post_text('prevnav_plugin_widget_title'));
				
				
				$ok = qa_lang('admin/options_saved');
			}
			else if (qa_clicked('prevnav_reset_button')) {
				foreach($_POST as $i => $v) {
					$def = $this->option_default($i);
					if($def !== null) qa_opt($i,$def);
				}
				$ok = qa_lang('admin/options_reset');
			}			
		//	Create the form for display
			
		
			$fields = array();


			$fields[] = array(
				'type' => 'blank',
			);			
			
			$fields[] = array(
				'label' => 'PrevNav buttons custom css',
				'tags' => 'NAME="prevnav_plugin_css"',
				'value' => qa_opt('prevnav_plugin_css'),
				'type' => 'textarea',
				'rows' => 20
			);
									
			$fields[] = array(
				'label' => 'Widget Title',
				'tags' => 'NAME="prevnav_plugin_widget_title"',
				'value' => qa_opt('prevnav_plugin_widget_title'),
			);

			$fields[] = array(
				'type' => 'blank',
			);			

			$fields[] = array(
				'type' => 'blank',
			);			
						
			return array(
				'ok' => ($ok && !isset($error)) ? $ok : null,
				
				'fields' => $fields,
				
				'buttons' => array(
					array(
					'label' => qa_lang_html('main/save_button'),
					'tags' => 'NAME="prevnav_save_button"',
					),
					array(
					'label' => qa_lang_html('admin/reset_options_button'),
					'tags' => 'NAME="prevnav_reset_button"',
					),
				),
			);
		}
	}
