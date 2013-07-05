<h2>View Layout</h2>

    <div id="viewtabs" class="dbtools_tabs">
        <ul class="content-box-tabs">
            <li><a href="#gridview">Grid View</a></li>
            <li><a href="#templateview">Template</a></li>
        </ul>
        <div id="gridview" class="setupTab">



    <div style="margin-top: 20px; padding: 5px;" class="notice">
    <input type="button" class="button" value="Sync Fields" onclick="viewsSetup_getFields();" />
    <input type="button" class="button" value="Insert Row" onclick="viewSetup_AddRow();" />
    <input type="button" class="button" id="AddSection" value="Add Section Break" onclick="dr_addSectionBreak('view');" />
    <!--<input type="button" class="button" id="AddTab" value="Add Tab" onclick="dr_addTab('view');" />-->
    Width: <input type="text" id="_popupWidthview" name="Data[Content][_popupWidthview]" value="<?php if(!empty($Element['Content']['_popupWidthview'])){ echo $Element['Content']['_popupWidthview'];}else{ echo '450';} ?>" size="5" maxlength="4" style="width:40px;" />px
    <input type="checkbox" id="_modalPopup" name="Data[Content][_popupTypeView]" value="modal" <?php if(!empty($Element['Content']['_popupTypeView'])) {
                    echo 'checked="checked"';
                       } ?> /> <label for="_modalPopup">Modal</label>
    <?php
    /*
    <input type="checkbox" id="_ajaxViews" name="Data[Content][_ajaxViews]" value="1" <?php if(!empty($Element['Content']['_ajaxViews'])) {
                    echo 'checked="checked"';
                       } ?> /> <label for="_ajaxViews">Ajax View</label>\

     */
    ?>
    </div>


	<div id="viewGridview">
<?php


        $cfg = $Element['Content'];
        if(empty($cfg['_gridViewLayout']))
            $cfg['_gridViewLayout'] = '';
        
        parse_str($cfg['_gridViewLayout'], $layout);
        //vardump($layout);

        if(!empty($cfg['_gridView'])){
            $newRow = 1;
            foreach($cfg['_gridView'] as $row=>$cols){
                echo '<div class="rowWrapperView">';

                    echo '<div id="viewRow'.$newRow.'" class="viewRow" style="clear: both; width: 85%; float: left;">';
                        $newCol = 1;
                        foreach($cols as $col=>$width){

                            echo '<div class="columnView" id="viewRow'.$newRow.'_viewCol'.$newCol.'" style="padding: 0pt; margin: 0pt; width: '.$width.'; float: left;">';

                                echo '<input type="hidden" value="'.$width.'" name="Data[Content][_gridView][viewRow'.$newRow.'][viewCol'.$newCol.']" class="viewRow'.$newRow.'_controlView" id="viewRow'.$newRow.'_viewCol'.$newCol.'_controlView">';//row'.$newRow.'_col'.$newCol.'';
                                echo '<div style="padding: 10px; margin: 10px; " class="ui-state-error viewGridview viewColumn ui-sortable">';

                                $content = array_keys($layout, $row.'_'.$col);
                                if(!empty($content)){
                                    $output = '';
                                    foreach($content as $render){
                                        //$dta = get_option($render);
                                        $Name = str_replace('View_', '', $render);
                                        if(!empty($cfg['_FieldTitle'][$render])){
                                            $name = $cfg['_FieldTitle'][$render];
                                        }else{
                                            $name = df_parseCamelCase($Name);
                                        }

                                        if(!empty($cfg['_SectionBreak'][$render])){
                                            $output .= '<div style="padding: 3px;" class="viewportlet list_row4 table_sorter sectionBreak ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" id="SectionBreak'.$render.'"><div class="viewportlet-header ui-corner-all"><span class="ui-icon ui-icon-close"></span><img align="absmiddle" class="OrderSorter" src="data_report/arrow_out.png" style=""/><strong>Title:</strong> <input type="text" class="sectionTitle" name="Data[Content][_SectionBreak]['.$render.'][Title]" value="'.$cfg['_SectionBreak'][$render]['Title'].'asdasd" /></div><div style="padding:3px;"><strong>Caption:</strong> <input type="text" class="sectionTitle" name="Data[Content][_SectionBreak]['.$render.'][Caption]" value="'.$cfg['_SectionBreak'][$render]['Caption'].'" /></div><input class="layOutview positioning" type="hidden" name="'.$render.'" id="'.$render.'" value="row'.$newRow.'_col'.$newCol.'"/></div>';
                                            $_SESSION['dataview']['OutScripts'] .= "
                                                jQuery('.viewportlet-header .ui-icon').click(function() {
                                                        jQuery(this).toggleClass(\"ui-icon-minusthick\");
                                                        jQuery(this).parents(\".viewportlet:first\").remove();
                                                });
                                            ";



                                        }elseif(!empty($cfg['_Tabs'][$render])){

                                        }elseif(substr($render,0,11) == 'View_Field_'){
                                            $output .= '<div class="viewportlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" id="viewportlet_'.$render.'"><div class="viewportlet-header ui-corner-all"><span class="ui-icon ui-icon-close"></span><div><strong>'.$name.'</strong></div><span class="description">field: '.str_replace('View_Field_', '', $render).'</span><input class="layOutview positioning" type="hidden" name="'.$render.'" id="interface_'.$render.'" value="viewRow'.$newRow.'_viewCol'.$newCol.'"/></div></div>';
                                            $_SESSION['dataview']['OutScripts'] .= "
                                                jQuery('.viewportlet-header .ui-icon').click(function() {
                                                        jQuery(this).toggleClass(\"ui-icon-minusthick\");
                                                        jQuery(this).parents(\".viewportlet:first\").remove();
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

                    echo '<div id="row1Control" class="viewRow" style="width: 15%; padding-top: 12px; float: left;">';
                        echo '<img height="16" width="16" onclick="viewSetupColumns(\'viewRow'.$newRow.'\');" style="cursor: pointer;" src="'.WP_PLUGIN_URL.'/db-toolkit/data_report/cog.png">';
                        echo '<img height="16" width="16" onclick="viewAddColumn(\'viewRow'.$newRow.'\');" style="cursor: pointer;" src="'.WP_PLUGIN_URL.'/db-toolkit/data_report/add.png">';
                        echo '<img height="16" width="16" onclick="viewSubtractColumn(\'viewRow'.$newRow.'\');" style="cursor: pointer;" src="'.WP_PLUGIN_URL.'/db-toolkit/data_report/delete.png">';
                        echo '<img height="16" width="16" onclick="viewRemoveColumns(\'viewRow'.$newRow.'\');" style="cursor: pointer;" src="'.WP_PLUGIN_URL.'/db-toolkit/data_report/no.png">';
                    echo '</div>';

                echo '</div>';
            $newRow++;
            }
        }


    ?>
	</div>
	<div style="clear:both; width:350px;"><br /><br />

        <h2>Available Fields</h2>
        <div style="padding:10px;" class="viewGridview" id="fieldTrayview">
       <?php
        echo $Hidden;
       ?>
		</div>
    </div>

    <input type="checkbox" name="Data[Content][_disableLayoutEngineview]" id="disableLayoutEngineview" <?php if(!empty($Element['Content']['_disableLayoutEngineview'])){ echo 'checked="checked"';} ?>/>
    <label for="disableLayoutEngineview"> Disable Layout Engine</label>
	<input name="Data[Content][_gridViewLayout]" type="hidden" id="gridLayoutBoxView" value="<?php echo $cfg['_gridViewLayoutView']; ?>" size="100" <?php if(!empty($element['content']['_disablelayoutengineview'])){ echo 'disabled="disabled"';} ?>="<?php if(!empty($Element['Content']['_disableLayoutEngineview'])){ echo 'disabled="disabled"';} ?>" />
	
        </div>
        <div id="templateview" class="setupTab">


            <?php

            $Sel = '';
            if(!empty($Element['Content']['_UseViewTemplate'])) {
                $Sel = 'checked="checked"';
            }
            echo dais_customfield('checkbox', 'Use Template', '_UseViewTemplate', '_UseViewTemplate', 'list_row1' , 1, $Sel);

            echo dais_customfield('textarea', 'Content Wrapper Start', '_ViewTemplateContentWrapperStart', '_ViewTemplateContentWrapperStart', 'list_row2' , $Element['Content']['_ViewTemplateContentWrapperStart'], '');
            echo dais_customfield('textarea', 'PreContent', '_ViewTemplatePreContent', '_ViewTemplatePreContent', 'list_row2' , $Element['Content']['_ViewTemplatePreContent'], '');
            echo dais_customfield('textarea', 'Content', '_ViewTemplateContent', '_ViewTemplateContent', 'list_row2' , $Element['Content']['_ViewTemplateContent'], '');
            echo '<h2>Useable Keys</h2>';
            ?>
            <pre>
{{_PageID}}		: Page ID
{{_PageName}}		: Page Name
{{_EID}}		: Element ID
{{_<i>Fieldname</i>_name}}	: Field Title
{{<i>Fieldname</i>}}		: Field Data
{{_return_<i><b>Fieldname</b></i>}}	: Return Field
            </pre>
            Field Keys:
            <?php
            if(!empty($Element['Content']['_FieldTitle'])){
                foreach($Element['Content']['_FieldTitle'] as $FieldKey=>$Val) {
                    echo $Val.' = {{'.$FieldKey.'}}<br />';
                }
            }
            echo '<br /><br />';
            echo dais_customfield('textarea', 'PostContent', '_ViewTemplatePostContent', '_ViewTemplatePostContent', 'list_row2' , $Element['Content']['_ViewTemplatePostContent'], '');
            echo dais_customfield('textarea', 'Content Wrapper End', '_ViewTemplateContentWrapperEnd', '_ViewTemplateContentWrapperEnd', 'list_row2' , $Element['Content']['_ViewTemplateContentWrapperEnd'], '');



            ?>



        </div>
    </div>
        
        


<script>
	jQuery(function() {
                jQuery("#viewtabs").tabs();
		jQuery('#disableLayoutEngineview').bind('change', function(ui, e){

			if(jQuery(this).attr('checked') == true){
				jQuery('#gridLayoutBoxView').attr('disabled', 'disabled');
			}else{
				jQuery('#gridLayoutBoxView').removeAttr('disabled');
			}

		});


		jQuery(".columnViewSorter").sortable({
			placeholder: 'sortable-placeholder',
			forcePlaceholderSize: true,
			connectWith: '.columnViewSorter',
			stop: function(p){
				//alert(columns);
				viewSetup_columSave();
			}

		});
			jQuery(".viewportlet").addClass("ui-widget ui-widget-content ui-helper-clearfix ui-corner-all").find(".viewportlet-header").addClass("ui-corner-all");
			jQuery(".viewportlet-header .ui-icon").click(function() {
				jQuery(this).toggleClass("ui-icon-minusthick");
				jQuery(this).parents(".viewportlet:first").remove();
			});

		viewSetup_columSave();

		jQuery(".viewGridview").sortable({
			connectWith: '.viewGridview',
			update: function(event, ui){
                                if(jQuery(this).parent().attr('id') != 'undefined'){
                                    jQuery(this).find(".positioning").val(jQuery(this).parent().attr('id'));
                                }
				viewSetup_columSave();
			}
		});
		//jQuery(".viewGridview").disableSelection();

	});


function viewsSetup_getFields(){

	//jQuery('#fieldTrayview').html('');
	//jQuery('.viewColumn').html('');
	jQuery('#FieldList_Main .table_sorter').each(function(){
		if(jQuery('#viewportlet_'+this.id).length == 0){
			title = jQuery(this).find('h3').html();
			jQuery('#fieldTrayview').append('<div class="viewportlet" id="viewportlet_'+this.id+'"><div class="viewportlet-header"><span class="ui-icon ui-icon-close"></span><strong>'+title+'</strong><input class="layOutview positioning" type="hidden" name="View_'+this.id+'" id="View_'+this.id+'" value="1"/></div></div>');
		}
	});
	jQuery(".viewportlet").addClass("ui-widget ui-widget-content ui-helper-clearfix ui-corner-all")
		.find(".viewportlet-header").addClass("ui-corner-all");
		jQuery(".viewportlet-header .ui-icon").click(function() {
			jQuery(this).toggleClass("ui-icon-minusthick");
			jQuery(this).parents(".viewportlet:first").remove();
                        viewSetup_columSave();
		});
}


function viewSetup_InsertInterface(eid){
    jQuery('.tools_widgets').find('ul').fadeOut('fast');
    jQuery('#fieldTrayholdingPen').html('Loading Interface...');
    ajaxCall('dr_loadInsertInterfaceBox', eid, function(i){
        jQuery('#fieldTrayholdingPen').html(i);
        df_loadOutScripts();
    })
    //alert(eid);
}

function viewSetup_columSave(){
	jQuery('#gridLayoutBoxView').val(jQuery('.layOutview').serialize());
}

function viewSetup_AddRow(){
	rownum = jQuery('.rowWrapperView').length+1;
	//alert(rownum);
	//.each(function(){
	//	alert(jQuery(this).length);
	//});


	jQuery('#viewGridview').append('<div class="rowWrapperView"><div style="clear:both; width:85%; float:left;" class="viewRow" id="viewRow'+rownum+'"><div style="padding:0; margin:0; width:100%; float:left;" id="viewRow'+rownum+'_viewCol1" class="columnView"><input id="viewRow'+rownum+'_viewCol1_controlView" class="viewRow'+rownum+'_controlView" name="Data[Content][_gridView][viewRow'+rownum+'][viewCol1]" type="hidden" value="100%" /><div class="ui-state-error viewGridview viewColumn" style="padding:10px; margin:10px;"></div></div></div><div style="width:15%; padding-top:12px; float:left;" class="viewRow" id="viewRow1Control"><img src="<?php echo WP_PLUGIN_URL; ?>/db-toolkit/data_report/cog.png" style="cursor:pointer;" width="16" height="16" onclick="viewSetupColumns(\'viewRow'+rownum+'\');" /><img src="<?php echo WP_PLUGIN_URL; ?>/db-toolkit/data_report/add.png" style="cursor:pointer;" width="16" height="16" onclick="viewAddColumn(\'viewRow'+rownum+'\');" /><img src="<?php echo WP_PLUGIN_URL; ?>/db-toolkit/data_report/delete.png" style="cursor:pointer;" width="16" height="16" onclick="viewSubtractColumn(\'viewRow'+rownum+'\');" /><img src="<?php echo WP_PLUGIN_URL; ?>/db-toolkit/data_report/no.png" style="cursor:pointer;" width="16" height="16" onclick="viewRemoveColumns(\'viewRow'+rownum+'\');" /></div></div>');
		jQuery(".viewGridview").sortable({
			connectWith: '.viewGridview',
			update: function(event, ui){
                                if(jQuery(this).parent().attr('id') != 'undefined'){
                                    jQuery(this).find(".positioning").val(jQuery(this).parent().attr('id'));
                                }
				viewSetup_columSave();
			}
		});
		//jQuery(".viewGridview").disableSelection();
}

function viewSetupColumns(row){
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
				jQuery('#'+row+' .columnView').each(function(){
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
                                            jQuery('#'+jQuery(this).attr('id')+'_controlView').val(width);
					});
				});
				viewSetup_columSave();
				jQuery("#ui-jsDialog-"+row+"").remove();
			}
		});
}

function viewAddColumn(row){

	cols = jQuery('#'+row+' .columnView').length+1;
	width = 100/cols;
	//jQuery('#'+row).html('');
	//for(i=1; i<= cols; i++){
	//}
	//jQuery('#'+row+' .columnView').each(function(){
		jQuery('#'+row+' .columnView').width(width+'%').animate({},1, function(){
		//alert('ping');
			if(this.id == row+'_viewCol'+(cols-1)){
				jQuery('#'+row).append('<div style="padding:0; margin:0; width:'+width+'%; float:left;" id="'+row+'_viewCol'+cols+'" class="columnView"><input id="'+row+'_viewCol'+cols+'_controlView" class="'+row+'_controlView" name="Data[Content][_gridView]['+row+'][viewCol'+cols+']" type="hidden" value="'+width+'%" /><div class="ui-state-error viewGridview viewColumn" style="padding:10px; margin:10px;"></div>');
				jQuery(".viewGridview").sortable({
					connectWith: '.viewGridview',
					update: function(event, ui){
                                                if(jQuery(this).parent().attr('id') != 'undefined'){
                                                    jQuery(this).find(".positioning").val(jQuery(this).parent().attr('id'));
                                                }
						viewSetup_columSave();
					}
				});
				//jQuery(".viewGridview").disableSelection();
                                jQuery('.'+row+'_controlView').val(width+'%');
			}
                        if(jQuery(this).attr('id').length > 0){
                            jQuery(this).find(".positioning").val(jQuery(this).attr('id'));
                        }
			viewSetup_columSave();
		});

}
function viewSubtractColumn(row){
	cols = jQuery('#'+row+' .columnView').length-1;
	if(cols <= 0){
		return false;
	}
	width = 100/cols;
	//jQuery('#'+row).html('');
	//for(i=1; i<= cols; i++){
	//jQuery('#'+row).append('<div style="padding:0; margin:0; width:'+width+'%; float:left;" id="'+row+'_col'+i+'" class="column"><div class="ui-state-error viewGridview viewColumn" style="padding:10px; margin:10px;"></div></div>');
	jQuery('#'+row+'_viewCol'+(cols+1)+' .viewportlet').appendTo('#'+row+'_viewCol'+cols+' .viewColumn');
	//jQuery('#'+row+'_col'+(cols+1)).fadeOut(100, function(){
		jQuery('#'+row+'_viewCol'+(cols+1)).remove();
		jQuery('#'+row+' .columnView').width(width+'%').animate({}, 1, function(){
                        if(jQuery(this).parent().attr('id') != 'undefined'){
                            jQuery(this).find(".positioning").val(jQuery(this).attr('id'));
                            jQuery('.'+row+'_controlView').val(width+'%');
                        }
			viewSetup_columSave();
		});
	//});
	//}
	jQuery(".viewGridview").sortable({
		connectWith: '.viewGridview',
		update: function(event, ui){
                        if(jQuery(this).parent().attr('id') != 'undefined'){
                            jQuery(this).find(".positioning").val(jQuery(this).parent().attr('id'));
                        }
			viewSetup_columSave();
		}
	});
	//jQuery(".viewGridview").disableSelection();
}
function viewRemoveColumns(row){
	if(jQuery('#'+row+' .viewportlet').length != 0){
		alert('Cannot remove. Row not empty.');
	}else{
		jQuery('#'+row).fadeOut(200, function(){
			jQuery(this).parent().remove();
		});
	}
}

</script>