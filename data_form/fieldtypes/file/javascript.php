//<script>

function file_removeFile(el){
	//if(confirm('Are you sure you want to remove this file?')){
		jQuery('#box_'+el+' .togglebutton').toggle();
		jQuery('#box_'+el).animate({opacity: 0.3}, 200, function(){
			//jQuery(this).slideUp(500, function(){
				jQuery('#'+el).attr('disabled', 'disabled');
				//jQuery(this).remove();
			//});
		});	
//	}
}

function file_undoRemoveFile(el){
	//if(confirm('Are you sure you want to remove this file?')){
		jQuery('#box_'+el+' .togglebutton').toggle();
		jQuery('#box_'+el).animate({opacity: 1}, 200, function(){
			//jQuery(this).slideUp(500, function(){
				jQuery('#'+el).removeAttr('disabled');
				//jQuery(this).remove();
			//});
		});	
//	}
}