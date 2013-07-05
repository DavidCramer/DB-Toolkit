<div id="dbt_container" class="wrap poststuff" style="width:950px;">

        
        <div id="header">

            <div class="title">
                <?php
                if(!empty($_GET['cluster'])){
                    if(!empty($Element['_ClusterTitle'])){
                        echo '<h2>Editing: '.$Element['_ClusterTitle'].'</h2>';
                    }
                }else{
                ?>
                <h2>Create new Cluster</h2>
                <?php
                }
                ?>
            </div>
            <?php

            $icon = '<img src="'.WP_PLUGIN_URL.'/db-toolkit/images/defaultlogo.png" />';

            if(!empty($appConfig['imageURL'])){
               $icon = '<img src="'.UseImage($appConfig['imageURL'], 7, 150, 100).'" />';
            }
            ?>
            <div class="logo"><?php echo $icon; ?></div>

            <div class="clear"></div>
        </div>
        <div id="main">
            <?php
            // Tabs
            ?>
            <div id="dbt-nav">
                <ul>
                <?php

                    echo '<li class="current">';
                    echo '<a href="#dbt-option-1" title="Config">Cluster Setup</a>';
                    echo '</li>';
                    
                    echo '<li class="">';
                    echo '<a href="#dbt-option-2" title="Layout">Cluster Layout</a>';
                    echo '</li>';

                ?>
                </ul>

            </div>

            <div id="content" style="width: 760px">

                <?php
                // Option Tab

                    echo '<div id="dbt-option-1" class="group" style="display: block;">';
                        echo '<h2>Configure Cluster</h2>';
                        echo dais_customfield('text', 'Menu Group', '_MenuGroup', '_MenuGroup', 'list_row2' , $Element['_MenuGroup'] , '');
                        $Sel = '';
                        if(!empty($Element['Content']['_SetAdminMenu'])) {
                            $Sel = 'checked="checked"';
                        }
                        echo dais_customfield('checkbox', 'Admin Menu Item<div><span class="description">Requires a menu group</span></div>', '_SetAdminMenu', '_SetAdminMenu', 'list_row1' , 1 , $Sel);
                        echo dais_customfield('text', 'Menu Label', '_MenuLable', '_MenuLable', 'list_row1' , $Element['_MenuLable'] , '');
                        echo dais_customfield('text', 'Cluster Title', '_ClusterTitle', '_ClusterTitle', 'list_row2' , $Element['_ClusterTitle']  , '');
                        echo dais_customfield('text', 'Cluster Description', '_ClusterDescription', '_ClusterDescription', 'list_row1' , $Element['_ClusterDescription']  , '');
                        $Sel = '';
                        if(!empty($Element['Content']['_DashboardItem'])) {
                            $Sel = 'checked="checked"';
                        }
                        echo dais_customfield('checkbox', 'Set as Dashboard Item', '_DashboardItem', '_DashboardItem', 'list_row1' , 1 , $Sel);

                        if(empty($Element['Content']['_menuAccess'])){
                            $Element['Content']['_menuAccess'] = 'read';
                        }
                    echo '</div>';


                    echo '<div id="dbt-option-2" class="group" style="display: none;">';
                    echo '<h2>Build Cluster Layout</h2>';

                        include(WP_PLUGIN_DIR.'/db-toolkit/data_report/clusterlayout.php');
                        
                    echo '</div>';


            ?>


            </div>
            <div class="clear"></div>

        </div>
        <div class="save_bar_top">

                <span class="submit-footer-reset">
                    <input type="button" onclick="return window.location='admin.php?page=dbt_builder';" class="button submit-button reset-button" value="Close" name="close">
                    <?php echo dais_standardSetupbuttons($Element); ?>
                </span>
        </div>

    <div style="clear:both;"></div>
</div>











<script type="text/javascript">
			jQuery(document).ready(function(){

                            jQuery('#dbt-nav li a').click(function(){
                                jQuery('#dbt-nav li').removeClass('current');
                                jQuery('.group').hide();
                                jQuery(''+jQuery(this).attr('href')+'').show();
                                jQuery(this).parent().addClass('current');
                                //alert(jQuery(this).attr('href'));
                                return false;
                            });

                            jQuery('#dbt_container .help').click(function(){
                                jQuery(''+jQuery(this).attr('href')+'').toggle();
                                return false;
                            })
			});
		</script>



<?php

return;

?>
















