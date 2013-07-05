<div id="dbt_container" class="wrap poststuff">

        <input type="hidden" name="Data[Content][_FormLayout]" cols="50" rows="10" id="_FormLayout" />
        <div id="header">
            <div class="logo">
                <h2>App Market</h2>
            </div>

            <div class="clear"></div>
        </div>
        <div id="main">
            <?php
            // Tabs
            ?>
            <div id="dbt-nav">
                <ul>
                <?php

                //app_fetchCategories($user, $pass);
            //$interfaces = $wpdb->get_results("SELECT option_name FROM $wpdb->options WHERE `option_name` LIKE 'dt_intfc%' ", ARRAY_A);
                
                $marketToken = get_option('_app_marketid_'.get_current_user_id());
                
                if(empty($marketToken)){
                    $cats[0]['category'] = 'Market Login';
                    $cats[0]['description'] = 'Market Login';
                    $cats[0]['appmarket_categoriesID'] = 'login';
                }else{
                    $cats = app_fetchCategories($marketToken);
                }
                //vardump($cats);
                $tabIndex = 1;
                foreach($cats as $cat){
                    
                    $Class = '';
                    if(!empty($_GET['ctb'])){
                    if($_GET['ctb'] == $tabIndex){
                        $Class = 'current';
                    }
                    }else{
                        if($tabIndex == 1){
                            $Class= 'current';
                        }
                    }
                    echo '<li class="'.$Class.'">';
                    echo '<a href="#dbt-option-'.$tabIndex++.'" id="'.$cat['appmarket_categoriesID'].'" title="'.$cat['description'].'">'.$cat['category'].'</a>';
                    echo '</li>';
                }

                ?>
                </ul>

            </div>

            <div id="content">

                <?php
                // Option Tab
                $tabIndex = 1;
                
                foreach($cats as $cat){
                    $view = 'none';
                    if(!empty($_GET['ctb'])){
                        if($_GET['ctb'] == $tabIndex){
                            $view = 'block';
                        }
                    }else{
                        if($tabIndex == 1){
                            $view = 'block';
                        }
                    }

                    echo '<div id="dbt-option-'.$tabIndex.'" class="group" style="display: '.$view.';">';

                    // Do Call for apps in category
                    echo '<h2>'.$cat['category'].'</h2>';
                    echo '<div id="panel_'.$cat['appmarket_categoriesID'].'">';
                        if($cat['appmarket_categoriesID'] == 'login'){
                            include(DB_TOOLKIT.'marketlogin.php');
                        }else{
                            if($tabIndex == 1){
                                echo app_fetchApps($cat['appmarket_categoriesID']);
                            }
                        }
                    echo '</div>';
                    echo '</div>';
                    $tabIndex++;
                }


            ?>


            </div>
            <div class="clear"></div>

        </div>
        <div class="save_bar_top">

                <span class="submit-footer-reset">
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
            cat = jQuery(this).attr('id');
            jQuery('#panel_'+cat).html('Loading Apps...');
            ajaxCall('app_fetchApps', cat, function(l){
                jQuery('#panel_'+cat).html(l);
            })
            return false;
        });

        jQuery('#dbt_container .help').click(function(){
            jQuery(''+jQuery(this).attr('href')+'').toggle();
            return false;
        })
    });
</script>