// Uploading files
var image_picker_frame, cu_use_uploaded_image;

cu_use_uploaded_image = function(obj){

	// check for error
	if(obj.data.error){
		alert(obj.data.error);
		obj.params.trigger.prop('disabled', true);
		return;
	}

	var parent = jQuery('#' + obj.params.trigger.data('id')),
		image = parent.find('.image-picker-thumbnail'),
		id = parent.find('.image-picker-image-id'),
		thumb = parent.find('.image-picker-image-thumb');

		image.attr('src', obj.data.url);
		id.val(obj.data.ID);
		thumb.val(obj.data.url);
		obj.params.trigger.prop('disabled', true);
}

jQuery(function($){



	$('body').on('click', '.cu-image-picker,.cu-image-picker-select', function( e ){
		
		e.preventDefault();
		var clicked = $(this),
			panel = clicked.closest('.dbtoolkit-field-group'),
			thumbnail = panel.find('.image-picker-thumbnail'),
			thumbnail_val = panel.find('.image-picker-image-thumb'),
			sizer = panel.find('.image-picker-sizer'),
			value = panel.find('.image-picker-image-id'),
			picksize = panel.find('.image-picker-content'),
			remover = panel.find('.cu-image-remover');
		
		if(clicked.hasClass('cu-image-picker-select')){
			clicked.next().prop('disabled', false).trigger('click');
			return;
		}

		if ( !image_picker_frame ) {

			// Create the media frame.

			image_picker_frame = wp.media({
				title: clicked.data( 'title' ),
				button: {
					text: clicked.data( 'button' ),
				},
				library: { type: 'image'},
				multiple: true
			});
		}
		var select_handler = function(e){
			attachment = image_picker_frame.state().get('selection').first().toJSON();
			sizer.prop('disabled', false);
			value.prop('disabled', false);
			value.val(attachment.id);
			thumbnail_val.prop('disabled', false);
			console.log(thumbnail_val);
			if(picksize.hasClass('image-thumb-lrg')){
				if(attachment.sizes.large){
					thumbnail.attr('src', attachment.sizes.large.url);
					thumbnail_val.val(attachment.sizes.large.url);
				}else if(attachment.sizes.medium){
					thumbnail.attr('src', attachment.sizes.medium.url);
					thumbnail_val.val(attachment.sizes.medium.url);
				}else{
					thumbnail.attr('src', attachment.sizes.full.url);
					thumbnail_val.val(attachment.sizes.full.url);
				}
			}else{
				thumbnail.attr('src', attachment.sizes.thumbnail.url);
				thumbnail_val.val(attachment.sizes.thumbnail.url);
			}			
			remover.prop('disabled', false);
			image_picker_frame.off( 'select', select_handler);
		};
		image_picker_frame.on( 'select', select_handler);

		image_picker_frame.open();
	});
	$('body').on('click', '.cu-image-remover', function( e ){
		var clicked = $(this),
			panel = clicked.closest('.dbtoolkit-field-group'),
			thumbnail = panel.find('.image-picker-thumbnail'),
			thumbnail_val = panel.find('.image-picker-image-thumb'),
			value = panel.find('.image-picker-image-id'),
			sizer = panel.find('.image-picker-sizer'),
			remover = panel.find('.cu-image-remover');

		thumbnail.attr('src', thumbnail.data('placehold'));
		remover.prop('disabled', true);
		sizer.prop('disabled', true);
		value.prop('disabled', true);
		thumbnail_val.prop('disabled', true);
	});
	$('body').on('change', '.image-picker-allowed-size', function( e ){
		var clicked = $(this),
			panel = clicked.closest('.dbtoolkit-field-group').prev(),
			sizer = panel.find('.image-picker-sizer'),
			option = sizer.find('option[value="'+this.value+'"]'),
			bestoption = sizer.find('option').not(':disabled,option[value="'+this.value+'"]').first().val(),
			checks = clicked.closest('.dbtoolkit-config-field').find('input:checked'),
			buttons = panel.find('.image-picker-button');

			if(checks.length <= 0){
				clicked.prop('checked', true);
				return;
			}else if(checks.length === 1){
				buttons.addClass('image-picker-button-solo');
				sizer.hide();
			}else{
				buttons.removeClass('image-picker-button-solo');
				sizer.show();
			}

		if(!clicked.prop('checked')){			
			if(sizer.val() === clicked.val()){
				sizer.val(bestoption);
			}
			option.attr('disabled', 'disabled').hide();
		}else{
			option.removeAttr('disabled').show();
		}

	});

	$('body').on('change', '.image-picker-size', function( e ){
		
		var clicked = $(this),
			panel = clicked.closest('.dbtoolkit-variable-config-group').find('.image-picker-content'),
			thumbnail = panel.find('.image-picker-thumbnail');
		
		panel.removeClass('image-thumb').removeClass('image-thumb-lrg').addClass(clicked.val());




	});
})
