/*
 *
 * Main Javascript File
 * - DB-Toolkit
 * David Cramer - 2012
 *
 *
 */

function df_fetchFormSetp(id){
	table = jQuery('#'+id).val();
	jQuery('#FieldList_Main').html(' <img src="../wp-content/plugins/db-toolkit/loading.gif" width="16" height="16" alt="loading" align="absmiddle" /> Loading Fields');
	jQuery('#FieldList_Right').html('');
	jQuery('#Return_FieldSelect').html(' <img src="../wp-content/plugins/db-toolkit/loading.gif" width="16" height="16" alt="loading" align="absmiddle" /> Loading Fields');
	ajaxCall('df_tableFormSetup',table, function(c){
            jQuery('#FieldList_Main').html(c);
            df_sorter();
	});
	ajaxCall('df_loadReturnFields',table, function(r){
            jQuery('#Return_FieldSelect').html(r);
	});

    }

    function df_typeChange(id){
	jQuery('#ExtraSetting_'+id).html('');
	ajaxCall('df_alignmentSetup',id, function(j){
            jQuery('#ExtraSetting_'+id).html(' => '+j);
	});
    }
    function df_noOptions(id){
	jQuery('#ExtraSetting_'+id).html('');
    }

    function df_setOptions(field, func, fieldtype){
        /// SET the selected Button to the active button.
        // Minimise the fild type selector
        jQuery('#fieldTypeButton_'+field).html(jQuery('#'+field+'_'+fieldtype).html());
        jQuery('#Fieldtype_'+field).val(fieldtype);
        jQuery('#'+field+'_FieldTypePanel').fadeOut(function(){
            jQuery('#'+field+'_FieldTypePanel').html('');
        });
	jQuery('#ExtraSetting_'+field).html('');
	if(func != 'null'){
            jQuery('#ExtraSetting_'+field).html(' <img src="../wp-content/plugins/db-toolkit/images/indicator.gif" width="16" height="16" alt="loading" align="absmiddle" /> Loading Config...');
            ajaxCall('df_controlFunc',field, jQuery('#_main_table').val(), func, function(x){
                jQuery('#ExtraSetting_'+field).html('&nbsp;'+x);
                jQuery('#ExtraSetting_'+field).fadeIn();
                df_loadOutScripts();
            });
	}
        return false;
    }

    function df_enumOptions(id){
	//table = jQuery('#'+id).val();
	//ajaxCall('df_enumOptions',table, id, function(en){
        //jQuery('#ExtraSetting_'+id).html(' => '+en+'<span id="extrasettings_'+id+'"></span>');
	//});
    }

    function df_sessionvalueChange(id){
	jQuery('#ExtraSetting_'+id).html(' <img src="../wp-content/plugins/db-toolkit/images/indicator.gif" width="16" height="16" alt="loading" align="absmiddle" /> Loading Session Value Setup');
	ajaxCall('df_sessionValueSetup',id, function(tf){
            jQuery('#ExtraSetting_'+id).html(' => '+tf+'<span id="extrasettings_'+id+'"></span>');
	});
    }

    function df_linkedTableSelector(id, req){
	jQuery('#ExtraSetting_'+id).html(' <img src="../wp-content/plugins/db-toolkit/images/indicator.gif" width="16" height="16" alt="loading" align="absmiddle" /> Loading Tables');
	ajaxCall('df_listTables',id, 'df_loadLikedFields', true, false, req, function(tf){
            jQuery('#ExtraSetting_'+id).html(' => '+tf+'<span id="extrasettings_'+id+'"></span>');
	});
    }
    function df_linkedfilteredTableSelector(id, req){
	jQuery('#ExtraSetting_'+id).html(' <img src="../wp-content/plugins/db-toolkit/images/indicator.gif" width="16" height="16" alt="loading" align="absmiddle" /> Loading Tables');
	ajaxCall('df_listTables',id, 'df_loadFilteredLikedFields', true, false, req, function(tf){
            jQuery('#ExtraSetting_'+id).html(' => '+tf+'<span id="extrasettings_'+id+'"></span>');
	});
    }

    function df_loadLikedFields(id){
	table = jQuery('#'+id).val();
	jQuery('#extrasettings_'+id).html(' <img src="../wp-content/plugins/db-toolkit/images/indicator.gif" width="16" height="16" alt="loading" align="absmiddle" /> Loading Fields');
	ajaxCall('df_loadlinkedfields',table,id,function(lr){
            //alert(id);
            jQuery('#extrasettings_'+id).html(' => '+lr);
	});
    }
    function df_loadFilteredLikedFields(id){
	table = jQuery('#'+id).val();
	main = jQuery('#_main_table').val();
	jQuery('#extrasettings_'+id).html(' <img src="../wp-content/plugins/db-toolkit/images/indicator.gif" width="16" height="16" alt="loading" align="absmiddle" /> Loading Fields');
	ajaxCall('df_loadlinkedfilteredfields',table,id, main,function(lr){
            //alert(id);
            jQuery('#extrasettings_'+id).html(' => '+lr);
	});
    }

function bf_loadFieldTypePanel(el, table){
        ert = el.split('_FieldTypePanel');
        field = ert[0];
        if(ert.length == 4){
            field = '__'+ert[2];
        }

        if(jQuery('#'+el).html() == ''){
            jQuery('#'+el+'_status').show();
            ajaxCall('df_buildFieldTypesMenu',field, table, function(l){
                jQuery('#'+el+'_status').hide();
                jQuery('#'+el).html(l);
                jQuery('#'+el).fadeIn();
                //jQuery('#'+el).dialog({
                //    height:140,
                //    modal: true
                //});
            });
        }else{
            jQuery('#'+el).slideToggle(function(){
                jQuery('#'+el).html('');
            });
        }
    }

    function df_sorter(){
        jQuery('.fieldSorter').Sortable({
            accept: 'table_sorter',
            activeclass : 	'sortableactive',
            handle: 'img.OrderSorter',
            hoverclass : 	'highlight',
            snapDistance: '5px',
            tolerance: 'intersect'
        }
    );
    }


function app_dockApp(app){

        ajaxCall('app_dockApp', app, function(a){
            if(a.docked == true){
                jQuery('#app_'+app).removeClass('button');
                jQuery('#app_'+app).addClass('button-primary');
                jQuery('#app_'+app).html('Undock');
            }
            if(a.docked == false){
                jQuery('#app_'+app).addClass('button');
                jQuery('#app_'+app).removeClass('button-primary');
                jQuery('#app_'+app).html('Dock');
            }
            // alter Meny
            jQuery.ajax({
              url: "admin.php?page=dbt_builder",
              context: document.body,
              success: function(data){
                jQuery('#adminmenu').html(jQuery('#adminmenu', data).html());
                jQuery('#wpadminbar').html(jQuery('#wpadminbar', data).html());
              }
            });
            //jQuery('#adminmenu').html(a.html);
        })
    }

    function app_saveDesc(){



        text = jQuery('.appConfigPanel').serialize();



        jQuery('.appConfigPanel').attr('disabled', true);
        ajaxCall('app_SaveDesc', text, function(a){
            alert(a);
            jQuery('.appConfigPanel').removeAttr('disabled');
        })

    }

    function dt_saveInterface(str){

        jQuery('.save_bar_top').css('position', 'relative').prepend('<div class="ui-overlay" id="newInterfaceForm_overlay"><div class="ui-widget-overlay ui-corner-all"></div><div style="position:absolute; padding:6px; left:0; top:0;"><span class="ui-icon ui-icon-arrowrefresh-1-w" unselectable="on" style=" float:left;">close</span>Saving Data</div></div>')
        ajaxCall('dt_saveInterface', str, function(o){
            jQuery('#interfaceID').val(o);
            jQuery('#newInterfaceForm_overlay').hide('slow', function(){
                jQuery(this).remove();
            });
        });
    }

    function dbt_sendError(interface, errordata){
        //alert(errordata);
        ajaxCall('dbt_sendError', interface, errordata, function(i){

            if(i){
                alert('Error Report Sent!')
                jQuery('#interfaceError').fadeOut('5000');
            }else{

                alert('Error sending report. Your server might not be configured to use PHP\'s mail() function. Sorry:)')
                jQuery('#interfaceError').fadeOut('5000');
            }

        })
    }

    function addRowTemplate(){

        // add notification
        ajaxCall('dr_addListRowTemplate', function(t){
            jQuery('#rowTemplateHolder').append(t);
        })

    }

    function dr_addListFieldTemplate(field){

        ajaxCall('dr_addListFieldTemplate', field, function(f){
             jQuery('#fieldTemplateHolder').append(f);
        })


    }

    function df_addPRocess(process){
        table = jQuery('#_main_table').val();
        jQuery('.root_item a.parent').html('<img src="../wp-content/plugins/db-toolkit/images/indicator.gif" align="absmiddle" /> Loading Processor...')
        jQuery('.root_item ul').hide();
        ajaxCall('df_addProcess', process, table, function(p){
            jQuery('#formProcessList').append(p);
            jQuery('.root_item a.parent').html('<strong>Processors</strong>');
            df_loadOutScripts();
        });
    }
    function df_addViewProcess(process){
        table = jQuery('#_main_table').val();
        jQuery('.root_item a.parent').html('<img src="../wp-content/plugins/db-toolkit/images/indicator.gif" align="absmiddle" /> Loading Processor...')
        jQuery('.root_item ul').hide();
        ajaxCall('df_addViewProcess', process, table, function(p){
            jQuery('#viewProcessList').append(p);
            jQuery('.root_item a.parent').html('<strong>Processors</strong>');
            df_loadOutScripts();
        });
    }

    function dt_addLibrary(){
        ajaxCall('dais_addJSLibrary', function(i){
            jQuery('#addonLibrary').append(i);
        })

    }
    function dt_addCSSLibrary(){
        ajaxCall('dais_addCSSLibrary', function(i){
            jQuery('#addonCSSLibrary').append(i);
        })

    }

    function dt_saveFilterSet(EID){
        //alert(EID);
	jQuery('body').prepend('<div id="ui-jsDialog-filterlock" title="Save Filter Set"><p>Loading Panel</p></div>');
	jQuery("#ui-jsDialog-filterlock").dialog({
            position: 'center',
            autoResize: true,
            width: 330,
            modal: true,
            buttons: {
                'Close': function() {jQuery(this).dialog("close"); },
                'Save': function() {

                    ajaxCall('dt_saveFilterLock', EID, jQuery('#ui-jsDialog-filterlock form').serializeArray(), function(c){
                        if(c == true){

                            jQuery('#setFilters_'+EID).submit();
                        }else{
                            jQuery("#ui-jsDialog-filterlock").html('<form>'+c+'</form>');
                            jQuery("#ui-jsDialog-filterlock").dialog('option', 'position', 'center');
                            df_loadOutScripts();
                        }
                    });

                }
            },

            open: function(event, ui) {
                ajaxCall('dt_saveFilterLock', EID, function(c){
                    jQuery("#ui-jsDialog-filterlock").html('<form>'+c+'</form>');
                    jQuery("#ui-jsDialog-filterlock").dialog('option', 'position', 'center');
                    df_loadOutScripts();
                });
            },
            close: function(event, ui) {
                jQuery("#ui-jsDialog-filterlock").remove();
            }
        });


    }

    function dt_addNewApp(){
        sw = jQuery('#_Application_New').css('display');
        if(sw == 'none'){
            jQuery('#_Application_New').show().removeAttr('disabled').focus();
            jQuery('#appsSelector').hide().attr('disabled', 'disabled');
            jQuery('#addAppB').html('Cancel');
        }else{
            jQuery('#_Application_New').hide().attr('disabled', 'disabled');
            jQuery('#appsSelector').show().removeAttr('disabled').focus();
            jQuery('#addAppB').html('Add New');
        }
        return false;
    }

    function dt_iconChooser(icon){
        src = jQuery('#interfaceIconPreview').attr('src');
        parts = src.split('/');

	jQuery('body').prepend('<div id="ui-jsDialog-iconchooser" title="Loading"><p>Loading Icons</p></div>');
	jQuery("#ui-jsDialog-iconchooser").dialog({
            position: 'center',
            autoResize: true,
            width: 390,
            modal: true,
            buttons: {
                'Close': function() {jQuery(this).dialog("close"); },
                'Select': function() {
                    newIcon = jQuery('input[name=selectedIcon]:checked').val();
                    //alert(newIcon);
                    jQuery('#interfaceIconPreview').attr("src", newIcon);
                    iconparts = newIcon.split('/');
                    jQuery('#_Application_Icon').val(iconparts[iconparts.length-1]);
                    jQuery(this).dialog("close");
                }
            },

            open: function(event, ui) {
                ajaxCall('dt_iconSelector',parts[parts.length-1], function(c){
                    jQuery("#ui-jsDialog-iconchooser").html(c);
                    jQuery("#ui-jsDialog-iconchooser").dialog('option', 'position', 'center');
                    df_loadOutScripts();
                });
            },
            close: function(event, ui) {
                jQuery("#ui-jsDialog-iconchooser").remove();
            }
        });
    }

    function dr_addLinking(table){

        ajaxCall('df_tableReportSetup', table, 0, 0, 'Linking', function(l){

            jQuery('#drToolBox').append(l);

        })
    }

    function dr_loadLinkFields(table){
        ajaxCall('df_ListFields', table, 'false', '', function(p){

            alert(p);

        })
    }

    function dr_getParameterByName(name){
        name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
        var regexS = "[\\?&]"+name+"=([^&#]*)";
        var regex = new RegExp( regexS );
        var results = regex.exec( window.location.href );
        if( results == null )
            return "";
        else
            return results[1];
    }


    function dr_pageInput(eid, pg){
	jQuery("input").keypress(function(e){
            switch(e.which){
                case 13:
                    dr_goToPage(eid, pg);
                    break;
            }
	});
    }

    function dr_switchLive(EID){
	jQuery('#liveView_'+EID).toggleClass('reloadliveon', 1000);
    }

    function dr_addSectionBreak(area){

        // number = jQuery('.sectionBreak').length;
	number=Math.floor(Math.random()*99999);
	jQuery('#fieldTray'+area).prepend('<div style="padding: 3px;" class="list_row4 table_sorter sectionBreak '+area+'portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" id="SectionBreak_SectionBreak'+number+'"><div class="'+area+'portlet-header ui-corner-all"><img align="absmiddle" class="OrderSorter" src="data_report/arrow_out.png" style=""/><strong>Title:</strong><input type="text" class="sectionTitle" name="Data[Content][_SectionBreak][_SectionBreak'+number+'][Title]" /><span class="ui-icon ui-icon-close"></span></div><div style="padding:3px;"><strong>Caption:</strong> <input type="text" class="sectionTitle" name="Data[Content][_SectionBreak][_SectionBreak'+number+'][Caption]" /></div><input class="layOut'+area+' positioning" type="hidden" name="_SectionBreak'+number+'" id="section_'+number+'" value="1"/></div>');
	jQuery("."+area+"portlet-header .ui-icon").click(function() {
            jQuery(this).toggleClass("ui-icon-minusthick");
            jQuery(this).parents("."+area+"portlet:first").remove();
	});
        return;

    }

    function dr_addTab(area){

        // number = jQuery('.sectionBreak').length;
	number=Math.floor(Math.random()*99999);
	jQuery('#fieldTray'+area).prepend('<div style="padding: 3px;" class="list_row4 table_sorter tab '+area+'portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" id="tab_tab'+number+'"><div class="'+area+'portlet-header ui-corner-all"><img align="absmiddle" class="OrderSorter" src="data_report/arrow_out.png" style=""/>Tab Title<input type="text" class="tabTitle" name="Data[Content][_Tab][_tab'+number+'][Title]" /><span class="ui-icon ui-icon-close"></span></div><input class="layOut'+area+' positioning" type="hidden" name="_tab'+number+'" id="tab_'+number+'" value="1"/></div>');
	jQuery("."+area+"portlet-header .ui-icon").click(function() {
            jQuery(this).toggleClass("ui-icon-minusthick");
            jQuery(this).parents("."+area+"portlet:first").remove();
	});

	jQuery("."+area+"Grid"+area).sortable({
            connectWith: '.'+area+'Grid'+area,
            update: function(event, ui){
                jQuery(this).find(".positioning").val(jQuery(this).parent().attr('id'));
                formSetup_columSave();
                viewSetup_columSave();
            }
	});
	//jQuery(".formGrid"+area).disableSelection();

	//dr_sorter();
    }

    function dr_addHTML(area){

        // number = jQuery('.sectionBreak').length;
	number=Math.floor(Math.random()*99999);
	jQuery('#fieldTray'+area).prepend('<div style="padding: 3px;" class="list_row4 table_sorter html '+area+'portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" id="html_html'+number+'"><div class="'+area+'portlet-header ui-corner-all"><img align="absmiddle" class="OrderSorter" src="data_report/arrow_out.png" style=""/>HTML<br /><textarea class="htmlTitle" name="Data[Content][_html][_html'+number+'][Title]" style="width:90%; height:100px;" ></textarea><span class="ui-icon ui-icon-close"></span></div><input class="layOut'+area+' positioning" type="hidden" name="_html'+number+'" id="html_'+number+'" value="1"/></div>');
	jQuery("."+area+"portlet-header .ui-icon").click(function() {
            jQuery(this).toggleClass("ui-icon-minusthick");
            jQuery(this).parents("."+area+"portlet:first").remove();
	});

	jQuery("."+area+"Grid"+area).sortable({
            connectWith: '.'+area+'Grid'+area,
            update: function(event, ui){
                jQuery(this).find(".positioning").val(jQuery(this).parent().attr('id'));
                formSetup_columSave();
                viewSetup_columSave();
            }
	});
	//jQuery(".formGrid"+area).disableSelection();

	//dr_sorter();
    }

    function dr_removeSectionBreak(el){
	jQuery('#'+el).remove();
	//dr_sorter();
	//alert('pi');
    }
    function dr_addPassbackField(){
	table = jQuery('#_main_table').val();
        remove = 0;
        if(jQuery('.passBackField').length >= 1){
            remove = 1;
        }

	ajaxCall('dr_loadPassbackFields', table, 'none', 0, remove, function(c){
            jQuery('#PassBack_FieldSelect').append(c);
	});
    }


    function dr_loadDataSource(url){

        jQuery('#_dataSourceView').html('<img src="../wp-content/plugins/db-toolkit/images/indicator.gif" align="absmiddle" /> Loading Data...');
        ajaxCall('dr_dataSourceMapping', url, function(f){
            jQuery('#_dataSourceView').html(f);
            df_loadOutScripts();
        });

    }

    function dr_loadFieldMapping(url, root, table){

        jQuery('#_dataFieldMapView').html('<img src="../wp-content/plugins/db-toolkit/images/indicator.gif" align="absmiddle" /> Loading Data...');
        ajaxCall('dr_loadFieldMapping', url, root, table, function(i){
           jQuery('#_dataFieldMapView').html(i);
        });
    }




    function dt_addNewTable(db){

        id = Math.floor(Math.random()*9999999);

	if(jQuery("#ui-jsDialog-"+id).length == 1){
            jQuery("#ui-jsDialog-"+id).remove();
	}
	jQuery('body').prepend('<div id="ui-jsDialog-'+id+'" title="Create Table"><p>This will add a new table to `<strong>'+db+'</strong>`</p><p><label>Name</lable><input type="text" id="'+id+'_newTable" value="" style="width:98%;" /></p></div>');
	jQuery("#ui-jsDialog-"+id).dialog({
            position: 'center',
            title: 'New Table',
            autoResize: true,
            minWidth: 200,
            modal: true,
            buttons: {
                'Cancel': function() {jQuery(this).dialog("close"); },
                'Create': function() {
                    if(jQuery('#'+id+'_newTable').val().length <= 0){
                        alert('Please give your table a name.');
                    }else{
                        jQuery('#'+id+'_newTable').attr('disabled', 'disabled');
                        ajaxCall('dt_buildNewTable', jQuery('#'+id+'_newTable').val(), function(v){
                            if(v.error){
                                alert(v.error);
                                jQuery('#'+id+'_newTable').removeAttr('disabled');
                            }else{
                                jQuery('#mainTableSelector').html(v.html);
                                dr_fetchPrimSetup('_main_table');

                                jQuery('#ui-jsDialog-'+id).dialog("close");
                            }

                        })
                    }
                }
            },
            close: function(event, ui) {
                jQuery("#ui-jsDialog-"+id).remove();
            }
        });

    }


    function dr_addField(){

        id = Math.floor(Math.random()*9999999);

	if(jQuery("#ui-jsDialog-"+id).length == 1){
            jQuery("#ui-jsDialog-"+id).remove();
	}
	jQuery('body').prepend('<div id="ui-jsDialog-'+id+'" title="Add Field"><p><label>Name</lable><input type="text" id="'+id+'_newField" value="" style="width:98%;" /></p></div>');
	jQuery("#ui-jsDialog-"+id).dialog({
            position: 'center',
            title: 'Add Field',
            autoResize: true,
            minWidth: 200,
            modal: true,
            buttons: {
                'Cancel': function() {jQuery(this).dialog("close"); },
                'Add Field': function() {
                    if(jQuery('#'+id+'_newField').val().length <= 0){
                        alert('Please give your field a name.');
                    }else{
                        jQuery('#'+id+'_newField').attr('disabled', 'disabled');
                        ajaxCall('dt_buildNewField', jQuery('#'+id+'_newField').val(), function(f){
                            if(f.error){
                                alert(f.error);
                                jQuery('#'+id+'_newField').removeAttr('disabled');
                            }else{
                                jQuery('#FieldList_Main').append(f);
                                jQuery('#ui-jsDialog-'+id).dialog("close");
                            }

                        })
                    }
                }
            },
            close: function(event, ui) {
                jQuery("#ui-jsDialog-"+id).remove();
            }
        });


    }

    function dr_rebuildApps(){


        jQuery('#content').append('<div id="rebuildStatus" style="padding:10px;">Rebuilding Application Indexes....</div>');
        jQuery('#dbt-apps').hide();

        ajaxCall('dr_rebuildApps', function(f){
            jQuery('#rebuildStatus').remove();
            // alter Meny
            jQuery.ajax({
              url: "admin.php?page=dbt_builder",
              context: document.body,
              success: function(data){
                jQuery('#dbt-apps').html(jQuery('#dbt-apps', data).html());
                jQuery('#dbt-apps').fadeIn(500);
              }
            });
        });

    }

    function dr_addApplication(){

        id = Math.floor(Math.random()*9999999);

	if(jQuery("#ui-jsDialog-"+id).length == 1){
            jQuery("#ui-jsDialog-"+id).remove();
	}
	jQuery('body').prepend('<div id="ui-jsDialog-'+id+'" title="New Application"><p><label>Name</lable><input type="text" id="'+id+'_newTitle" value="" style="width:98%;" /></p><p><label>Description</lable><textarea type="text" id="'+id+'_newDesc" style="width:98%; height:100px;" ></textarea></p></div>');
	jQuery("#ui-jsDialog-"+id).dialog({
            position: 'center',
            title: 'New Application',
            autoResize: true,
            minWidth: 200,
            modal: true,
            buttons: {
                'Cancel': function() {jQuery(this).dialog("close"); },
                'Create Application': function() {
                    if(jQuery('#'+id+'_newTitle').val().length <= 0){
                        alert('You need to give your application a name.');
                    }else{
                        jQuery('#'+id+'_newTitle').attr('disabled', 'disabled');
                        jQuery('#'+id+'_newDesc').attr('disabled', 'disabled');
                        ajaxCall('app_createApplication', jQuery('#'+id+'_newTitle').val(),jQuery('#'+id+'_newDesc').val(), function(f){
                            if(f.error){
                                alert(f.error);
                                jQuery('#'+id+'_newTitle').removeAttr('disabled');
                                jQuery('#'+id+'_newDesc').removeAttr('disabled');
                            }else{
                                jQuery('#FieldList_Main').append(f);
                                window.location = "admin.php?page=dbt_builder";
                                //location.reload();
                            }

                        })
                    }
                }
            },
            close: function(event, ui) {
                jQuery("#ui-jsDialog-"+id).remove();
            }
        });


    }



        function dbtMarketLogin(){

            user=jQuery('#dbt_user').val();
            pass=jQuery('#dbt_pass').val();
            jQuery('#loginStatus').html('');
            ajaxCall('app_marketLogin', user, pass, function(x){
                if(x.error){
                     if(x.error.errors.incorrect_password){
                        jQuery('#loginStatus').html(x.error.errors.incorrect_password[0]);
                     }
                     if(x.error.errors.invalid_username){
                        jQuery('#loginStatus').html(x.error.errors.invalid_username[0]);
                     }
                     return;
                }
                location.reload();
            })

        }

        function app_setLanding(app, intf){

            if(jQuery('#rdo_'+intf).attr('checked')){
                ajaxCall('app_setLanding', app, intf, function(){});
            }

        }