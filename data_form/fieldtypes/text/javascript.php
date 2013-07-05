//<script>

function text_runCode(field, EID, ID){
	if(confirm('Are you sure you want to run this code?')){
		jQuery('#codeRun_'+ID).attr('disabled', 'disabled');
		ajaxCall('text_runCode',field, EID, ID, function(x){
			jQuery('#codeRun_'+ID).removeAttr('disabled');
			alert('Result\n\r------------------------------------------------------\n\r'+x);
	   });
	}
}