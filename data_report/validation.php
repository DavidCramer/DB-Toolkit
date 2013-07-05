<div id="tabs-validate">
    <?php
    InfoBox('Advanced Validation');
    ?>
    <div class="ui-state-highlight ui-corner-all" style=" padding: 5px;">
        <input type="button" onclick="validation_getFields();" value="Sync Fields">
    </div>

    <table width="50%" cellpadding="4" cellspacing="4">
        <thead>
            <tr>
                <th style="width: 200px;">Fields</th>
                <th style="width: 200px;">Values</th>
                <th style="width: 200px;">Altered States</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td id="validationColumnFields" valign="top"></td>
                <td id="validationColumnValues" valign="top"></td>
                <td id="validationColumnStates" valign="top"></td>
            </tr>
        </tbody>

    </table>

    <?php
    endinfobox();
    ?>
</div>

<script>



function validation_getFields(){

	//$('#fieldTrayform').html('');
	//$('.formColumn').html('');
	jQuery('.Advanced_Validation_Enabled').each(function(){
                title = this.title;
		fieldID = this.value;
		jQuery('#validationColumnFields').append('<div class="ui-widget-header ui-widget-content ui-corner-all" id="advancedValidation_'+fieldID+'" style="cursor:pointer;"><div class="formportlet-header" onclick="validation_loadFieldValidation(\''+fieldID+'\');">'+title+'</div>');
	});
	
}

function validation_loadFieldValidation(field){
    
    jQuery('#validationColumnFields div').removeClass('ui-state-highlight');
    jQuery('#advancedValidation_'+field).addClass('ui-state-highlight');
    ajaxCall('dr_renderField', field, '<?php echo $_GET['interface']; ?>', function(c){
        jQuery('#validationColumnValues').html(c);
    });
}


</script>