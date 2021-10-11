<?php
/*
Copyright 2020 DIYthemes, LLC. Patent pending. All rights reserved.
License: DIYthemes Software License Agreement
License URI: https://diythemes.com/thesis/rtfm/software-license-agreement/
*/
class thesis_aioseo {
	private $post_meta = 'aioseo-settings';
	private $cpt = array(
		'thesis_modular',
		'focus_cards');

	public function __construct() {
		if (!function_exists('aioseo')) return;
		add_filter('thesis_boxes', array($this, 'remove_boxes'), 11);
		add_filter('hidden_meta_boxes', array($this, 'hide_post_meta'), 10, 3);
		add_filter('thesis_schema_use', '__return_false');
	}

	public function remove_boxes($boxes) {
		$remove = array(
			'thesis_title_tag',
			'thesis_meta_description',
			'thesis_meta_keywords',
			'thesis_meta_robots',
			'thesis_canonical_link');
		foreach ($remove as $box)
			unset($boxes[array_search($box, $boxes)]);
		return $boxes;
	}

	public function hide_post_meta($hidden, $screen, $use_defaults) {
		global $wp_meta_boxes;
		$hide = array();
		foreach ($this->cpt as $cpt)
			if ($screen->id === $cpt && isset($wp_meta_boxes[$cpt]))
				$hide[] = $this->post_meta;
		return array_merge($hidden, $hide);
	}
}