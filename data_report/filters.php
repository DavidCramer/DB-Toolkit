<?php
if (!empty($Config['_Show_Filters'])) {
    $Filters = false;
    $FilterVisiable = 'none';
    if (empty($Config['_toggle_Filters'])) {
        $FilterVisiable = 'block';
    }
    if (!empty($_SESSION['reportFilters'][$Media['ID']])) {
        if (count($_SESSION['reportFilters'][$Media['ID']]) > 1) {
            $Filters = df_cleanArray($_SESSION['reportFilters'][$Media['ID']]);
            $FilterVisiable = 'block';
        }
    }


    $customClass= '';
    if(!empty($Config['_filterbarClass'])){
        $customClass= $Config['_filterbarClass'];
    }
    $customClassButtonBar= '';
    if(!empty($Config['_filterbuttonbarClass'])){
        $customClassButtonBar= $Config['_filterbuttonbarClass'];
    }

    //if(empty($_SESSION['lockedFilters'][$Media['ID']]) || !empty($_SESSION['UserLogged'])){
?>
    <div class="filterpanels" id="filterPanel_<?php echo $Media['ID']; ?>" style="visibility:visible; display:<?php echo $FilterVisiable; ?>;">

        <form id="setFilters_<?php echo $Media['ID']; ?>" name="setFilters" method="post" action="" style="margin:0;">
            <input type="hidden" id="reportFilters_<?php echo $Media['ID']; ?>" value="<?php echo $Media['ID']; ?>" name="reportFilter[<?php echo $Media['ID']; ?>][EID]" />
            <div class="report_filters_panel <?php echo $customClass; ?>">
<?php
    echo dr_BuildReportFilters($Config, $Media['ID'], $Filters);
?>
                <div style="clear:both"></div>
            </div>
            <div class="list_row3 <?php echo $customClassButtonBar; ?>" style="clear:both;">
            <?php
            if(empty($Config['_ajax_Filters'])){
                echo dr_toolbarButton('Apply Filters', 'jQuery(\'#setFilters_'.$Media['ID'].'\').submit();', 'applyfilter');
            }else{
                echo dr_toolbarButton('Apply Filters', 'dr_applyFilters(\''.$Media['ID'].'\');', 'applyfilter');
            }
                echo dr_toolbarSeperator();
            ?>

<?php
////if(!empty($_SESSION['reportFilters'][$Media['ID']])){
?>
            <div class="btnseparator"></div><input type="hidden" name="reportFilter[ClearFilters]" id="clearFilters_<?php echo $Media['ID']; ?>" value="" />
            <?php
            if(empty($Config['_ajax_Filters'])){
                echo dr_toolbarButton('Clear Filters', 'jQuery(\'#clearFilters_'.$Media['ID'].'\').val(1); jQuery(\'#setFilters_'.$Media['ID'].'\').submit();', 'clearfilter');
            }else{
                echo dr_toolbarButton('Clear Filters', 'dr_applyFilters(\''.$Media['ID'].'\', true);', 'clearfilter');
            }
                echo dr_toolbarSeperator();

//}
            if (is_admin ()) {
                if (empty($Config['_Hide_FilterLock'])) {
                    if (empty($_SESSION['lockedFilters'][$Media['ID']])) {
?>
                        <input type="hidden" name="reportFilter[reportFilterLock]" id="lockFilters_<?php echo $Media['ID']; ?>" value="" />
                        <?php
                            echo dr_toolbarButton('Lock Filters', 'jQuery(\'#lockFilters_'.$Media['ID'].'\').val(\''.$Media['ID'].'\'); jQuery(\'#setFilters_'.$Media['ID'].'\').submit();', 'lockfilterfilter');
                            echo dr_toolbarSeperator();
                    }
                    if (!empty($_SESSION['lockedFilters'][$Media['ID']])) {
                        ?>
                        <input type="hidden" name="reportFilter[reportFilterUnlock]" id="unlockFilters_<?php echo $Media['ID']; ?>" value="" />
                        <?php
                            echo dr_toolbarButton('Unlock Filters', 'jQuery(\'#unlockFilters_'.$Media['ID'].'\').val(\''.$Media['ID'].'\'); jQuery(\'#setFilters_'.$Media['ID'].'\').submit();', 'unlockfilterfilter');
                            echo dr_toolbarSeperator();
                    }
                }
?>
<?php
            }
?>
<?php
    if (!empty($Config['_toggle_Filters'])) {

    echo dr_toolbarButton('Close Filters', 'jQuery(\'#filterPanel_'.$Media['ID'].'\').toggle(); return false; ', 'closefilter');
    echo dr_toolbarSeperator();

    }
?>            <?php
            /* <!-- <input type="submit" name="reportFilter[Submit]" id="button add-new-h2" value="Apply Filters" class="buttons" />&nbsp;<input type="button add-new-h2" name="button add-new-h2" id="button add-new-h2" value="Close Panel" class="buttons" onclick="toggle('filterPanel_<?php echo $Media['ID']; ?>'); return false; " />&nbsp;<input type="submit" name="reportFilter[ClearFilters]" id="button add-new-h2" value="Clear Filters" class="buttons" onclick="return confirm('Are you sure you want to clear the filters?');" /></div> --> */
            ?>
            <div style="clear:both;"></div>
        </div>
    </form>

</div>
            <?php
            //}
        }
            ?>