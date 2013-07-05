<div id="dbt_container" class="wrap poststuff" style="width:950px;">

        <input type="hidden" name="Data[Content][_FormLayout]" cols="50" rows="10" id="_FormLayout" />
        <div id="header">

            <div class="title">
                <?php
                if(!empty($_GET['interface'])){
                    if(!empty($Element['_ReportDescription'])){
                        echo '<h2>Editing: '.$Element['_ReportDescription'].'</h2>';
                    }

                    if(!empty($Element['_ClusterTitle'])){
                        echo '<h2>Editing: '.$Element['_ClusterTitle'].'</h2>';
                    }
                }else{
                ?>
                <h2>Create new Interface</h2>
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

                // Dynamic Listing
                $Tabs = array(
                    'Interface'=>'interfacesetup.php',
                    'Fields Setup'=>'fieldsetup.php',
                    'General Settings'=>'viewsettings.php',
                    'Permissions'=>'permissions.php',
                    'API Settings'=>'api.php',
                    'Data Source*' => 'datasource.php',
                    'Form Layout'=>'formlayout.php',
                    'Form Processors'=>'process.php',
                    'View Layout'=>'viewlayout.php',
                    'View Processors' => 'viewprocess.php',
                    'Templates'=>'listtemplate.php',
                    'Redirects' => 'redirects.php',
                    'Bindings' => 'bindings.php',
                    'Custom Scripts'=>'customscripts.php',
                    'Custom WHERE'=>'where.php',
                    'Import & Export'=>'importexport.php'
                );

                $tabIndex = 1;
                foreach($Tabs as $Title=>$File){
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
                    echo '<a href="#dbt-option-'.$tabIndex++.'" title="'.$Title.'">'.$Title.'</a>';
                    echo '</li>';

                }

                ?>
                </ul>

            </div>

            <div id="content" style="width: 760px">

                <?php
                // Option Tab

                $tabIndex = 1;
                foreach($Tabs as $Title=>$File){
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

                        include(WP_PLUGIN_DIR.'/db-toolkit/data_report/'.$File);

                    echo '</div>';

                    $tabIndex++;
                }


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

