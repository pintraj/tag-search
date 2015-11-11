<?php

	class qa_prevnav_widget {
	
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
				qa_opt('prevnav_plugin_widget_title'),
				'</H3>'
			);*/
			if(qa_is_mobile_probably())
				return;
			
			$url = qa_path($request, null, qa_opt('site_url'));
			$out1='

<ul class="nav" >
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">GATE CS & IT<span class="caret"></span></a>
          <ul class="dropdown-menu">';
			for($i=2009; $i <= 2013; $i++)
			{
				$out1.='<li><a href="/tag/gate'.$i.'">GATE '.$i.'</a></li>';
			}
			for($i=2014; $i <= 2015; $i++)
			{
				$out1.='<li role="separator" class="divider"></li>';
				for($j = 1; $j <= 3; $j++){
					$out1.='<li><a href="/tag/gate'.$i.'-'.$j.'">GATE '.$i.' Session '.$j.'</a></li>';}
			}
$out1.='
          </ul>
        </li>
      ';
$out1.='
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">GATE CS<span class="caret"></span></a>
          <ul class="dropdown-menu">';
			for($i=1991; $i <= 2008; $i++)
			{
				$out1.='<li><a href="/tag/gate'.$i.'">GATE '.$i.'</a></li>';
			}
$out1.='
          </ul>
        </li>
      ';
$out1.='
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">GATE IT<span class="caret"></span></a>
          <ul class="dropdown-menu">';
			for($i=2004; $i <= 2008; $i++)
			{
				$out1.='<li><a href="/tag/gate'.$i.'"-it>GATE '.$i.'-IT</a></li>';
			}
$out1.='
          </ul>
        </li>
      ';

	/*		$out="<select>";
			for($i=1991; $i <= 2015; $i++)
			{
				$out.='<option value="'.$i.'">GATE '.$i.'</option>';
			}
			$out.="</select>";*/

			
			$out1.='
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">TIFR<span class="caret"></span></a>
          <ul class="dropdown-menu">';
			for($i=2010; $i <= 2015; $i++)
			{
				$out1.='<li><a href="/tag/tifr'.$i.'">TIFR '.$i.'</a></li>';
			}
			$output = '<div class="prevnav-widget-container">'.$out1.'</div>';
$out1.='
          </ul>
        </li>
      ';
			$out1.='
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">TIFR Maths<span class="caret"></span></a>
          <ul class="dropdown-menu">';
			for($i=2010; $i <= 2015; $i++)
			{
				$out1.='<li><a href="/tag/tifr-maths-'.$i.'">TIFR Maths '.$i.'</a></li>';
			}
			$output = '<div class="prevnav-widget-container">'.$out1.'</div>';
$out1.='
          </ul>
        </li>
      </ul>';
			
			$themeobject->output(
				$output
			);			
		}
	};


/*
	Omit PHP closing tag to help avoid accidental output
*/
