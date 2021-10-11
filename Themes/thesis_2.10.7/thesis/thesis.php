<?php
/*
Copyright 2012 DIYthemes, LLC. Patent pending. All rights reserved.
License: DIYthemes Software License Agreement
License URI: https://diythemes.com/thesis/rtfm/software-license-agreement/
*/
final class thesis {
	public $version = '2.10.7';
	public $changelog = 'https://diythemes.com/thesis/rtfm/changelog/v2107/';
	public $store = 'https://diythemes.com';
	public $box_admin = array();		// (array) Keeps track of any Boxes that have added admin pages via class options
	public $box_post_meta = array(); 	// (array) Keeps track of any Boxes that have added post meta
	public $admin_popup = array(		// (array) Admin pages with popup iframes
		'thesis_skin_uploader_window',
		'thesis_box_uploader_window',
		'thesis_favicon_window');

	public function __construct() {
		$this->constants();
		$this->files();
		// Set up the Thesis environment
		$this->environment = is_user_logged_in() && current_user_can('manage_options') ?
			(!empty($_GET['thesis_editor']) && $_GET['thesis_editor'] === '1' ?
				'editor' : (!empty($_GET['thesis_canvas']) ?
				'canvas' : (is_admin() ?
				(defined('DOING_AJAX') && DOING_AJAX === true ?
					'ajax' : (!empty($_GET['page']) && $_GET['page'] == 'thesis' ?
					'thesis' :
					'admin')) :
				false))) :
			false;
		$this->compatibility();
		// Launch Thesis components
		add_action('after_setup_theme', array($this, 'init'), 1);
		add_action('widgets_init', array($this, 'skin'), 2);
	}

	private function constants() {
		// Dirs
		define('THESIS_LIB', TEMPLATEPATH. '/lib');
		define('THESIS_ADMIN', THESIS_LIB. '/admin');
		define('THESIS_COMPATIBILITY', THESIS_LIB. '/compatibility');
		define('THESIS_CORE', THESIS_LIB. '/core');
		define('THESIS_JS', THESIS_LIB. '/js');
		define('THESIS_SKINS', THESIS_LIB. '/skins');
		define('THESIS_WP', THESIS_LIB. '/wp');
		// URLs
		define('THESIS_URL', get_bloginfo('template_url')); #wp
		define('THESIS_CSS_URL', THESIS_URL. '/lib/css');
		define('THESIS_JS_URL', THESIS_URL. '/lib/js');
		define('THESIS_IMAGES_URL', THESIS_URL. '/lib/images');
		// User dirs
		define('THESIS_USER', WP_CONTENT_DIR. '/thesis');
		define('THESIS_USER_SKINS', THESIS_USER. '/skins');
		define('THESIS_USER_BOXES', THESIS_USER. '/boxes');
		define('THESIS_USER_PACKAGES', THESIS_USER. '/packages');
		// User URLs
		define('THESIS_USER_URL', content_url('thesis'));
		define('THESIS_USER_SKINS_URL', THESIS_USER_URL. '/skins');
		define('THESIS_USER_BOXES_URL', THESIS_USER_URL. '/boxes');
		define('THESIS_USER_PACKAGES_URL', THESIS_USER_URL. '/packages');
	}

	private function files() {
		require_once(THESIS_CORE. '/api.php');
		require_once(THESIS_CORE. '/box.php');
		require_once(THESIS_CORE. '/manager.php');
		require_once(THESIS_CORE. '/skin.php');
		require_once(THESIS_CORE. '/skins.php');
		require_once(THESIS_WP. '/wp.php');
		if (file_exists(THESIS_USER. '/master.php'))
			include_once(THESIS_USER. '/master.php');
	}

	public function init() {
		$this->api = new thesis_api;
		$this->wp = new thesis_wp;
		if (is_admin()) {
			require_once(THESIS_ADMIN. '/admin.php');
			$this->admin = new thesis_admin;
		}
		$this->skins = new thesis_skins;
	}

	public function skin() {
		if (!empty($this->skins->skin['class']) && class_exists($this->skins->skin['class']) && is_subclass_of($this->skins->skin['class'], 'thesis_skin'))
			$this->skin = new $this->skins->skin['class']($this->skins->skin);
	}

	private function compatibility() {
		require_once(THESIS_COMPATIBILITY. '/aioseo.php');
		require_once(THESIS_COMPATIBILITY. '/contact-form-7.php');
		require_once(THESIS_COMPATIBILITY. '/disqus.php');
		require_once(THESIS_COMPATIBILITY. '/edd.php');
		require_once(THESIS_COMPATIBILITY. '/nextgen-gallery.php');
		require_once(THESIS_COMPATIBILITY. '/optimizepress.php');
		require_once(THESIS_COMPATIBILITY. '/rank-math.php');
//		require_once(THESIS_COMPATIBILITY. '/sensei.php');
		require_once(THESIS_COMPATIBILITY. '/thrive-comments.php');
		require_once(THESIS_COMPATIBILITY. '/woocommerce.php');
		require_once(THESIS_COMPATIBILITY. '/yoast.php');
		new thesis_aioseo;
		new thesis_contact_form_7;
		new thesis_disqus;
		new thesis_edd;
		new thesis_nextgen_gallery;
		new thesis_optimizepress;
		new thesis_rank_math;
//		new thesis_sensei;
		new thesis_thrive_comments;
		new thesis_woocommerce;
		new thesis_yoast_seo;
		$this->wp_customize = is_user_logged_in() && (!empty($_REQUEST['wp_customize']) || $GLOBALS['pagenow'] === 'customize.php') ? true : false;
		$this->caching = defined('W3TC') || defined('WPCACHEHOME') || defined('WPFC_MAIN_PATH') ? true : false;
	}
}