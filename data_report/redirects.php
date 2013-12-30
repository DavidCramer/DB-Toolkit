<h2>Redirects <span href="#redirectHelp" class="help redirect-help" >&nbsp;</span></h2>
<div class="description" id="redirectHelp" style="display: none;">
    <p>by selecting a redirect, you tell the interface to goto the selected item and pass back the passback fields as GET variables (in the URL). Redirects happen when you insert a new item, edit an existing entry, or view an item.</p>
    <p>There are two types of redirects; admin and public.</p>
    <p>An 'admin' redirect will redirect you to the interface or cluster selected</p>
    <p>A 'public' will redirect the user to the page selected.</p>
</div>
<div id="redirectTabs" class="dbtools_tabs">
    <ul class="content-box-tabs">
        <li><a href="#publicRedirect">Public</a></li>
        <li><a href="#adminRedirect">Interface</a></li>
    </ul>
    <div class="setupTab" id="publicRedirect">
        <?php
        InfoBox('Page');
        $Sel = '';
        if(empty($Element['Content']['_ItemViewPage'])) {
            $Sel = 'checked="checked"';
        }
        echo dais_customfield('radio', 'No Redirect', '_ItemViewPage', '_ItemViewPage', 'list_row1' , 0, $Sel);
        if(empty($Element['Content']['_ItemViewPage']))
            $Element['Content']['_ItemViewPage'] = '';
        $PageList = array();
        $PageList[] = $Element['Content']['_ItemViewPage'];
        echo dais_page_selector('s', $PageList, false, '_ItemViewPage');
        EndInfoBox();
        ?>
    </div>
    <div class="setupTab" id="adminRedirect">
        <?php
        InfoBox('Applications');

        $Sel = '';
        if(empty($Element['Content']['_ItemViewInterface'])) {
            $Sel = 'checked="checked"';
        }
        echo dais_customfield('radio', 'No Redirect', '_ItemViewInterface', '_ItemViewInterface', 'list_row1' , 0, $Sel);
        $Sel = '';
        if(!empty($Element['Content']['_targetInterface'])) {
            $Sel = 'checked="checked"';
        }
        echo dais_customfield('checkbox', 'Passback Targeting', '_targetInterface', '_targetInterface', 'list_row1' , 1, $Sel, 'Push passback fields to target Interface');
        $Sel = '';
        if(!empty($Element['Content']['_targetInterfaceFilter'])) {
            $Sel = 'checked="checked"';
        }
        echo dais_customfield('checkbox', 'Filter Targeting', '_targetInterfaceFilter', '_targetInterfaceFilter', 'list_row1' , 1, $Sel, 'Push filters to target Interface');


        global $wpdb;
        $Interfaces = $wpdb->get_results("SELECT option_name FROM $wpdb->options WHERE `option_name` LIKE 'dt_intfc%' ", ARRAY_A);
        $Clusters = $wpdb->get_results("SELECT option_name FROM $wpdb->options WHERE `option_name` LIKE 'dt_clstr%' ", ARRAY_A);
        $Interfaces = array_merge($Interfaces, $Clusters);
        if(!empty($Interfaces) || !empty($Clusters)) {
            // group by App
            $Apps = array();
            foreach($Interfaces as $Interface) {
                    $option = get_option($Interface['option_name']);
                    //if(empty($option['_ItemGroup'])){
                    //    $option['_ItemGroup'] = '__Ungrouped';
                    //}
                    $Apps[$option['_Application']][$option['Type']][] = $option;
            }
            ksort($Apps);



            foreach($Apps as $App=>$PrimInterfaces){
                $appID = uniqid('rowapp_');

            echo '<div class="admin_list_row3 postbox">';
                echo '<img align="absmiddle" style="float: right; padding: 5px;" onclick="jQuery(\'#'.$appID.'\').toggle();" src="'.WP_PLUGIN_URL.'/db-toolkit/images/cog.png">';
                echo '<h3 class="fieldTypeHandle">'.ucwords($App).'</h3>';

                // set visibility if redirect is in this app.

                $Show = 'none';


                        $Plugins = array();
                        $Clusters = array();
                        if(empty($PrimInterfaces['Plugin']))
                            $PrimInterfaces['Plugin'] = '';

                        if(is_array($PrimInterfaces['Plugin'])){
                            foreach($PrimInterfaces['Plugin'] as $PrimInterface) {
                                if(empty($PrimInterface['_ItemGroup'])){
                                    $PrimInterface['_ItemGroup'] = '__Ungrouped';
                                }
                                $Plugins[$PrimInterface['_ItemGroup']][] = $PrimInterface;
                                if(!empty($Element['Content']['_ItemViewInterface'])){
                                    if($Element['Content']['_ItemViewInterface'] == $PrimInterface['ID']){
                                        $Show = 'block';
                                    }
                                }
                            }
                        }
                        if(key_exists('Cluster', $PrimInterfaces) && is_array($PrimInterfaces['Cluster'])){
                            foreach($PrimInterfaces['Cluster'] as $PrimInterface) {
                                if(empty($PrimInterface['_ItemGroup'])){
                                    $PrimInterface['_ItemGroup'] = '__Ungrouped';
                                }
                                $Clusters   [$PrimInterface['_ItemGroup']][] = $PrimInterface;
                                if(!empty($Element['Content']['_ItemViewInterface'])){
                                    if($Element['Content']['_ItemViewInterface'] == $PrimInterface['ID']){
                                        $Show = 'block';
                                    }
                                }
                            }
                        }

                        echo '<div style="display: '.$Show.'; clear:both;" class="admin_config_panel" id="'.$appID.'">';
                        // left colum for interfaces
                        echo '<div style="float:left; width:49%;">';

                        echo '<div id="'.$appID.'_interfaces" class="widefat">';
                        echo '<h3 class="fieldTypeHandle">Interfaces</h3>';
                        echo '<div class="">';
                        foreach($Plugins as $Plugin=>$Interfaces){
                            if($Plugin == '__Ungrouped'){
                                $Plugin = '<em>Ungrouped</em>';
                            }
                            echo '<div style="padding:5px 3px 3px;"><strong>'.$Plugin.'</strong></div>';

                            foreach($Interfaces as $Interface){

                                $Dis = '';
                                $Cls = '';
                                $Sel = '';
                                if($Interface['ID'] == $_GET['interface']){
                                    $Dis = 'disabled="disabled"';
                                    $Cls = 'highlight';
                                }
                               if($Interface['ID'] == $Element['Content']['_ItemViewInterface']){
                                    $Sel = 'checked="checked"';
                               }

                                //echo dais_customfield('radio', $Interface['_interfaceName'], '_ItemViewInterface', '_ItemViewInterface', 'list_row1' , 0, $Sel);
                                echo '<div class="list_row4 '.$Cls.'" style="padding: 3px 3px 3px 12px;">';
                                echo '<label for="_ItemViewInterface_'.$Interface['ID'].'">';
                                    echo '<img width="16" height="16" border="0" align="absmiddle" src="'.WP_PLUGIN_URL.'/db-toolkit/data_report/table.png">';
                                    echo '<input type="radio" value="'.$Interface['ID'].'" id="_ItemViewInterface_'.$Interface['ID'].'" name="Data[Content][_ItemViewInterface]" '.$Sel.' '.$Dis.'> '.$Interface['_ReportDescription'].'<div style="padding: 3px 3px 3px 18px;" class="description">'.$Interface['_ReportExtendedDescription'].'</div></label>';
                                echo '</div>';
                            }
                        }
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';

                        echo '<div style="float:right; width:49%;">';

                        echo '<div id="'.$appID.'_clusters" class="widefat">';
                        echo '<h3>Clusters</h3>';
                        echo '<div class="">';

                        foreach($Clusters as $Cluster=>$Interfaces){
                            if($Cluster == '__Ungrouped'){
                                $Cluster = '<em>Ungrouped</em>';
                            }
                            echo '<div style="padding:5px 3px 3px;"><strong>'.$Cluster.'</strong></div>';

                            foreach($Interfaces as $Interface){

                                $Dis = '';
                                $Cls = '';
                                $Sel = '';
                                if(empty($_GET['interface']))
                                    $_GET['interface'] = false;

                                if($Interface['ID'] == $_GET['interface']){
                                    $Dis = 'disabled="disabled"';
                                    $Cls = 'highlight';
                                }
                                if(empty($Element['Content']['_ItemViewInterface']))
                                    $Element['Content']['_ItemViewInterface'] ='';

                               if($Interface['ID'] == $Element['Content']['_ItemViewInterface']){
                                    $Sel = 'checked="checked"';
                               }

                                //echo dais_customfield('radio', $Interface['_interfaceName'], '_ItemViewInterface', '_ItemViewInterface', 'list_row1' , 0, $Sel);
                                echo '<div class="list_row4 '.$Cls.'" style="padding: 3px 3px 3px 12px;">';
                                echo '<label for="_ItemViewInterface_'.$Interface['ID'].'">';
                                    echo '<img width="16" height="16" border="0" align="absmiddle" src="'.WP_PLUGIN_URL.'/db-toolkit/data_report/table.png">';
                                    echo '<input type="radio" value="'.$Interface['ID'].'" id="_ItemViewInterface_'.$Interface['ID'].'" name="Data[Content][_ItemViewInterface]" '.$Sel.' '.$Dis.'> '.$Interface['_ClusterTitle'].'<div style="padding: 3px 3px 3px 18px;" class="description">'.$Interface['_ClusterDescription'].'</div></label>';
                                echo '</div>';
                            }
                        }
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '<div style="clear:both;"></div>';

                echo '</div>';
            echo '</div>';

            }

        }

        EndInfoBox();
        ?>

    </div>
</div>
<?php
ob_start();
?>


		//jQuery("#dbtools_tabs").tabs();
                jQuery("#redirectTabs").tabs();

<?php
$_SESSION['dataform']['OutScripts'] .= ob_get_clean();
?>