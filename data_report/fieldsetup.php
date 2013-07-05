<h2>Fields Setup</h2>
<div id="mainTableSelector">
<?php
    if(empty($Element['Content']['_main_table']))
        $Element['Content']['_main_table'] = '';
    echo df_listTables('_main_table', 'dr_fetchPrimSetup', $Element['Content']['_main_table']);
//EndInfoBox();
?>
</div>
<div id="col-container" >
    <?php
    echo '<h2>Define Fieldtypes</h2>';
//dump($Element);
    $addFieldButton = 'none';
    if(!empty($Element['Content']['_main_table'])){
        $addFieldButton = '';
    }
    ?>
        <div style="width:565px;">        
            <div class="list_row3">
                <?php if ($_GET['page'] != 'Add_New') { ?><input type="button" class="button" value="Add Virtual Field" onclick="dr_addLinking('<?php echo $Element['Content']['_main_table']; ?>')" /> <?php } ?>
                <input id="addFieldButton" type="button" style="display:<?php echo $addFieldButton; ?>;" class="button" value="Add Field" onclick="dr_addField()" />
            </div>
        <div class="columnSorter" id="drToolBox">
            <?php
                //echo df_tableReportSetup($Element['Content']['_main_table'], $Element, false, 'C');
            ?>
        </div>


    </div>
        <div style="">
            <div id="referenceSetup"></div>
            <div style="overflow:auto;">
                <table width="" border="0" cellspacing="2" cellpadding="2">
                    <tr>
                        <td valign="top" class="columnSorter" id="FieldList_Main"><?php
        echo df_tableReportSetup($Element['Content']['_main_table'], 'false', $Element);
    ?></td>
                </tr>
            </table>
        </div>
    </div>

</div>
<?php
        echo '<h2>Passback Field</h2>';
?>
        <div style="padding:3px;">
            <input type="button" name="button" id="button" class="button" value="Add Passback Field" onclick="dr_addPassbackField();"/></div>
        <div id="PassBack_FieldSelect">
    <?php
    if(empty($Element['Content']['_ReturnFields']))
        $Element['Content']['_ReturnFields'] = '';
        
        echo dr_loadPassbackFields($Element['Content']['_main_table'], $Element['Content']['_ReturnFields'], $Element['Content']);
    ?></div>
<?php
        echo '<h2>Sort Field</h2>';
?>
        <div id="sortFieldSelect">
<?php
        if ($_GET['page'] != 'Add_New') {
            if(empty($Element['Content']['_SortField']))
                $Element['Content']['_SortField'] = '';

            if(empty($Element['Content']['_SortDirection']))
                $Element['Content']['_SortDirection'] = '';

            echo df_loadSortFields($Element['Content']['_main_table'], $Element['Content']['_SortField'], $Element['Content']['_SortDirection']);
        }
?>
</div>