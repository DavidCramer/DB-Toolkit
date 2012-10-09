<div class="wrap poststuff" id="dbt_container">

    <input type="hidden" id="_FormLayout" rows="10" cols="50" name="Data[Content][_FormLayout]">
    <div id="header">
        <div class="title">
            <h2>Applications</h2>
        </div>
        <div class="logo"></div>
        <div class=""></div>
        <div class="clear"></div>
    </div>
    <div class="save_bar_tools">
        <span class="fbutton"><a href="#create_new" id="createNewApp"><div id="addNewInterface" class="button"><span class="icon-plus-sign" style="margin-top:-1px;"></span> Create App</div></a></span>
        <span class="fbutton"><a href="#importer_screen" id="importer"><div class="button"><span class="icon-file" style="margin-top:-1px;"></span> Import</div></a></span>
    </div>   
    <div id="main">
        <div id="content" style="width: 755px !important;">
            <div style="display: none;" class="group" id="create_new">
                <h2>Create New Application</h2>
                <form method="post" enctype="multipart/form-data" >
                    App Title: <input type="textfield" name="_appTitle" /> App Description: <input type="textfield" name="_appDesc" style="width:335px" /> <input class="button" type="submit" value="Create" name="_createApp" />
                </form>
            </div>
            <div style="display: none;" class="group" id="importer_screen">
                <h2>Import Application</h2>
                <form method="post" enctype="multipart/form-data" >
                    File <input type="file" name="import" /><input class="button" type="submit" value="Import" />
                </form>
            </div>
            <?php
            $index = 1;
            $apps = get_option('dt_int_Apps');
            if(!empty($apps)){
                foreach ($apps as $app => $info) {
                    if(!empty($app)){
                    $appID = uniqid('app');
                    if(empty($info['description'])){
                        $info['description'] = 'No description given.';
                    }
                    $bClass = '';
                    $bTitle = 'Publish';

                    if($info['state'] == 'publish'){
                        $bClass = 'active';
                        $bTitle = 'Publish';
                    }
                ?>
                    <div id="app_<?php echo $app; ?>" class="dbt-appBox">
                        <div class="dbt-appWrap <?php echo $appID; ?>">
                            <span class="fbutton" style="float:right;"><a href="#" onclick="jQuery('.<?php echo $appID; ?>').slideToggle('fast'); return false;"><div class="button"><span class="icon-remove-circle"></span></div></a></span><h3><?php echo $info['name']; ?></h3>
                            <div class="description"><?php echo $info['description']; ?></div>
                        </div>
                        <div style="display:none;" class="dbt-appWrap confirm <?php echo $appID; ?>">
                            <div class="infoDelete">Delete Application?</div>
                            <div class="buttonbar">
                                <span class="fbutton"><a href="#" onclick="dbt_deleteApp('<?php echo $app; ?>');"><div class="button"><span class="icon-check"></span></div></a></span>
                                <span class="fbutton"><a href="#" onclick="jQuery('.<?php echo $appID; ?>').slideToggle('fast');return false;"><div class="button"><span class="icon-share-alt"></span> Cancel</div></a></span>
                                <div class="clear"></div>
                            </div>

                        </div>
                        <div class="buttonbar <?php echo $appID; ?>">
                            <span class="fbutton"><a href="?page=app_builder&loadapp=<?php echo $app; ?>"><div class="button"><span class="icon-eye-open"></span> Open</div></a></span>
                            <!-- <span class="fbutton"><a href="?page=app_builder"><div class="button"><span class="icon-list-alt"></span> Dock</div></a></span> -->
                            <span class="fbutton"><a href="?page=app_builder&togglepub=<?php echo $app; ?>"><div class="button <?php echo $bClass; ?>"><span class="icon-check"></span> <?php echo $bTitle; ?></div></a></span>
                            <div class="clear"></div>
                        </div>

                    </div>
                <?php
                    }
                }
            }else{
                
                echo 'You dont have any apps yet, Create one now.';
                
            }
            ?>
            <div class="clear"></div>
        
        </div>
        <div class="clear"></div>
    </div>
    <div class="save_bar_top">
        <span class="infoLabel" style="float:left;"><?php echo dbt_getVersion(); ?></span>
        <span class="submit-footer-reset">&copy; <?php echo date('Y'); ?> - David Cramer | <a href="http://dbtoolkit.co.za" target="_blank">dbtoolkit.co.za</a></span>
    </div>
</div>

<script type="text/javascript">

    jQuery(document).ready(function(){

        jQuery('.confirm').click(function(){
            var ele = jQuery(this).attr('rel');
            jQuery('.buttons_'+ele).slideToggle();
        });
        jQuery('.infoTrigger').click(function(){
            jQuery('#options_'+jQuery(this).attr('rel')).slideToggle('fast');
        });
        jQuery('#ce-nav li a').click(function(){
            jQuery('#ce-nav li').removeClass('current');
            jQuery('.group').hide();
            jQuery(''+jQuery(this).attr('href')+'').show();
            jQuery(this).parent().addClass('current');
            return false;
        });
        jQuery('#importer, #createNewApp').click(function(){
            
            jQuery(''+jQuery(this).attr('href')+'').toggle();
            
            return false;
        });

    });


    function dbt_deleteApp(app){
        dbt_ajaxCall('dbt_deleteApp', app, function(d){
            jQuery('#app_'+app).fadeOut('slow');
        })
    }
</script>