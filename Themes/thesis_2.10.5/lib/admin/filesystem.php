<?php
/*
Copyright 2012 DIYthemes, LLC. Patent pending. All rights reserved.
License: DIYthemes Software License Agreement
License URI: https://diythemes.com/thesis/rtfm/software-license-agreement/
*/
class thesis_filesystem {
	public function __construct() {
		global $pagenow;
		if ($pagenow == 'update.php') {
			require_once(ABSPATH. 'wp-admin/includes/class-wp-upgrader.php');
			require_once(THESIS_ADMIN. '/wp-upgrader.php');
			add_action('update-custom_thesis-install-components', array($this, 'install')); // initial Thesis install
			add_action('update-custom_thesis_update_objects', array($this, 'update')); 		// update Skins or Boxes
			add_action('update-custom_thesis_delete_object', array($this, 'delete')); 		// delete Skins or Boxes
			add_action('update-custom_thesis_generate_skin', array($this, 'generate_zip'));	// generate .zip
		}
		// Installation
		add_action('after_switch_theme', array($this, 'install_branch'));
		if (defined('THESIS_USER_SKINS') && is_dir(THESIS_USER_SKINS) && !is_dir(WP_CONTENT_DIR. '/thesis/classic-r') && is_dir(THESIS_SKINS. '/classic-r'))
			$this->install_classic_r();
		// Updates
		add_filter('update_theme_complete_actions', array($this, 'admin_update_complete'), 10, 2);
	}

	public function install() {
		global $thesis;
		if (!current_user_can('manage_options') || !wp_verify_nonce($_REQUEST['_wpnonce'], 'thesis-install'))
			wp_die(__('You are not allowed to install Thesis.', 'thesis'));
		require_once(ABSPATH. 'wp-admin/admin-header.php');
		$install = new thesis_install(new WP_Upgrader_Skin(array(
			'title' => __('Install Thesis', 'thesis'))));
		$install->run();
		$thesis->api->delete_transients(true);
		include(ABSPATH. 'wp-admin/admin-footer.php');
	}

	public function update() {
		global $thesis;
		if (!current_user_can('manage_options') || !wp_verify_nonce($_REQUEST['_wpnonce'], 'thesis-update-objects'))
			wp_die(__('You are not allowed to update Thesis objects.', 'thesis'));
		require_once(ABSPATH. 'wp-admin/admin-header.php');
		$update = new thesis_update_objects(new thesis_update_objects_skin(array(
			'title' => sprintf(__('Update Thesis %1$s: %2$s', 'thesis'), $thesis->api->efh(ucfirst($_GET['type'])), $thesis->api->efh($_GET['name'])))));
		$update->update();
		$thesis->api->delete_transients();
		if ($_GET['type'] == 'skin' && !empty($_GET['class']))
			$thesis->api->hook('thesis_after_update_skin', array_filter(array(
				'class' => !empty($_GET['class']) ? $_GET['class'] : false,
				'version' => !empty($_GET['version']) ? $_GET['version'] : false,
				'folder' => !empty($_GET['folder']) ? $_GET['folder'] : false)));
		elseif ($_GET['type'] == 'box')
			$thesis->api->hook('thesis_after_update_box');
		include(ABSPATH. 'wp-admin/admin-footer.php');
	}

	public function delete() {
		global $thesis;
		if (!current_user_can('manage_options'))
			wp_die(__('You are not allowed to delete Thesis or its components.', 'thesis'));
		check_admin_referer('thesis-delete-object');
		if (empty($_GET['thesis_object_class']) || empty($_GET['thesis_object_name']) || empty($_GET['thesis_object_type']) || !in_array($_GET['thesis_object_type'], array('skin', 'box', 'package')))
			wp_die(__('The passed object data is either incorrect or incomplete.', 'thesis'));
		add_action('admin_head', array('thesis_upload', 'admin_css'));
		require_once(ABSPATH. 'wp-admin/admin-header.php');
		$delete = new thesis_delete(new thesis_delete_skin(array(
			'title' => sprintf(__('Delete Thesis %1$s: %2$s', 'thesis'), ucfirst(esc_attr($_GET['thesis_object_type'])), ucfirst(esc_attr($_GET['thesis_object_name']))))));
		$delete->delete_object($_GET['thesis_object_type'], $_GET['thesis_object_class']);
		$thesis->api->delete_transients();
		include(ABSPATH. 'wp-admin/admin-footer.php');
	}

	public function generate_zip() {
		if (!current_user_can('manage_options'))
			wp_die(__('You are not allowed to create .zip files.', 'thesis'));
		check_admin_referer('thesis-generate-skin');
		if (empty($_GET['skin']))
			wp_die(__('The Skin class was passed as empty.', 'thesis'));
		require_once(ABSPATH. 'wp-admin/admin-header.php');
		$generate = new thesis_generate(new thesis_generate_skin(array(
			'title' => __('Create Skin .zip File', 'thesis'))));
		$generate->generate();
		include(ABSPATH. 'wp-admin/admin-footer.php');
	}

	public function install_branch() {
		include_once(ABSPATH. '/wp-admin/includes/file.php');
		if (get_filesystem_method() === 'direct' && !is_dir(WP_CONTENT_DIR. '/thesis') && is_dir(THESIS_SKINS)) {
			// first, set up wp_filesystem
			WP_Filesystem();
			$f = $GLOBALS['wp_filesystem'];
			// directories
			$directories = array(
				'thesis/',
				'thesis/boxes/',
				'thesis/skins/');
			foreach ($directories as $dir)
				$f->mkdir($f->wp_content_dir(). $dir);
			// master.php
			$f->put_contents($f->wp_content_dir(). 'thesis/master.php',
				"<?php\n".
				"/*\n".
				"Any hooks or filters you add here will affect your site, regardless of the Skin you’re using.\n".
				"Also, you can use this Thesis master.php file to affect every site on your network.\n".
				"*/");
			// move Skins
			$from = trailingslashit($f->find_folder(THESIS_SKINS));
			$to = $f->wp_content_dir(). 'thesis/skins/';
			$skins = array_keys($f->dirlist($from));
			foreach ($skins as $skin) {
				$f->move($from. $skin, $to. $skin);
				if (!$f->exists($to.$skin.'/images'))
					$f->mkdir($to.$skin.'/images');
				if (!$f->exists($to.$skin.'/custom.php'))
					$f->put_contents($to.$skin.'/custom.php',
						"<?php\n".
						"/*\n".
						"This file is for Skin-specific customizations. Do not change your Skin’s skin.php file,\n".
						"as that will be upgraded in the future and your work will be lost.\n\n".
						"If you are comfortable with PHP, you can make more powerful customizations by using the\n".
						"Thesis Box system to create elements you can interact with in the Thesis HTML Editor.\n\n".
						"For more information, please visit: https://diythemes.com/thesis/rtfm/api/box/\n".
						"*/");
			}
			// clean up
			if (($lib = array_keys($f->dirlist(THESIS_SKINS))) && empty($lib))
				$f->delete(THESIS_SKINS);
		}
	}

	public function install_classic_r() {
		include_once(ABSPATH. '/wp-admin/includes/file.php');
		if (get_filesystem_method() === 'direct') {
			if (empty($GLOBALS['wp_filesystem']) || !is_object($GLOBALS['wp_filesystem']))
				WP_Filesystem();
			$f = $GLOBALS['wp_filesystem'];
			if (!$f->move(THESIS_SKINS. '/classic-r', THESIS_USER_SKINS. '/classic-r'))
				return;
		}
	}

	public function admin_update_complete($update_actions, $theme) {
		global $thesis;
		$thesis->api->delete_transients(true);
		return $theme == 'thesis' ?
			'<a class="button button-primary button-large" href="'. wp_nonce_url(admin_url('admin.php?page=thesis'), 'thesis_did_update'). '">'. __('Return to Thesis &rarr;', 'thesis'). '</a>' :
			$update_actions;
	}
}