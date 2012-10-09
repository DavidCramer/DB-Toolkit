<?php

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

echo dbt_configOption('ReportDescription', 'ReportDescription', 'textfield', 'Interface Title', $Config);
echo dbt_configOption('slug', 'slug', 'textfield', 'Interface Slug', $Config);
echo dbt_configOption('ReportExtendedDescription', 'ReportExtendedDescription', 'textfield', 'Description', $Config);
echo dbt_configOption('shortCode', 'shortCode', 'textfield', 'Shortcode', $Config);
echo dbt_configOption('ItemGroup', 'ItemGroup', 'textfield', 'Category', $Config);
echo dbt_configOption('ReportTitle', 'ReportTitle', 'textfield', 'Menu Link Text', $Config);
echo dbt_configOption('SetDashboard', 'SetDashboard', 'checkbox', 'On Dashboad', $Config);
echo dbt_configOption('SetAdminMenu', 'SetAdminMenu', 'checkbox', 'On Admin Menu', $Config);
echo dbt_configOption('ViewMode', 'ViewMode', 'radio', 'Mode|list,form,filters,api', $Config);

                $interfaceGroups = array();
                if(!empty($app['interfaces'])){
                    foreach($app['interfaces'] as $ID=>$type){
                        $intf = get_option($ID);
                        if(empty($intf['_ItemGroup'])){
                            $intf['_ItemGroup'] = 'Ungrouped';
                        }
                        $interfaceGroups[$intf['_ItemGroup']][$intf['_ID']] = $intf['_ReportDescription'];
                    }
                }
                ksort($interfaceGroups);
?>
<div class="dbt_configOption">
    <div class="dbt_configTitle">Redirect</div>
    <div class="dbt_configField">        
        <select id="Redirect" name="data[_redirect]">
            <?php
            $sel = '';
            if(empty($Config['_redirect'])){
                $sel = 'selected="selected"';
            }
            echo "<option value=\"\" ".$sel.">Self</option>\n";
            $sel = '';
            if(!empty($Config['_redirect'])){
                if($Config['_redirect'] == '_URL'){
                    $sel = 'selected="selected"';
                }
            }

            echo "<option value=\"_URL\" ".$sel.">URL</option>\n";
            foreach($interfaceGroups as $group=>$interfaces){                
                echo "<optgroup label=\"".$group."\">/n";
                foreach($interfaces as $ID=>$interface){
                    $sel = '';
                    if(!empty($Config['_redirect'])){
                        if($Config['_redirect'] == $ID){
                            $sel = 'selected="selected"';
                        }
                    }

                    echo "  <option value=\"".$ID."\" ".$sel.">".$interface."</option>\n";
                }
                echo "</optgroup>/n";
            }
            ?>
        </select>
    </div>
    <div class="clear"></div>
</div>
<?php
echo dbt_configOption('customRedirect', 'customRedirect', 'textfield', 'Redirect URL', $Config);
?>
<?php
/*
?>
<div class="dbt_configOption">
    <div class="dbt_configTitle">Base Page</div>
    <div class="dbt_configField">
    <?php
        $defaultBase = 0;
        
        if(!empty($Config['_basePage'])){
            $defaultBase = $Config['_basePage'];
        }
        $args = array(
            'selected' => $defaultBase,
            'post_type' => 'page',
            'show_option_none' => 'Default',
            'name' => 'data[_basePage]',
            'id' => 'basePage'
        );
        wp_dropdown_pages($args);
    ?>
    </div>
    <div class="clear"></div>
</div>
<?php
*/
?>
<div class="dbt_configOption">
    <div class="dbt_configTitle">Base Template</div>
    <div class="dbt_configField">
        <select name="data[_baseTemplate]" id="baseTemplate">
        <?php
        $sel = '';
        if(empty($Config['_baseTemplate'])){
            $Config['_baseTemplate'] = '__app__';
            $sel = 'selected="selected"';
        }
        echo '<option value="__app__" '.$sel.'>App Default</option>';
        $sel = '';
        if(!empty($Config['_baseTemplate'])){
            if($Config['_baseTemplate'] == 'default'){
                $sel = 'selected="selected"';
            }
        }        
        echo '<option value="default" '.$sel.'>Theme Default</option>';
        
        page_template_dropdown($Config['_baseTemplate']);

        ?>
        </select>
    </div>
    <div class="clear"></div>
</div>
