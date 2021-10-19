<?php

function header_css() {
echo '
<link rel="preload" href="https://ariesm.site/wp-content/thesis/skins/aries/fonts/Linearicons.ttf" as="font" type="font/ttf" crossorigin>
<link rel="preload" href="https://ariesm.site/wp-content/thesis/skins/aries/fonts/ionicons.ttf?v=2.0.0" as="font" type="font/ttf" crossorigin>
<link rel="preload" href="https://ariesm.site/wp-content/thesis/skins/aries/fonts/poppins-regular-webfont.woff2" as="font" type="font/woff2" crossorigin>

<link href="'.THESIS_USER_URL.'/skins/aries/css/pure.bootstrap.min.css" rel="stylesheet" media="screen">
<link href="'.THESIS_USER_URL.'/skins/aries/css/pure.font-awesome.min.css" rel="stylesheet" media="screen">
<link href="'.THESIS_USER_URL.'/skins/aries/css/pure.ionicons.min.css" rel="stylesheet" media="screen">
<link href="'.THESIS_USER_URL.'/skins/aries/css/pure.linearicons.min.css" rel="stylesheet" media="screen">
<link href="'.THESIS_USER_URL.'/skins/aries/css/jquery.fullPage.css" rel="stylesheet" media="screen">
<link href="'.THESIS_USER_URL.'/skins/aries/css/jquery.pagepiling.css" rel="stylesheet" media="screen">
<link href="'.THESIS_USER_URL.'/skins/aries/css/owl.carousel.css" rel="stylesheet" media="screen">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link href="'.THESIS_USER_URL.'/skins/aries/css/style.css" rel="stylesheet" media="screen">
';
}
add_action('wp_head', 'header_css', 0);


function footer_scripts() {
echo '
<script src="'.THESIS_USER_URL.'/skins/aries/js/jquery.min.js"></script>
<script src="'.THESIS_USER_URL.'/skins/aries/js/bootstrap.min.js"></script>
<script src="'.THESIS_USER_URL.'/skins/aries/js/smoothscroll.js"></script>
<script src="'.THESIS_USER_URL.'/skins/aries/js/jquery.validate.min.js"></script>
<script src="'.THESIS_USER_URL.'/skins/aries/js/owl.carousel.min.js"></script>
<script src="'.THESIS_USER_URL.'/skins/aries/js/jquery.pagepiling.js"></script>
<!-- Scripts -->
<script src="'.THESIS_USER_URL.'/skins/aries/js/scripts.js"></script> 
';
}
add_action('wp_footer', 'footer_scripts', 10); 