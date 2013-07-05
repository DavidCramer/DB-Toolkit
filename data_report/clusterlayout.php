<div class="admin_config_toolbar">
    <ul class="tools_widgets">
        <li class="root_item"><a class="parent"><strong>Apps</strong></a>
            <ul id="" style="visibility: hidden; display: block;">

                <?php                
                    echo dr_buildInterfaceList();
                ?>

            </ul>
        </li>
        <li class="root_item"><a class="parent" onclick="formSetup_AddRow();"><strong>Insert Row</strong></a></li>



        <!--<li class="root_item"><a class="parent" onclick="dr_addTab('holdingPen');"><strong>Add Tab</strong></a></li>-->
        <li class="root_item"><div id="fieldTrayholdingPen" class="formGridform" style="width:280px; margin-left:5px;"></div></li>
    </ul>
    <div style="clear:both;"></div>
</div>

<div id="formGridform">

    <?php


        $cfg = $Element['Content'];
        parse_str($cfg['_clusterLayout'], $layout);
        

        if(!empty($cfg['_grid'])){
            $newRow = 1;
            foreach($cfg['_grid'] as $row=>$cols){
                echo '<div class="rowWrapperForm">';

                    echo '<div id="row'.$newRow.'" class="formRow" style="clear: both; width: 90%; float: left;">';
                        $newCol = 1;
                        foreach($cols as $col=>$width){

                            echo '<div class="column" id="row'.$newRow.'_col'.$newCol.'" style="padding: 0pt; margin: 0pt; width: '.$width.'; float: left;">';

                                echo '<input type="hidden" value="'.$width.'" name="Data[Content][_grid][row'.$newRow.'][col'.$newCol.']" class="row'.$newRow.'_control" id="row'.$newRow.'_col'.$newCol.'_control">';//row'.$newRow.'_col'.$newCol.'';
                                echo '<div style="padding: 10px; margin: 10px; -moz-user-select: none;" class="ui-state-error formGridform formColumn ui-sortable" unselectable="on">';

                                $content = array_keys($layout, $row.'_'.$col);
                                if(!empty($content)){
                                    $output = '';
                                    foreach($content as $render){
                                        $dta = get_option($render);                                        
                                        $output .= '<div class="formportlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" id="formportlet_'.$dta['ID'].'"><div class="formportlet-header ui-corner-all"><span class="ui-icon ui-icon-close"></span><div><strong>'.$dta['_ReportDescription'].'</strong></div><span class="description">'.$dta['_ReportExtendedDescription'].'</span><input class="layOutform positioning" type="hidden" name="'.$dta['ID'].'" id="interface_'.$dta['ID'].'" value="row'.$newRow.'_col'.$newCol.'"/></div></div>';
                                        $_SESSION['dataform']['OutScripts'] .= "
                                            jQuery('.formportlet-header .ui-icon').click(function() {
                                                    jQuery(this).toggleClass(\"ui-icon-minusthick\");
                                                    jQuery(this).parents(\".formportlet:first\").remove();
                                                    formSetup_columSave();
                                            });
                                        ";

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

                    echo '<div id="row1Control" class="formRow" style="width: 10%; padding-top: 12px; float: left;">';
                        echo '<img height="16" width="16" onclick="formSetupColumns(\'row'.$newRow.'\');" style="cursor: pointer;" src="'.WP_PLUGIN_URL.'/db-toolkit/data_report/cog.png">';
                        echo '<img height="16" width="16" onclick="formAddColumn(\'row'.$newRow.'\');" style="cursor: pointer;" src="'.WP_PLUGIN_URL.'/db-toolkit/data_report/add.png">';
                        echo '<img height="16" width="16" onclick="formSubtractColumn(\'row'.$newRow.'\');" style="cursor: pointer;" src="'.WP_PLUGIN_URL.'/db-toolkit/data_report/delete.png">';
                        echo '<img height="16" width="16" onclick="formRemoveColumns(\'row'.$newRow.'\');" style="cursor: pointer;" src="'.WP_PLUGIN_URL.'/db-toolkit/data_report/no.png">';
                    echo '</div>';

                echo '</div>';
            $newRow++;
            }
        }


    ?>
</div>


<input name="Data[Content][_clusterLayout]" type="hidden" id="gridLayoutBoxForm" value="<?php echo $Element['Content']['_gridLayout']; ?>" size="100" <?php if(!empty($element['content']['_disablelayoutengine'])){ echo 'disabled';} ?>="<?php if(!empty($Element['Content']['_disableLayoutEngine'])){ echo '"disabled"';} ?>" />



<div style="clear:both;"></div>



<?php
$_SESSION['dataform']['OutScripts'] .= "

    // activate menus
    jQuery('.tools_widgets ul').css({
        display: \"none\"
    });
    jQuery('.tools_widgets li').hover(function(){
        jQuery(this).find('ul:first').css({
            visibility: \"visible\",
            display: \"none\"
        }).fadeIn(250);
    },function(){
        jQuery(this).find('ul:first').css({
            visibility: \"hidden\"
        });
    });
";
?>


<script>
	jQuery(function() {
		
                jQuery('#Save').bind('mouseover', function(){                    
                    formSetup_columSave();
                });

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
			stop: function(p){
				//alert(columns);
				formSetup_columSave();
			},
			update: function(event, ui){
                                if(jQuery(this).parent().attr('id').length > 0){
                                    jQuery(this).find(".positioning").val(jQuery(this).parent().attr('id'));
                                }
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
                                if(jQuery(this).parent().attr('id').length > 0){
                                    jQuery(this).find(".positioning").val(jQuery(this).parent().attr('id'));
                                }
				formSetup_columSave();
			},
			stop: function(p){
				//alert(columns);
				formSetup_columSave();
			}

		});
		jQuery(".formGridform").disableSelection();
		
	});

function formSetup_InsertInterface(eid){
    jQuery('.tools_widgets').find('ul').fadeOut('fast');
    jQuery('#fieldTrayholdingPen').html('Loading Interface...');
    ajaxCall('dr_loadInsertInterfaceBox', eid, function(i){
        jQuery('#fieldTrayholdingPen').html(i);
        df_loadOutScripts();
        formSetup_columSave();
    })
    formSetup_columSave();
    //alert(eid);
}

function formSetup_columSave(){
	jQuery('#gridLayoutBoxForm').val(jQuery('.layOutform').serialize());
}

function formSetup_AddRow(){
	rownum = jQuery('.rowWrapperForm').length+1;
	//alert(rownum);
	//.each(function(){
	//	alert(jQuery(this).length);
	//});
	
	
	jQuery('#formGridform').append('<div class="rowWrapperForm"><div style="clear:both; width:90%; float:left;" class="formRow" id="row'+rownum+'"><div style="padding:0; margin:0; width:100%; float:left;" id="row'+rownum+'_col1" class="column"><input id="row'+rownum+'_col1_control" class="row'+rownum+'_control" name="Data[Content][_grid][row'+rownum+'][col1]" type="hidden" value="100%" /><div class="ui-state-error formGridform formColumn" style="padding:10px; margin:10px;"></div></div></div><div style="width:10%; padding-top:12px; float:left;" class="formRow" id="row1Control"><img src="<?php echo WP_PLUGIN_URL; ?>/db-toolkit/data_report/cog.png" style="cursor:pointer;" width="16" height="16" onclick="formSetupColumns(\'row'+rownum+'\');" /><img src="<?php echo WP_PLUGIN_URL; ?>/db-toolkit/data_report/add.png" style="cursor:pointer;" width="16" height="16" onclick="formAddColumn(\'row'+rownum+'\');" /><img src="<?php echo WP_PLUGIN_URL; ?>/db-toolkit/data_report/delete.png" style="cursor:pointer;" width="16" height="16" onclick="formSubtractColumn(\'row'+rownum+'\');" /><img src="<?php echo WP_PLUGIN_URL; ?>/db-toolkit/data_report/no.png" style="cursor:pointer;" width="16" height="16" onclick="formRemoveColumns(\'row'+rownum+'\');" /></div></div>');
		jQuery(".formGridform").sortable({
			connectWith: '.formGridform',
			update: function(event, ui){
                                if(jQuery(this).parent().attr('id') != 'undefined'){
                                    jQuery(this).find(".positioning").val(jQuery(this).parent().attr('id'));
                                }
				formSetup_columSave();
			}
		});
		jQuery(".formGridform").disableSelection();
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
		//alert('ping');
			if(this.id == row+'_col'+(cols-1)){
				jQuery('#'+row).append('<div style="padding:0; margin:0; width:'+width+'%; float:left;" id="'+row+'_col'+cols+'" class="column"><input id="row'+row+'_'+cols+'_control" class="'+row+'_control" name="Data[Content][_grid]['+row+'][col'+cols+']" type="hidden" value="'+width+'%" /><div class="ui-state-error formGridform formColumn" style="padding:10px; margin:10px;"></div>');
				jQuery(".formGridform").sortable({
					connectWith: '.formGridform',
					update: function(event, ui){
                                                if(jQuery(this).parent().attr('id') != 'undefined'){
                                                    jQuery(this).find(".positioning").val(jQuery(this).parent().attr('id'));
                                                }
						formSetup_columSave();
					}
				});
				jQuery(".formGridform").disableSelection();
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
	//jQuery('#'+row).append('<div style="padding:0; margin:0; width:'+width+'%; float:left;" id="'+row+'_col'+i+'" class="column"><div class="ui-state-error formGridform formColumn" style="padding:10px; margin:10px;"></div></div>');
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
	jQuery(".formGridform").disableSelection();
}
function formRemoveColumns(row){
	if(jQuery('#'+row+' .formportlet').length != 0){
		alert('Cannot remove. Row not empty.');	
	}else{
		jQuery('#'+row).fadeOut(200, function(){
			jQuery(this).parent().remove();
		});
	}
        formSetup_columSave();
}	

</script>