<div class="report_filters_panel">
    <form id="setFilters_<?php echo $Media['ID']; ?>" name="setFilters" method="post" action="" style="margin:0;">
        <input type="hidden" id="reportFilters_<?php echo $Media['ID']; ?>" value="<?php echo $Media['ID']; ?>" name="reportFilter[<?php echo $Media['ID']; ?>][EID]" />
            <?php
            if(!empty($_SESSION['reportFilters'][$Media['ID']])) {
                if(count($_SESSION['reportFilters'][$Media['ID']]) > 1) {
                    $Filters = df_cleanArray($_SESSION['reportFilters'][$Media['ID']]);
                    $_SESSION['reportFilters'][$Media['ID']] = $Filters;
                    $FilterVisiable = 'block';
                }
            }
            
            echo dr_BuildReportFilters($Config, $Media['ID'], $Filters);
            ?>
        <div style="clear:both;"></div>
            <?php
            $ButtonAlign = 'center';
            if(!empty($Config['_SubmitAlignment'])) {
                $ButtonAlign = $Config['_SubmitAlignment'];
            }

            ?>
        <div style="padding:10px 2px 0 2px; text-align:<?php echo $ButtonAlign; ?>">
            <input type="submit" value="Search" class="filterSearchbutton" name="search_<?php echo $Config['_ViewMode']; ?>" />&nbsp;
                <?php
                if(!empty($_SESSION['reportFilters'][$Media['ID']])) {
                    //dump($_SESSION['reportFilters']);
             ?>
            <input type="submit" value="Clear Results" class="filterSearchbutton" onclick="jQuery('#clearFilters_<?php echo $Media['ID']; ?>').val(1); jQuery('#setFilters_<?php echo $Media['ID']; ?>').submit();" /><input type="hidden" name="reportFilter[ClearFilters]" id="clearFilters_<?php echo $Media['ID']; ?>" value="" />
                <?php
                }
            ?>
        </div>
    </form>
    <div style="clear:both"></div>
</div>
    <?php   

    
    

    if(!empty($_SESSION['reportFilters'][$Media['ID']])){
        echo dr_BuildReportGrid($Media['ID'], $gotTo, $_SESSION['report_'.$Media['ID']]['SortField'], $_SESSION['report_'.$Media['ID']]['SortDir']);
    }


    return;

    ?>