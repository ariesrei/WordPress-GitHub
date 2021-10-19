<?php

global $frontpage_id;
$frontpage_id = get_option('page_on_front');
//$GLOBALS['frontpage_id'];


/*
// hook_top_{}
// hook_after_{} 
// hook_before_{}
*/


function custom_hook_loader() {
?>
	<div class="loader"><div class="spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div></div></div>
   	<div class="click-capture"></div>
<?php
}
add_action('hook_before_loader', 'custom_hook_loader');


function custom_hook_menu() { 
?>
	<span class="close-menu icon-cross2 right-boxed"></span>
	<!--<div class="menu-lang right-boxed">
        <a href="" class="active">Eng</a>
        <a href="">Fra</a>
        <a href="">Ger</a>
  	</div>-->

	<ul class="menu-list right-boxed">
	<li  data-menuanchor="introduction"><a  href="#introduction">Home</a></li>
	<li  data-menuanchor="what-i-do"><a href="#what-i-do">Specialization</a></li>
	<li  data-menuanchor="featured-works"><a href="#featured-works">Projects</a></li>
	<li  data-menuanchor="services"><a href="#services">Services</a></li>
	<li  data-menuanchor="testimonials"><a href="#testimonials">Reviews</a></li>
	<li  data-menuanchor="contacts"><a href="#contacts">Contact</a></li>
	</ul>

	<div class="menu-footer right-boxed">

		<div class="social-list">

		<?php 

			if(function_exists('get_field')): 

			$facebook = get_field('facebook', $GLOBALS['frontpage_id']);
			$twitter = get_field('twitter', $GLOBALS['frontpage_id']);
			$linkedin = get_field('linkedin', $GLOBALS['frontpage_id']);
 			$instagram = get_field('instagram', $GLOBALS['frontpage_id']);
 
			if(!empty($facebook)) echo '<a href="'.$facebook.'" class="icon ion-social-facebook"></a>';
			if(!empty($twitter)) echo '<a href="'.$twitter.'" class="icon ion-social-twitter"></a>';
			if(!empty($linkedin)) echo '<a href="'.$linkedin.'" class="icon ion-social-linkedin"></a>';
			if(!empty($instagram)) echo '<a href="'.$instagram.'" class="icon ion-social-instagram"></a>';

			endif;
		?>	
        </div>
        <div class="copy">© 2021 Aries M. All Rights Reseverd.</div>
  	</div>

<?php
}
add_action('hook_top_menu', 'custom_hook_menu');


function custom_hook_header() { 
?>
	<div class="navbar-bg"></div>
	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
	</button>

	<a class="brand" href="<?=site_url()?>">
		<img class="brand-img" alt="" src="https://ariesm.site/wp-content/thesis/skins/aries/images/brand.png">
		<div class="brand-info">
			<div class="brand-name"><?=get_bloginfo('name')?></div>
			<div class="brand-text"><?=get_bloginfo('description')?></div>
		</div>
	</a>

	<div class="social-list hidden-xs">
		<?php 

			if(function_exists('get_field')): 

			$facebook = get_field('facebook', $GLOBALS['frontpage_id']);
			$twitter = get_field('twitter', $GLOBALS['frontpage_id']);
			$linkedin = get_field('linkedin', $GLOBALS['frontpage_id']);
 			$instagram = get_field('instagram', $GLOBALS['frontpage_id']);
 
			if(!empty($facebook)) echo '<a href="'.$facebook.'" class="icon ion-social-facebook"></a>';
			if(!empty($twitter)) echo '<a href="'.$twitter.'" class="icon ion-social-twitter"></a>';
			if(!empty($linkedin)) echo '<a href="'.$linkedin.'" class="icon ion-social-linkedin"></a>';
			if(!empty($instagram)) echo '<a href="'.$instagram.'" class="icon ion-social-instagram"></a>';

			endif;
		?>		
	</div>

<?php
}
add_action('hook_top_header', 'custom_hook_header');