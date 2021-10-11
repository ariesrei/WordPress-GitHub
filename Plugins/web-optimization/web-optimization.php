<?php 
/* 
Plugin Name: Web Optimization
Plugin URI: https://wpwebguru.tech/
Description: Just another web optimization plugin.
Author: Aries M.
Text Domain: web-optimization
Version: 1.0
*/

define( 'WEB_OPTIMIZATION_PLUGIN_PATH', plugins_url().'/web-optimization' );
define( 'WEB_OPTIMIZATION_PLUGIN_PATH_ROOT', $_SERVER['DOCUMENT_ROOT'].'/wp-content/plugins/web-optimization' );

include_once WEB_OPTIMIZATION_PLUGIN_PATH_ROOT.'/lib/wpc7.php';
include_once WEB_OPTIMIZATION_PLUGIN_PATH_ROOT.'/lib/google-optimization.php';
include_once WEB_OPTIMIZATION_PLUGIN_PATH_ROOT.'/lib/minify.php';

function web_optimization_menu() {

	add_options_page( 
		'Web Optimization', 
		'Web Optimization', 
		'manage_options', 
		'web_optimization', 
		'web_optimization_options' 
	);
}
add_action( 'admin_menu', 'web_optimization_menu' );

function web_optimization_options() {

    if (!current_user_can('manage_options')) {
		wp_die( __('You do not have sufficient permissions to access this page.') );
    }	
    include_once 'web-optimization-settings.php';
}