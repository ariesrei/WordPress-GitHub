<?php
/*
Copyright 2015 DIYthemes, LLC. Patent pending. All rights reserved.
License: DIYthemes Software License Agreement
License URI: https://diythemes.com/thesis/rtfm/software-license-agreement/
*/
class thesis_license_key {
	private $product = 148383;
	private $name = 'Thesis';
	private $class = 'thesis';
	private $admin_url = 'admin.php?page=thesis&canvas=license_key';
	private $response_key = 'thesis-update-response';
	private $license_key = false;
	private $thesis = false;

	public function __construct() {
		if (!current_user_can('manage_options')) return;
		$this->license_key = trim(get_option('thesis_license_key'));
		// Update filters
		add_filter('thesis_transient', array($this, 'thesis_transient'));
		add_filter('thesis_updates', array($this, 'updates'));
		add_filter('thesis_license_key_nag', array($this, 'license_key_nag'));
		add_filter('site_transient_update_themes', array($this, 'theme_update_transient'));
		add_filter('delete_site_transient_update_themes', array($this, 'delete_theme_update_transient'));
		add_filter('http_request_args', array($this, 'disable_wporg_request'), 5, 2);
		// Update actions
		add_action('load-update-core.php', array($this, 'delete_theme_update_transient'));
		add_action('load-themes.php', array($this, 'delete_theme_update_transient'));
		add_action('admin_notices', array($this, 'update_nag'));
		if (empty($_GET['page']) || !($_GET['page'] == 'thesis'))
			$this->delete_theme_update_transient();
		// License key admin page
		add_filter('thesis_site_menu', array($this, 'thesis_menu'), 1);
		add_filter('thesis_quicklaunch_menu', array($this, 'quicklaunch_menu'), 60);
		add_action('admin_post_thesis_license_key', array($this, 'save'));
		if (!empty($_GET['canvas']) && $_GET['canvas'] == 'license_key') {
			add_action('admin_init', array($this, 'admin_js'));
			add_action('thesis_admin_canvas', array($this, 'admin'));
		}
	}

	public function thesis_transient() {
		return $this->response_key;
	}

	public function updates($updates) {
		$this->thesis = $this->check_update_thesis();
		$this->check_update_components();
		if (!empty($this->thesis) && !empty($this->thesis['new_version']) && !empty($this->thesis['package']))
			$updates['core'] = $this->thesis['new_version'];
		foreach (array('skins', 'boxes') as $type)
			if (($update = get_transient("thesis_{$type}_update")) && !empty($update))
				$updates[$type] = $update;
		return $updates;
	}

	public function license_key_nag($nag) {
		if (!empty($this->license_key)) return $nag;
		$tab = str_repeat("\t", 3);
		// return $nag.
		// 	"$tab<div class=\"t_update_alert\">\n".
		// 	"$tab\t<h3 class=\"t_update_available\">". __('Please enter a Thesis license key!', 'thesis'). "</h3>\n".
		// 	"$tab\t<p>". sprintf(__('You&rsquo;ll need to <a href="%1$s">enter a valid Thesis license key</a> to receive updates on Thesis, Skins, and Boxes.', 'thesis'), admin_url($this->admin_url)). "</p>\n".
		// 	"$tab</div>\n";

		return '';
	}

	public function theme_update_transient($value) {
		if (!empty($this->thesis) && is_object($value)) {
			$this->thesis['theme'] = $this->class;
			$value->response[$this->class] = $this->thesis;
		}
		return $value;
	}

	public function delete_theme_update_transient() {
		delete_transient($this->response_key);
	}

	public function admin() {
		global $thesis;
		$status = get_option("{$this->class}_license_key_status", false);
		$message = !empty($_GET['msg']) ? urldecode($_GET['msg']) : get_transient("{$this->class}_license_message", false);
		if (empty($message) && !empty($this->license_key)) {
			set_transient("{$this->class}_license_message", $thesis->api->check_license(
				$this->license_key,
				$this->product,
				$this->name,
				$this->class,
				$this->admin_url,
				$this->response_key), 60*60*12);
			$message = get_transient("{$this->class}_license_message");
		}
		$license = $thesis->api->form->fields(array(
			'license_key' => array(
				'type' => 'text',
				'width' => 'long',
				'code' => true,
				'label' => __('Enter Your Thesis License Key', 'thesis'),
				'tooltip' => sprintf(__('Check out the <a href="%1$s" target="_blank" rel="noopener noreferrer">Thesis License Key documentation</a> for everything you need to know about license keys.', 'thesis'), esc_url('https://diythemes.com/thesis/rtfm/how-to/license-key/')))), !empty($this->license_key) ? array('license_key' => $this->license_key) : array(), 't_license_', false, 3, 3);
		echo
			"\t\t<h3>", __('Thesis License Key', 'thesis'), "</h3>\n",
			"\t\t<form class=\"thesis_options_form\" method=\"post\" action=\"", esc_url(admin_url('admin-post.php?action=thesis_license_key')), "\" id=\"t_license\">\n",
			"\t\t\t{$license['output']}\n",
			(!empty($message) ?
			"\t\t\t<div class=\"status\">$message</div>\n" : ''),
			"\t\t\t<button data-style=\"button save\" name=\"save_options\" value=\"1\"><span data-style=\"dashicon big squeeze\">&#xf147;</span> ", $thesis->api->efn(__('Save License Key', 'thesis')), "</button>\n",
			"\t\t\t<button data-style=\"button delete inline\" name=\"delete_options\" value=\"1\"><span data-style=\"dashicon\">&#xf153;</span> ", $thesis->api->efn(__('Delete License Key', 'thesis')), "</button>\n",
			wp_nonce_field('thesis_license_key', '_wpnonce', true, false),
			"\t\t</form>\n";
	}

	public function admin_js() {
		wp_enqueue_script('thesis-options');
	}

	public function save() {
		global $thesis;
		$thesis->wp->check();
		check_admin_referer('thesis_license_key');
		if (!empty($_POST['save_options']) && !empty($_POST['license_key']) && ($this->license_key = trim($_POST['license_key']))) {
			$thesis->api->delete_transients(true);
			$thesis->api->activate(
				$this->license_key,
				$this->product,
				$this->name,
				$this->class,
				$this->admin_url,
				$this->response_key);
		}
		elseif (empty($_POST['license_key']) || !empty($_POST['delete_options'])) {
			$old_key = $this->license_key;
			$this->license_key = false;
			$thesis->api->delete_transients(true);
			$thesis->api->deactivate(
				$old_key,
				$this->product,
				$this->name,
				$this->class,
				$this->admin_url,
				$this->response_key);
		}
		wp_redirect(admin_url($this->admin_url));
		exit;
	}

	public function update_nag() {
		global $thesis;
		$api_response = get_transient($this->response_key);
		if ($api_response === false)
			return;
		if (version_compare($thesis->version, $api_response->new_version, '<') && !empty($api_response->package)) {
			$theme = wp_get_theme($this->class);
			echo '<div class="update-nag notice notice-warning">';
			printf(
				__('<strong>%1$s %2$s</strong> is available. <a href="%3$s" target="_blank" rel="noopener noreferrer">Check out what&rsquo;s new</a> or <a href="%5$s"%6$s>update now</a>.', 'thesis'),
				$theme->get('Name'),
				$api_response->new_version,
				"{$thesis->store}/thesis/rtfm/changelog/v". str_replace('.', '', $api_response->new_version). '/',
				$theme->get('Name'),
				wp_nonce_url('update.php?action=upgrade-theme&amp;theme='. urlencode($this->class), 'upgrade-theme_'. $this->class),
				' onclick="if (confirm(\''. esc_js(__("Proceed with Theme update? 'Cancel' to stop, 'OK' to update.", 'thesis')). '\')) {return true;}return false;"');
			echo '</div>';
			echo '<div id="'. $this->class. '_'. 'changelog" style="display:none;">';
			echo wpautop($api_response->sections['changelog']);
			echo '</div>';
		}
	}

	private function check_update_thesis() {
		global $thesis;
		$update_data = get_transient($this->response_key);
		if ($update_data === false) {
			$failed = false;
			$response = $thesis->api->post($thesis->store, array(
				'edd_action' => 'get_version',
				'license' => $this->license_key,
				'item_id' => $this->product,
				'name' => $this->name,
				'slug' => $this->class,
				'version' => $thesis->version,
				'author' => 'Chris Pearson | DIYthemes',
				'beta' => false));
			$update_data = json_decode(wp_remote_retrieve_body($response));
			if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200 || !is_object($update_data))
				$failed = true;
			if ($failed) {
				$data = new stdClass;
				$data->new_version = $thesis->version;
				set_transient($this->response_key, $data, 60*30);
				return false;
			}
			else {
				$update_data->sections = maybe_unserialize($update_data->sections);
				set_transient($this->response_key, $update_data, 60*60*12);
			}
		}
		if (version_compare($thesis->version, $update_data->new_version, '>='))
			return false;
		return (array) $update_data;
	}

	private function check_update_components() {
		global $thesis;
		if (empty($this->license_key) || get_transient('thesis_component_callout'))
			return;
		$thesis->api->delete_transients();
		set_transient('thesis_component_callout', time(), 60*60*12);
		$objects = array(
			'skins' => thesis_skins::get_items(),
			'boxes' => thesis_user_boxes::get_items());
		$transients = array(
			'skins' => 'thesis_skins_update',
			'boxes' => 'thesis_boxes_update');
		$all = array();
		foreach ($objects as $type => $object)
			if (!empty($object) && is_array($object))
				foreach ($object as $class => $data)
					$all[$type][$class] = $data['version'];
		$all['thesis'] = $thesis->version;
		foreach ($transients as $key => $transient)
			if (get_transient($transient))
				unset($all[$key]);
		if (empty($all))
			return;
		$body = array(
			'data' => $all,
			'wp' => $GLOBALS['wp_version'],
			'php' => phpversion(),
			'thesis_key' => $this->license_key,
			'user-agent' => "WordPress/{$GLOBALS['wp_version']};". home_url());
		$focus_key = trim(get_option('thesis_focus_license_key', false));
		if (!empty($focus_key)) {
			$focus_response = $thesis->api->post($thesis->store, array(
				'edd_action' => 'check_license',
				'license' => $focus_key,
				'item_id' => 148293,
				'item_name' => 'Focus',
				'url' => home_url()));
			if (!is_wp_error($focus_response) && wp_remote_retrieve_response_code($focus_response) === 200) {
				$license_data = json_decode(wp_remote_retrieve_body($focus_response));
				if (!empty($license_data) && isset($license_data->license) && $license_data->license === 'valid')
					$body['thesis_focus'] = $license_data->license;
				elseif (!empty($body['skins']) && !empty($body['skins']['thesis_focus']))
					unset($body['skins']['thesis_focus']);
			}
		}
		$body['data'] = serialize($body['data']);
		$url = apply_filters('thesis_update_url', 'http://thesisapi.com/updates/update.php');
		$post = $thesis->api->post($url, $body);
		if (is_wp_error($post) || empty($post['body']))
			return;
		$returned = @unserialize($post['body']);
		if (!is_array($returned))
			return;
		foreach ($returned as $type => $data) // will only return the data we need to update
			if (in_array("thesis_{$type}_update", $transients))
				set_transient("thesis_{$type}_update", $returned[$type], 60*60*12);
	}

	public function thesis_menu($menu) {
		$menu['license'] = array(
			'id' => 't_license',
			'text' => __('Thesis License Key', 'thesis'),
			'url' => admin_url($this->admin_url),
			'description' => __('Enter a valid Thesis License Key to receive automatic updates', 'thesis'));
		return $menu;
	}

	public function quicklaunch_menu($menu) {
		$menu['license'] = array(
			'text' => __('Thesis License Key', 'thesis'),
			'url' => $this->admin_url,
			'title' => __('Enter a valid license key for automatic updates.', 'thesis'));
		return $menu;
	}

	public function disable_wporg_request($r, $url) {
		if (strpos($url, 'https://api.wordpress.org/themes/update-check/1.1/') !== 0)
			return $r;
		$themes = json_decode($r['body']['themes']);
		$parent = get_option('template');
		$child = get_option('stylesheet');
		unset($themes->themes->$parent);
		unset($themes->themes->$child);
		$r['body']['themes'] = json_encode($themes);
		return $r;
	}
}