/* libraries panel js */


// setup sortables
function dbt_reset_libraries_sortables(el){

	jQuery( ".dbtoolkit-library-items-wrapper" ).not('.ui-sortable').sortable({
		items					:	".dbtoolkit-library-item",
		handle					:	".dbtoolkit-library-handle",
		forcePlaceholderSize	:	true,
		placeholder				:	"dbtoolkit-variable-group-helper",
	});

	jQuery( ".dbtoolkit-variable-holder" ).not('.ui-sortable').sortable({
		connectWith				: ".dbtoolkit-variable-holder",
		items					:	".dbtoolkit-variable-item",
		handle					:	"h4",
		forcePlaceholderSize	:	true,
		placeholder				:	"dbtoolkit-variable-group-helper",
		activate				:	function(){
			dbt_check_holder_count(this)
		},
		update					:	function(){
			dbt_check_holder_count(this)
		}
	});

	jQuery('.dbtoolkit-variable-holder').each(function(k,el){
		dbt_check_holder_count(el);
	})
}
function dbt_check_holder_count(el){
	var holder = jQuery(el);
	if(!holder.children().length){
		holder.closest('.dbtoolkit-variable-group').animate({opacity: .5}, dbt_animation_speed);
	}else{
		holder.closest('.dbtoolkit-variable-group').animate({opacity: 1}, dbt_animation_speed);
	}
}