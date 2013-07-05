<?php
//vardump($_POST);
if(!empty($_POST['Data'])) {
    $_POST = stripslashes_deep($_POST);
    update_option('_dbtoolkit_defaultinterface', $_POST['Data']['Content']);
}



$defaults = get_option('_dbtoolkit_defaultinterface');
$Element['Content'] = $defaults;

?>
<form name="saveSettings" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
<div id="dbt_container" class="wrap poststuff">
    <?php
    //<form id="ofform" enctype="multipart/form-data" action="">
    ?>
        <input type="hidden" name="Data[Content][_FormLayout]" cols="50" rows="10" id="_FormLayout" />
        <div id="header">
            <div class="logo">
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
                <h2>Default Settings for New interfaces</h2>
                <?php
                }
                ?>
            </div>
            <div class="clear"></div>
        </div>
        <div id="main">

            <div id="dbt-nav">
                <ul>
                <?php

                // Dynamic Listing
                $Tabs = array(                    
                    'General Settings'=>'viewsettings.php',
                    //'Chart Setup'=>'chartlayout.php'
                );

                //Load Configs for extensions
                $ext = array();

                // View Processors
                $viewProcessors = list_files(DB_TOOLKIT.'data_report/processors');
                foreach($viewProcessors as $processor){
                    if(basename($processor) == 'conf.php'){
                        include($processor);
                        if(!empty($ConfigFile)){
                            
                            if(file_exists('processors/'.basename(dirname($processor)).'/'.$ConfigFile)){
                                $ext[$ViewTitle] = 'processors/'.basename(dirname($processor)).'/'.$ConfigFile;
                            }
                        }
                        unset($ConfigFile);
                    }
                }
                // Form Processors
                $formProcessors = list_files(DB_TOOLKIT.'data_report/processors');
                foreach($formProcessors as $processor){
                    if(basename($processor) == 'conf.php'){
                        include($processor);
                        if(!empty($ConfigFile)){

                            if(file_exists('processors/'.basename(dirname($processor)).'/'.$ConfigFile)){
                                $ext[$ViewTitle] = 'processors/'.basename(dirname($processor)).'/'.$ConfigFile;
                            }
                        }
                        unset($ConfigFile);
                    }
                }

                
                ksort($ext);
                $Tabs = array_merge($Tabs, $ext);
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

            <div id="content">

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
    <?php
    //</form>
    ?>
    <div style="clear:both;"></div>
</div>
</form>


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