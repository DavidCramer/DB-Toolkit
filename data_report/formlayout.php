  <div id="tabs-2" class="setupTab">
	<?php
	InfoBox('Form Layout');
	?>
    <div style="margin-top: 20px; padding: 5px;" class="notice">
    <input type="button" class="button" value="Sync Fields" onclick="formsSetup_getFields();" />
    <input type="button" class="button" value="Insert Row" onclick="formSetup_AddRow();" />
    <input type="button" class="button" id="AddSection" value="Add Section Break" onclick="dr_addSectionBreak('form');" />
    <input type="button" class="button" id="AddTab" value="Add Tab" onclick="dr_addTab('form');" />
    <input type="button" class="button" id="AddHMTL" value="Add HTML" onclick="dr_addHTML('form');" />
    Width: <input type="text" id="_popupWidth" name="Data[Content][_popupWidth]" value="<?php if(!empty($Element['Content']['_popupWidth'])){ echo $Element['Content']['_popupWidth'];}else{ echo '450';} ?>" size="5" maxlength="4" style="width:40px;" />px
    <input type="checkbox" id="_modalPopup" name="Data[Content][_popupTypeForm]" value="modal" <?php if(!empty($Element['Content']['_popupTypeForm'])) {
                    echo 'checked="checked"';
                       } ?> /> <label for="_modalPopup">Modal</label>
    
    <input type="checkbox" id="_ajaxForms" name="Data[Content][_ajaxForms]" value="1" <?php if(!empty($Element['Content']['_ajaxForms'])) {
                    echo 'checked="checked"';
                       } ?> /> <label for="_ajaxForms">Ajax Form</label>
     
    
    </div>

	
	<ul id="formGridform">
<?php


        $cfg = $Element['Content'];
        if(empty($cfg['_gridLayout']))
            $cfg['_gridLayout'] = '';
        
        parse_str($cfg['_gridLayout'], $layout);

        //vardump($layout);
        if(!empty($cfg['_grid'])){
            $newRow = 1;
            foreach($cfg['_grid'] as $row=>$cols){
                echo '<li class="rowWrapperForm">';                    
                    echo '<div id="row'.$newRow.'" class="formRow" style="clear: both; width: 85%; float: left;">';
                        $newCol = 1;
                        foreach($cols as $col=>$width){

                            echo '<div class="column" id="row'.$newRow.'_col'.$newCol.'" style="padding: 0pt; margin: 0pt; width: '.$width.'; float: left;">';

                                echo '<input type="hidden" value="'.$width.'" name="Data[Content][_grid][row'.$newRow.'][col'.$newCol.']" class="row'.$newRow.'_control" id="row'.$newRow.'_col'.$newCol.'_control">';//row'.$newRow.'_col'.$newCol.'';
                                echo '<div style="padding: 10px; margin: 10px; -moz-user-select: none;" class="ui-state-highlight formGridform formColumn ui-sortable" unselectable="on">';
                                
                                $content = array_keys($layout, $row.'_'.$col);
                                if(!empty($content)){
                                    $output = '';
                                    foreach($content as $render){
                                        //$output .= $render;
                                        //$dta = get_option($render);                                        
                                        $Name = str_replace('Field_', '', $render);
                                        if(!empty($cfg['_FieldTitle'][$render])){
                                            $name = $cfg['_FieldTitle'][$render];
                                        }else{
                                            $name = df_parseCamelCase($Name);
                                        }

                                        if(!empty($cfg['_SectionBreak'][$render])){
                                            $output .= '<div style="padding: 3px;" class="formportlet list_row4 table_sorter sectionBreak ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" id="SectionBreak'.$render.'"><div class="formportlet-header ui-corner-all"><span class="ui-icon ui-icon-close"></span><img align="absmiddle" class="OrderSorter" src="data_report/arrow_out.png" style="-moz-user-select: none;"/><strong>Title:</strong> <input type="text" class="sectionTitle" name="Data[Content][_SectionBreak]['.$render.'][Title]" value="'.$cfg['_SectionBreak'][$render]['Title'].'" /></div><div style="padding:3px;"><strong>Caption:</strong> <input type="text" class="sectionTitle" name="Data[Content][_SectionBreak]['.$render.'][Caption]" value="'.$cfg['_SectionBreak'][$render]['Caption'].'" /></div><input class="layOutform positioning" type="hidden" name="'.$render.'" id="'.$render.'" value="row'.$newRow.'_col'.$newCol.'"/></div>';
                                        }elseif(!empty($cfg['_Tab'][$render])){

                                            $output .= '<div style="padding: 3px;" class="formportlet list_row4 table_sorter tab formportlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" id="tab'.$render.'"><div class="formportlet-header ui-corner-all"><img align="absmiddle" class="OrderSorter" src="data_report/arrow_out.png" style=""/>Tab Title<input type="text" class="tabTitle" name="Data[Content][_Tab]['.$render.'][Title]" value="'.$cfg['_Tab'][$render]['Title'].'" /><span class="ui-icon ui-icon-close"></span></div><input class="layOutform positioning" type="hidden" name="'.$render.'" id="'.$render.'" value="row'.$newRow.'_col'.$newCol.'"/></div>';
                                            //$output .= $render;
                                        }elseif(!empty($cfg['_html'][$render])){

                                            $output .= '<div style="padding: 3px;" class="formportlet list_row4 table_sorter html formportlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" id="html'.$render.'"><div class="formportlet-header ui-corner-all"><img align="absmiddle" class="OrderSorter" src="data_report/arrow_out.png" style=""/>HTML<br /><textarea class="htmlTitle" name="Data[Content][_html]['.$render.'][Title]" style="width:90%; height:100px;">'.$cfg['_html'][$render]['Title'].'</textarea><span class="ui-icon ui-icon-close"></span></div><input class="layOutform positioning" type="hidden" name="'.$render.'" id="'.$render.'" value="row'.$newRow.'_col'.$newCol.'"/></div>';

                                            //$output .= $render;
                                        }elseif(substr($render,0,6) == 'Field_'){
                                            $output .= '<div class="formportlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" id="formportlet_'.$render.'"><div class="formportlet-header ui-corner-all"><span class="ui-icon ui-icon-close"></span><div><strong>'.$name.'</strong></div><span class="description">field: '.$render.'</span><input class="layOutform positioning" type="hidden" name="'.$render.'" id="interface_'.$render.'" value="row'.$newRow.'_col'.$newCol.'"/></div></div>';
                                            $_SESSION['dataform']['OutScripts'] .= "
                                                jQuery('.formportlet-header .ui-icon').click(function() {
                                                        jQuery(this).toggleClass(\"ui-icon-minusthick\");
                                                        jQuery(this).parents(\".formportlet:first\").remove();
                                                });
                                            ";
                                        }

                                        

                                    }
                                    echo $output;
                                }else{
                                    echo '';
                                }


                                echo '</div>';

                            echo '</div>';
                        $newCol++;
                        }

                    echo '</div>';

                    echo '<div id="row1Control" class="formRow" style="width: 15%; padding-top: 12px; float: left;">';
                        echo '<img height="16" width="16" onclick="formSetupColumns(\'row'.$newRow.'\');" style="cursor: pointer;" src="'.WP_PLUGIN_URL.'/db-toolkit/data_report/cog.png">';
                        echo '<img height="16" width="16" onclick="formAddColumn(\'row'.$newRow.'\');" style="cursor: pointer;" src="'.WP_PLUGIN_URL.'/db-toolkit/data_report/add.png">';
                        echo '<img height="16" width="16" onclick="formSubtractColumn(\'row'.$newRow.'\');" style="cursor: pointer;" src="'.WP_PLUGIN_URL.'/db-toolkit/data_report/delete.png">';
                        echo '<img height="16" width="16" onclick="formRemoveColumns(\'row'.$newRow.'\');" style="cursor: pointer;" src="'.WP_PLUGIN_URL.'/db-toolkit/data_report/no.png">';
                        echo ' <i><img id="mover" height="16" width="16" style="cursor: move;" src="'.WP_PLUGIN_URL.'/db-toolkit/data_report/arrow-move.png"></i>';
                    echo '</div>';
                    echo '<div style="clear:both;"></div>';
                echo '</li>';
            $newRow++;
            }
        }


    ?>
	</ul>
	<div style="clear:both; width:350px;"><br /><br />
	
        <?php echo InfoBox('Available Fields'); ?>
        <div style="padding:10px;" class="formGridform" id="fieldTrayform">
       <?php
       if(empty($Hidden))
           $Hidden = '';
       
        echo $Hidden;
       ?>
		</div>
        <?php
		EndInfoBox();
		?>
    </div>
	<?php
	EndInfoBox();
	?>
    <input type="checkbox" name="Data[Content][_disableLayoutEngineform]" id="disableLayoutEngineform" <?php if(!empty($Element['Content']['_disableLayoutEngineform'])){ echo 'checked="checked"';} ?>/>
    <label for="disableLayoutEngineform"> Disable Layout Engine</label>
               
               <input  type="hidden" id="gridLayoutBoxForm" name="Data[Content][_gridLayout]" value="<?php echo $cfg['_gridLayout']; ?>" size="100" <?php if (!empty($element['content']['_disablelayoutengine'])) {
                   echo 'disabled="disabled"';
               } ?>="<?php if (!empty($Element['Content']['_disableLayoutEngine'])) {
                   echo 'disabled="disabled"';
               } ?>" />
	</div>

<script>
	jQuery(function() {

		jQuery('#disableLayoutEngineform').bind('change', function(ui, e){

			if(jQuery(this).attr('checked') == true){
				jQuery('#gridLayoutBoxForm').attr('disabled', 'disabled');
			}else{
				jQuery('#gridLayoutBoxForm').removeAttr('disabled');
			}

		});


		jQuery(".columnSorter").sortable({
			placeholder: 'sortable-placeholder',
			forcePlaceholderSize: true,
			connectWith: '.columnSorter',
                        handle: 'h3',
			stop: function(p){
				formSetup_columSave();
			}

		});



			jQuery(".formportlet").addClass("ui-widget ui-widget-content ui-helper-clearfix ui-corner-all").find(".formportlet-header").addClass("ui-corner-all");
			jQuery(".formportlet-header .ui-icon").click(function() {
				jQuery(this).toggleClass("ui-icon-minusthick");
				jQuery(this).parents(".formportlet:first").remove();
			});

		formSetup_columSave();

		jQuery(".formGridform").sortable({
			connectWith: '.formGridform',
			update: function(event, ui){                            
                                if(jQuery(this).parent().attr('id') != 'undefined'){
                                    jQuery(this).find(".positioning").val(jQuery(this).parent().attr('id'));
                                }
				formSetup_columSave();
			}
		});
		//jQuery(".formGridform").disableSelection();

	});


function formsSetup_getFields(){

	//jQuery('#fieldTrayform').html('');
	//jQuery('.formColumn').html('');
	jQuery('#FieldList_Main .table_sorter').each(function(){
		if(jQuery('#formportlet_'+this.id).length == 0){
			title = jQuery(this).find('h3').html();
			jQuery('#fieldTrayform').append('<div class="formportlet" id="formportlet_'+this.id+'"><div class="formportlet-header"><span class="ui-icon ui-icon-close"></span><strong>'+title+'</strong><input class="layOutform positioning" type="hidden" name="'+this.id+'" id="field_'+this.id+'" value="1"/></div></div>');
		}
	});
	jQuery(".formportlet").addClass("ui-widget ui-widget-content ui-helper-clearfix ui-corner-all")
		.find(".formportlet-header").addClass("ui-corner-all");
		jQuery(".formportlet-header .ui-icon").click(function() {
			jQuery(this).toggleClass("ui-icon-minusthick");
			jQuery(this).parents(".formportlet:first").remove();
		});
}


function formSetup_InsertInterface(eid){
    jQuery('.tools_widgets').find('ul').fadeOut('fast');
    jQuery('#fieldTrayholdingPen').html('Loading Interface...');
    ajaxCall('dr_loadInsertInterfaceBox', eid, function(i){
        jQuery('#fieldTrayholdingPen').html(i);
        df_loadOutScripts();
    })
    //alert(eid);
}

function formSetup_columSave(){
	jQuery('#gridLayoutBoxForm').val(jQuery('.layOutform').serialize());
        jQuery( "#formGridform" ).sortable({
            placeholder: 'sortable-placeholder',
            forcePlaceholderSize: true,
            connectWith: ".rowWrapperForm",
            handle: 'i',
            stop: function(p){
                    formSetup_columSave();
            }

        }).disableSelection();
}

function formSetup_AddRow(){
	rownum = jQuery('.rowWrapperForm').length+1;
	//alert(rownum);
	//.each(function(){
	//	alert(jQuery(this).length);
	//});


	jQuery('#formGridform').append('<li class="rowWrapperForm"><div style="clear:both; width:85%; float:left;" class="formRow" id="row'+rownum+'"><div style="padding:0; margin:0; width:100%; float:left;" id="row'+rownum+'_col1" class="column"><input id="row'+rownum+'_col1_control" class="row'+rownum+'_control" name="Data[Content][_grid][row'+rownum+'][col1]" type="hidden" value="100%" /><div class="ui-state-highlight formGridform formColumn" style="padding:10px; margin:10px;"></div></div></div><div style="width:15%; padding-top:12px; float:left;" class="formRow" id="row1Control"><img src="<?php echo WP_PLUGIN_URL; ?>/db-toolkit/data_report/cog.png" style="cursor:pointer;" width="16" height="16" onclick="formSetupColumns(\'row'+rownum+'\');" /><img src="<?php echo WP_PLUGIN_URL; ?>/db-toolkit/data_report/add.png" style="cursor:pointer;" width="16" height="16" onclick="formAddColumn(\'row'+rownum+'\');" /><img src="<?php echo WP_PLUGIN_URL; ?>/db-toolkit/data_report/delete.png" style="cursor:pointer;" width="16" height="16" onclick="formSubtractColumn(\'row'+rownum+'\');" /><img src="<?php echo WP_PLUGIN_URL; ?>/db-toolkit/data_report/no.png" style="cursor:pointer;" width="16" height="16" onclick="formRemoveColumns(\'row'+rownum+'\');" /> <i><img src="<?php echo WP_PLUGIN_URL; ?>/db-toolkit/data_report/arrow-move.png" width="16" height="16" style="cursor:move;" /></i></div><div style="clear:both;"></div></li>');
		jQuery(".formGridform").sortable({
			connectWith: '.formGridform',
			update: function(event, ui){                            
                                if(jQuery(this).parent().attr('id') != 'undefined'){
                                    jQuery(this).find(".positioning").val(jQuery(this).parent().attr('id'));
                                }
				formSetup_columSave();
			}
		});
		//jQuery(".formGridform").disableSelection();
}

function formSetupColumns(row){
	jQuery('body').prepend('<div id="ui-jsDialog-'+row+'" title="Row Config"><p>Loading Entry</p></div>');
	jQuery("#ui-jsDialog-"+row+"").dialog({
			position: 'center',
			autoResize: true,
			minWidth: 200,
			buttons: {
				'Save': function() {jQuery(this).dialog("close"); }
			},
			open: function(event, ui) {
				jQuery("#ui-jsDialog-"+row+"").dialog('option', 'title', row);
				jQuery("#ui-jsDialog-"+row+"").html('');
				jQuery('#'+row+' .column').each(function(){
					jQuery("#ui-jsDialog-"+row+"").append('<div><input type="text" class="setting_'+row+'" ref="'+this.id+'" id="column234234" value="'+document.getElementById(jQuery(this).attr('id')).style.width+'" /></div>');
				});
			},
			close: function(event, ui) {
				jQuery('.setting_'+row).each(function(){
					jQuery('#'+jQuery(this).attr('ref')).css('width', this.value);
                                        width = this.value;
					jQuery('#'+jQuery(this).attr('ref')).each(function(){
                                            if(jQuery(this).attr('id').length > 0){
                                                jQuery(this).find(".positioning").val(jQuery(this).attr('id'));
                                            }
                                            jQuery('#'+jQuery(this).attr('id')+'_control').val(width);
					});
				});
				formSetup_columSave();
				jQuery("#ui-jsDialog-"+row+"").remove();
			}
		});
}

function formAddColumn(row){

	cols = jQuery('#'+row+' .column').length+1;
	width = 100/cols;
	//jQuery('#'+row).html('');
	//for(i=1; i<= cols; i++){
	//}
	//jQuery('#'+row+' .column').each(function(){
                jQuery('#'+row+' .column').width(width+'%').animate({},1, function(){
		//alert(width);
			if(this.id == row+'_col'+(cols-1)){
				jQuery('#'+row).append('<div style="padding:0; margin:0; width:'+width+'%; float:left;" id="'+row+'_col'+cols+'" class="column"><input id="row'+row+'_'+cols+'_control" class="'+row+'_control" name="Data[Content][_grid]['+row+'][col'+cols+']" type="hidden" value="'+width+'%" /><div class="ui-state-highlight formGridform formColumn" style="padding:10px; margin:10px;"></div>');
				jQuery(".formGridform").sortable({
					connectWith: '.formGridform',
					update: function(event, ui){
                                                if(jQuery(this).parent().attr('id') != 'undefined'){
                                                    jQuery(this).find(".positioning").val(jQuery(this).parent().attr('id'));
                                                }
						formSetup_columSave();
					}
				});
				//jQuery(".formGridform").disableSelection();
                                jQuery('.'+row+'_control').val(width+'%');
			}
                        if(jQuery(this).attr('id').length > 0){
                            jQuery(this).find(".positioning").val(jQuery(this).attr('id'));
                        }
			formSetup_columSave();
		});

}
function formSubtractColumn(row){
	cols = jQuery('#'+row+' .column').length-1;
	if(cols <= 0){
		return false;
	}
	width = 100/cols;
	//jQuery('#'+row).html('');
	//for(i=1; i<= cols; i++){
	//jQuery('#'+row).append('<div style="padding:0; margin:0; width:'+width+'%; float:left;" id="'+row+'_col'+i+'" class="column"><div class="ui-state-highlight formGridform formColumn" style="padding:10px; margin:10px;"></div></div>');
	jQuery('#'+row+'_col'+(cols+1)+' .formportlet').appendTo('#'+row+'_col'+cols+' .formColumn');
	//jQuery('#'+row+'_col'+(cols+1)).fadeOut(100, function(){
		jQuery('#'+row+'_col'+(cols+1)).remove();
		jQuery('#'+row+' .column').width(width+'%').animate({}, 1, function(){
                        if(jQuery(this).parent().attr('id') != 'undefined'){
                            jQuery(this).find(".positioning").val(jQuery(this).attr('id'));
                            jQuery('.'+row+'_control').val(width+'%');
                        }
			formSetup_columSave();
		});
	//});
	//}
	jQuery(".formGridform").sortable({
		connectWith: '.formGridform',
		update: function(event, ui){
                        if(jQuery(this).parent().attr('id') != 'undefined'){
                            jQuery(this).find(".positioning").val(jQuery(this).parent().attr('id'));
                        }
			formSetup_columSave();
		}
	});
	//jQuery(".formGridform").disableSelection();
}
function formRemoveColumns(row){
	if(jQuery('#'+row+' .formportlet').length != 0){
		alert('Cannot remove. Row not empty.');
	}else{
		jQuery('#'+row).fadeOut(200, function(){
			jQuery(this).parent().remove();
		});
	}
}

</script>