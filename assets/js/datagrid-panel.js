/* Variables panel js */

// create a new group object
function dbt_add_datagrid_group(obj){

	var group_count 	= obj.target.children().length + 1,
		group_id		= dbt_generate_id(),
		datagrid_count	= jQuery('.dbtoolkit-datagrid-item').length + 1,
		new_item		= dbt_add_datagrid_item(obj),
		group = [
		{
			id			: group_id,
			label		: 'Group '+ ( group_count ),
			slug		: 'group_'+ ( group_count )			
		}
	];
	if(datagrid_count<=1){
		group[0].datagrids = new_item.datagrids;
		group[0].datagrids[0].group = group_id;
	}	

	return { datagrid_groups : group };
}

// create a new item object
function dbt_add_datagrid_item(obj){
	
	var datagrid_count	= jQuery('.dbtoolkit-datagrid-item').length + 1,
		item = [
		{	
			group	: ( obj.target.data('group') ? obj.target.data('group') : '' ),
			id		: dbt_generate_id(),
			label	: 'Variable '+ ( datagrid_count ),
			slug	: 'datagrid_'+ ( datagrid_count ),
			new_item: true
		}
	];

	return { datagrids : item };
}

// create a new item config panel
function dbt_add_datagrid_config(obj){	

	var group_count 	= obj.target.children().length,
		datagrid_count	= jQuery('.dbtoolkit-datagrid-item').length,
		group = [
		{
			datagrids	: [
				{	
					id		: obj.trigger.data('id'),
					label	: 'Variable '+ ( datagrid_count ),
					slug	: 'datagrid_'+ ( datagrid_count ),
				}
			]
		}
	];

	return { datagrid_groups : group };
}

function dbt_trigger_new_config(obj){
	obj.params.trigger.parent().find('.dbtoolkit-group-config').trigger('click');
}

function dbt_delete_datagrid_field(obj){
	var id = obj.trigger.data('id');

	if(confirm('Are you sure?')){		
		jQuery('#'+id+', [data-id="' + id + '"]').animate({height:0,opacity:0}, dbt_animation_speed * 2, function(){
			jQuery(this).remove();
		});
		dbt_reset_datagrid_sortables();
	}
}
function dbt_delete_datagrid_group(obj){
	var id = obj.trigger.data('id'),
		group = jQuery('#'+id),
		fields = group.find('.dbtoolkit-datagrid-item');

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
		dbt_reset_datagrid_sortables();
	}	
}
// show config panel
function dbt_show_datagrid_config_panel(el){
	var clicked = jQuery(el);
		
	if(clicked.parent().hasClass('highlight')){
		jQuery('.dbtoolkit-datagrid-item').removeClass('highlight');
		jQuery('.dbtoolkit-datagrid-config-group:visible').fadeOut(dbt_animation_speed);
		return false;
	}
	
	jQuery('.dbtoolkit-datagrid-item').removeClass('highlight');

	if(jQuery('.dbtoolkit-datagrid-config-group:visible').length){
		jQuery('.dbtoolkit-datagrid-config-group:visible').fadeOut(dbt_animation_speed, function(){
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
function dbt_reset_datagrid_sortables(el){



	jQuery( ".dbtoolkit-datagrid-group" ).not('.ui-sortable').sortable({
		connectWith				: ".dbtoolkit-datagrid-holder",
		items					:	".dbtoolkit-datagrid-item",
		handle					:	"h4",
		forcePlaceholderSize	:	true,
		placeholder				:	"dbtoolkit-datagrid-group-helper",
		activate				:	function(){
			dbt_check_holder_count(this);
		},
		update					:	function(){
			//dbt_check_holder_count(this);
			// update field config groups
			//var panel 	= jQuery(this);
			//panel.find('.datagrid-group-id').val(panel.data('group'));
		
		}
	});

	jQuery('.dbtoolkit-datagrid-holder').each(function(k,el){
		dbt_check_holder_count(el);
	})
}
function dbt_check_holder_count(el){
	var holder = jQuery(el);
	if(!holder.children().length){
		holder.closest('.dbtoolkit-datagrid-group').animate({opacity: .5}, dbt_animation_speed);
	}else{
		holder.closest('.dbtoolkit-datagrid-group').animate({opacity: 1}, dbt_animation_speed);
	}
}

function dbt_set_dataset_field_visible(obj){
	var input = jQuery('#'+obj.trigger.data('id')+'_visibility');

	if(obj.trigger.hasClass('dbtoolkit-datagrid-visible-field')){
		obj.trigger.removeClass('dbtoolkit-datagrid-visible-field');
		input.val('0');
	}else{
		obj.trigger.addClass('dbtoolkit-datagrid-visible-field');
		input.val('1');		
	}
}





