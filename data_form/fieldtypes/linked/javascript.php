//<script>

function linked_loadfields(table, field, maintable){
	jQuery('#linkingConfig_'+field).html('&nbsp;Loading Config..');
	ajaxCall('linked_loadfields',table, field, maintable, function(v){
		jQuery('#linkingConfig_'+field).html(v);
	});
}
function linked_loadfilterfields(table, field, maintable){
	jQuery('#linkingConfig_'+field).html('&nbsp;Loading Config..');
	ajaxCall('linked_loadfilterfields',table, field, jQuery('#_main_table').val(), function(v){
		jQuery('#linkingConfig_'+field).html(v);
	});
}
function linked_addReturn(table, field, type){
	//jQuery('#'+field+'_additionalValues').html('&nbsp;Loading..');
	
	ajaxCall('linked_loadAdditionalValue',table, field, false, type, function(ad){
		jQuery('#'+field+'_additionalValues').append(ad);
	
	});
}


function linked_addOption(el){
	alert(el);
}
