<?php
// Control Buttons
    //_useToolbarTemplate _layoutTemplate
    if(!empty($_SESSION['DF_Notification'])){
        
        ob_start();
        foreach($_SESSION['DF_Notification'] as $Key=>$Notice){
        $uid = uniqid();
        ?>
            <div class="alert alert-<?php echo $_SESSION['DF_NotificationTypes'][$Key]; ?>" id="<?php echo $uid; ?>">
            <a class="close" onClick="jQuery('#<?php echo $uid; ?>').fadeOut('slow');">×</a>
            <?php echo $Notice; ?>
            </div>
        <?php
        }
        unset($_SESSION['DF_Notification']);
        echo ob_get_clean();
    }

if (!empty($Config['_Hide_Toolbar'])) {

    if(!empty($Config['_useToolbarTemplate'])){
    //    echo
        
        $Template = $Config['_layoutTemplate']['_Toolbar'];
        // Replace Codes with Buttons

        
        //For Add Item - Only if showed
        if (empty($Config['_New_Item_Hide'])) {
            $ajaxSubmit = 'true';
            if (is_admin ()) {
                if (empty($Config['_ajaxForms'])) {
                    $ajaxSubmit = 'false';
                }
            } else {
                if (empty($Config['_ajaxForms'])) {
                    $ajaxSubmit = 'false';
                }
            }
            if(!empty($_GET)){
                $hasQuery = build_query($_GET);
            }else{
                $hasQuery = false;
            }
            $Template = str_replace('{{_button_addItem}}', dr_toolbarButton($Config['_New_Item_Title'], 'df_buildQuickCaptureForm(\'' . $Media['ID'] . '\', ' . $ajaxSubmit . ',\''.$hasQuery.'\');return false;','add'), $Template);
        }else{
            $Template = str_replace('{{_button_addItem}}', '', $Template);
        }

        // replace import button
        if (!empty($Config['_Show_Import'])) {
            //{{_button_import}}
            $Template = str_replace('{{_button_import}}', dr_toolbarButton('Import', 'df_buildImportForm(\'' . $Media['ID'] . '\');return false;', 'import'), $Template);
        }else{
            $Template = str_replace('{{_button_import}}', '', $Template);
        }

        //replace {{_button_toggleFilters}}
        if (!empty($Config['_Show_Filters'])) {
            $Template = str_replace('{{_button_toggleFilters}}', dr_toolbarButton('Filters', 'jQuery(\'#filterPanel_' . $Media['ID'] . '\').toggle();', 'filterbutton'), $Template);
        }else{
            $Template = str_replace('{{_button_toggleFilters}}', '', $Template);
        }

        // replace {{_button_reload}}
        if (!empty($Config['_showReload'])) {
                $Template = str_replace('{{_button_reload}}', dr_toolbarButton('Reload', 'dr_goToPage(\'' . $Media['ID'] . '\', false, false);', 'reload'), $Template);
        }else{

        }

        // replace {{_button_selectAll}}
        if (!empty($Config['_Show_Delete'])) {
            if (!empty($Config['_Show_Select'])) {
                $Template = str_replace('{{_button_selectAll}}', dr_toolbarButton('Select All', 'dr_selectAll(\'' . $Media['ID'] . '\');', 'selectall'), $Template);
                $Template = str_replace('{{_button_unselect}}', dr_toolbarButton('Unselect All', 'dr_deSelectAll(\'' . $Media['ID'] . '\');', 'unselectall'), $Template);
            }else{
                $Template = str_replace('{{_button_selectAll}}', '', $Template);
                $Template = str_replace('{{_button_unselect}}', '', $Template);
            }

            $Template = str_replace('{{_button_deleteSelected}}', dr_toolbarButton('Delete Selected', 'dr_deleteEntries(\'' . $Media['ID'] . '\');"', 'delete'), $Template);
        }else{
            $Template = str_replace('{{_button_selectAll}}', '', $Template);
            $Template = str_replace('{{_button_unselect}}', '', $Template);
            $Template = str_replace('{{_button_deleteSelected}}', '', $Template);
        }


        //replace {{_button_export_pdf}} and {{_button_export_csv}}
        if (!empty($Config['_Show_Export'])) {
            if(empty($Global))
                $Global = false;

            $Template = str_replace('{{_button_export_pdf}}', dr_toolbarButton('Export PDF', 'dr_exportReport(\'?format_' . $Media['ID'] . '=pdf\', \'' . $Media['ID'] . '\',\'' . $Global . '\');', 'export'), $Template);
            $Template = str_replace('{{_button_export_csv}}', dr_toolbarButton('Export CSV', false, 'export', '?format_' . $Media['ID'] . '=csv'), $Template);
        }else{
            $Template = str_replace('{{_button_export_pdf}}', '', $Template);
            $Template = str_replace('{{_button_export_csv}}', '', $Template);
        }

        


        echo $Template;
    }else{


        $customClass= '';
        if(!empty($Config['_toolbarClass'])){
            $customClass= $Config['_toolbarClass'];
        }

        echo '<div id="report_tools_' . $Media['ID'] . '" class="report_tools list_row3 '.$customClass.'">';

        if (empty($Config['_New_Item_Hide'])) {
            $ajaxSubmit = 'true';
            if (is_admin ()) {
                if (empty($Config['_ajaxForms'])) {
                    $ajaxSubmit = 'false';
                }
            } else {
                if (empty($Config['_ajaxForms'])) {
                    $ajaxSubmit = 'false';
                }
            }

            //vardump($Config['_ReturnFields']);
           echo dr_toolbarButton($Config['_New_Item_Title'], 'df_buildQuickCaptureForm(\'' . $Media['ID'] . '\', ' . $ajaxSubmit . ', \''.build_query($_GET).'\');return false;','add');
           echo dr_toolbarSeperator();
            //echo '<div class="fbutton"><div class="button add-new-h2" onclick=""><span class="add">' . $Config['_New_Item_Title'] . '</span></div></div>';
        }

        if (!empty($Config['_Show_Import'])) {
            echo dr_toolbarButton('Import', 'df_buildImportForm(\'' . $Media['ID'] . '\');return false;', 'import');
            echo dr_toolbarSeperator();
        }

        //if(empty($_SESSION['lockedFilters'][$Media['ID']]) || !empty($_SESSION['UserLogged'])){
        if (!empty($Config['_Show_Filters'])) {
            if (!empty($Config['_toggle_Filters'])) {
                echo dr_toolbarButton('Filters', 'jQuery(\'#filterPanel_' . $Media['ID'] . '\').toggle();', 'filterbutton');
                echo dr_toolbarSeperator();
            }
        }
        //}
        if (!empty($Config['_showReload'])) {
                echo dr_toolbarButton('Reload', 'dr_goToPage(\'' . $Media['ID'] . '\', false, false);', 'reload');
                echo dr_toolbarSeperator();
        }

        //dr_selectAll
        if (!empty($Config['_Show_Delete'])) {
            if (!empty($Config['_Show_Select'])) {
                echo dr_toolbarButton('Select All', 'dr_selectAll(\'' . $Media['ID'] . '\');', 'selectall');
                echo dr_toolbarSeperator();

                echo dr_toolbarButton('Unselect All', 'dr_deSelectAll(\'' . $Media['ID'] . '\');', 'unselectall');
                echo dr_toolbarSeperator();
            }

            echo dr_toolbarButton('Delete Selected', 'dr_deleteEntries(\'' . $Media['ID'] . '\');"', 'delete');
            echo dr_toolbarSeperator();
        }


        if (!empty($Config['_Show_Export'])) {
            if(empty($Global))
                $Global = false;

            echo dr_toolbarButton('Export PDF', 'dr_exportReport(\'?format_' . $Media['ID'] . '=pdf\', \'' . $Media['ID'] . '\',\'' . $Global . '\');', 'export');
            echo dr_toolbarSeperator();

            echo dr_toolbarButton('Export CSV', 'dr_exportReport(\'?format_' . $Media['ID'] . '=csv\', \'' . $Media['ID'] . '\',\'' . $Global . '\');', 'export');
            echo dr_toolbarSeperator();
        }


        //echo '<div class="btnseparator ui-dialog-tile" style="display:none;"></div>';
        //echo '<div class="fbutton ui-dialog-tile" style="display:none;"><div class="button add-new-h2"><span class="selectall"  onclick="dialog_tile();">Tile Dialogs</span></div></div>';

        if (!empty($Config['_Show_Plugins'])) {
            $ListButtons = loadFolderContents(WP_PLUGIN_DIR . '/db-toolkit/data_report/plugins');
            foreach ($ListButtons as $PlugButton) {
                foreach ($PlugButton as $Button) {
                    include(WP_PLUGIN_DIR . '/db-toolkit/data_report/plugins/' . $Button[1] . '/button.php');
                }
            }
        }
        echo '<div style="clear:both;"></div></div>';
    }
}
//echo $Buttons;
