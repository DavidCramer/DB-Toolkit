var current_screen_object = {page : "admin", state : ""}, dbt_animation_speed = 100;

function dbt_confirm_delete(){
	return confirm('Are you sure you want to remove this item?');
}
function dbt_reload_elements(){
	jQuery('#dbtoolkit-canvas .wp-filter-link.dbtoolkit-trigger.current').trigger('click');
}

function dbt_create_new_element(){
	return '{"type" : true}';
}
function dbt_clear_canvas(){

	jQuery('.dbtoolkit-element-tool').each(function(k,v){
		setTimeout(function(){
			jQuery(v).animate({opacity: 0, marginLeft: -10}, dbt_animation_speed);
		}, ( dbt_animation_speed / 2 ) *jQuery(v).index());
	});

	jQuery('#dbtoolkit-canvas').fadeOut(dbt_animation_speed, function(){
		jQuery(this).empty().show();
	})

}
function dbt_toggle_activation(obj){
	obj.params.trigger.removeClass('disabled')
	if(obj.data.message === 'Activate'){
		obj.params.trigger.removeClass('button-primary').text(obj.data.message);
	}else{
		obj.params.trigger.addClass('button-primary').text(obj.data.message);
	}
}
function dbt_reset_screen_state(){
	current_screen_object.state = '';
}
function dbt_build_new_element(el){
	var clicked 	= jQuery(el),
		name 		= jQuery('#new_element_name'),
		description	= jQuery('#new_element_description'),
		project 	= jQuery('#new_element_project'),
		slug		= jQuery('#new_element_slug');

	if(!name.val().length){
		name.focus();
		return false;
	}
	if(!slug.val().length){
		slug.focus();
		return false;
	}

	clicked.data('element_name', name.val() );
	clicked.data('element_description', description.val() );
	clicked.data('element_project', project.val() );
	clicked.data('element_slug', slug.val() );

	dbt_clear_canvas();
	
	return true;

}

jQuery(function($){
	var baldrickPending = [];
	// bind slugs
	$(document).on('keyup change', '[data-format="slug"]', function(){
		var slugs 		= $('input[data-format="slug"]').not(this),
			has_error 	= false;
		this.value = this.value.replace(/[^a-z0-9]/gi, '_').toLowerCase();
		for(var i = 0; i < slugs.length; i++){
			if( slugs[i].value === this.value ){				
				has_error = true;
			}
		}
		if(has_error || !this.value.length){
			$(this).addClass('has-error');
		}else{
			$(this).removeClass('has-error');
		}
	});
	
	// bind label update
	$(document).on('keyup change', '[data-sync]', function(){
		var input = $(this),
			sync = $('.' + input.data('sync'));
		sync.text(input.val());
	});

	// initialise baldrick triggers
	$('.dbtoolkit-trigger').baldrick({
		request			:	ajaxurl,
		method			:	'POST',
		activeClass		:	'none',
		loadQuery		:	'#loading-indicator',
		helper			:	{
			event		:	function(el, obj, ev){
				baldrickPending.push( obj.request );
			},
			refresh		:	function(obj){
				baldrickPending.shift();
				//setup canvas state
				if(!baldrickPending.length){				
					if(!current_screen_object.page){
						current_screen_object.state = $('#dbtoolkit-canvas').serialize();
					}
				}
			}
		}
	});

	// initialize baldrick core form
	$('#dbtoolkit-canvas').baldrick({
		request			:	ajaxurl,		
		method			:	'POST',
		activeClass		:	'none',
		loadQuery		:	'#loading-indicator',
		before			:	function(el, e){			
			if(typeof current_screen_editors !== 'boolean'){
				for(var editor in current_screen_editors){
					current_screen_editors[editor].save();
				}
			}
		}
	});

});
