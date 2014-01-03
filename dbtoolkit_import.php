<?php
if(!empty($_SESSION['appInstall'])){
    if(!file_exists($_SESSION['appInstall'])){
        unset($_SESSION['appInstall']);
    }
}
if(!empty($_FILES['itfInstaller']['size'])){
    $overrides = array('action'=>'itf_install', 'mimes'=> array('itf' => 'application/itf', 'dbt' => 'application/dbt'));
    $_POST['action'] = 'itf_install';
    $upload = wp_handle_upload($_FILES['itfInstaller'], $overrides);
    $_SESSION['appInstall'] = $upload['file'];
}


?>


<div class="wrap ">
    <img src="<?php echo WP_PLUGIN_URL . '/db-toolkit/images/dbtoolkit-logo.png'; ?>" name="DB-Toolkit" title="DB-Toolkit" align="absmiddle" />Application Installer
   
    <br class="clear" /><br />
    <div id="poststuff">
        <?php
        if(!empty($_SESSION['appInstall'])){
            global $user_ID;
            
            $pth = pathinfo($_SESSION['appInstall']);
            
            //die;

            $data = file_get_contents($_SESSION['appInstall']);
            if($predata = @gzinflate($data)){
                $data = $predata;
            }

            $data = unserialize(base64_decode($data));

            if($pth['extension'] == 'itf'){

                $exisits = get_option('_'.sanitize_title($data['appInfo']['name']).'_app');
                if(!empty($ewxisits)){
                    echo '<p><strong>ERROR: App is already installed.</strong></p>';
                    echo '<p id="returnLink" style="display:block;"><a href="'.$_SERVER['REQUEST_URI'].'">Back to installer</p>';
                    unset($_SESSION['appInstall']);
                    return;
                }

                if(!empty($data['appInfo'])){
                    $Ext = pathinfo(basename($data['appInfo']['imageFile']));
                    if(!empty($data['logo'])){
                        $filename = sanitize_file_name($data['appInfo']['name'].'.'.$Ext['extension']);
                        $src = wp_upload_bits($filename, null, base64_decode($data['logo']));
                        $data['appInfo']['imageURL'] = $src['url'];
                        $data['appInfo']['imageFile'] = $src['file'];
                    }
                    update_option('_'.sanitize_title($data['appInfo']['name']).'_app', $data['appInfo']);

                }


                if(!empty($data['application'])){
                echo '<p>Installing Application: <strong>'.$data['application'].'</strong></p>';
                echo '<p id="createingInterfaces">Creating Interfaces...</p>';
                if(!empty($data['tables'])){
                    echo '<p id="buildingTables">Building Tables...</p>';
                }
                echo '<p id="populatingApp">Populating App...</p>';
                echo '<p id="installStatus"></p>';
                echo '<p id="returnLink" style="display:none;"><a href="'.$_SERVER['REQUEST_URI'].'">Back to installer</p>';
                $_SESSION['dataform']['OutScripts'] .= "


                    ajaxCall('core_createInterfaces', '".$_SESSION['appInstall']."', function(P){
                        if(P){
                            jQuery('#createingInterfaces').html('Creating Interfaces...<strong>COMPLETE</strong>');
                            ajaxCall('core_createTables', '".$_SESSION['appInstall']."', function(T){
                                if(T){
                                    jQuery('#buildingTables').html('Building Tables...<strong>COMPLETE</strong>');
                                    ajaxCall('core_populateApp', '".$_SESSION['appInstall']."', function(E){
                                        if(E){
                                            jQuery('#populatingApp').html('Populating App...<strong>COMPLETE</strong>');
                                            jQuery('#installStatus').html('<strong>INSTALLATION COMPLETE</strong>');
                                            jQuery('#returnLink').show('slow');

                                        }
                                    });

                                }
                            });
                        }else{
                            jQuery('#createingInterfaces').html('Creating Interfaces...<strong>ERROR: Could not create interfaces.</strong>');
                        }
                    });


                ";
                }else{
                    unset($_SESSION['appInstall']);
                    echo '<p><strong>Invalid File.</strong></p>';
                    echo '<p id="returnLink" style="display:none;"><a href="'.$_SERVER['REQUEST_URI'].'">Back to installer</p>';
                    $_SESSION['dataform']['OutScripts'] .= "
                    jQuery('#returnLink').show('slow');
                    ";
                }
            }
            // New DBT format installation
            if($pth['extension'] == 'dbt'){
                echo '<h2>Installation Results</h2>';
                // Extract Main App Definition and Settings
                $data['MainApp'] = unserialize($data['MainApp']);
                $data['AppSettings'] = unserialize($data['AppSettings']);
                if(empty($data['MainApp'])){
                    $data['MainApp']['state'] = $data['AppSettings']['state'];
                    $data['MainApp']['name'] = $data['AppSettings']['name'];
                    $data['MainApp']['description'] = $data['AppSettings']['description'];
                }
                //vardump($data);
                //die;

                // Get App Key
                $appKey = sanitize_title($data['MainApp']['name']);
                // Check if its installed
                if(get_option('_'.$appKey.'_app')){
                    unset($_SESSION['appInstall']);
                    echo '<strong>'.$data['MainApp']['name'].'</strong> is already Installed';
                    echo '<p id="returnLink" style="display:block;"><a href="'.$_SERVER['REQUEST_URI'].'">Back to installer</p>';
                    echo '</div></div>';
                    exit;
                }
                // Register App
                $allApps = get_option('dt_int_Apps');
                $allApps[$appKey] = $data['MainApp'];
                update_option('dt_int_Apps', $allApps);
                unset($data['MainApp']);

                // Create Interfaces
                foreach($data['Interfaces'] as $InterfaceID=>$cfg){
                    $cfg = unserialize($cfg);
                    $cfg['Content'] = unserialize(base64_decode($cfg['Content']));
                    // Apply System Prefix
                    array_walk_recursive($cfg['Content'], 'core_applySystemTables');
                    //Save config
                    $cfg['Content'] = base64_encode(serialize($cfg['Content']));
                    update_option($InterfaceID, $cfg);
                }
                unset($data['Interfaces']);

                // Create Filter Locks if any
                if(!empty($data['FilterLocks'])){
                    foreach($data['FilterLocks'] as $InterfaceLock=>$cfg){
                        $cfg = unserialize($cfg);
                        update_option($InterfaceLock, $cfg);
                    }
                    unset($data['FilterLocks']);
                }

                // Create Clusters
                foreach($data['Clusters'] as $ClusterID=>$cfg){
                    $cfg = unserialize($cfg);                    
                    update_option($ClusterID, $cfg);                    
                }
                unset($data['Clusters']);

                // Upload Logo

                $newFileName = uniqid('dbtlgo').'.png';

                if (isset($data['Logo'])) {
                    $logoFile = wp_upload_bits($newFileName, null, base64_decode($data['Logo']));
                    if(!empty($logoFile)){
                        $data['AppSettings']['imageURL'] = $logoFile['url'];
                        $data['AppSettings']['imageFile'] = $logoFile['file'];
                    }
                unset($data['Logo']);
                }

                // Create Tables
                global $wpdb;
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                foreach($data['Tables'] as $tableKey=>$tableStruct){
                    
                    // decode table settings
                    $query = str_replace('{{wp_prefix}}', $wpdb->prefix, base64_decode($tableStruct));
                    dbDelta($query);
                }
                unset($data['Tables']);


                // Create Default Data
                foreach($data['Data'] as $Row){
                    $query = str_replace('{{wp_prefix}}', $wpdb->prefix, base64_decode($Row));
                    dbDelta($query);
                }
                unset($data['Data']);
                $complete = true;

                    if(!empty($data['Bindings'])){
                        if(!empty($_POST['pageSettings'])){
                            
                            $complete = false;                            
                            foreach($data['Bindings'] as $interface=>$pagename){

                                if($_POST['page_'.$interface] == 'newpage'){
                                    $new_post = array(
                                        'post_title' => $pagename,
                                        'post_content' => '',
                                        'post_status' => 'publish',
                                        'post_date' => date('Y-m-d H:i:s'),
                                        'post_author' => $user_ID,
                                        'post_type' => 'page'
                                    );
                                    $post_id = wp_insert_post($new_post);
                                }else{
                                    $post_id = $_POST['binding'][$interface];
                                }
                                // update binding with new page ID
                                // check Page Bindings
                                //check previous binding
                                $OldBinding = get_option('_dbtbinding_'.$post_id);
                                if(!empty($OldBinding)){
                                    //unbind old interface
                                    $prvBindingCFG = get_option($OldBinding);
                                    $prvBindingCFG['Content'] = unserialize(base64_decode($prvBindingCFG['Content']));
                                    if($prvBindingCFG['Content']['_ItemBoundPage'] == $post_id){
                                        $prvBindingCFG['Content']['_ItemBoundPage'] = 0;
                                        $prvBindingCFG['Content'] = base64_encode(serialize($prvBindingCFG['Content']));
                                        update_option($OldBinding, $prvBindingCFG);
                                    }
                                }
                                //bind interface to new page//
                                $oldOptions = get_option($interface);
                                if(!empty($oldOptions)){
                                    // remove bindings if there are any
                                    $oldOptions['Content'] = unserialize(base64_decode($oldOptions['Content']));
                                    $oldOptions['Content']['_ItemBoundPage'] = $post_id;
                                    $oldOptions['Content'] = base64_encode(serialize($oldOptions['Content']));
                                    $oldOptions['_ItemBound'] = $post_id;
                                    update_option($interface, $oldOptions);
                                }

                                update_option('_dbtbinding_'.$post_id, $interface, false);

                                // Check Dependicies and change redirects
                                if(is_array($data['Dependencies'][$pagename])){
                                    foreach($data['Dependencies'][$pagename] as $redirectInterface){
                                        $redirectCFG = get_option($redirectInterface);
                                        $redirectCFG['Content'] = unserialize(base64_decode($redirectCFG['Content']));
                                        $redirectCFG['Content']['_ItemViewPage'] = $post_id;
                                        $redirectCFG['Content'] = base64_encode(serialize($redirectCFG['Content']));
                                        update_option($redirectInterface, $redirectCFG);
                                    }
                                }

                            }


                            $complete = true;
                        }else{
                            $pageList = array();
                            $complete = false;
                            echo '<h2 style="margin-bottom:0;">Page Setup</h2>';
                            echo '<span class="description">These are pages that the application use to display content. You can assign them to new pages, or let the installer create them for you.</span>';
                            echo '<br /><br /><form name="importApplication" enctype="multipart/form-data" method="POST" action="'.$_SERVER['REQUEST_URI'].'">';
                            foreach($data['Bindings'] as $interface=>$pagename){
                                $icfg = get_option($interface);
                                //vardump($icfg);
                                //titles
                                echo '<div style="width:200px; float:left;  padding-right:20px; border-right:1px dashed #ccc;">';
                                echo '<div><strong>'.$icfg['_ReportDescription'].'</strong></div>';
                                echo '<span class="description">'.$icfg['_ReportExtendedDescription'].'&nbsp;</span>';
                                echo '</div>';
                                // settings
                                echo '<div style="width:400px; float:left; padding-left:20px;">';
                                echo '<div style="padding:3px;"><input type="radio" id="newpage_'.$interface.'" name="page_'.$interface.'" value="newpage" checked="checked" /><label for="newpage_'.$interface.'"> Create a new page called: <strong>'.$pagename.'</strong></label></div>';
                                echo '<div style="padding:3px;"><input type="radio" id="existingpage_'.$interface.'" name="page_'.$interface.'" value="existing" /><label for="existingpage_'.$interface.'"> Use existing Page: '.wp_dropdown_pages( "name=binding[".$interface."]&echo=0&show_option_none=-select-").'</label></div>';
                                echo '</div>';
                                echo '<div style="clear:both;padding:15px 0;"></div>';
                                $pageList[$pagename] = $interface;

                            }
                            //if(empty($data['Dependencies'])){
                                echo '<input type="submit" value="Save Setting" name="pageSettings" class="button">';
                                echo '</form>';
                            //}
                        }
                    }
                    // check redirect pages (dependencies)
                    /*
                    if(!empty($data['Dependencies'])){
                        $complete = false;
                        if(!empty($_POST['pageSettings'])){

                            
                            

                        }else{
                            $complete = false;
                            echo '<h2>Link Pages</h2>';
                            echo '<span class="description">These are pages that the application pages link to.<br />You can assign them to new pages, or let the installer create them for you.</span>';
                            echo '<br /><br />';
                            foreach($data['Dependencies'] as $interface=>$pagename){
                                $icfg = get_option($interface);
                                //vardump($icfg);
                                //titles
                                echo '<div style="width:200px; float:left;  padding-right:20px; border-right:1px dashed #ccc;">';
                                echo '<div><strong>'.$icfg['_ReportDescription'].'</strong></div>';
                                echo '<span class="description">'.$icfg['_ReportExtendedDescription'].'&nbsp;</span>';
                                echo '</div>';
                                // settings
                                echo '<div style="width:400px; float:left; padding-left:20px;">';
                                echo '<div style="padding:3px;"><input type="radio" id="newpage_'.$interface.'" name="page_'.$interface.'" value="newpage" checked="checked" /><label for="newpage_'.$interface.'"> Create a new page called: <strong>'.$pagename.'</strong></label></div>';
                                echo '<div style="padding:3px;"><input type="radio" id="existingpage_'.$interface.'" name="page_'.$interface.'" value="existing" /><label for="existingpage_'.$interface.'"> Use existing Page: '.wp_dropdown_pages( "name=binding[".$interface."]&echo=0&show_option_none=-select-").'</label></div>';
                                echo '</div>';
                                echo '<div style="clear:both;padding:15px 0;"></div>';

                            }
                            echo '<input type="submit" value="Save Setting" name="pageSettings" class="button">';
                            echo '</form>';
                            
                            vardump($data);
                            die;
                        }
                    }
                    */

                    if(!empty($complete)){

                        // Create Main App Settings
                        update_option('_'.$appKey.'_app', $data['AppSettings']);
                        unset($data['AppSettings']);

                        // Clear Session and end off.
                        unset($_SESSION['appInstall']);
                        echo '<p><strong>Installation Complete.</strong></p>';
                        echo '<p id="returnLink" style="display:block;"><a href="'.$_SERVER['REQUEST_URI'].'">Back to installer</p>';
                    }

            }

        }else{
        ?>
        <form name="importApplication" enctype="multipart/form-data" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
        <h4>Install a DB-Toolkit Application in .itf or .dbt format</h4>
        <p class="install-help">If you have an application in .itf or .dbt format, you may install it by uploading it here.</p>
	<input type="file" name="itfInstaller">
	<input type="submit" value="Install Now" class="button">
        </form>
        <?php
        }
        ?>
    </div>
</div>

<?php
/*
                        global $user_ID;
                        $new_post = array(
                            'post_title' => $pagename,
                            'post_content' => '',
                            'post_status' => 'publish',
                            'post_date' => date('Y-m-d H:i:s'),
                            'post_author' => $user_ID,
                            'post_type' => 'page'
                        );
                        $post_id = wp_insert_post($new_post);

 */
?>