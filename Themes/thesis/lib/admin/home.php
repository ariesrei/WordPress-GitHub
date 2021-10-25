<?php
/*
Copyright 2015 DIYthemes, LLC. Patent pending. All rights reserved.
License: DIYthemes Software License Agreement
License URI: https://diythemes.com/thesis/rtfm/software-license-agreement/
*/
class thesis_admin_home {
	public function __construct() {
		add_action('after_switch_theme', array($this, 'home'));
		if (!empty($_GET['page']) && $_GET['page'] == 'thesis' && empty($_GET['canvas'])) {
			add_action('thesis_admin_canvas', array($this, 'admin'));
			add_action('thesis_admin_home', array($this, 'site'), 50);
		}
	}

	public function home() {
		wp_redirect(admin_url('admin.php?page=thesis'));
	}

	public function admin() {
		global $thesis;
		$skin = false;
		if (!empty($thesis->skin->_skin) && !empty($thesis->skin->_skin['name']))
			$skin = !empty($thesis->skin->_skin['version']) ? (!empty($thesis->skin->_skin['changelog']) ?
				sprintf(__('%1$s <a href="%2$s" target="_blank" rel="noopener">%3$s</a>', 'thesis'),
					$thesis->skin->_skin['name'],
					$thesis->skin->_skin['changelog'],
					$thesis->skin->_skin['version']) :
				sprintf(__('%1$s %2$s', 'thesis'), $thesis->skin->_skin['name'], $thesis->skin->_skin['version'])) :
				$thesis->skin->_skin['name'];
		echo
			(!is_dir(WP_CONTENT_DIR. '/thesis') ?
			"<p><a data-style=\"button save\" style=\"margin-bottom: 24px;\" href=\"".
			wp_nonce_url(admin_url('update.php?action=thesis-install-components'), 'thesis-install').
			"\">". __('Get started!', 'thesis'). "</a></p>" : ''),
			"\t\t<div class=\"t_canvas_left t_text\"", (!file_exists(WP_CONTENT_DIR. '/thesis') ? " style=\"opacity: 0.15;\"" : ''), ">\n",
			apply_filters('thesis_license_key_nag', ''),
			$this->update_nag(3);
		do_action('thesis_admin_home');
		echo
			"\t\t</div>\n",
			"\t\t<div class=\"t_canvas_right\">\n";
		// echo
		// 	"\t\t\t<div class=\"t_changelog\">\n",
		// 	"\t\t\t\t<h4>You are running:</h4>\n",
		// 	"\t\t\t\t<ul>\n",
		// 	(!empty($thesis->changelog) && !empty($thesis->version) ?
		// 	"\t\t\t\t\t<li>". sprintf(__('Thesis <a href="%1$s" target="_blank" rel="noopener">%2$s</a>', 'thesis'), $thesis->changelog, $thesis->version). "</li>\n" : ''),
		// 	(!empty($skin) ?
		// 	"\t\t\t\t\t<li>$skin</li>\n" : ''),
		// 	"\t\t\t\t</ul>\n",
		// 	"\t\t\t</div>\n";

		// do_action('thesis_current_skin');
		// $tip = $this->bubble_tips();
		// echo
		// 	"\t\t\t<div class=\"t_bubble\">\n",
		// 	"\t\t\t\t<p>{$tip['tip']}</p>\n",
		// 	"\t\t\t</div>\n",
		// 	"\t\t\t<div class=\"t_bubble_cite\">\n",
		// 	"\t\t\t\t<img class=\"t_bubble_pic\" src=\"{$tip['img']}\" alt=\"{$tip['name']}\" width=\"90\" height=\"90\" />\n",
		// 	"\t\t\t\t<p>{$tip['name']}</p>\n",
		// 	"\t\t\t</div>\n",
		// 	"\t\t</div>\n";
		echo "\t\t</div>\n";
	}

	private function update_nag($tab = 0) {
		global $thesis;
		if (empty($thesis->admin->updates))
			return '';
		$tab = str_repeat("\t", $tab);
		// return
		// 	"$tab<div class=\"t_update_alert\">\n".
		// 	"$tab\t<h3 class=\"t_update_available\">". __('Updates Available!', 'thesis'). "</h3>\n".
		// 	"$tab\t<ul>\n".
		// 	(!empty($thesis->admin->updates['core']) ?
		// 	"$tab\t\t<li>". sprintf(__('Thesis %1$s is available. <a href="%2$s">Update Now!</a>', 'thesis'), $thesis->admin->updates['core'], esc_url(wp_nonce_url('update.php?action=upgrade-theme&amp;theme=thesis', 'upgrade-theme_thesis'))). "</li>\n" : '').
		// 	(!empty($thesis->admin->updates['skins']) && is_array($thesis->admin->updates['skins']) ?
		// 	"$tab\t\t<li>". sprintf(__('You have <a href="%1$s">%2$s Skin update%3$s available</a>.', 'thesis'), esc_url(admin_url('admin.php?page=thesis&canvas=select_skin')), count($thesis->admin->updates['skins']), count($thesis->admin->updates['skins']) > 1 ? 's' : ''). "</li>\n" : '').
		// 	(!empty($thesis->admin->updates['boxes']) && is_array($thesis->admin->updates['boxes']) ?
		// 	"$tab\t\t<li>". sprintf(__('You have <a href="%1$s">%2$s Box update%3$s available</a>.', 'thesis'), esc_url(admin_url('admin.php?page=thesis&canvas=boxes')), count($thesis->admin->updates['boxes']), count($thesis->admin->updates['boxes']) > 1 ? 's' : ''). "</li>\n" : '').
		// 	"$tab\t</ul>\n".
		// 	"$tab</div>\n";

		return '';
	}

	public function site() {
		$items = '';
		foreach (apply_filters('thesis_site_menu', array()) as $item)
			if ($item['url'] !== '#')
				$items .= "\t\t\t\t<li><strong><a href=\"{$item['url']}\">{$item['text']}</a></strong>". (!empty($item['description']) ? ": {$item['description']}" : ''). "</li>\n";
		echo
			"\t\t\t<h3>", __('Sitewide Options', 'thesis'), "</h3>\n",
			"\t\t\t<p>", __('The following Sitewide Options provide extended functionality for your site, regardless of the Skin you&#8217;re using.', 'thesis'), "</p>\n",
			"\t\t\t<ul>\n",
			$items,
			"\t\t\t</ul>\n",
			"\t\t\t<p>", __('<strong>Note:</strong> You can also use the Site menu at the top of the screen to access this functionality.', 'thesis'), "</p>\n";
	}

	private function bubble_tips() {
		$authors = array(
			'presley' => array(
				'name' => 'Presley',
				'img' => 'presley.jpg'),
			'missieur' => array(
				'name' => 'Missieur',
				'img' => 'missieur.png'),
			'pearsonified' => array(
				'name' => 'Chris Pearson',
				'img' => 'pearsonified.png'));
		$tips = array(
			'age-of-social-media' => array(
				'tip' => sprintf(__('<strong>The internet has changed!</strong> <a href="%s">Has your site changed with it?</a>', 'thesis'), 'https://diythemes.com/nobody-browses-websites/'),
				'author' => 'pearsonified'),
			'truth' => array(
				'tip' => sprintf(__('<strong>You&#8217;ve been lied to!</strong> Discover <a href="%s">the truth about WordPress</a>.', 'thesis'), 'https://diythemes.com/truth/'),
				'author' => 'presley'),
			'gutenberg' => array(
				'tip' => sprintf(__('The <strong>Gutenberg Editor</strong> is destroying your website and making it slower. I <em>highly</em> recommend you stop using it immediately. <a href="%s">Here&#8217;s what you need to know.</a>', 'thesis'), 'https://diythemes.com/gutenberg-editor-problems/'),
				'author' => 'pearsonified'),
			'wp-editor' => array(
				'tip' => sprintf(__('Be sure to avoid these <a href="%s"><strong>WordPress Editor gotchas!</strong></a>', 'thesis'), 'https://diythemes.com/wordpress-editor-gotchas/'),
				'author' => 'presley'),
			'performance' => array(
				'tip' => sprintf(__('<strong>Faster is better!</strong> I&#8217;ll show you <a href="%s">how to test your pages for performance and optimization</a>.', 'thesis'), 'https://diythemes.com/performance-optimization-testing/'),
				'author' => 'pearsonified'),
			'category-seo' => array(
				'tip' => __('Supercharge the <abbr title="Search Engine Optimization">SEO</abbr> of your archive pages by supplying <strong>Archive Title</strong> and <strong>Archive Content</strong> information on the editing pages for categories, tags, and taxonomies.', 'thesis'),
				'author' => 'pearsonified'),
			'404page' => array(
				'tip' => sprintf(__('Thesis lets you control the content of your 404 page. Simply <a href="%s">specify a 404 page</a>, and boom&#8212;magic!', 'thesis'), admin_url('admin.php?page=thesis&canvas=thesis_404')),
				'author' => 'presley'),
			'blog' => array(
				'tip' => sprintf(__('In addition to making Thesis and <a href="%1$s">Focus</a>, DIYthemes publishes a <a href="%2$s">killer blog</a> dedicated to helping you run a better website.', 'thesis'), 'https://diythemes.com/focus/', 'https://diythemes.com/blog/'),
				'author' => 'pearsonified'),
			'verify' => array(
				'tip' => sprintf(__('You like ranking in search engines, don&#8217;t ya? Then be sure to verify your site with both Google and Bing Webmaster Tools on the <a href="%s">Site Verification page.</a>', 'thesis'), admin_url('admin.php?page=thesis&canvas=thesis_meta_verify')),
				'author' => 'presley'),
			'march-2008' => array(
				'tip' => sprintf(__('<strong>Did you know?</strong><br />Thesis launched on March 29, 2008. And <a href="%s">Focus</a> launched on February 6, 2019.', 'thesis'), 'https://diythemes.com/focus/'),
				'author' => 'pearsonified'),
			'seo-tips' => array(
				'tip' => sprintf(__('Besides using Thesis, what else can you do to improve your <abbr title="Search Engine Optimization">SEO</abbr>? Check out DIYthemes&#8217; series on <a href="%s">WordPress <abbr title="Search Engine Optimization">SEO</abbr> for Everybody</a>.', 'thesis'), 'https://diythemes.com/thesis/wordpress-seo/'),
				'author' => 'presley'),
			'blog-page-seo'	=> array(
				'tip' => sprintf(__('Amp up your site&#8217;s search engine performance by providing <a href="%1$s">Blog Page <abbr title="Search Engine Optimization">SEO</abbr></a> details.', 'thesis'), admin_url('admin.php?page=thesis&canvas=thesis_home_seo')),
				'author' => 'presley'),
			'email-marketing' => array(
				'tip' => sprintf(__('<strong>Did you know?</strong><br />Email marketing is probably the best way to leverage the web to grow your business. Get started today with DIYthemes&#8217; exclusive guide: <a href="%1$s">Email Marketing for Everybody</a>.', 'thesis'), 'https://diythemes.com/thesis/email-marketing-everybody/'),
				'author' => 'pearsonified'),
			'custom-templates' => array(
				'tip' => sprintf(__('No matter which Skin you use, you can always create custom templates in the <a href="%s">Skin Editor</a> for things like landing pages, checkout pages, and more.', 'thesis'), set_url_scheme(home_url('?thesis_editor=1'))),
				'author' => 'presley'),
			'custom-css' => array(
				'tip' => sprintf(__('<strong>How do I add custom <abbr title="Cascading Style Sheet">CSS</abbr>?</strong><br />With Thesis, you don&#8217;t need a separate file for your customizations&#8212;instead, you can organize everything in one place on the <a href="%s">Custom <abbr title="Cascading Style Sheet">CSS</abbr> page</a>.', 'thesis'), admin_url('admin.php?page=thesis&canvas=custom_css')),
				'author' => 'pearsonified'));
		$pick = $tips;
		shuffle($pick);
		$tip = array_shift($pick);
		$tip['name'] = $authors[$tip['author']]['name'];
		$tip['img'] = THESIS_IMAGES_URL. "/{$authors[$tip['author']]['img']}";
		return $tip;
	}
}