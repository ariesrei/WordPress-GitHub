<?php
include_once WEB_OPTIMIZATION_PLUGIN_PATH_ROOT."/lib/options.php";

if ( !empty($opt_val_remove_jquery) ) add_action('wp_default_scripts', 'remove_jquery_migrate');
if ( !empty($opt_val_force_load_async) ) add_filter('script_loader_tag', 'add_async_attribute', 10, 2);
if ( !empty($opt_val_force_to_defer) ) add_filter('script_loader_tag', 'add_defer_attribute', 10, 2);

if ( !empty($opt_val_remove_js_css_strings) ) {
    add_filter('style_loader_src', '_remove_script_version', 15, 1);
    add_filter('script_loader_src', '_remove_script_version', 15, 1);
} 


if ( !empty($opt_val_minify_html) ) {

    if (function_exists('is_admin')) {
       
       if ( !is_admin() ) {
          add_action('template_redirect', 'wp_html_compression_start', -1);
          
          // In case above fails (it does sometimes.. ??)
          add_action('get_header', 'wp_html_compression_start');
       } else {
          // For v0.6
          //require_once dirname(__FILE__) . '/admin.php';
       }
    }
}

if ( !empty($opt_val_remove_js_css_types) ) {
    add_action('wp_loaded', 'prefix_output_buffer_start');
    add_action('shutdown', 'prefix_output_buffer_end');
} 

/* ************************************************
*
*  Start Web Optimizations Functions
*
***************************************************/

/*  Remove query strings from CSS and JS inclusions  */
function _remove_script_version($src) {
   $parts = explode('?ver', $src);
   return $parts[0];
}
// add_filter('style_loader_src', '_remove_script_version', 15, 1);
// add_filter('script_loader_src', '_remove_script_version', 15, 1);


/*  Force scripts to load asynchronously for better performance */
function add_async_attribute($tag, $handle) {
  $scripts_to_async = array('script-handle', 'another-handle');
  foreach($scripts_to_async as $async_script) {
      if ($async_script !== $handle) return $tag;
      return str_replace(' src', ' async="async" src', $tag);
  }
  return $tag;
}
//add_filter('script_loader_tag', 'add_async_attribute', 10, 2);



/*  Force scripts to defer for better performance  */
function add_defer_attribute($tag, $handle) {
    
    $scripts_to_defer = array('script-handle', 'another-handle');
  foreach($scripts_to_defer as $defer_script) {
      if ($async_script !== $handle) return $tag;
        return str_replace(' src', ' defer="defer" src', $tag);
  }
    return $tag;
}
//add_filter('script_loader_tag', 'add_defer_attribute', 10, 2);

/* Remove jquery migrate for enhanced performance */
function remove_jquery_migrate($scripts) {
   if (is_admin()) return;
   $scripts->remove('jquery');
   $scripts->add('jquery', false, array('jquery-core-js'), '1.12.4');
}
//add_action('wp_default_scripts', 'remove_jquery_migrate');



// add_action('wp_loaded', 'prefix_output_buffer_start');
function prefix_output_buffer_start() { 
    ob_start("prefix_output_callback"); 
}

//add_action('shutdown', 'prefix_output_buffer_end');
function prefix_output_buffer_end() { 
    ob_end_flush(); 
}

function prefix_output_callback($buffer) {
    return preg_replace( "%[ ]type=[\'\"]text\/(javascript|css)[\'\"]%", '', $buffer );
}