<?php
/*
Copyright 2021 DIYthemes, LLC. Patent pending. All rights reserved.
License: DIYthemes Software License Agreement
License URI: https://diythemes.com/thesis/rtfm/software-license-agreement/
*/
class thesis_disqus {
	public function __construct() {
		if (!class_exists('Disqus')) return;
		add_filter('thesis_comments_preload', '__return_false');
	}
}