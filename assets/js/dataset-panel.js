/* Variables panel js */
var table_field_object = {}

function dbt_reset_dataset_order(){
	var clones = jQuery('.dbtoolkit-is-clone');

	clones.each(function(k,v){
		var clone 	= jQuery(v),
			master	= jQuery(clone.data('master'));
		clone.appendTo(master);
	});
}
// create a new item object
function dbt_add_dataset_item(obj){
	
	var dataset_count	= jQuery('.dbtoolkit-dataset-item').length + 1,
		item = [
		{	
			group	: ( obj.target.data('group') ? obj.target.data('group') : '' ),
			id		: dbt_generate_id(),
			label	: 'Variable '+ ( dataset_count ),
			slug	: 'dataset_'+ ( dataset_count ),
			new_item: true
		}
	];

	return { datasets : item };
}

// create a new item config panel
function dbt_add_dataset_config(obj){	

	var group_count 	= obj.target.children().length,
		dataset_count	= jQuery('.dbtoolkit-dataset-item').length,
		dataset = {
			fields	: [
				{	
					Field	: obj.trigger.data('field'),
					Type	: obj.trigger.data('fieldtype'),
				}
			]
		};

	return dataset;
}
function dbt_psudo_this(obj){
	//console.log(obj.trigger.data('field'));
	var field_name = obj.trigger.data('id') + '_' + ( obj.target.find('.dbtoolkit-dataset-item').length + 1 ),
		new_clone = {
			Field: field_name,
			Type: "psudo",
			Key: "",
			Clone: obj.trigger.data('id'),
			new_item: true
		},
		new_object = {
			fields: [new_clone]
		};

	current_loaded_object.fields[field_name] = new_clone;
	return new_object;
}
function dbt_kill_psudo(el){
	var clicked  = jQuery(el),
		wrap 	 = jQuery('#field-line-'+clicked.data('id')),
		children = wrap.find('.dbtoolkit-dataset-item');

	if(confirm('Are you sure?')){
		children.each(function(k,f){
			var field = jQuery(f).data('id');
			jQuery('#field-config-'+field).remove();
			jQuery('[data-bound="'+field+'"]').remove();
			delete current_loaded_object.fields[field];
		});
		wrap.slideUp(dbt_animation_speed, function(){
			jQuery(this).remove();
		});
	}

	return false;
}
function dbt_dataset_currenct_object(obj){
	current_loaded_object = obj.rawData;
}

function dbt_trigger_new_config(obj){
	obj.params.trigger.parent().find('.dbtoolkit-group-config').trigger('click');
}
function dbt_update_table_field_selection(obj){

	var fields = jQuery(obj.params.trigger.data('selector'));

	fields.each(function(k,f){
		
		var field = jQuery(f);
		field.empty();
		
		if(field.is('select')){
			field.append('<option></option>');
			for( var i = 0; i < obj.data.length; i++ ){
				field.append('<option value="'+obj.data[i]+'">'+obj.data[i]+'</option>');
			}
			if(field.data('default')){
				field.val(field.data('default'));
			}
		}else{
			for( var i = 0; i < obj.data.length; i++ ){
				field.append('<label style="display: block; width: 250px;"><input type="checkbox" value="'+obj.data[i]+'"> '+obj.data[i]+'</label>');
			}
		}
	});

	/*parent.find('select[data-default]').each(function(k,v){
		var item = jQuery(v);
		item.val(item.data('default'));
	});*/
}
function dbt_init_field_handler_switch(el){
	var trigger = jQuery(el),
		template = '#dbtoolkit-handler-template-' + trigger.val() + '-tmpl';

	if(trigger.val() === ''){
		jQuery(trigger.data('target')).empty();
		return false;
	}
	trigger.data('template', template);
	return true;
}

function dbt_setup_field_handler(obj){
	var tmpl_block	= jQuery('#dbtoolkit-handler-template-' + obj.trigger.val() + '-tmpl').html(),
		template;

	if(tmpl_block){
		tmpl_block = "{{#each fields}}{{#is Field value=\""+obj.trigger.data('field')+"\"}}" + tmpl_block + "{{/is}}{{/each}}";
		template = Handlebars.compile(tmpl_block);
	}else{
		return '';
	}
	for( var field in current_loaded_object.fields){
		current_loaded_object.fields[field].slug = jQuery('#field-slug-'+field).val();
	}
	return template(current_loaded_object);
}

function dbt_delete_dataset_field(obj){
	var id = obj.trigger.data('id');

	if(confirm('Are you sure?')){		
		jQuery('#'+id+', [data-id="' + id + '"]').animate({height:0,opacity:0}, dbt_animation_speed * 2, function(){
			jQuery(this).remove();
		});
		dbt_reset_dataset_sortables();
	}
}
function dbt_delete_dataset_group(obj){
	var id = obj.trigger.data('id'),
		group = jQuery('#'+id),
		fields = group.find('.dbtoolkit-dataset-item');

	if(confirm('Are you sure?')){
		if(fields.length){
			for( var f = 0; f<fields.length; f++){
				var fid = jQuery(fields[f]).data('id');
				jQuery('#'+fid+', [data-id="' + fid + '"]').remove();		
			}
		}
		group.animate({height:0,opacity:0}, dbt_animation_speed * 2, function(){
			jQuery(this).remove();
		});
		dbt_reset_dataset_sortables();
	}	
}
// show config panel
function dbt_show_dataset_config_panel(el){
	var clicked = jQuery(el);
		
	if(clicked.parent().hasClass('highlight')){
		jQuery('.dbtoolkit-dataset-item').removeClass('highlight');
		jQuery('.dbtoolkit-dataset-config-group:visible').fadeOut(dbt_animation_speed);
		return false;
	}
	
	jQuery('.dbtoolkit-dataset-item').removeClass('highlight');

	if(jQuery('.dbtoolkit-dataset-config-group:visible').length){
		jQuery('.dbtoolkit-dataset-config-group:visible').fadeOut(dbt_animation_speed, function(){
			jQuery('#field-config-' + clicked.data('id')).fadeIn(dbt_animation_speed);
		});
	}else{
		jQuery('#field-config-' + clicked.data('id')).fadeIn(dbt_animation_speed);
	}
	clicked.parent().addClass('highlight');
	return false;
}
// toggle group config
function dbt_toggle_group_config(obj){
	jQuery(obj.trigger.data('config')).slideToggle(dbt_animation_speed);
}

// setup sortables
function dbt_reset_dataset_sortables(el){
	
	jQuery( ".dbtoolkit-dataset-group" ).not('.ui-sortable').sortable({
		connectWith				: ".dbtoolkit-dataset-holder",
		items					:	".dbtoolkit-dataset-item",
		handle					:	"h4",
		forcePlaceholderSize	:	true,
		placeholder				:	"dbtoolkit-dataset-group-helper",
		activate				:	function(){
			dbt_check_holder_count(this);
		},
		update					:	function(){
			//dbt_check_holder_count(this);
			// update field config groups
			//var panel 	= jQuery(this);
			//panel.find('.dataset-group-id').val(panel.data('group'));
		
		}
	});

	jQuery('.dbtoolkit-dataset-holder').each(function(k,el){
		dbt_check_holder_count(el);
	})
}
function dbt_check_holder_count(el){
	var holder = jQuery(el);
	if(!holder.children().length){
		holder.closest('.dbtoolkit-dataset-group').animate({opacity: .5}, dbt_animation_speed);
	}else{
		holder.closest('.dbtoolkit-dataset-group').animate({opacity: 1}, dbt_animation_speed);
	}
}

function dbt_clear_configs(){
	jQuery('#ce-datasets-items-config-wrap').empty();
	return true;
}





