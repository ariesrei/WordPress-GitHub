/*
Copyright 2012 DIYthemes, LLC. Patent pending. All rights reserved.
License: DIYthemes Software License Agreement
License URI: https://diythemes.com/thesis/rtfm/software-license-agreement/
*/
jQuery(document).ready(function($) {
	$("#post").attr('enctype', 'multipart/form-data');
	$("#post").attr('encoding', 'multipart/form-data');
	$('.option_field label .toggle_tooltip').on('click', function() {
		$(this).parents('label').parents('p').siblings('.tooltip:first').toggle();
		return false;
	});
	$('.option_field .list_label .toggle_tooltip').on('click', function() {
		$(this).parents('.list_label').siblings('.tooltip:first').toggle();
		return false;
	});
	$('.tooltip').on('mouseleave', function() { $(this).hide(); });
	$('.count_field').each(function() {
		var count = $(this).val().length;
		$(this).siblings('.counter').val(count);
		$(this).siblings('label').children('.counter').val(count);
	}).keyup(function() {
		var count = $(this).val().length;
		$(this).siblings('.counter').val(count);
		$(this).siblings('label').children('.counter').val(count);
	});
	$('.t_media_upload').click(function(){
		var send_back = wp.media.editor.send.attachment,
			$this = $(this);
		wp.media.editor.send.attachment = function(props, attachment) {
			if (/image/i.test(attachment.mime)) {
				$this.parent().siblings('p.t_add_media').children('input').each(function() {
					var attr = $(this).attr('name');
					if (/url/.test(attr))
						$(this).val(attachment.sizes[props.size].url);
					else if (/height/.test(attr))
						$(this).val(attachment.sizes[props.size].height);
					else if (/width/.test(attr))
						$(this).val(attachment.sizes[props.size].width);
					else if (/id/.test(attr))
						$(this).val(attachment.id);
				});
				$this.parent().siblings('p.current_image').children('img').attr('src', attachment.sizes[props.size].url);
				$this.parent().siblings('p.current_image').show();
				$('#thesis_post_image .option_item, #thesis_post_thumbnail .option_item').show();
			}
			wp.media.editor.send.attachment = send_back;
		}
		wp.media.editor.open();
		return false;
	});
	$('input[name*="[image][url]"]').each(function(){
		if ($(this).val().length > 0)
			$(this).closest('.option_item').siblings('.option_item').each(function() { $(this).show(); });
	});
	post_id = $('#post_ID').val();
	if (post_id)
		$('#thesis_modular_shortcode').html("<input type='text' class='text_input full' value='[modular_content id=\""+post_id+"\"]' readonly='readonly' tabindex='90' />");
	$('#thesis_modular_shortcode input').on('focus', function(){
		$(this).select();
		$(this).mouseup(function() {
			$(this).unbind("mouseup");
			return false;
		});
	});
});