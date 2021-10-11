<?php
/*
Copyright 2020 DIYthemes, LLC. Patent pending. All rights reserved.
License: DIYthemes Software License Agreement
License URI: https://diythemes.com/thesis/rtfm/software-license-agreement/
*/
class thesis_edd {
	public $post_class = false;			// The active Skin can provide a post class for valet formatting purposes
	public $cpt = 'download';
	public $exclude = array(
		'post_types' => array(
			'logs',
			'payment',
			'discount',
			'license',
			'post'),
		'taxonomies' => array(
			'logs',
			'payments',
			'discounts',
			'licenses',
			'posts'));

	public function __construct() {
		if (!class_exists('Easy_Digital_Downloads')) return;
		add_action('init', array($this, 'post_meta'), 100);
		add_filter('thesis_exclude_template_types', array($this, 'exclude_template_types'));
		add_filter('thesis_exclude_query_types', array($this, 'exclude_query_types'));
//		add_action('template_redirect', array($this, 'edd'));
	}

	public function post_meta() {
		global $thesis;
		$post_meta = apply_filters('thesis_edd_post_meta_cpt', !empty($thesis->box_post_meta) ? $thesis->box_post_meta : array());
		foreach ($post_meta as $meta)
			add_filter("{$meta}_post_meta_cpt", array($this, 'filter_post_meta'));
	}

	public function filter_post_meta($meta) {
		$meta[] = $this->cpt;
		return $meta;
	}

	public function exclude_template_types($template_types) {
		foreach (array_merge($this->exclude['post_types'], $this->exclude['taxonomies']) as $type)
			$template_types[] = $type;
		return $template_types;
	}

	public function exclude_query_types($query_types) {
		foreach ($this->exclude['post_types'] as $type)
			$query_types[] = $type;
		return $query_types;
	}

	public function edd() { }

	public function strip_scripts() { }

	public function post_class($classes) {
		$classes[] = $this->post_class;
		return $classes;
	}
}