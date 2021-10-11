<?php
/*
Copyright 2020 DIYthemes, LLC. Patent pending. All rights reserved.
License: DIYthemes Software License Agreement
License URI: https://diythemes.com/thesis/rtfm/software-license-agreement/
*/
class thesis_nextgen_gallery {
	public function __construct() {
		add_action('init', array($this, 'compatibility'), 100);
	}

	public function compatibility() {
		global $thesis;
		add_filter('show_nextgen_version', '__return_false');
		if ($thesis->environment == 'editor' || $thesis->environment == 'canvas' || (is_admin() && !empty($_GET['action']) && in_array($_GET['action'], $thesis->admin_popup)))
			add_filter('run_ngg_resource_manager', '__return_false');
	}
}