<?php
if (!current_user_can('manage_options')){
  	wp_die( __('You do not have sufficient permissions to access this page.') );
}

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

$arr_val = [];
$updated = false;

foreach($arr_options as &$val) {

	$val = strtolower(str_replace(' ', '_', $val));

	$arr_val[] = ${'opt_'.$val} = $val;
	${'hidden_field_'.$val} = $val.'_hidden';
	${'data_field_'.$val} = $val;
	${'opt_val_'.$val} = get_option( ${'opt_'.$val});

}

foreach($arr_val as &$aVal) {

	if( isset($_POST[ ${'hidden_field_'.$aVal} ]) && $_POST[ ${'hidden_field_'.$aVal} ] == 'Y' ) {

		${'opt_val_'.$aVal} = $_POST[ ${'data_field_'.$aVal} ];

		require "lib/update-option.php";
		$updated = true;	
	}
}
?>
	<div class="updated" style="<?php echo($updated?'display:block':'display:none')?> ">
		<p><strong><?php _e('settings saved.', 'web-optimization' ); ?></strong></p>
	</div>
<?php
/* -------------------------------------------------------------- */
echo "<h2>" . __( 'WEB OPTIMIZATION', 'web-optimization' ) . " <span style='font-size: 12px;'>by: wpwebguru.tech</span></h2>";
?>
<style>
	.web-opti table {
		width: 100%;
		border: 1px #ddd solid;
		border-collapse: collapse;
	}
	.web-opti table td {
		padding: 5px;
		border: 1px #ddd solid;
	}
	.w_o_notice {
		font-size: 12px;
		color: red;
		font-weight: bold;
	}
</style>
 
<form name="form1" method="post" action="" class="web-opti">
<input type="hidden" name="<?php echo $hidden_field_remove_jquery; ?>" value="Y">
<input type="hidden" name="<?php echo $hidden_field_remove_js_css_strings; ?>" value="Y">
<input type="hidden" name="<?php echo $hidden_field_force_load_async; ?>" value="Y">
<input type="hidden" name="<?php echo $hidden_field_force_to_defer; ?>" value="Y">
<input type="hidden" name="<?php echo $hidden_field_minify_html; ?>" value="Y">
<input type="hidden" name="<?php echo $hidden_field_remove_js_css_types; ?>" value="Y">
<input type="hidden" name="<?php echo $hidden_field_contact_form_7; ?>" value="Y">
<input type="hidden" name="<?php echo $hidden_field_wpc7_pid; ?>" value="Y">

<p><strong><?php _e("Enable / Disable Settings here", 'web-optimization' ); ?></strong></p> 

<table>
	<tr>
		<td>
			<input type="checkbox" name="<?php echo $data_field_remove_jquery; ?>" <?=(!empty($opt_val_remove_jquery))?'checked':''?> >
		</td>
		<td> Remove jquery migrate for enhanced performance  <span class="w_o_notice">(If your wp site requires jquery, then don't disable this)</span></td>
	</tr>
	<tr>
		<td>
			<input type="checkbox" name="<?php echo $data_field_remove_js_css_strings; ?>" <?=(!empty($opt_val_remove_js_css_strings))?'checked':''?>>
		</td>
		<td> Remove query strings from CSS and JS inclusions. <span class="w_o_notice">( remove ?ver=1.x.x )</span></td>
	</tr>
	<tr>
		<td>
			<input type="checkbox" name="<?php echo $data_field_force_load_async; ?>" <?=(!empty($opt_val_force_load_async))?'checked':''?>>
		</td>
		<td> Force scripts to load asynchronously for better performance</td>
	</tr>
 	<tr>
		<td>
			<input type="checkbox" name="<?php echo $data_field_force_to_defer; ?>" <?=(!empty($opt_val_force_to_defer))?'checked':''?> >
		</td>
		<td>Force scripts to defer for better performance</td>
	</tr>
	<tr>
		<td>
			<input type="checkbox" name="<?php echo $data_field_minify_html; ?>" <?=(!empty($opt_val_minify_html))?'checked':''?> >
		</td>
		<td>Minify HTML</td>
	</tr>
	<tr>
		<td>
			<input type="checkbox" name="<?php echo $data_field_remove_js_css_types; ?>" <?=(!empty($opt_val_remove_js_css_types))?'checked':''?> >
		</td>
		<td>Remove "type=javascript/css". W3C Validator Warnings </td>
	</tr>

	<tr>
		<td>
			<input type="checkbox" name="<?php echo $data_field_contact_form_7; ?>" <?=(!empty($opt_val_contact_form_7))?'checked':''?> >
		</td>
		<td> Load Contact Form 7 assets on a specific page <br/></td>
	</tr>

	<tr>
		<td> </td>
		<td> <input type="text" name="<?=$data_field_wpc7_pid?>" value="<?=$opt_val_wpc7_pid?>"> Enter page id separated by a comma. </td>
	</tr>

</table>

<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>
</form>