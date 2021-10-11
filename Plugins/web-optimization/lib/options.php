<?php 
$arr_options = [
	'Remove Jquery',
	'Remove JS CSS Strings',
	'Force Load Async',
	'Force To Defer',
	'Minify Html',
	'Remove JS CSS Types',
	'Contact Form 7',
	'Wpc7 Pid'
];

foreach($arr_options as &$val) {

	$val = strtolower(str_replace(' ', '_', $val));
	${'opt_'.$val} = $val;
	${'opt_val_'.$val} = get_option( ${'opt_'.$val});
}