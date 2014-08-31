/* Variables panel js */

// create a new group object
function dbt_add_dataset_group(obj){

	var group_count 	= obj.target.children().length + 1,
		group_id		= dbt_generate_id(),
		dataset_count	= jQuery('.dbtoolkit-dataset-item').length + 1,
		new_item		= dbt_add_dataset_item(obj),
		group = [
		{
			id			: group_id,
			label		: 'Group '+ ( group_count ),
			slug		: 'group_'+ ( group_count )			
		}
	];
	if(dataset_count<=1){
		group[0].datasets = new_item.datasets;
		group[0].datasets[0].group = group_id;
	}	

	return { dataset_groups : group };
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
		group = [
		{
			datasets	: [
				{	
					id		: obj.trigger.data('id'),
					label	: 'Variable '+ ( dataset_count ),
					slug	: 'dataset_'+ ( dataset_count ),
				}
			]
		}
	];

	return { dataset_groups : group };
}

function dbt_trigger_new_config(obj){
	obj.params.trigger.parent().find('.dbtoolkit-group-config').trigger('click');
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
			jQuery('#' + clicked.data('id')).fadeIn(dbt_animation_speed);
		});
	}else{
		jQuery('#' + clicked.data('id')).fadeIn(dbt_animation_speed);
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
			dbt_check_holder_count(this);
			// update field config groups
			var panel 	= jQuery(this);
			panel.find('.dataset-group-id').val(panel.data('group'));
		
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







