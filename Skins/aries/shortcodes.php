<?php

function custom_modular_content_fn($atts) {

	$atts = shortcode_atts(
        array(
            'id' => ''
        ),
        $atts,
        'custom_modular_content'
    );

 	ob_start();

	if(function_exists('get_field')):

 		if(!empty($atts['id'])) :

 			if($atts['id'] == 8) :
				$image = get_field('background_image', $atts['id']);
				echo '<div class="section-bg" style="background-image:url('.$image['url'].');"></div>
				<div class="scrollable-content">
				<div class="vertical-centred">
				<div class="boxed boxed-inner">
				<div class="vertical-title hidden-xs hidden-sm"><span>'.get_field('title',$atts['id']).'</span></div>
				<div class="boxed"><div class="container"><div class="intro"><div class="row">
				<div class="col-md-8 col-lg-6">
			 	'.get_field('content',$atts['id']).'
				<div class="hr-bottom"></div>
				</div></div></div></div></div></div></div></div>';
			endif;


			if($atts['id'] == 9) :
				$what_i_do_title = get_field('what_i_do_title', $atts['id']);
				$what_i_do_header_1 = get_field('what_i_do_header_1', $atts['id']);
				$what_i_do_header_2 = get_field('what_i_do_header_2', $atts['id']);
				$what_i_do_description = get_field('what_i_do_description', $atts['id']);
				$skills = get_field('skills', $atts['id']);
				echo '<div class="scrollable-content">
	            <div class="vertical-centred">
              	<div class="boxed boxed-inner">
                <div class="vertical-title text-dark hidden-xs hidden-sm"><span>'.$what_i_do_title.'</span></div>
                <div class="boxed">
              	<div class="container">
                <div class="intro">
              	<div class="row">
                <div class="col-md-5 col-lg-5">
                  <p class="subtitle-top text-dark">'.$what_i_do_header_1.'</p>
                  <h2 class="title-uppercase">'.$what_i_do_header_2.'</h2>
                  '.$what_i_do_description.'
                </div>
                <div class="col-md-6 col-lg-5 col-md-offset-1 col-lg-offset-2">
                  <div class="progress-bars">'.strip_tags(do_shortcode($skills),'<div>').'</div>
                </div></div></div></div></div></div></div></div>';
      		endif;

      		if($atts['id'] == 50) :

      		$works = get_field('featured_works', $atts['id']);
      		$works_image = get_field('featured_works_background', $atts['id']);

      		echo '<div class="bg-changer">'.do_shortcode($works_image).'</div>';
         	echo '<div class="scrollable-content">
	            <div class="vertical-centred">
              	<div class="boxed boxed-inner">
                <div class="vertical-title hidden-xs hidden-sm"><span>'.get_field('featured_works_title',$atts['id']).'		</span></div>
            	<div class="boxed">
              	<div class="container">
                <div class="intro">
              	<div class="row">
                <div class="col-md-12">
              	<h2 class="title-uppercase text-white">'.get_field('featured_works_header',$atts['id']).'</h2>
              	<div class="row-project-box row">'.strip_tags(do_shortcode($works), ['div','a','h5']).'</div>
              	</div></div></div></div></div></div></div></div>';
         	endif;

         	if($atts['id'] == 56) :
         	$services_list = get_field('services_list', $atts['id']);
         	$years = get_field('year', $atts['id']);
         	$image = $years['image']['url'];
         	$year = $years['years_of_experience'];

					$service = get_field('services_title', $atts['id']);

         	echo '<div class="scrollable-content">
            <div class="vertical-centred">
              <div class="boxed boxed-inner">
                <div class="vertical-title text-dark hidden-xs hidden-sm"><span>'.$service.'</span></div>
                <div class="boxed">
                  <div class="container">
                    <div class="intro">
                      <div class="row">
                        <div class="col-md-5 col-lg-5">
                          <p class="subtitle-top text-dark">'.get_field('services_header_1', $atts['id']).'</p>
                          <h2 class="title-uppercase">'.get_field('services_header_2', $atts['id']).'</h2>
                          <ul class="service-list">'.strip_tags(do_shortcode($services_list),['li','a']).'</ul>
                        </div>
                        <div class="col-md-6 col-lg-5 col-md-offset-1 col-lg-offset-2">
                          <div class="dots-image-2">
                            <img alt="" class="img-responsive" src="'.$image.'">
                            <div class="dots"></div>
                            <div class="experience-info">
                              <div class="number">'.$year.'</div>
                              <div class="text">Years<br>Experience<br>Working</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>';

        	endif;


        	if($atts['id'] == 58) :
        		$bgImg = get_field('experience_background_image', $atts['id']);
        		$education = get_field('education',$atts['id']);
        		$experience = get_field('experience',$atts['id']);

        		echo '<div class="section-bg" style="background-image:url('.$bgImg['url'].');"></div>
		          <div class="scrollable-content">
		            <div class="vertical-centred">
		              <div class="boxed boxed-inner">
		                <div class="vertical-title hidden-xs hidden-sm"><span>'.get_field('experience_title', $atts['id']).'</span></div>
		                <div class="boxed">
		                  <div class="container">
		                    <div class="intro">
		                      <div class="row">
		                        <div class="col-md-6">
		                          <div class="col-resume">
		                            <h6 class="resume-title">Education</h6>
		                            <div class="resume-content">
		                              <div class="resume-inner">'.do_shortcode($education).'</div>
		                            </div>
		                          </div>
		                        </div>
		                        <div class="col-md-6">
		                          <div class="col-resume">
		                            <h6 class="resume-title">Experience</h6>
		                            <div class="resume-content">
		                              <div class="resume-inner">'.do_shortcode($experience).'</div>
		                            </div>
		                          </div>
		                        </div>
		                       </div>
		                    </div>
		                  </div>
		                </div>
		              </div>
		            </div>
		          </div>';
        	endif;

        	if($atts['id'] == 74) :
        		$imgBg = get_field('testimonials_background_image', $atts['id']);
        		$testimonials = get_field('testimonials', $atts['id']);

        		echo '<div class="section-bg" style="background-image:url('.$imgBg['url'].');"></div>
		          <div class="scrollable-content">
		            <div class="vertical-centred">
		              <div class="boxed boxed-inner">
		                <div class="vertical-title hidden-xs hidden-sm"><span>'.get_field('testimonials_title',$atts['id']).'</span></div>
		                <div class="boxed">
		                  <div class="container">
		                    <div class="intro">
		                      <div class="row">
		                        <div class="col-md-6 col-lg-5">
		                          <span class="icon-quote ion-quote"></span>
		                          <h2 class="title-uppercase text-white">'.get_field('testimonials_header',$atts['id']).'</h2>
		                        </div>
		                        <div class="col-md-5 col-lg-5  col-md-offset-1 col-lg-offset-2">
		                          <div class="review-carousel owl-carousel">'.do_shortcode($testimonials).'</div>
		                        </div>
		                      </div>
		                    </div>
		                  </div>
		                </div>
		              </div>
		            </div>
		          </div>';
        	endif;

        	if($atts['id'] == 75) :
        		$contact_form = get_field('contact_form_shortcode',$atts['id']);
        		echo '<div class="scrollable-content">
            <div class="vertical-centred">
              <div class="boxed boxed-inner">
                <div class="vertical-title text-dark hidden-xs hidden-sm"><span>'.get_field('contacts_title', $atts['id']).'</span></div>
                <div class="boxed">
                  <div class="container">
                    <div class="intro overflow-hidden">
                      <div class="row">
                        <div class="col-md-12">
                          <h2 class="title-uppercase">'.get_field('contacts_header', $atts['id']).'</h2>
                          <div class="contact-info">'.do_shortcode($contact_form).'</div>
            </div></div></div></div></div></div></div></div>';
        	endif;

		endif;

  	endif;

  	return ob_get_clean();
}
add_shortcode('custom_modular_content', 'custom_modular_content_fn');


function skills_shortcode($atts) {

	$atts = shortcode_atts(
        array(
            'title' => '',
            'percentage' => ''
        ),
        $atts,
        'skills'
    );

	ob_start();

	echo '<div class="clearfix"><div class="number pull-left">'.$atts['title'].'</div>
		<div class="number pull-right">'.$atts['percentage'].'%</div></div>
		<div class="progress"><div class="progress-bar" role="progressbar" 
			style="width: '.$atts['percentage'].'%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
		</div>';

	return ob_get_clean();
}
add_shortcode('skills', 'skills_shortcode');

function works_bg_shortcode($atts) {

	$atts = shortcode_atts(
        array(
            'image' => '' 
        ),
        $atts,
        'works-bg'
    );

	ob_start();
	echo '<div class="section-bg" style="background-image:url('.$atts['image'].');"></div>'; 
	return ob_get_clean();
}
add_shortcode('works-bg', 'works_bg_shortcode');


function works_shortcode($atts) {

	$atts = shortcode_atts(
        array(
            'project-name' => '',
            'category' => '',
            'image' => '',
            'url' => ''
        ),
        $atts,
        'works'
    );

	ob_start();
	$url = (!empty($atts['url'])) ?: '#';
	echo '<div class="col-project-box col-sm-6 col-md-4 col-lg-3">
          <a href="'.$url.'" class="project-box">
            <div class="project-box-inner">
              <h5>'.$atts['project-name'].'</h5>
              <div class="project-category">'.$atts['category'].'</div>
            </div>
          </a>
        </div>'; 
	return ob_get_clean();
}
add_shortcode('works', 'works_shortcode');



function services_shortcode($atts) {
	$atts = shortcode_atts(
        array(
            'title' => '',
            'url' => ''
        ),
        $atts,
        'services'
    );

	ob_start();
	$url = (!empty($atts['url'])) ?: '#';
	echo '<li><a href="'.$url.'">'.$atts['title'].'</a></li>';
	return ob_get_clean();
}
add_shortcode('services', 'services_shortcode');



function education_shortcode($atts) {
	$atts = shortcode_atts(
        array(
            'title' => '',
            'school' => '',
            'school-year' => '',
            'description' => ''
        ),
        $atts,
        'education'
    );

	ob_start();
	$url = (!empty($atts['url'])) ?: '#';

	echo '<div class="resume-row">
        <h6 class="resume-type">'.$atts['title'].'</h6>
        <p class="resume-study">'.$atts['school'].'</p>
        <p class="resume-date text-primary">'.$atts['school-year'].'</p>
        <p class="resume-text">'.$atts['description'].'</p>
      </div>'; 

	return ob_get_clean();
}
add_shortcode('education', 'education_shortcode');

 
function experience_shortcode($atts) {
	$atts = shortcode_atts(
        array(
            'title' => '',
            'company' => '',
            'year' => '',
            'description' => ''
        ),
        $atts,
        'experience'
    );

	ob_start();
	$url = (!empty($atts['url'])) ?: '#';

	echo '<div class="resume-row">
        <h6 class="resume-type">'.$atts['title'].'</h6>
        <p class="resume-study">'.$atts['company'].'</p>
        <p class="resume-date text-primary">'.$atts['year'].'</p>
        <p class="resume-text">'.$atts['description'].'</p>
      </div>'; 

	return ob_get_clean();
}
add_shortcode('experience', 'experience_shortcode');



function testimonials_shortcode($atts) {
	$atts = shortcode_atts(
        array(
            'description' => '',
            'author' => '',
            'company' => ''
        ),
        $atts,
        'testimonials'
    );

	ob_start();
	echo '<div class="review-carousel-item">
	        <div class="text">
	        <p>“ '.$atts['description'].'"</p>
	        </div>
	        <div class="review-author">
	          <div class="author-name">'.$atts['author'].'</div>
	          <i>'.$atts['company'].'</i>
	        </div>
	      </div>'; 

	return ob_get_clean();
}
add_shortcode('testimonials', 'testimonials_shortcode');

