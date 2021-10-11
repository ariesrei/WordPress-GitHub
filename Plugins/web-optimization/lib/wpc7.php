<?php
include_once WEB_OPTIMIZATION_PLUGIN_PATH_ROOT."/lib/options.php";

if ( !empty($opt_val_contact_form_7) ) {

	add_filter( 'wpcf7_load_js', '__return_false' );
	add_filter( 'wpcf7_load_css', '__return_false' );

	if ( !empty($opt_val_wpc7_pid) ) {
		add_filter('wp_head', 'init_wpc7_optimization');	
	}	
}

function init_wpc7_optimization() {

	require WEB_OPTIMIZATION_PLUGIN_PATH_ROOT."/lib/options.php";

	global $wp_query;
	$session = $wp_query->get_queried_object_id();
	$wpc7_pid_exp = explode(",",$opt_val_wpc7_pid);

	if( in_array($session, $wpc7_pid_exp) ) { 

		if( function_exists( 'wpcf7_enqueue_scripts' )) wpcf7_enqueue_scripts();
		if( function_exists( 'wpcf7_enqueue_styles' )) wpcf7_enqueue_styles();
	}
}
// add_filter('wp', 'init_wpc7_optimization');