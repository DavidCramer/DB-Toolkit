<?php
global $footerscripts;
$currentApp = get_option('_dbt_activeApp');
$app = get_option('_'.$currentApp.'_app');

    $Title = 'New Interface';
    if(!empty($_GET['interface'])){
        $Config = get_option($_GET['interface']);
        if(!empty($Config['Content'])){
            $Config = unserialize(base64_decode($Config['Content']));
        }
        $Config['_ID'] = $_GET['interface'];
        $Title = 'Editing: '.$Config['_ReportDescription'];
    }else{
        $Config = get_option('dbt_default_interface');
    }


?>
<form action="?page=app_builder" method="post" id="interfaceEditForm">
    <?php

        if(!empty($Config['_ID'])){
            echo dbt_configOption('ID', 'ID', 'hidden', false, $Config);
        }

        wp_nonce_field('dbt-interface-edit');
    ?>
    <div class="wrap poststuff wide" id="dbt_container">
        <div id="header">            
            <div class="title">
                <h2><?php echo $Title; ?></h2>
            </div>
            <div class="logo"></div>
            <div class="clear"></div>
        </div>
        <div id="main">
            <div id="dbt-nav">

                <ul>
                    <?php

                    $items = array(
                        'Interface'=>'settings.php',
                        'Fields & Data'=>'fields.php',
                        'Display & Styles'=>'display.php',
                        'Notifications & Texts'=>'notify.php',
                        'Permissions' => 'permissions.php',
                        'Form & View Layout' => 'form.php',
                        'Form Processors' => 'formprocessors.php',
                        'List Processors'=>'listprocessors.php',
                        'Templates'=>'templates.php',
                        'Libraries'=>'libraries.php',
                        'Custom Scripts'=>'none',
                        'Custom WHERE'=>'none',
                        
                    );
                    $Descriptions = array(
                        'Interface'=>'Configures interface naming and identification settings.',
                        'Fields & Data'=>'Configures the fields of the interface.',
                        'Form & View Layout' => 'Setup up the layout for the capture form and view item panels.',
                        'Form Processors' => 'Form Processors pre-process entry data on submission. This will work with the data prior to saving or after saved. Using Form processes enables features like emailing subittions or doing API calls agains submitted data.',
                        'Libraries' => 'Add custom Javascript and CSS libraries to include in your interface.<br />You can upload a file or simple enter the URL.',
                    );


                    $index = 1;
                    foreach($items as $Name=>$File){
                        $class = '';
                        if($index == 1){
                            $class = 'current';
                        }
                        if(!empty($_GET['ctb'])){
                            if($_GET['ctb'] == $index){
                                $class = 'current';
                            }else{
                                $class = '';
                            }
                        }

                    ?>
                    <li class="<?php echo $class; ?>">
                        <a title="<?php echo $Name; ?>" href="#ctb_<?php echo $index; ?>"><?php echo $Name; ?></a>
                    </li>
                    <?php
                    $index++;
                    }
                    ?>
                </ul>

            </div>

            <div id="content">
                <?php
                    $index = 1;
                    foreach($items as $Name=>$File){
                        $display = 'none';
                        if($index === 1){
                            $display = 'block';
                        }
                        if(!empty($_GET['ctb'])){
                            if($_GET['ctb'] == $index){
                                $display = 'block';
                            }else{
                                $display = 'none';
                            }
                        }

               ?>


                <div style="display: <?php echo $display; ?>;" class="group" id="ctb_<?php echo $index; ?>">
                    <h2><?php echo $Name; ?></h2>
                    <?php
                    if(!empty($Descriptions[$Name])){
                        echo '<div style="padding: 2px 0 8px;"><span class="description">'.$Descriptions[$Name].'</span></div>';
                    }
                    if(file_exists(DBT_PATH . 'libs/'.$File)){
                        include DBT_PATH . 'libs/'.$File;
                    }else{
                        echo 'Config options for this area are still under development';
                    }
                    ?>
                </div>

                <?php
                    $index++;
                    }
                ?>

            </div>
            <div class="clear"></div>

        </div>
        <div class="save_bar_top">
            <input type="submit" class="button" value="Save" /><?php
            if(!empty($_GET['element'])){
            ?>&nbsp;
            <span class="submit-footer-reset">
                <input type="button" class="button-primary" value="Apply" onclick="dbt_applyElement('<?php echo $_GET['element']; ?>');">
            </span>
            <?php
            }
            ?>
        </div>
<span id="saveIndicator">Saving</span>
        <div style="clear:both;"></div>
    </div>
</form>
<script type="text/javascript">
    function randomUUID() {
      var s = [], itoh = '0123456789ABCDEF';
      for (var i = 0; i <17; i++) s[i] = Math.floor(Math.random()*0x17);
      return s.join('');
    }
    
    jQuery(document).ready(function(){
            
        jQuery('#dbt-nav li a').click(function(){
            jQuery('#dbt-nav li').removeClass('current');
            jQuery('.group').hide();
            jQuery(''+jQuery(this).attr('href')+'').show();
            jQuery(this).parent().addClass('current');
            return false;
        });

    });
</script>