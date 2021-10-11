<?php

function header_css() {
echo '
<link href="'.THESIS_USER_URL.'/skins/wpwebguru/vendor/fontawesome/css/light.min.css" rel="stylesheet"/>
';
}
add_action('wp_head', 'header_css', 10);


function footer_scripts() {

if(!is_404()) {  
echo '
<script src="'.THESIS_USER_URL.'/skins/wpwebguru/js/jquery.min.js"></script>
<script src="'.THESIS_USER_URL.'/skins/wpwebguru/js/animateheader/cbpAnimatedHeader.min.js"></script>
<script src="'.THESIS_USER_URL.'/skins/wpwebguru/js/animateheader/classie.js"></script>
<script src="'.THESIS_USER_URL.'/skins/wpwebguru/js/animateheader/modernizr.custom.js"></script>
<script src="'.THESIS_USER_URL.'/skins/wpwebguru/vendor/svg-injector/svg-injector.min.js"></script>
<script src="'.THESIS_USER_URL.'/skins/wpwebguru/js/custom.js"></script>
';
}
}
add_action('wp_footer', 'footer_scripts', 10);