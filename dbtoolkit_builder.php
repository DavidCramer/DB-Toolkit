<?php
$Apps = get_option('dt_int_Apps');

?>
<h2 id="appTitle">DB-Toolkit</h2>
<div id="dbt_container" class="wrap poststuff">

        <input type="hidden" name="Data[Content][_FormLayout]" cols="50" rows="10" id="_FormLayout" />
        <div id="header">
            <div class="title">
                <h2>Application Builder</h2>
            </div>
            <?php
            $icon = '<img src="'.WP_PLUGIN_URL.'/db-toolkit/images/defaultlogo.png" />';
            ?>
            <div class="logo"><?php echo $icon; ?></div>

            <div class="clear"></div>
        </div>
        <div class="save_bar_tools">

            <div class="fbutton"  onclick="dr_addApplication();"><div class="button add-new-h2"><span class="add" style="padding-left: 20px;">New Application</span></div></div>
            <div class="fbutton"  onclick="dr_rebuildApps();"><div class="button add-new-h2"><span class="reload" style="padding-left: 20px;">Rebuild Apps index</span></div></div>
            <div class="fbutton"><div class="button add-new-h2"><a href="admin.php?page=dbtools_importer"><span class="install" style="padding-left: 20px;">Install Application</span></a></div></div>

        </div>
        <div id="main">
            <div id="content" style="width:755px !important;">


                <div id="dbt-apps" class="group" style="display: block;">

                <?php
                if(!empty($Apps)){
                   foreach($Apps as $app=>$config){
                    if(strtolower($app) == 'base'){
                        $config = array();
                        $config['state'] = 'open';
                        $config['name'] = 'Draft Interfaces';
                        $config['description'] = 'A place for storing test and draft interfaces.';
                        $config['version'] = 'drafts';
                    }
                    if($config['state'] == 'open'){

                        $appConfig = get_option('_'.$app.'_app');                        
                        
                ?>


                <div id="<?php echo 'app-'.$app; ?>" class="appModule smlPanel">
                
                <h2><?php echo $config['name']; ?></h2>
                
                <div class="appDescription">

                <div class="appLogo">
                    <div class="appIntCount"><?php
                    if(!empty($appConfig['interfaces']))
                        echo count($appConfig['interfaces']);
                    else
                        echo '0';

                    ?></div>
                <p>Interfaces</p>
                </div>

                <p><?php
                if(empty($config['description'])){
                    echo 'No description given';
                }else{
                    echo $config['description'];
                }
                ?></p>
                </div>

                <div class="appModuleButton">
                    <a title="Open App to Edit" href="?page=dbt_builder&open=<?php echo $app; ?>" class="button">Open App</a>
                    <?php
                    $dockedClass = 'button';
                    $title = 'Dock';
                    if(!empty($appConfig['docked'])){
                        $dockedClass = 'button-primary';
                        $title = 'Undock';
                    }
                    ?>
                    <a title="Add App to Wordpress Menu" href="#" onclick="app_dockApp('<?php echo $app; ?>'); return false;" id="app_<?php echo $app; ?>" class="<?php echo $dockedClass; ?>"><?php echo $title; ?></a>
                </div>                
                </div>

                <?php
                   }
                   }
                }else{
                    echo '<p>You have no apps. Create one now.</p>';
                }
                ?>
                </div>



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
            //alert(jQuery(this).attr('href'));
            return false;
        });

        jQuery('#dbt_container .help').click(function(){
            jQuery(''+jQuery(this).attr('href')+'').toggle();
            return false;
        })
    });
</script>