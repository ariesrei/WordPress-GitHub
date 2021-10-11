<?php
/*
Copyright 2020 DIYthemes, LLC. Patent pending. All rights reserved.
License: DIYthemes Software License Agreement
License URI: https://diythemes.com/thesis/rtfm/software-license-agreement/
*/
class thesis_yoast_seo {
	public function __construct() {
		if (!defined('WPSEO_VERSION')) return;
		add_filter('thesis_title_tag_wp', '__return_true');
		add_filter('thesis_boxes', array($this, 'remove_boxes'), 11);
		add_filter('thesis_schema_use', '__return_false');
	}

	public function remove_boxes($boxes) {
		$remove = array(
			'thesis_meta_description',
			'thesis_meta_keywords',
			'thesis_meta_robots',
			'thesis_canonical_link');
		foreach ($remove as $box)
			unset($boxes[array_search($box, $boxes)]);
		return $boxes;
	}
}