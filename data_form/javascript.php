<?php
define('DBT_URL', '../wp-content/plugins/db-toolkit');

function fieldtypes_script_loadFolderContents($Folder){
    $Index = 0;
    $List = array();
    if (is_dir($Folder)) {
        if ($dh = opendir($Folder)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != '.' && $file != '..') {
                    $type = 0;
                    if (filetype($Folder . '/' . $file) == 'file') {
                        $type = 1;
                    }
                    $List[$type][$Index] = $Folder . '/' . $file;
                }
                $Index++;
            }
            closedir($dh);
        }
    }
    ksort($List);
    return $List;
}

if (file_exists('fieldtypes')) {
    $Types = fieldtypes_script_loadFolderContents('fieldtypes');
    foreach ($Types[0] as $Type) {
        //echo "alert('".$Type."');";
        //echo "alert('".$Type[0]."');\n";
        if (file_exists($Type . '/javascript.php')) {
            echo "
				// " . $Type . "
			";
            include($Type . '/javascript.php');
        }
    }
} else {
    $Types = fieldtypes_script_loadFolderContents('fieldtypes');
    foreach ($Types[0] as $Type) {
        //echo "alert('".$Type[0]."');";
        //echo "alert('".$Type[0]."');\n";
        if (file_exists($Type . '/javascript.php')) {
            echo "
				// " . $Type . "
			";
            include($Type . '/javascript.php');
        }
    }
}
//ob_start();
//dump($Types);
//$ot = ob_get_clean();
//echo "alert('".$ot."');\n";
?>
//<script>

    function df_setToggle(id){
        if(jQuery('#'+id+'_check').is(':checked') == false){
            jQuery('#'+id+'_check').attr('checked', 'checked');
            jQuery('#'+id).addClass('button-primary');
            jQuery('#'+id).removeClass('button');
        }else{
            jQuery('#'+id+'_check').removeAttr('checked');
            jQuery('#'+id).addClass('button');
            jQuery('#'+id).removeClass('button-primary');
        }
    }



    function df_dialog(message, id){
        if(id != false){
            //row = jQuery('tr[ref="'+id+' highlight"] td');
            //alert(row.className())
            //row.switchClass('report_entry','ui-state-highlight',500,function(){
            //alert('done');
            //	row.switchClass('ui-state-highlight','report_entry',500);
            //});
        }
        if(jQuery("#ui-dialog-notice").length == 1){
            jQuery("#ui-dialog-notice").remove();
        }
        jQuery('body').append('<div id="ui-dialog-notice" title="Notice"><p><span class="ui-icon ui-icon-info" style="float: left; margin-right: 0.3em;"></span>'+message+'</p></div>');
        jQuery("#ui-dialog-notice").dialog({
            bgiframe: true,
            resizable: false,
            height:'auto',
            modal: true,
            buttons: {
		'Close': function() {
                    jQuery(this).dialog("close");
                }
            },
            close: function(event, ui) {
		jQuery("#ui-dialog-notice").remove();
            },
            open: function(event, ui) {
		jQuery("#ui-dialog-notice").dialog('option', 'position', 'center');
            }
        });

	return;

	var Dialog = new Boxy('<div>'+message+'</div>', {title: 'Notice', modal: true, unloadOnHide: true});
	return false;



    }


    function df_buildQuickCaptureForm(eid, ajaxSubmit, addquery, callback){


	if(jQuery("#ui-jsDialog-"+eid+"").length == 1){
            jQuery("#ui-jsDialog-"+eid+"").remove();
	}

	jQuery('body').append('<div id="ui-jsDialog-'+eid+'" title="Loading"><p><img src="<?php echo DBT_URL; ?>/images/indicator.gif" width="16" height="16" alt="loading" align="absmiddle" /> Loading Form</p></div>');
	jQuery("#ui-jsDialog-"+eid+"").dialog({
            position: 'center',
            autoResize: true,
            modal: true,
            buttons: {
                'Cancel': function() {jQuery(this).dialog("close"); }
            },
            dragStart: function(event, ui) {
                jQuery(".formError").remove();
            },
            open: function(event, ui) {
                ajaxCall('df_buildQuickCaptureForm',eid, addquery, function(c){
                    jQuery("#ui-jsDialog-"+eid+"").dialog('option', 'title', c.title);
                    jQuery("#ui-jsDialog-"+eid+"").dialog('option', 'buttons', {
                        'Cancel': function() {
                            jQuery(this).dialog("close");
                        },
                        'Save': function() {
                            //dr_BuildUpDateForm(eid, id); jQuery(this).dialog('close');
                            //if(ajaxSubmit == false){
                            //alert(ajaxSubmit);
                            if(ajaxSubmit == true){
                                //alert('p');
                                jQuery("#data_form_"+eid+"").bind('submit', function(){
                                formData = jQuery("#data_form_"+eid+"").serialize();
                                jQuery("#ui-jsDialog-"+eid+"").html('Sending...');
                                jQuery("#ui-jsDialog-"+eid+"").dialog('option', 'buttons', {});
                                ajaxCall('df_processAjaxForm',formData, addquery, function(p){
                                    jQuery("#ui-jsDialog-"+eid+"").remove();

                                    //load callback
                                    if (typeof callback == 'function') { // make sure the callback is a function
                                        callback.call(this, eid, ajaxSubmit, addquery, p); // brings the scope to the callback
                                    }

                                    df_loadOutScripts();
                                    dr_goToPage(eid, false, false, addquery);
                                });

                                return false;
                                });
                                jQuery("#data_form_"+eid+"").submit();
                            }else{
                                jQuery("#data_form_"+eid+"").submit();
                            }

                            //alert(eid);
                        }
                    });
                    jQuery("#ui-jsDialog-"+eid+"").html(c.html);

                    if(c.script){
                        eval(c.script);
                    }
                    jQuery("#ui-jsDialog-"+eid+"").dialog('option', 'width', parseFloat(c.width));
                    jQuery("#ui-jsDialog-"+eid+"").dialog('option', 'position', 'center');
                });
            },
            close: function(event, ui) {
                jQuery(".formError").remove();
                jQuery("#ui-jsDialog-"+eid+"").remove();
            }
        });
    }

    function df_buildImportForm(eid){


	if(jQuery("#ui-jsDialog-"+eid+"").length == 1){
            jQuery("#ui-jsDialog-"+eid+"").remove();
	}
	jQuery('#report_tools_'+eid).append('<div id="ui-jsDialog-'+eid+'" title="Loading"><p><img src="<?php echo DBT_URL; ?>/images/indicator.gif" width="16" height="16" alt="loading" align="absmiddle" /> Loading Form</p></div>');
	jQuery("#ui-jsDialog-"+eid+"").dialog({
            position: 'center',
            autoResize: true,
            modal: true,
            buttons: {
                'Cancel': function() {jQuery(this).dialog("close"); }
            },
            dragStart: function(event, ui) {
                jQuery(".formError").remove();
            },
            open: function(event, ui) {
                ajaxCall('dr_importer',eid, function(c){
                    jQuery("#ui-jsDialog-"+eid+"").dialog('option', 'title', c.title);
                    jQuery("#ui-jsDialog-"+eid+"").dialog('option', 'buttons', {
                        'Cancel': function() {
                            jQuery(this).dialog("close");
                        },
                        'Import': function() {
                            //dr_BuildUpDateForm(eid, id); jQuery(this).dialog('close');
                            //if(ajaxSubmit == false){
                            jQuery("#import_form_"+eid+"").submit();
                        }
                    });
                    jQuery("#ui-jsDialog-"+eid+"").html(c.html);
                    //alert(parseFloat(c.width));
                    jQuery("#ui-jsDialog-"+eid+"").dialog('option', 'width', parseFloat(c.width));
                    jQuery("#ui-jsDialog-"+eid+"").dialog('option', 'position', 'center');
                    df_loadOutScripts();
                });
            },
            close: function(event, ui) {
                jQuery(".formError").remove();
                jQuery("#ui-jsDialog-"+eid+"").remove();
            }
        });
    }

    function df_buildImportManager(eid){

	if(jQuery("#ui-jsDialog-"+eid+"").length == 1){
            jQuery("#ui-jsDialog-"+eid+"").remove();
	}
	jQuery('#report_tools_'+eid).append('<div id="ui-jsDialog-'+eid+'" title="Loading"><p><img src="<?php echo DBT_URL; ?>/images/indicator.gif" width="16" height="16" alt="loading" align="absmiddle" /> Loading Form</p></div>');
	jQuery("#ui-jsDialog-"+eid+"").dialog({
            position: 'center',
            autoResize: true,
            modal: true,
            buttons: {
                'Cancel': function() {jQuery(this).dialog("close"); }
            },
            dragStart: function(event, ui) {
                jQuery(".formError").remove();
            },
            open: function(event, ui) {
                ajaxCall('dr_buildImportManager',eid, function(c){
                    jQuery("#ui-jsDialog-"+eid+"").dialog('option', 'title', c.title);
                    jQuery("#ui-jsDialog-"+eid+"").dialog('option', 'buttons', {
                        'Cancel': function() {
                            ajaxCall('dr_cancelImport', eid, function(){})
                            jQuery(this).dialog("close");
                        },
                        'Import': function() {
                            //dr_BuildUpDateForm(eid, id); jQuery(this).dialog('close');
                            //if(ajaxSubmit == false){
                            ajaxCall('dr_prepairImport', eid, function(r){
                                jQuery("#import_form_"+eid+"").submit();
                            });
                        }
                    });
                    jQuery("#ui-jsDialog-"+eid+"").html(c.html);
                    //alert(parseFloat(c.width));
                    jQuery("#ui-jsDialog-"+eid+"").dialog('option', 'width', parseFloat(c.width));
                    jQuery("#ui-jsDialog-"+eid+"").dialog('option', 'position', 'center');
                    df_loadOutScripts();
                });
            },
            close: function(event, ui) {
                ajaxCall('dr_cancelImport', eid, function(){})
                jQuery(".formError").remove();
                jQuery("#ui-jsDialog-"+eid+"").remove();
            }
        });
    }
    function df_processImport(eid){

	if(jQuery("#ui-jsDialog-"+eid+"").length == 1){
            jQuery("#ui-jsDialog-"+eid+"").remove();
	}
	jQuery('#report_tools_'+eid).append('<div id="ui-jsDialog-'+eid+'" title="Loading"><p><img src="<?php echo DBT_URL; ?>/images/indicator.gif" width="16" height="16" alt="loading" align="absmiddle" /> Loading Form</p></div>');
	jQuery("#ui-jsDialog-"+eid+"").dialog({
            position: 'center',
            autoResize: true,
            modal: true,
            dragStart: function(event, ui) {
                jQuery(".formError").remove();
            },
            open: function(event, ui) {
                ajaxCall('dr_prepairImport',eid, function(c){
                    jQuery("#ui-jsDialog-"+eid+"").dialog('option', 'title', c.title);
                    jQuery("#ui-jsDialog-"+eid+"").html(c.html);
                    //alert(parseFloat(c.width));
                    jQuery("#ui-jsDialog-"+eid+"").dialog('option', 'width', parseFloat(c.width));
                    jQuery("#ui-jsDialog-"+eid+"").dialog('option', 'position', 'center');

                    jQuery( "#"+eid+"_importProgress").progressbar({
                        value: 0
                    });
                    dr_startImportBatch(eid);
                    df_loadOutScripts();
                });
            },
            close: function(event, ui) {
                ajaxCall('dr_cancelImport', eid, function(){})
                jQuery(".formError").remove();
                jQuery("#ui-jsDialog-"+eid+"").remove();
            }
        });
    }

    function dr_startImportBatch(eid){

        ajaxCall('dr_processImport', eid, function(p){
            if(p != 'false'){
                jQuery( "#"+eid+"_importProgress").progressbar( "option", "value", p.p );
                jQuery('#import_processedCount').html(p.d);
                dr_startImportBatch(eid);
            }else{
                jQuery( "#"+eid+"_importProgress").progressbar( "option", "value", 100 );
                jQuery("#ui-jsDialog-"+eid+"").remove();
                dr_goToPage(eid, false);
            }

        });

    }

    function dr_reloadImport(eid, delim){


        ajaxCall('dr_buildImportManager',eid, delim, function(c){
            jQuery("#ui-jsDialog-"+eid+"").html(c.html);
            jQuery('#importDelimeter').focus();
        });


    }

    function df_loadOutScripts(){
	ajaxCall('df_loadOutScripts',function(os){
            eval(os);
	});
    }

    function df_shortenURL(el){
	url = jQuery('#'+el).val();
	ajaxCall('df_anewFunctionToBe',url, function(short){
            jQuery('#'+el).val(short);
	});
    }

