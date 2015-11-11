<?php

	class qa_adchat_widget {
	
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
			/*$themeobject->output(
				'<H3 STYLE="margin-top:0; padding-top:0;">',
				qa_opt('adchat_plugin_widget_title'),
				'</H3>'
			);*/
			$out='';
			require_once QA_INCLUDE_DIR.'qa-app-users.php';
			if(qa_is_logged_in())
			{
				$out='<div class="chat">
<iframe src="/chat" style="border:0; width:100%; height:480px;"></iframe>
</div>';

			}
			else {
				$out='<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- GATE Overflow -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-1529295637495038"
     data-ad-slot="5219402911"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
			}			
	$output = '<div class="adchat-widget-container">'.$out.'</div>';
		
			$themeobject->output(
				$output
			);			
		}
	};


/*
	Omit PHP closing tag to help avoid accidental output
*/
