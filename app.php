<?php

    $pages = array();
    if(!empty($app['interfaces'])){
        foreach ($app['interfaces'] as $interface => $info) {
            $inf = get_option($interface);
            if(!empty($inf['Content'])){
                $inf = unserialize(base64_decode($inf['Content']));
            }
                if(empty($inf['_ItemGroup'])){
                    $inf['_ItemGroup'] = 'Ungrouped';
                }
            $pages[$inf['_ItemGroup']][$interface] = $inf;
        }
        ksort($pages);
    }



?>
<div class="wrap poststuff" id="dbt_container">
    <input type="hidden" id="_FormLayout" rows="10" cols="50" name="Data[Content][_FormLayout]">
    <div id="header">
        <div class="title">
            <h2><?php echo $appInfo['name']; ?></h2>
        </div>
        <div class="logo"></div>
        <div class="clear"></div>
    </div>
    <div class="save_bar_tools">
        <span class="fbutton"><a href="?page=app_builder&action=edit"><div id="addNewInterface" class="button"><span class="icon-plus-sign" style="margin-top:-1px;"></span> New Interface</div></a></span>
        <span class="fbutton"><a href="#importer_screen" id="importer"><div class="button"><span class="icon-download" style="margin-top:-1px;"></span> Export</div></a></span>
        <span class="fbutton"><a href="?page=app_builder&closeapp=true" id="importer"><div class="button"><span class="icon-chevron-left" style="margin-top:-1px;"></span> Close App</div></a></span>
        <span style="float:right; margin-top: 0; " class="itemField"><div class="dbt-elementItem small" style="height: 26px; margin-top:2px;padding-left: 8px;">Base Template: <?php

        /*$defaultBase = 0;
        if(!empty($app['basePage'])){
            $defaultBase = $app['basePage'];
        }
        $args = array(
            'selected' => $defaultBase,
            'post_type' => 'page',
            'show_option_none' => 'Site Front Page',
            'name' => 'basePage'
        );
        
        wp_dropdown_pages($args);
        */
        ?>

        <select name="baseTemplate" id="baseTemplate" style="width: 160px;">
        <?php
        $sel = '';
        $Default = get_post_meta($app['basePage'],'_wp_page_template',true);
        if(empty($Default)){
            $Default = 'default';
            $sel = 'selected="selected"';
        }else{
            if($Default == 'default'){
                $sel = 'selected="selected"';
            }
        }
        echo '<option value="default" '.$sel.'>Theme Default</option>';
        page_template_dropdown($Default);

        ?>
        </select>
        Landing:
        <select name="landing" id="baseInterface" style="width: 160px;" onchange="dbt_app_setLanding(this.value);">
        <?php
        $sel = '';
        if(empty($app['landing'])){
            $app['baseTemplate'] = 'default';
            $sel = 'selected="selected"';
        }else{
            if($app['baseTemplate'] == 'default'){
                $sel = 'selected="selected"';
            }
        }
        echo '<option value="page" '.$sel.'>No Interface</option>';
        foreach($pages as $category=>$group){
            echo "<optgroup label=\"".ucwords($category)."\">'n";
                foreach($group as $tmID=>$itm){
                    echo "<option value=\"".$tmID."\">".$itm['_ReportDescription']."</option>\n";
                }
            echo "</optgroup>";
        }

        ?>
        </select>

            </div></span>
    </div>
    <div id="main">
        <div id="content">
            <?php
            $landing = '';
            if(!empty($app['landing'])){
                $landing = $app['landing'];
            }
            if(!empty($app['interfaces'])){
                $index = 1;
                foreach($pages as $group => $Interfaces) {

                    $tabid = sanitize_key($group);
                    $show = 'none';
                    if($index === 1){
                        $show = 'block';
                    }

                ?>
                <div style="display: <?php echo $show; ?>;" class="group" id="<?php echo $tabid; ?>">
                    <?php
                        foreach($Interfaces as $ID=>$Interface){
                        $ShortCode = str_replace('dt_intf', 'dbt ', $ID);
                        $wasActive = '';
                        if(!empty($_GET['ref'])){
                            if($_GET['ref'] == $ID){
                                $wasActive = 'lastactive';
                                $lastEdited = get_option($_GET['ref']);                                
                            }
                        }
                    ?>
                    <div id="element_<?php echo $ID; ?>">
                        <div class="dbt-elementItem <?php echo $wasActive; ?>">
                            <div class="dbt-elementIcon <?php echo $Interface['_ViewMode']; ?>">
                                <?php
                                /*<span class="infoTrigger" rel="<?php echo $ID; ?>">Details</span>*/
                                ?>
                            </div>
                            <div class="dbt-elementInfoPanel">
                                <?php echo $Interface['_ReportDescription']; ?>
                                <div class="dbt-elementInfoPanel description"><?php echo $Interface['_ReportExtendedDescription']; ?></div>
                            </div>
                            <div class="dbt-elementInfoPanel mid">Shortcode
                                <div class="dbt-elementInfoPanel description">[<?php echo $ShortCode; ?>]</div>
                            </div>
                            <div id="" class="dbt-elementInfoPanel last buttonbar buttons_<?php echo $ID; ?>" style="display:block;">
                                <?php if($Interface['_main_table'] != '__unconfigured__'){ ?>
                                <span class="fbutton"><a href="?page=app_builder&action=render&interface=<?php echo $ID; ?>"><div class="button"><span class="icon-eye-open"></span></div></a></span>
                                <?php }else{ ?>
                                
                                <?php } ?>
                                <span class="fbutton"><a href="?page=app_builder&action=edit&interface=<?php echo $ID; ?>"><div class="button"><span class="icon-edit"></span></div></a></span>
                                <!--<span class="fbutton"><a href="?page=app_builder&export=<?php echo $ID; ?>"><div class="button"><span class="icon-share"></span></div></a></span>-->
                                <span class="fbutton"><a href="#" class="confirm" rel="<?php echo $ID; ?>" onclick="return false;"><div class="button"><span class="icon-remove-sign"></span></div></a></span>
                            </div>
                            <div id="confirm_<?php echo $ID; ?>" class="dbt-elementInfoPanel last buttons_<?php echo $ID; ?>" style="display:none;">
                                <div class="infoDelete">Delete Interface</div>
                                <span class="fbutton"><a href="#" onclick="dbt_deleteInterface('<?php echo $ID; ?>'); return false;"><div class="button"><span class="icon-ok"></span></div></a></span>
                                <span class="fbutton"><a href="#" class="confirm" rel="<?php echo $ID; ?>"><div class="button"><span class="icon-share-alt"></span> Cancel</div></a></span>

                            </div>
                        </div>

                        <div class="dbt-infopanel dbt-elementItem">
                            <div style="display:none;" id="options_<?php echo $ID; ?>">                                
                                <div class="infoburst"><span class="icon-th-list" title="Database Table"></span> <?php echo $Interface['_main_table']; ?></div>
                                <div class="infoburst"><span class="icon-eye-open" title="Permission/Visibility"></span> <?php echo $Interface['_visibilityPermission']; ?></div>
                                <div class="clear"></div>
                            </div>
                        </div>

                    </div>
                    <?php
                        }
                    ?>

                </div>


                <?php
                    $index++;
                }
                ?>
                <div class="clear"></div>
        <?php
        }else{
        ?>
                You have no interfaces, go make one now.
        <?php
        }
        ?>
        </div>
        <div id="dbt-nav">

            <ul>
                <?php
                $index = 1;
                foreach($pages as $page=>$items){
                    
                    $class = '';
                    if($index === 1){
                        $class = 'class="current"';
                    }
                    $tabid = sanitize_key($page);

                ?>
                <li <?php echo $class; ?> id="tab_<?php echo $tabid; ?>">
                    <span class="dbt-elementCount"><?php echo count($items); ?></span><a title="<?php echo $page; ?>" href="#<?php echo $tabid; ?>"><?php echo $page; ?></a>
                </li>
                <?php
                    $index++;
                }
                ?>

            </ul>

        </div>
        <div class="clear"></div>
    </div>
    <div class="save_bar_top">
        <span class="submit-footer-reset">
        </span>
    </div>
</div>
<script type="text/javascript">

    jQuery(document).ready(function(){

//        jQuery('#basePage').change(function(){
//            dbt_ajaxCall('dbt_setBasePage', jQuery(this).val(), function(){});
//        })
        jQuery('#baseTemplate').change(function(){
            dbt_ajaxCall('dbt_setBaseTemplate', jQuery(this).val(), function(){});
        })

        jQuery('.infoTrigger').click(function(){
            jQuery('#options_'+jQuery(this).attr('rel')).slideToggle('fast');
        });

        jQuery('#dbt-nav li a').click(function(){
            jQuery('#dbt-nav li').removeClass('current');
            jQuery('.group').hide();
            jQuery(''+jQuery(this).attr('href')+'').show();
            jQuery(this).parent().addClass('current');
            return false;
        });
        jQuery('.confirm').click(function(){
            var ele = jQuery(this).attr('rel');
            jQuery('.buttons_'+ele).slideToggle();
            return false;
        });
        <?php

        if(!empty($lastEdited)){
            if(empty($lastEdited['_ItemGroup'])){
                $lastEdited['_ItemGroup'] = 'Ungrouped';
            }
            echo "jQuery('#dbt-nav li').removeClass('current');\n";
            echo "jQuery('.group').hide();\n";
            echo "jQuery('#".sanitize_key($lastEdited['_ItemGroup'])."').show();\n";
            echo "jQuery('#tab_".sanitize_key($lastEdited['_ItemGroup'])."').addClass('current');\n";
            

        }




        ?>
    });

    function dbt_deleteInterface(IID){
        dbt_ajaxCall('dbt_deleteInterface', IID, function(d){
            jQuery('#element_'+IID).slideUp('slow');
            var count = parseFloat(jQuery('.current .dbt-elementCount').html())-1;
            jQuery('.current .dbt-elementCount').html(count);
        })
    }
    function dbt_app_setLanding(id){
        dbt_ajaxCall('dbt_setLanding', id, function(d){});
    }
</script>