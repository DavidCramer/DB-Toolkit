//<script>

    function dr_exportReport(url, eid, isGlobal){

        jQuery('.export').addClass('active');
        jQuery('.export').removeClass('export');
        if(isGlobal){
            chartData = jQuery('.chartData').serializeArray();
        }else{
            chartData = jQuery('#chartData_'+eid).serializeArray();
        }

        if(chartData.length > 0){
            ajaxCall('dr_exportChartImage',chartData, function(d){
                jQuery('.active').addClass('export');
                jQuery('.active').removeClass('active');
                window.location.replace(url);
            })
        }else{
                jQuery('.active').addClass('export');
                jQuery('.active').removeClass('active');
                window.location.replace(url);
        }
        return;
    }

    function dr_applyFilters(eid, clear){

        filterstr = jQuery('#setFilters_'+eid).serialize();
        jQuery('#reportPanel_'+eid).css('position', 'relative').prepend('<div class="ui-overlay" id="reportpanel_block_'+eid+'"><div class="ui-widget-overlay ui-corner-all"></div><div style="position:absolute; z-index:999999; padding:6px; left:0; top:0;"><span class="ui-icon ui-icon-arrowrefresh-1-w" unselectable="on" style=" float:left;"></span>Loading Data</div></div>');

        if(clear == true){
            filterstr = 'clear';
            jQuery('#setFilters_'+eid+' input').val('');
            jQuery('#setFilters_'+eid+' input').removeAttr("checked");;
            //jQuery('.filterSearch').val('');
        }

        ajaxCall('dr_setFilters', eid, filterstr, function(p){
           jQuery('#reportpanel_block_'+eid).remove();
           dr_goToPage(eid, false, false);

           jQuery('#report_tools_'+eid).remove();
           jQuery('#filterPanel_'+eid).replaceWith(p);
        });

    }

    function dr_sortReport(eid, field, dir){

	jQuery('#reportPanel_'+eid).css('position', 'relative').prepend('<div class="ui-overlay" id="reportpanel_block_'+eid+'"><div class="ui-widget-overlay ui-corner-all"></div><div style="position:absolute;z-index:999999; padding:6px; left:0; top:0;"><span class="ui-icon ui-icon-arrowrefresh-1-w" unselectable="on" style=" float:left;">close</span>Loading Data</div></div>');

	ajaxCall('dr_BuildReportGrid',eid, 0, field, dir, function(dta){
            jQuery('#reportPanel_'+eid).html(dta);
            df_loadOutScripts();
	});
    }
    function dr_goToPage(eid, page, global, addQuery){

	if(page == false){
            if(global== false){
                jQuery('#reportPanel_'+eid).css('position', 'relative').prepend('<div class="ui-overlay" id="reportpanel_block_'+eid+'"><div class="ui-widget-overlay ui-corner-all"></div><div style="position:absolute;z-index:999999; padding:6px; left:0; top:0;"><span class="ui-icon ui-icon-arrowrefresh-1-w" unselectable="on" style=" float:left;">close</span>Loading Data</div></div>');
                ajaxCall('dr_BuildReportGrid',eid, 0,0,0,0,0,0,addQuery, function(dta){
                    jQuery('#reportPanel_'+eid).html(dta);
                    df_loadOutScripts();
                });
            }else{
                jQuery('.data_report_Table').each(function(g){
                    report = this.id.split("_")[2];
                    jQuery('#reportPanel_'+report).css('position', 'relative').prepend('<div class="ui-overlay" id="reportpanel_block_'+report+'"><div class="ui-widget-overlay ui-corner-all"></div><div style="position:absolute;z-index:999999; padding:6px; left:0; top:0;"><span class="ui-icon ui-icon-arrowrefresh-1-w" unselectable="on" style=" float:left;">close</span>Loading Data</div></div>');
                    dr_reloadData(report);
                });
            }
	}else{

            jQuery('#reportPanel_'+eid).css('position', 'relative').prepend('<div class="ui-overlay" id="reportpanel_block_'+eid+'"><div class="ui-widget-overlay ui-corner-all"></div><div style="position:absolute;z-index:999999; padding:6px; left:0; top:0;"><span class="ui-icon ui-icon-arrowrefresh-1-w" unselectable="on" style=" float:left;">close</span>Loading Data</div></div>');
            ajaxCall('dr_BuildReportGrid',eid, page,0,0,0,0,0,addQuery, function(dta){
                jQuery('#reportPanel_'+eid).html(dta);
                df_loadOutScripts();
            });
	}
	df_loadOutScripts();
    }

    function dr_reloadData(eid){
	ajaxCall('dr_BuildReportGrid',eid, function(x){
            jQuery('#reportPanel_'+eid).html(x);
            df_loadOutScripts();
	});
    }

    function dr_selectAll(id){
	jQuery('.itemRow_'+id).addClass('highlight');
    }
    function dr_deSelectAll(id){
	jQuery('.itemRow_'+id).removeClass('highlight');
    }

    function dr_fetchPrimSetup(table){
        jQuery('#addFieldButton').show();
	jQuery('#fieldTray').html('');
	jQuery('.formColumn').html('');
	table = jQuery('#'+table).val();
	jQuery('#FieldList_left').html('');
	jQuery('#FieldList_right').html('');
	jQuery('#referenceSetup').html('Searching insert reference...');
	jQuery('#PassBack_FieldSelect').html('Loading Fields...');
	ajaxCall('df_searchReferenceForm',table, function(v){
            jQuery('#PassBack_FieldSelect').html('');
            if(v == false){
                jQuery('#referenceSetup').html('');
                df_fetchreportSetp(table, false);
                dr_addPassbackField();
            }else{
                jQuery('#referenceSetup').html(v);
            }
	});
    }

    function df_fetchreportSetp(table, eid){
	jQuery('#FieldList_Main').html('Loading Fields');
	jQuery('#Return_FieldSelect').html('Loading Fields');
	jQuery('#sortFieldSelect').html('Loading Fields');
	ajaxCall('df_tableReportSetup',table, eid, function(c){
            jQuery('#FieldList_Main').html(c);
            dr_sorter();
	});
	ajaxCall('df_loadReturnFields',table, function(r){
            jQuery('#Return_FieldSelect').html(r);
	});
	ajaxCall('df_loadSortFields',table, function(s){
            jQuery('#sortFieldSelect').html(s);
	});
    }

    function dr_sorter(){
        jQuery(".columnSorter").sortable({

            placeholder: 'sortable-placeholder',
            forcePlaceholderSize: true,
            connectWith: '.columnSorter',
            handle: 'h3',
            stop: function(p){
                //alert(columns);
                dr_columSave();
            }

        });
        dr_columSave();
    }
    function dr_columSave(){
	var columns = new Array();
	var index = 0;
	jQuery('.columnSorter').each(function(l){
            list = this.id;
            if(list != ''){
                colSer = jQuery(this).sortable('serialize', {'key' : this.id+'[]'});
                columns[index] = colSer
                index++;
            }
	});
	output = columns.join('&');
	jQuery('#_FormLayout').val(output);
    }
    function dr_enableDisableField(field){
	if(field.checked == false){
            jQuery('#Fieldtype_'+field.id).attr('disabled', 'disabled');
	}else{
            jQuery('#Fieldtype_'+field.id).removeAttr('disabled');
	}

    }

    function df_valuedFilter(id){
        jQuery('#ExtraSetting_'+id).html('Loading Filter Setup');

	ajaxCall('df_listTables',id, 'dr_loadvaluefilteredfields', function(tf){
            jQuery('#ExtraSetting_'+id).html(' => '+tf+'<span id="extrasettings_'+id+'"></span>');
	});


	//ajaxCall('df_vlauedFilterSetup',id, function(tf){
	//	jQuery('#ExtraSetting_'+id).html(' => '+tf+'<span id="extrasettings_'+id+'"></span>');
	//});
    }

    function df_loadEntry(rid, eid, ismodal){

        id = Math.floor(Math.random()*9999999);

	if(jQuery("#ui-jsDialog-"+id+"-"+eid+"").length == 1){
            jQuery("#ui-jsDialog-"+id+"-"+eid+"").remove();
	}
	jQuery('body').prepend('<div id="ui-jsDialog-'+id+'-'+eid+'" title="Loading"><p>Loading Entry</p></div>');
	jQuery("#ui-jsDialog-"+id+"-"+eid+"").dialog({
            position: 'center',
            autoResize: true,
            minWidth: 200,
            modal: ismodal,
            buttons: {
                'Cancel': function() {jQuery(this).dialog("close"); }
            },

            open: function(event, ui) {
                ajaxCall('di_showItem', eid, rid, function(c){
                    jQuery("#ui-jsDialog-"+id+"-"+eid+"").dialog('option', 'title', c.title);
                    if(c.edit == true){
                        jQuery("#ui-jsDialog-"+id+"-"+eid+"").dialog('option', 'buttons', {
                            'Close': function() {
                                jQuery(this).dialog("close"); },
                            'Edit': function() {
                                jQuery(this).dialog('close');
                                dr_BuildUpDateForm(eid, rid);
                            }
                        });
                        //}else{
                        //	jQuery("#ui-jsDialog-"+id+"-"+eid+"").dialog('option', 'buttons', { 'Close': function() {
                        //		jQuery(this).dialog("close"); }
                        //	});
                    }
                    jQuery("#ui-jsDialog-"+id+"-"+eid+"").html(c.html);
                    jQuery("#ui-jsDialog-"+id+"-"+eid+"").dialog('option', 'width', parseFloat(c.width));
                    jQuery("#ui-jsDialog-"+id+"-"+eid+"").dialog('option', 'position', 'center');

                    // add tile dialog tool
                    if(jQuery('.ui-dialog').length == 2){
                        jQuery('.ui-dialog-tile').show();
                    }

                    df_loadOutScripts();
                });
            },
            close: function(event, ui) {
                if(jQuery('.ui-dialog').length <= 1){
                    jQuery('.ui-dialog-tile').hide();
                }
                jQuery("#ui-jsDialog-"+id+"-"+eid+"").remove();
            }
        });
    }

    function dr_pushResult(eid, str){

        jQuery('#reportPanel_'+eid).css('position', 'relative').prepend('<div class="ui-overlay" id="reportpanel_block_'+eid+'"><div class="ui-widget-overlay ui-corner-all"></div><div style="position:absolute; padding:6px; left:0; top:0; z-index:9999999;"><span class="ui-icon ui-icon-arrowrefresh-1-w" unselectable="on" style=" float:left;"></span>Loading Data</div></div>');
        //jQuery('#'+eid+'_wrapper').html('<div class="loading">loading</div>');
        ajaxCall('dr_callInterface',eid, str, function(h){
            jQuery('#report_tools_'+eid).remove();
            jQuery('#reportPanel_'+eid).replaceWith(h);
            df_loadOutScripts();

        });
    }

    function dr_BuildUpDateForm(eid, rid, ajaxSubmit, addquery){

		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!

        id = rid;//Math.floor(Math.random()*9999999);

	if(jQuery("#ui-jsDialog-"+id+"-"+eid+"").length == 1){
            jQuery("#ui-jsDialog-"+id+"-"+eid+"").remove();
	}
	jQuery('body').prepend('<div id="ui-jsDialog-'+id+'-'+eid+'" title="Loading"><p>Loading Entry</p></div>');
	jQuery("#ui-jsDialog-"+id+"-"+eid+"").dialog({
            autoResize: true,
            modal: true,
            buttons: {
                'Cancel': function() {jQuery(this).dialog("close"); }
            },
            dragStart: function(event, ui) {
                jQuery(".formError").remove();
            },
            open: function(event, ui) {
                ajaxCall('dr_BuildUpDateForm',eid, rid,addquery, function(c){
                    jQuery("#ui-jsDialog-"+id+"-"+eid+"").dialog('option', 'title', c.title);
                    jQuery("#ui-jsDialog-"+id+"-"+eid+"").dialog('option', 'buttons', {
                        'Cancel': function() {
                            jQuery(this).dialog("close");
                        },
                        'Save': function() {
                            //dr_BuildUpDateForm(eid, id); jQuery(this).dialog('close');
                                if(ajaxSubmit == true){
                                jQuery("#data_form_"+eid+"").bind('submit', function(){
                                formData = jQuery("#data_form_"+eid+"").serialize();
                                jQuery("#ui-jsDialog-"+eid+"").html('Sending...');
                                jQuery("#ui-jsDialog-"+eid+"").dialog('option', 'buttons', {});
                                ajaxCall('df_processAjaxForm',formData, addquery, function(p){
                                    jQuery("#ui-jsDialog-"+id+"-"+eid+"").remove();

                                    //load callback
                                    if (typeof callback == 'function') { // make sure the callback is a function
                                        callback.call(this, eid, ajaxSubmit, addquery, p); // brings the scope to the callback
                                    }
                                    dr_goToPage(eid, false, false, addquery);
                                    df_loadOutScripts();
                                });

                                return false;
                                });
                                jQuery("#data_form_"+eid+"").submit();
                            }else{
                                jQuery("#data_form_"+eid+"").submit();
                            }

                        }
                    });
                    jQuery("#ui-jsDialog-"+id+"-"+eid+"").html(c.html);

                    jQuery("#ui-jsDialog-"+id+"-"+eid+"").dialog('option', 'width', parseFloat(c.width));
                    jQuery("#ui-jsDialog-"+id+"-"+eid+"").dialog('option', 'position', 'center');
                    df_loadOutScripts();
                });
            },
            close: function(event, ui) {
                jQuery(".formError").remove();
                jQuery("#ui-jsDialog-"+id+"-"+eid+"").remove();
            }
        });
    }

    function dr_fetchPageElements(el){
	jQuery('#MatchingElements').html('Loading Elements');
	ajaxCall('dr_loadPageElements',el, function(x){
            jQuery('#MatchingElements').html(x);
	});
    }

    function dr_addTotalsField(){
	main = jQuery('#_main_table').val();
	if(main == ''){
            jQuery('#totalsListStatus').html('Select a table first');
            return;
	}
	ajaxCall('dr_addTotalsField',main, function(x){
            jQuery('#totalsListStatus').html('');
            jQuery('#totalsList').append(x);
	});
    }

    function dr_exportResults(q, r){
	ajaxCall('dr_db2csv',q, r, function(f){
            eval(f);
	});
    }


    function dr_deleteEntries(EID){
	var items = jQuery('.itemRow_'+EID+'.highlight');
	if(items.length > 0){
            if(confirm("Are you sure you want to delete the selected items?")){
                var itemlist=new Array();
                for(i=0;i<items.length;i++){
                    itemlist[i] = jQuery(items[i]).attr('ref');
                }
                jQuery('#reportPanel_'+EID).css('position', 'relative').append('<div class="ui-overlay" id="reportpanel_block_'+EID+'"><div class="ui-widget-overlay ui-corner-all"></div><div style="position:absolute; padding:6px; left:0; top:0;" id="reportpanel_block_message_'+EID+'"><span class="ui-icon ui-icon-notice" unselectable="on" style=" float:left;">close</span>Deleting Items</div></div>')

                ajaxCall('df_deleteEntries',EID, itemlist.join('|||'), function(x){
                    jQuery('#reportpanel_block_message_'+EID).html('<span class="ui-icon ui-icon-check" unselectable="on" style=" float:left;">close</span>'+x+'</div></div>');
                    setTimeout('dr_goToPage(\''+EID+'\', jQuery(\'#pageJump_'+EID+'\').val())', 2000);
                    //df_dialog(x);
                });
            }
	}
    }

    function dr_deleteItem(EID, ID, addQuery){
        if(confirm("Are you sure you want to delete this entry?")){
            ajaxCall('df_deleteEntries',EID, ID, function(x){
                jQuery('#reportpanel_block_message_'+EID).html('<span class="ui-icon ui-icon-check" unselectable="on" style=" float:left;">close</span>'+x+'</div></div>');
                dr_goToPage(EID, false, false, addQuery);
                //df_dialog(x);
            });
        }
    }

    // Tile Dialogs

    function dialog_tile(){
	top = 20;
	left = 20;
	maxWidth = jQuery(window).width()-20;
	jQuery('.ui-dialog').each(function(){
            if(left >= maxWidth){
                top = top+jQuery(this).height()+10;
                left = 20;
            }
            jQuery(this).css('left', left);
            jQuery(this).css('top', top);

            left = left+jQuery(this).width()+10;
	})


    }



    function toggle(foo) {
	jQuery("#"+foo).animate({"height": "toggle"}, {"duration": 200});
    }

    function df_submitStart(notice){
	jQuery(body).append('<div class="ui-overlay" id="reportpanel_block"><div class="ui-widget-overlay ui-corner-all">Loading Data</div></div>')
    }

    function linked_reloadField(eid, ajaxSubmit, addquery, returnVal){

        ajaxCall('df_reloadFormField', eid, addquery, returnVal.Value, function(p){
           jQuery('#'+p.element).html(p.html);
        });
    }
    