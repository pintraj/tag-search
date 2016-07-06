<?php

/*
        Plugin Name: TagSearch
        Plugin URI: https://github.com/arjunsuresh/chat
        Plugin Update Check URI: https://raw.github.com/arjunsuresh/chat/master/qa-plugin.php
        Plugin Description: Adds option to search questions by tag(s) to sidepanel
        Plugin Version: 1.0
        Plugin Date: 2016-07-05
        Plugin Author: Arjun
        Plugin Author URI: http://gateoverflow.in 
        Plugin License: GPLv2
        Plugin Minimum Question2Answer Version: 1.7
*/


	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
			header('Location: ../../');
			exit;
	}
	
	qa_register_plugin_layer('qa-tagsearch-layer.php', 'TagSearch Layer');	
	
	qa_register_plugin_module('widget', 'qa-tagsearch-widget.php', 'qa_tagsearch_widget', 'TagSearch Widget');


	qa_register_plugin_module('module', 'qa-tagsearch-admin.php', 'qa_tagsearch_admin', 'Tag Search Admin');
/*
	Omit PHP closing tag to help avoid accidental output
*/
