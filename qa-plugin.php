<?php

/*
        Plugin Name: PrevNav
        Plugin URI: https://github.com/NoahY/q2a-share
        Plugin Update Check URI: https://raw.github.com/NoahY/q2a-share/master/qa-plugin.php
        Plugin Description: Adds nav bar to sidepanel
        Plugin Version: 1.0
        Plugin Date: 2015-11-08
        Plugin Author: Arjun
        Plugin Author URI: 
        Plugin License: GPLv2
        Plugin Minimum Question2Answer Version: 1.7
*/


	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
			header('Location: ../../');
			exit;
	}
	
	qa_register_plugin_layer('qa-prevnav-layer.php', 'Prev Nav Layer');	
	
qa_register_plugin_module('module', 'qa-prevnav-admin.php', 'qa_prevnav_admin', 'PrevNav Admin');
	qa_register_plugin_module('widget', 'qa-prevnav-widget.php', 'qa_prevnav_widget', 'Prev Nav Widget');

/*
	Omit PHP closing tag to help avoid accidental output
*/
