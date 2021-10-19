<?php
/*
This file is for Skin-specific customizations. Do not change your Skin’s skin.php file,
as that will be upgraded in the future and your work will be lost.

If you are comfortable with PHP, you can make more powerful customizations by using the
Thesis Box system to create elements you can interact with in the Thesis HTML Editor.

For more information, please visit: https://diythemes.com/thesis/rtfm/api/box/
*/

include_once "assets.php";
include_once "hooks.php";
include_once "shortcodes.php";

add_action('wp_enqueue_scripts', function() {
    wp_enqueue_script('main', THESIS_USER_URL.'/skins/aries/assets/dist/main.bundle.js', [], '1.0.0', true );
    wp_enqueue_style('main', THESIS_USER_URL.'/skins/aries/assets/dist/main.css', [], '1.0.0', 'all' );
});



// Fully Disable Gutenberg editor.
add_filter('use_block_editor_for_post_type', '__return_false', 10);
// Don't load Gutenberg-related stylesheets.
add_action( 'wp_enqueue_scripts', 'remove_block_css', 100 );
function remove_block_css() {
   wp_dequeue_style( 'wp-block-library' ); // WordPress core
   wp_dequeue_style( 'wp-block-library-theme' ); // WordPress core
   wp_dequeue_style( 'wc-block-style' ); // WooCommerce
   wp_dequeue_style( 'storefront-gutenberg-blocks' ); // Storefront theme
}

/** 
 * Removes empty paragraph tags from shortcodes in WordPress.
 */
function tg_remove_empty_paragraph_tags_from_shortcodes_wordpress( $content ) {
    $toFix = array( 
        '<p>['    => '[', 
        ']</p>'   => ']', 
        ']<br />' => ']'
    ); 
    return strtr( $content, $toFix );
}
add_filter( 'the_content', 'tg_remove_empty_paragraph_tags_from_shortcodes_wordpress' );


function the_content_filter($content) {
    $block = join("|",array("custom_modular_content", "skills", "works-bg","works","services"));
    $rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/","[$2$3]",$content);
    $rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/","[/$2]",$rep);
return $rep;
}
add_filter("the_content", "the_content_filter");
