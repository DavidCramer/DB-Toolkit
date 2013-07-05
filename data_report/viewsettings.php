<?php
                    echo '<h2>General Settings</h2>';
                    $Sel = 'checked="checked"';
                    if(!empty($Element['Content']['_ViewMode'])) {
                        if($Element['Content']['_ViewMode']!='list'){
                            $Sel = '';
                        }
                    }
                    echo dais_customfield('radio', 'List Mode', '_ViewMode', '_ViewMode_List', 'list_row2' , 'list' , $Sel ,'Sets the interface to be a list of entries.');
                    $Sel = '';
                    if(!empty($Element['Content']['_ViewMode'])) {
                        if($Element['Content']['_ViewMode']=='filter'){
                            $Sel = 'checked="checked"';
                        }
                    }
                    //echo dais_customfield('radio', 'Filter Mode', '_ViewMode', '_ViewMode_Filter', 'list_row2' , 'filter' , $Sel, 'Sets the interface into filter mode. Be sure to set the redirects to the interface you want to the filters to effect.');
                    $Sel = '';
                    if(!empty($Element['Content']['_ViewMode'])) {
                        if($Element['Content']['_ViewMode']=='view'){
                            $Sel = 'checked="checked"';
                        }
                    }
                    echo dais_customfield('radio', 'View Mode', '_ViewMode', '_ViewMode_View', 'list_row2' , 'view' , $Sel, 'Sets the interface to be a single entry view. This requires a passback value to be set, and the relating field to be set as a "Selected Item Filter"');
                    $Sel = '';
                    if(!empty($Element['Content']['_ViewMode'])) {
                        if($Element['Content']['_ViewMode']=='form'){
                            $Sel = 'checked="checked"';
                        }
                    }
                    echo dais_customfield('radio', 'Form Mode', '_ViewMode', '_ViewMode_Form', 'list_row1' , 'form' , $Sel, 'Sets the interface into a form for capture and edit.');
                    $Sel = '';
                    if(!empty($Element['Content']['_ViewMode'])) {
                        if($Element['Content']['_ViewMode']=='search'){
                            $Sel = 'checked="checked"';
                        }
                    }
                    echo dais_customfield('radio', 'Search Mode', '_ViewMode', '_ViewMode_Search', 'list_row2' , 'search' , $Sel, 'In Search mode, the interface will only show results once a filter has been set.');
                    $Sel = '';
                    if(!empty($Element['Content']['_ViewMode'])) {
                        if($Element['Content']['_ViewMode']=='API'){
                            $Sel = 'checked="checked"';
                        }
                    }
                    echo dais_customfield('radio', 'API Mode', '_ViewMode', '_ViewMode_API', 'list_row2' , 'API' , $Sel, 'In API mode, interface is only interactable via the API.');
                    $Sel = '';
                    if(!empty($Element['Content']['_HideFrame'])) {
                        $Sel = 'checked="checked"';
                    }

                    echo dais_customfield('checkbox', 'Hide Frame', '_HideFrame', '_HideFrame', 'list_row1' , 1 , $Sel, 'Hide the frame surrounding the interface.');
                    echo dais_customfield('text', 'New Item Title', '_New_Item_Title', '_New_Item_Title', 'list_row1' , $Element['Content']['_New_Item_Title'], 'The lable on the add entry button in the toolbar.');
                    $Sel = '';
                    if(!empty($Element['Content']['_New_Item_Hide'])) {
                        $Sel = 'checked="checked"';
                    }
                    echo dais_customfield('checkbox', 'Hide new item button', '_New_Item_Hide', '_New_Item_Hide', 'list_row2' , 1, $Sel, 'Dont show the add item button. This disables entry capture for the interface.');
                    echo dais_customfield('text', 'Items Per Page', '_Items_Per_Page', '_Items_Per_Page', 'list_row1' , $Element['Content']['_Items_Per_Page'], '','Items returned per report page. 0 sets the interface to show all entries.');
                    echo dais_customfield('text', 'Auto Polling Rate', '_autoPolling', '_autoPolling', 'list_row2' , $Element['Content']['_autoPolling'], '','Set the interface to refresh results at the set interval. value is in seconds. No value disables this feature.');
                    
                    echo '<h2>Tool Bar Settings</h2>';
                    $Sel = '';
                    if(!empty($Element['Content']['_Hide_Toolbar'])) {
                        $Sel = 'checked="checked"';
                    }
                    echo dais_customfield('checkbox', 'Show Tool Bar', '_Hide_Toolbar', '_Hide_Toolbar', 'list_row1' , 1 , $Sel, 'Render the toolbar. The toolbar holds the controls for the interface like Add Entry, export and so on.');

                    $Sel = '';
                    if(!empty($Element['Content']['_Show_Filters'])) {
                        $Sel = 'checked="checked"';
                    }
                    echo dais_customfield('checkbox', 'Show Filters', '_Show_Filters', '_Show_Filters', 'list_row2' , 1 , $Sel ,'Render the filters panel for the interface.');

                    $Sel = '';
                    if(!empty($Element['Content']['_ajax_Filters'])) {
                        $Sel = 'checked="checked"';
                    }
                    echo dais_customfield('checkbox', 'Ajax Filters', '_ajax_Filters', '_ajax_Filters', 'list_row2' , 1 , $Sel ,'Apply filters via ajax (no page reloads).');
                    $Sel = '';
                    if(!empty($Element['Content']['_Hide_FilterLock'])) {
                        $Sel = 'checked="checked"';
                    }
                    echo dais_customfield('checkbox', 'Hide Filter Lock', '_Hide_FilterLock', '_Hide_FilterLock', 'list_row2' , 1 , $Sel, 'Render the Filter Lock button. the filterlock allows you to preset the filters for the interface.');
                    $Sel = '';
                    if(!empty($Element['Content']['_toggle_Filters'])) {
                        $Sel = 'checked="checked"';
                    }
                    echo dais_customfield('checkbox', 'Autohide Filters', '_toggle_Filters', '_toggle_Filters', 'list_row1' , 1 , $Sel, 'Sets the filterpanel to hidden until the show filter button is clicked.');
                    $Sel = '';
                    if(!empty($Element['Content']['_Show_KeywordFilters'])) {
                        $Sel = 'checked="checked"';
                    }
                    echo dais_customfield('checkbox', 'Show Keyword Filter', '_Show_KeywordFilters', '_Show_Filters', 'list_row2' , 1 , $Sel, 'Render the keyword global filter box');
                    echo dais_customfield('text', 'Keyword Search Title', '_Keyword_Title', '_Keyword_Title', 'list_row1' , $Element['Content']['_Keyword_Title'] , '', 'Lable for the Keyword filter box.');
                    $Sel = '';
                    if(!empty($Element['Content']['_showReload'])) {
                        $Sel = 'checked="checked"';
                    }
                    echo dais_customfield('checkbox', 'Show Reloader', '_showReload', '_showReload', 'list_row1' , 1 , $Sel, 'Render the Reload Button on the toolbar.');
                    $Sel = '';
                    if(!empty($Element['Content']['_Show_Export'])) {
                        $Sel = 'checked="checked"';
                    }
                    echo dais_customfield('checkbox', 'Show Exporter', '_Show_Export', '_Show_Export', 'list_row2' , 1, $Sel, 'Render the Export button on the toolbar.');
                    $Sel = '';
                    if(!empty($Element['Content']['_Show_Import'])) {
                        $Sel = 'checked="checked"';
                    }
                    echo dais_customfield('checkbox', 'Show Importer', '_Show_Import', '_Show_Import', 'list_row1' , 1, $Sel, 'Render the import button for CSV importing.');
                    $Sel = '';
                    if(!empty($Element['Content']['_Show_Plugins'])) {
                        $Sel = 'checked="checked"';
                    }
                    echo dais_customfield('checkbox', 'Show Plugins', '_Show_Plugins', '_Show_Plugins', 'list_row1' , 1 , $Sel, 'Show additional toolbar button plugins. (This will be changing soon :)');

                    echo '<div style="padding:5px 0;" class="list_row2">Export Orientation&nbsp;&nbsp;';
                    echo '<select name="Data[Content][_orientation]" >';
                    $Sel = '';
                    if($Element['Content']['_orientation'] == 'P') {
                        $Sel = 'selected="selected"';
                    }
                    echo '<option value="P" '.$Sel.'>Portrait</option>';
                    $Sel = '';
                    if($Element['Content']['_orientation'] == 'L') {
                        $Sel = 'selected="selected"';
                    }
                    echo '<option value="L" '.$Sel.'>Landscape</option>';
                    echo '</select>';
                    echo '</div>';

                    $Sel = '';
                    if(!empty($Element['Content']['_Show_Select'])) {
                        $Sel = 'checked="checked"';
                    }
                    echo dais_customfield('checkbox', 'Show Select', '_Show_Select', '_Show_Select', 'list_row2' , 1 , $Sel, 'Render the "select all" toolbar button.');
                    $Sel = '';
                    if(!empty($Element['Content']['_Show_Delete'])) {
                        $Sel = 'checked="checked"';
                    }
                    echo dais_customfield('checkbox', 'Show Delete', '_Show_Delete', '_Show_Delete', 'list_row1' , 1, $Sel, 'Enable entry deleting and render the "delete selected items" button.');
                    
                    echo '<h2>List Settings</h2>';
                    $Sel = '';
                    if(!empty($Element['Content']['_Show_Edit'])) {
                        $Sel = 'checked="checked"';
                    }
                    echo dais_customfield('checkbox', 'Show Edit', '_Show_Edit', '_Show_Edit', 'list_row2' , 1, $Sel, 'Enable and render the edit entry icon.');
                    $Sel = '';
                    if(!empty($Element['Content']['_Show_View'])) {
                        $Sel = 'checked="checked"';
                    }
                    echo dais_customfield('checkbox', 'Show View', '_Show_View', '_Show_View', 'list_row1' , 1 , $Sel, 'Render the view entry icon.');
                    $Sel = '';
                    if(!empty($Element['Content']['_Show_Delete_action'])) {
                        $Sel = 'checked="checked"';
                    }
                    echo dais_customfield('checkbox', 'Show Delete', '_Show_Delete_action', '_Show_Delete_action', 'list_row1' , 1, $Sel, 'Render the delete entry icon.');
                    $Sel = '';
                    if(!empty($Element['Content']['_Show_popup'])) {
                        $Sel = 'checked="checked"';
                    }
                    echo dais_customfield('checkbox', 'Inline Actions', '_Show_popup', '_Show_popup', 'list_row2' , 1 , $Sel, 'Set View, Edit, Delete icons to an inline Wordpress style.');
                    $Sel = '';
                    if(!empty($Element['Content']['_Show_Footer'])) {
                        $Sel = 'checked="checked"';
                    }
                    echo dais_customfield('checkbox', 'Show Footer Bar', '_Show_Footer', '_Show_Footer', 'list_row1' , 1, $Sel, 'Render the footer which holds the page and items count.');
                    
                    echo '<h2>Style Classes</h2>';
                    echo dais_customfield('text', 'Submit Button Class', '_SubmitButtonClass', '_SubmitButtonClass', 'list_row1' , $Element['Content']['_SubmitButtonClass'], '', 'Set the insert entry button style class.');
                    echo dais_customfield('text', 'Update Button Class', '_UpdateButtonClass', '_UpdateButtonClass', 'list_row2' , $Element['Content']['_UpdateButtonClass'], '', 'Set the update entry button style class. ');
                    echo dais_customfield('text', 'List Table Class', '_ListTableClass', '_ListTableClass', 'list_row2' , $Element['Content']['_ListTableClass'], '', 'Set the class for the default list table. ');
                    echo dais_customfield('text', 'Form Class', '_FormClass', '_FormClass', 'list_row2' , $Element['Content']['_FormClass'], '', 'Set the class for the form. ');
                    echo dais_customfield('text', 'Toolbar Class', '_toolbarClass', '_toolbarClass', 'list_row2' , $Element['Content']['_toolbarClass'], '', 'Set the class for the toolbar. ');
                    echo dais_customfield('text', 'Filter Bar Class', '_filterbarClass', '_filterbarClass', 'list_row2' , $Element['Content']['_filterbarClass'], '', 'Set the class for the filters bar. ');
                    echo dais_customfield('text', 'Filter Button Bar Class', '_filterbuttonbarClass', '_filterbuttonbarClass', 'list_row2' , $Element['Content']['_filterbuttonbarClass'], '', 'Set the class for the filter buttons bar. ');


                    echo '<h2>Notification & Buttons</h2>';
                    echo dais_customfield('text', 'Insert Success Text', '_InsertSuccess', '_InsertSuccess', 'list_row1' , $Element['Content']['_InsertSuccess'], '', 'Set the successful insert entry dialog message.');
                    echo dais_customfield('text', 'Update Success Text', '_UpdateSuccess', '_UpdateSuccess', 'list_row2' , $Element['Content']['_UpdateSuccess'], '', 'Set the successful update entry dialog message.');
                    echo dais_customfield('text', 'Insert Fail Text', '_InsertFail', '_InsertFail', 'list_row1' , $Element['Content']['_InsertFail'], '', 'Set the failed entry insert dialog message.');
                    echo dais_customfield('text', 'Update Fail Text', '_UpdateFail', '_UpdateFail', 'list_row2' , $Element['Content']['_UpdateFail'], '', 'Set the failed entry update dialog message.');
                    echo dais_customfield('text', 'Submit Button Text', '_SubmitButtonText', '_SubmitButtonText', 'list_row1' , $Element['Content']['_SubmitButtonText'], '', 'Set the insert entry button label.');                    
                    echo dais_customfield('text', 'Update Button Text', '_UpdateButtonText', '_UpdateButtonText', 'list_row2' , $Element['Content']['_UpdateButtonText'], '', 'Set the update entry button label. ');
                    echo dais_customfield('text', 'Edit Form Title', '_EditFormText', '_EditFormText', 'list_row1' , $Element['Content']['_EditFormText'], '', 'Set the insert entry dialog title.');
                    echo dais_customfield('text', 'View Form Title', '_ViewFormText', '_ViewFormText', 'list_row1' , $Element['Content']['_ViewFormText'], '', 'Set the update entry dialog title.');
                    echo dais_customfield('text', 'No results text', '_NoResultsText', '_NoResultsText', 'list_row1' , $Element['Content']['_NoResultsText'], '', 'Set the "no items found" message.');

                    $Sel = '';
                    if(!empty($Element['Content']['_NotificationsOff'])) {
                        $Sel = 'checked="checked"';
                    }
                    echo dais_customfield('checkbox', 'Disable Notifications', '_NotificationsOff', '_NotificationsOff', 'list_row2' , 1, $Sel, 'Disables dialog notifications.');
                    $Sel = '';
                    if(!empty($Element['Content']['_inlineNotifications'])) {
                        $Sel = 'checked="checked"';
                    }
                    echo dais_customfield('checkbox', 'Inline Notifications', '_inlineNotifications', '_inlineNotifications', 'list_row2' , 1, $Sel, 'Uses inline Notifications over dialogs.');
                    $Sel = '';
                    if(!empty($Element['Content']['_ShowReset'])) {
                        $Sel = 'checked="checked"';
                    }
                    echo dais_customfield('checkbox', 'Show Reset Button', '_ShowReset', '_ShowReset', 'list_row1' , 1, $Sel, 'Render the reset form button.');

                    $Sel = '';
                    if(!empty($Element['Content']['_SubmitAlignment'])) {
                        switch($Element['Content']['_SubmitAlignment']) {
                            case 'left':
                                $Sel = 'left';
                                break;
                            case 'center':
                                $Sel = 'center';
                                break;
                            case 'right':
                                $Sel = 'right';
                                break;

                        }

                    }
                    echo '<div style="padding:5px 0;" class="list_row1">Button Alignment&nbsp;&nbsp;';
                    echo '<select name="Data[Content][_SubmitAlignment]" >';
                    echo '<option value="left" ';
                    if($Sel == 'left') {
                        echo 'selected="selected"';
                    };
                    echo '>Left</option>';
                    echo '<option value="center" ';
                    if($Sel == 'center') {
                        echo 'selected="selected"';
                    };
                    echo '>Center</option>';
                    echo '<option value="right" ';
                    if($Sel == 'right') {
                        echo 'selected="selected"';
                    };
                    echo '>Right</option>';
                    echo '</select>';
                    echo '</div>';
                    /*
                    echo '<h2>Auditing</h2>';
                    $Sel = '';
                    if(!empty($Element['Content']['_EnableAudit'])) {
                        $Sel = 'checked="checked"';
                    }
                    echo dais_customfield('checkbox', 'Enable Auditing', '_EnableAudit', '_EnableAudit', 'list_row2' , 1, $Sel, 'Keep a copy of all edited, inserted and deleted entries. A copy table will be created with the suffix _audit_<em>tablename</em>');
                    */
                    

                    ?>
                