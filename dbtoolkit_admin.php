<?php


/*
notes:



*/
/*
 *
 * Render Interface
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 */
if(!empty($_GET['renderinterface'])){
    $Interface = get_option($_GET['renderinterface']);
    if($Interface['Type'] == 'Cluster'){
        dt_renderCluster($_GET['renderinterface']);
        return;
    }
    $Title = $Interface['_interfaceName'];
    if(!empty($Interface['_ReportDescription'])) {
       $Title = $Interface['_ReportDescription'];
    }
    $appConfig = get_option('_'.$activeApp.'_app');

    $user = wp_get_current_user();


    if(!empty($user->caps['administrator']) && empty($noedit)){
    ?>

    <h2 id="appTitle"><?php echo $appConfig['name']; ?></h2>
    <?php
    }
    ?>
    <div class="wrap">
    <div id="icon-themes" class="icon32"></div><h2><?php _e($Title); ?>
        <?php
            //global $user;
        
            if(!empty($user->caps['administrator']) && $appConfig['state'] == 'open'){
        ?>
        <a class="button add-new-h2" href="admin.php?page=dbt_builder&interface=<?php echo $_GET['renderinterface']; ?>">Edit</a>
    <?php
            }
    ?></h2>
    <?php
    $fset = get_option('dt_set_'.$Interface['ID']);
    if(!empty($fset)){
    ?>
        <ul class="subsubsub">

                <?php
                    
                    $tablen = count($fset);
                    $index = 1;
                    $link = explode('&ftab', $_SERVER['REQUEST_URI']);
                    $class = 'class="current"';
                    if(!empty($_GET['ftab'])){
                        $class = '';
                    }
                    $total = dr_BuildReportGrid($Interface['ID'], false, false, false, 'count', true, false);
                    //unset($_SESSION['reportFilters'][$Interface['ID']]);
                    $counter = ' <span class="count">(<span class="'.$tab['code'].'">'.$total.'</span>)</span> ';
                    
                    echo '<li><a '.$class.' href="'.$link[0].'">All '.$counter.'</a> | </li>';
                    foreach($fset as $tab){
                        $break = '';
                        $counter = '';
                        $class = '';
                        if(!empty($_GET['ftab'])){                            
                            if($_GET['ftab'] == $tab['code']){
                                $class = 'class="current"';
                            }
                        }
                        if($index < $tablen){
                            $break = ' | ';
                        }
                        if($tab['ShowCount'] == 'yes'){
                            // need to do a counter only process
                            $total = dr_BuildReportGrid($Interface['ID'], false, false, false, 'count', true, $tab['Filters']);
                            //unset($_SESSION['reportFilters'][$Interface['ID']]);
                            $counter = ' <span class="count">(<span class="'.$tab['code'].'">'.$total.'</span>)</span> ';
                        }
                        $link = explode('&ftab', $_SERVER['REQUEST_URI']);
                        echo '<li><a '.$class.' href="'.$link[0].'&ftab='.$tab['code'].'">'.$tab['Title'].$counter.'</a>'.$break.'</li>';
                        $index++;
                    }
                ?>

        </ul>
<?php
}
?>

    <div class="clear"></div>
    <div id="poststuff">
    <?php
        echo dt_renderInterface($_GET['renderinterface']);
    ?>
    </div>
    </div>
<?php

 return;
}



/*
 *
 *
 *
 *
 *
 * Creating new Interface
 *
 *
 *
 *
 *
 *
 *
 */



        if($_GET['page'] == 'Add_New'){
        /*    ?>
        <div class="wrap">
            <div><img src="<?php echo WP_PLUGIN_URL . '/db-toolkit/images/dbtoolkit-logo.png'; ?>" name="DB-Toolkit" title="DB-Toolkit" align="absmiddle" />Create new Interface
            <div class="clear"></div>
                <br />
            <div id="poststuff">
                    <?php
                    //$optionTitle = uniqid('dt_intfc');

//                $_POST = stripslashes_deep($_POST);
//
//		$newOption = array();
//		$newOption['_interfaceName'] = $_POST['dt_newInterface'];
//		$newOption['_interfaceType'] = 'unconfigured';
//		$newOption['_interfaceDate'] = date('Y-m-d H:i:s');
//		$newOption['ID'] = $optionTitle;
//		$newOption['Element'] = 'data_report';
//		$newOption['Content'] = base64_encode(serialize(array()));
//		$newOption['ParentDocument'] = $optionTitle;
//		$newOption['Position'] = 0;
//		$newOption['Column'] = 0;
//		$newOption['Type'] = 'Plugin';
//		$newOption['Row'] = 0;

                    //add_option($optionTitle, serialize($newOption), NULL, "no");



*/
                 $appConfig = get_option('_'.$activeApp.'_app');
                    ?>
                <h2 id="appTitle"><?php echo $appConfig['name']; ?></h2>
                <form name="newInterfaceForm" id="newInterfaceForm" method="post" action="admin.php?page=dbt_builder">
                        <?php 
                        $defaults = get_option('_dbtoolkit_defaultinterface');
                        
                        $Element['Content'] = $defaults;
                        include('data_report/setup.plug.php');
                        ?>
                </form>
<?php
                        /*
                        ?>
                </form>
            </div>
        </div>
            <?php
                         * 
                         */
            return;
        }

/*
 *
 *
 *
 *
 *
 * Creating new Cluster
 *
 *
 *
 *
 *
 *
 *
 */



        if($_GET['page'] == 'New_Cluster'){
        /*    ?>
        <div class="wrap">
            <div><img src="<?php echo WP_PLUGIN_URL . '/db-toolkit/images/dbtoolkit-logo.png'; ?>" name="DB-Toolkit" title="DB-Toolkit" align="absmiddle" />Create new Interface
            <div class="clear"></div>
                <br />
            <div id="poststuff">
                    <?php
                    //$optionTitle = uniqid('dt_intfc');

//                $_POST = stripslashes_deep($_POST);
//
//		$newOption = array();
//		$newOption['_interfaceName'] = $_POST['dt_newInterface'];
//		$newOption['_interfaceType'] = 'unconfigured';
//		$newOption['_interfaceDate'] = date('Y-m-d H:i:s');
//		$newOption['ID'] = $optionTitle;
//		$newOption['Element'] = 'data_report';
//		$newOption['Content'] = base64_encode(serialize(array()));
//		$newOption['ParentDocument'] = $optionTitle;
//		$newOption['Position'] = 0;
//		$newOption['Column'] = 0;
//		$newOption['Type'] = 'Plugin';
//		$newOption['Row'] = 0;

                    //add_option($optionTitle, serialize($newOption), NULL, "no");



*/
                 $appConfig = get_option('_'.$activeApp.'_app');
                    ?>
                <h2 id="appTitle"><?php echo $appConfig['name']; ?></h2>
                <form name="newInterfaceForm" id="newInterfaceForm" method="post" action="admin.php?page=dbt_builder">
                        <?php
                        $defaults = get_option('_dbtoolkit_defaultinterface');

                        $Element['Content'] = $defaults;
                        include('data_report/cluster.plug.php');
                        ?>
                </form>
<?php
                        /*
                        ?>
                </form>
            </div>
        </div>
            <?php
                         *
                         */
            return;
        }




/*
 *
 *
 *
 *
 *
 * Edit Interface
 *
 *
 *
 *
 *
 *
 *
 *
 *
 *
 */







        if(!empty($_GET['interface']) || !empty($_GET['cluster'])) {
            $appConfig = get_option('_'.$activeApp.'_app');
            
            if(!empty($_GET['interface'])){
                $Element = get_option($_GET['interface']);
            }else{
                $Element = get_option($_GET['cluster']);
            }
            $Element['Content'] = unserialize(base64_decode($Element['Content']));
            
            

                    $URL = str_replace('&interface='.$Element['ID'], '', $_SERVER['REQUEST_URI']);
                    $URL = str_replace('&cluster='.$Element['ID'], '#clusters', $URL);

                ?>
                <h2 id="appTitle"><?php echo $appConfig['name']; ?></h2>
                <form name="newInterfaceForm" id="newInterfaceForm" method="post" action="<?php echo $URL; ?>">
                        <?php
                        if(!empty($_GET['interface'])){
                            include('data_report/setup.plug.php');
                        }else{
                            include('data_report/cluster.plug.php');
                        }
                        ?>
                    <input type="hidden" value="<?php echo $Element['ID']; ?>" name="Data[interfaceTitle]" />
                </form>

            <?php
            return;
        }







        /*
         *
         *
         *
         *
         *
         *
         * Interface listings
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         *
         */









         /*
          *
          *
          * App Image Uploader
          *
          *
          *
          */


        /// Start of listing applications
        if(!empty($_FILES['appImage']['size'])){
            $path = wp_upload_dir();
            // set filename and paths
            $Ext = pathinfo($_FILES['appImage']['name']);
            $newFileName = sanitize_file_name($activeApp.'.'.$Ext['extension']);
            $upload = wp_upload_bits($newFileName, null, file_get_contents($_FILES['appImage']['tmp_name']));

            $appConfig = get_option('_'.$activeApp.'_app');
            $appConfig['imageURL'] = $upload['url'];
            $appConfig['imageFile'] = $upload['file'];
            update_option('_'.$activeApp.'_app', $appConfig);
            
        }




        // get master app config
        $appConfig = get_option('_'.$activeApp.'_app');
        


        
       

        ?>

















<?php

$Apps = get_option('dt_int_Apps');
//vardump($Apps);
$icon = '<img src="'.WP_PLUGIN_URL.'/db-toolkit/images/defaultlogo.png" align="top" /><p class="description" style="text-align: right;">Vesion: '.DBT_VERSION.'</p>';
if(!empty($appConfig['imageURL'])){
   $icon = '<img src="'.UseImage($appConfig['imageURL'], 7, 150, 100).'" align="top" />';
}
?>
<h2 id="appTitle"><?php echo $appConfig['name']; ?></h2>

<div id="dbt_container" class="wrap poststuff">

        <input type="hidden" name="Data[Content][_FormLayout]" cols="50" rows="10" id="_FormLayout" />
        <div id="header">
            <div class="title">
                <h2><?php echo $appConfig['name']; ?></h2>                
            </div>
            <div class="logo"><?php echo $icon; ?></div>

            <div class="clear"></div>
        </div>
        <div class="save_bar_tools">
            <span class="fbutton" style="float:right;"><a href="?page=dbt_builder&delete=<?php echo $activeApp; ?>" onclick="return confirm('Are you sure you want to delete this App and all its interfaces? It\'s permanent!')"><div class="button add-new-h2"><span class="delete" style="padding-left: 20px;">Delete Application</span></div></a></span>
            <span class="fbutton"><a href="admin.php?page=Add_New"><div class="button add-new-h2" id="addNewInterface"><span class="add" style="padding-left: 20px;">New Interface</span></div></a></span>
            <span class="fbutton"><a href="admin.php?page=New_Cluster"><div class="button add-new-h2"><span class="add" style="padding-left: 20px;">New Cluster</span></div></a></span>
            <?php
            if(strtolower($activeApp) != 'base'){
            ?>
                <span class="fbutton"><a href="?page=dbt_builder&exportApp=true"><div class="button add-new-h2"><span class="export" style="padding-left: 20px;" >Export</span></div></a></span>
                <?php
                //<div class="fbutton"><a href="?page=dbt_builder&exportApp=true&plugin=true"><div class="button add-new-h2"><span class="export" style="padding-left: 20px;" >Export as Plugin</span></div></a></div>
                ?>
            <?php
            }
            ?>
            <span class="fbutton"><a href="?page=dbt_builder&close=<?php echo $activeApp; ?>"><div class="button add-new-h2"><span class="closefilter" style="padding-left: 20px;">Close Application</span></div></a></span>            


        </div>
        <div id="main">            
            <div id="dbt-nav">

                <ul>
                    <li class="current">
                        <a href="#interfaces" title="interfaces">Interfaces</a>
                    </li>
                    <li class="">
                        <a href="#clusters" title="clusters">Clusters</a>
                    </li>
                    <?php
                    /*
                    <li class="">
                        <a href="#database" title="database">Database Admin</a>
                    </li>
                     *
                     */
                    ?>
                    <li class="">
                        <a href="#config" title="config">Config</a>
                    </li>
                </ul>

            </div>
            
            <div id="content">
                
                <div id="interfaces" class="group" style="display: block;">

                <?php
                $blink = false;
                if(!empty($appConfig['interfaces'])) {
                    $Groups = array();
                    foreach($appConfig['interfaces'] as $interface=>$access) {
                        $Iname = $interface;
                        $cfg = get_option($Iname);
                        if(empty($cfg['_Application'])){
                            $cfg['_Application'] = 'Base';
                        }
                        //if(sanitize_title($cfg['_Application']) == $activeApp){
                            $GroupName = '__Ungrouped';
                            if(!empty($cfg['_ItemGroup'])){
                                $GroupName = $cfg['_ItemGroup'];
                            }
                            $Groups[$GroupName][] = $cfg;
                        //}
                    }
                    ksort($Groups);
                    foreach($Groups as $Group=>$data){
                        if($Group == '__Ungrouped')
                            $Group = '<em>Ungrouped</em>';
                        echo '<h2 style="clear:both;">'.$Group.'</h2>';

                        // Interface panel
                        foreach($data as $Interface){
                        ?>
                        <div id="<?php echo $Interface['ID']; ?>" class="interfaceModule">

                            <div class="interfaceDets">
                                <div><?php

                                if(!empty($Interface['_shortCode'])){
                                    echo '['.$Interface['_shortCode'].']';
                                }else{


                                ?>[interface id="<?php echo $Interface['ID']; ?>"]<?php
                                }
                                ?></div>
                            </div>
                            <h2><?php echo $Interface['_ReportDescription']; ?></h2>
                            <div class="interfaceDescription">
                            <?php
                                if(!empty($Interface['_ReportExtendedDescription']))
                                    echo $Interface['_ReportExtendedDescription'];


                                $Config = unserialize(base64_decode($Interface['Content']));
                                //vardump($Config);
                                //die;


                                $landing = '';
                                if(!empty($appConfig['landing'])){
                                    if($appConfig['landing'] == $Interface['ID']){
                                        $landing = 'checked="checked"';
                                    }
                                }



                            ?>
                            </div>

                            <div class="interfaceActions">Landing: <input type="radio" name="<?php echo 'landing_'.$activeApp; ?>" id="rdo_<?php echo $Interface['ID']; ?>" value="<?php echo $Interface['ID']; ?>" onclick="app_setLanding('<?php echo $activeApp; ?>', '<?php echo $Interface['ID']; ?>');" <?php echo $landing; ?> /> | <a href="admin.php?page=dbt_builder&interface=<?php echo $Interface['ID']; ?>">Edit</a> | <a href="admin.php?page=dbt_builder&renderinterface=<?php echo $Interface['ID']; ?>">View</a> | <a href="admin.php?page=dbt_builder&duplicateinterface=<?php echo $Interface['ID']; ?>">Duplicate</a> | <a href="#" onclick="dt_deleteInterface('<?php echo $Interface['ID']; ?>'); return false;">Delete</a></div>
                            <div class="interfaceSettings">Type: <strong><?php echo $Config['_ViewMode']; ?></strong> | Table: <strong><?php echo $Config['_main_table']; ?></strong> | Permission: <strong><?php echo $Interface['_menuAccess']; ?></strong>
                            <?php
                            if(!empty($Interface['_ItemBound'])){
                                $page = get_page($Interface['_ItemBound']);
                                echo ' | Bound to: <strong>'.$page->post_title.'</strong>';
                            }

                            ?>

                            </div>
                            
                        </div>

                        <?php
                        }



                    }
                }else{
                    echo '<h2>Interfaces</h2>';
                    echo 'You have no interfaces yet, go make some!';
                    $blink = true;

                }
                ?>



                </div>
                <div id="clusters" class="group" style="display: none;">
                <?php
                
                if(!empty($appConfig['clusters'])) {
                    $Groups = array();
                    foreach($appConfig['clusters'] as $interface=>$access) {
                        $Iname = $interface;
                        $cfg = get_option($Iname);
                        if(empty($cfg['_Application'])){
                            $cfg['_Application'] = 'Base';
                        }
                        //if(sanitize_title($cfg['_Application']) == $activeApp){
                            $GroupName = '__Ungrouped';
                            if(!empty($cfg['_ItemGroup'])){
                                $GroupName = $cfg['_ItemGroup'];
                            }
                            $Groups[$GroupName][] = $cfg;
                        //}
                    }
                    ksort($Groups);
                    foreach($Groups as $Group=>$data){
                        if($Group == '__Ungrouped')
                            $Group = '<em>Ungrouped</em>';
                        echo '<h2 style="clear:both;">'.$Group.'</h2>';

                        // Interface panel
                        foreach($data as $Interface){
                            //vardump($data);
                        
                        ?>
                        <div id="<?php echo $Interface['ID']; ?>" class="interfaceModule">

                            <div class="interfaceDets">
                                <div>[interface id="<?php echo $Interface['ID']; ?>"]</div>
                            </div>
                            <h2><?php echo $Interface['_ClusterTitle']; ?></h2>
                            <div class="interfaceDescription">
                            <?php
                                if(!empty($Interface['_ReportExtendedDescription']))
                                    echo $Interface['_ReportExtendedDescription'];


                                $Config = unserialize(base64_decode($Interface['Content']));
                                //vardump($Config);
                                //die;


                                $landing = '';
                                if(!empty($appConfig['landing'])){
                                    if($appConfig['landing'] == $Interface['ID']){
                                        $landing = 'checked="checked"';
                                    }
                                }



                            ?>
                            </div>

                            <div class="interfaceActions">Landing: <input type="radio" name="<?php echo 'landing_'.$activeApp; ?>" id="rdo_<?php echo $Interface['ID']; ?>" value="<?php echo $Interface['ID']; ?>" onclick="app_setLanding('<?php echo $activeApp; ?>', '<?php echo $Interface['ID']; ?>');" <?php echo $landing; ?> /> | <a href="admin.php?page=dbt_builder&cluster=<?php echo $Interface['ID']; ?>">Edit</a> | <a href="admin.php?page=dbt_builder&renderinterface=<?php echo $Interface['ID']; ?>">View</a> | <a href="admin.php?page=dbt_builder&duplicateinterface=<?php echo $Interface['ID']; ?>">Duplicate</a> | <a href="#" onclick="dt_deleteInterface('<?php echo $Interface['ID']; ?>'); return false;">Delete</a></div>
                            <div class="interfaceDetails">Type: <strong>Cluster</strong></div>

                        </div>

                        <?php
                        }



                    }
                }else{
                    echo '<h2>Clusters</h2>';
                    echo 'You have no clusters yet, go make some!';
                    $blink = true;

                }
                ?>


                </div>
                <div id="database" class="group" style="display: none;">
                    <h2>Database Admin</h2>
                    <p class="description">Tables need to be cleaned every now and then. Use this area to keep your tables optimised.</p>
                    <?php
                    global $wpdb;
                    $Tables = array();
                         foreach($appConfig['interfaces'] as $interface=>$access) {
                             $interfaceCFG = get_option($interface);
                             $icfg = unserialize(base64_decode($interfaceCFG['Content']));
                             if(!empty($icfg['_main_table'])){
                                $Tables[$icfg['_main_table']] = true;
                             }                             
                         }
                         ksort($Tables);
                    ?>
                    <div class="list_row1">Table:
                        <select id="tableOpt">
                            <?php
                                foreach($Tables as $Table=>$true){
                            ?>
                            <option value="<?php echo str_replace($wpdb->prefix.'dbt_', '', $Table); ?>"><?php echo str_replace($wpdb->prefix.'dbt_', '', $Table); ?></option>



                            <?php
                                }
                            ?>
                        </select>

        <select id="field_0_2" name="field_type[0]" class="column_type">
            <option value="INT">INT</option>
            <option value="VARCHAR">VARCHAR</option>
            <option value="TEXT">TEXT</option>
            <option value="DATE">DATE</option>
            <optgroup label="NUMERIC">
                <option value="TINYINT">TINYINT</option>
                <option value="SMALLINT">SMALLINT</option>
                <option value="MEDIUMINT">MEDIUMINT</option>
                <option value="INT">INT</option>
                <option value="BIGINT">BIGINT</option>
                <option value="DECIMAL">DECIMAL</option>
                <option value="FLOAT">FLOAT</option>
                <option value="DOUBLE">DOUBLE</option>
                <option value="REAL">REAL</option>
                <option value="BIT">BIT</option>
                <option value="BOOLEAN">BOOLEAN</option>
                <option value="SERIAL">SERIAL</option>
            </optgroup>
            <optgroup label="DATE and TIME">
                <option value="DATE">DATE</option>
                <option value="DATETIME">DATETIME</option>
                <option value="TIMESTAMP">TIMESTAMP</option>
                <option value="TIME">TIME</option>
                <option value="YEAR">YEAR</option>
            </optgroup>
            <optgroup label="STRING">
                <option value="CHAR">CHAR</option>
                <option value="VARCHAR">VARCHAR</option>
                <option value="TINYTEXT">TINYTEXT</option>
                <option value="TEXT">TEXT</option>
                <option value="MEDIUMTEXT">MEDIUMTEXT</option>
                <option value="LONGTEXT">LONGTEXT</option>
                <option value="BINARY">BINARY</option>
                <option value="VARBINARY">VARBINARY</option>
                <option value="TINYBLOB">TINYBLOB</option>
                <option value="MEDIUMBLOB">MEDIUMBLOB</option>
                <option value="BLOB">BLOB</option>
                <option value="LONGBLOB">LONGBLOB</option>
                <option value="ENUM">ENUM</option>
                <option value="SET">SET</option>
            </optgroup>

        </select>
                        <button class="button">Load Table</button>
                    </div>
                    <div id="tablePanel"></div>
                </div>
                <div id="config" class="group" style="display: none;">
                    <h2>Configuration</h2>

                    <p>Add a few extra details to define your app.</p>
                        <div id="configAccord">
                                <h2>Logo</h2>
                                <div>
                                        
                                            <form method="post" action="" enctype="multipart/form-data" id="application-switcher">
                                                    <?php
                                                    if(strtolower($activeApp) != 'base'){
                                                    ?>
                                                    <br />
                                                    Application logo: <input type="file" name="appImage"><input type="submit" value="Upload" class="button">
                                                    <div class="description">A 150 X 65px transparent PNG gives the best results</div>



                                                    <?php                                                        

                                                    }
                                                    ?>
                                            </form>
                                        
                                </div>                                
                                <h2>App Info</h2>
                                <div>
                                        <p>
                                                
                                        <div style="width:80px; float:left; clear: Both;">App Name:</div><input type="text" class="appConfigPanel" name="pluginName" id="appDesc" style="width:300px;" value="<?php echo $appConfig['name']; ?>" /><br />
                                        <div style="width:80px; float:left; clear: Both;">App URI:</div> <input type="text" class="appConfigPanel" name="pluginURI" id="appDesc" style="width:300px;" value="<?php echo $appConfig['pluginURI']; ?>" /><br />
                                        <div style="width:80px; float:left; clear: Both;">Description:</div> <textarea class="appConfigPanel" name="pluginDesc" id="appDesc" style="width:300px; height:150px;"><?php echo $appConfig['description']; ?></textarea><br />
                                        <div style="width:80px; float:left; clear: Both;">Version:</div> <input type="text" class="appConfigPanel" name="pluginVersion" id="appDesc" style="width:300px;" value="<?php echo $appConfig['pluginVersion']; ?>" /><br />
                                        <div style="width:80px; float:left; clear: Both;">Author:</div> <input type="text" class="appConfigPanel" name="pluginAuthor" id="appDesc" style="width:300px;" value="<?php echo $appConfig['pluginAuthor']; ?>" /><br />
                                        <div style="width:80px; float:left; clear: Both;">Author URI:</div> <input type="text" class="appConfigPanel" name="pluginAuthorURI" id="appDesc" style="width:300px;" value="<?php echo $appConfig['pluginAuthorURI']; ?>" /><br />
                                            
                                            <br><h2></h2>

                                            <input type="button" class="button-primary" value="Save Changes" onclick="app_saveDesc()"/>
                                        </p>

                                </div>
                                
                        </div>




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
        //jQuery( "#configAccord" ).accordion({
        //    autoHeight: false,
        //    navigation: true
        //});



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
        <div class="wrap">
            <div><?php
            if(!empty($appConfig['imageURL'])){
               echo '<img src="'.UseImage($appConfig['imageURL'], 7, 200, 100).'" name="DB-Toolkit" title="DB-Toolkit" align="absmiddle" />';
            }else{
                echo '<img src="'.WP_PLUGIN_URL . '/db-toolkit/images/dbtoolkit-logo.png" name="DB-Toolkit" title="DB-Toolkit" align="absmiddle" />';
            }
            ?>
                <a href="admin.php?page=Add_New" class="button">New Interface</a>&nbsp;
                <a href="admin.php?page=New_Cluster" class="button">New Cluster</a>
                <?php
                if(strtolower($activeApp) != 'base'){
                    echo '&nbsp;&nbsp; <input type="submit" class="button-secondary action" id="exportApp" name="exportApp" value="Export Application">';
                }
                
                ?>
                <a href="?page=dbt_builder&close=<?php echo $activeApp; ?>" class="button-secondary action" id="closeApp">Close Application</a>
                <br />
                <span class="description">Manage Interfaces & Clusters</span>
                <br class="clear" /><br />
            <?php
            if(!empty($_POST['Data'])) {
                
                global $newCFG;

                

                if(empty($_POST['Data']['ID'])){
                    $LinkID = $newCFG['ID'];
                }else{
                    $LinkID = $_POST['Data']['ID'];
                }
                
                if(isset($newCFG['_ReportDescription'])){
                    $Title = $newCFG['_ReportDescription'];
                }else{
                    $Title = $newCFG['_ClusterTitle'];
                }
                

                echo '<div class="notice fade" id="message"><p><strong>Interface <a href="admin.php?page=dbt_builder&renderinterface='.$LinkID.'">'.$Title.'</a> Updated.</strong></p></div>';
            }
            ?>
            <?php
            /*
            <form name="exportPublish" method="post" action="<?php echo str_replace('&interface='.$Element['ID'], '', $_SERVER['REQUEST_URI']); ?>">
                <?php
                if($activeApp != 'Base'){
                    echo '<input type="submit" class="button-secondary action" id="exportApp" name="exportApp" value="Export Application">';
                }
                ?>
            </form>
             */
            ?>
            </div>
            <?php
            

            ?>

                <script>
                    
                jQuery(document).ready(function($) {
                        jQuery("#dbToolkit_Tabs").tabs();
                });

            </script>
            <div id="dbToolkit_Tabs" class="dbtools_tabs">
              <ul>
                            <li><a href="#interfaces"><span>Interfaces</span></a></li>
                            <li><a href="#clusters"><span>Clusters</span></a></li>
              </ul>
            
            <div id="interfaces" class="setupTab">
            <table width="100%" border="0" cellspacing="2" cellpadding="2" class="widefat">
                <thead>
                    <tr>
                        <th scope="col" class="manage-column" id="interface-spacer-top"></th>
                        <th scope="col" class="manage-column" id="interface-name-top">Interface Name</th>
                        <th scope="col" class="manage-column" id="interface-name-top" style="width:80px;">Landing</th>
                        <th scope="col" class="manage-column" id="interface-table-top">Table Interfaced</th>
                        <th scope="col" class="manage-column" id="interface-date-top">Interface Type</th>
                        <th scope="col" class="manage-column" id="interface-date-top">Short Code</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th scope="col" class="manage-column" id="interface-spacer-bottom"></th>
                        <th scope="col" class="manage-column" id="interface-name-bottom">Interface Name</th>
                        <th scope="col" class="manage-column" id="interface-name-top" style="width:80px;">Landing</th>
                        <th scope="col" class="manage-column" id="interface-table-bottom">Table Interfaced</th>
                        <th scope="col" class="manage-column" id="interface-date-bottom">Interface Type</th>
                        <th scope="col" class="manage-column" id="interface-date-bottom">Short Code</th>                        
                    </tr>
                </tfoot>
                <?php
                
                if(!empty($appConfig['interfaces'])) {
                    $Groups = array();
                    foreach($appConfig['interfaces'] as $interface=>$access) {                        
                        $Iname = $interface;
                        $cfg = get_option($Iname);
                        if(empty($cfg['_Application'])){
                            $cfg['_Application'] = 'Base';
                        }
                        if(sanitize_title($cfg['_Application']) == $activeApp){
                            $GroupName = '__Ungrouped';
                            if(!empty($cfg['_ItemGroup'])){
                                $GroupName = $cfg['_ItemGroup'];
                            }
                            $Groups[$GroupName][] = $cfg;
                        }
                    }
                    ksort($Groups);
                    foreach($Groups as $Group=>$Interfaces){
                        
                        if($Group == '__Ungrouped'){
                            $Group = '<em>Ungrouped</em>';
                        }
                        ?>
                        <tr>
                            <th scope="row" colspan="6" class="highlight"><?php echo $Group; ?></th>
                        </tr>
                        <?php


                        $app = sanitize_title($appConfig['name']);
                        foreach($Interfaces as $Interface){

                        $landing = '';
                        if(!empty($appConfig['landing'])){
                            if($appConfig['landing'] == $Interface['ID']){
                                $landing = 'checked="checked"';
                            }
                        }
                        
                        $Config = unserialize(base64_decode($Interface['Content']));
                        //vardump($Config);
                        //vardump($cfg);
                        $API = str_replace('dt_intfc', '', $Interface['ID']).'_'.md5(str_replace('dt_intfc', '', $Interface['ID']).$Config['_APISeed']);
                        //$API = $Interface['ID'].'_'.md5($Interface['ID'].$Config['_APISeed']);//str_replace('dt_intfc', '', $Interface['ID']).'_'.md5(serialize($Config));

                        $Desc = '';
                        if(!empty($Config['_ReportExtendedDescription'])) {
                           $Desc = $Config['_ReportExtendedDescription'];
                        }
                        
                        ?>

                <tr id="<?php echo $Interface['ID']; ?>">
                    <td></td>
                    <td>
                        <strong><?php                                    
                                    $titleName = 'Untitled Interface';
                                    if(!empty($Interface['_ReportDescription'])) {
                                        $titleName = $Interface['_ReportDescription'];
                                    }
                                    _e($titleName); ?></strong>
                        <div><?php echo $Desc; ?></div>
                        <div class="row-actions"><a href="<?php echo $_SERVER['REQUEST_URI']; ?>&interface=<?php echo $Interface['ID']; ?>">Edit</a> | <a href="<?php echo $_SERVER['REQUEST_URI']; ?>&renderinterface=<?php echo $Interface['ID']; ?>">View</a> | <a href="<?php echo $_SERVER['REQUEST_URI']; ?>&duplicateinterface=<?php echo $Interface['ID']; ?>">Duplicate</a> | <a href="#" onclick="dt_deleteInterface('<?php echo $Interface['ID']; ?>'); return false;">Delete</a></div></div>
                    </td>
                    <td><input type="radio" name="<?php echo 'landing_'.$app; ?>" id="rdo_<?php echo $Interface['ID']; ?>" value="<?php echo $Interface['ID']; ?>" onclick="app_setLanding('<?php echo $app; ?>', '<?php echo $Interface['ID']; ?>');" <?php echo $landing; ?> /></td>
                    <td><?php echo $Config['_main_table']; ?></td>
                    <td><?php echo $Config['_ViewMode']; ?></td>
                    <td>[interface id="<?php echo $Interface['ID']; ?>"]</td>
                    
                </tr>
                        <?php
                    }
                   }
                }
                ?>
            </table>
            </div>
            <div id="clusters" class="setupTab">


<table width="100%" border="0" cellspacing="2" cellpadding="2" class="widefat">
                <thead>
                    <tr>
                        <th scope="col" class="manage-column" id="interface-spacer-top"></th>
                        <th scope="col" class="manage-column" id="interface-name-top">Cluster Name</th>
                        <th scope="col" class="manage-column" id="interface-table-top">Interfaces</th>
                        <th scope="col" class="manage-column" id="interface-date-top">Short Code</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th scope="col" class="manage-column" id="interface-spacer-bottom"></th>
                        <th scope="col" class="manage-column" id="interface-name-bottom">Cluster Name</th>
                        <th scope="col" class="manage-column" id="interface-table-bottom">Interfaces</th>
                        <th scope="col" class="manage-column" id="interface-date-bottom">Short Code</th>                        
                    </tr>
                </tfoot>
                <?php
                if(!empty($clusters)) {
                    $Groups = array();
                    foreach($clusters as $cluster) {
                        //vardump($interface);

                        $Iname = $cluster['option_name'];
                        $cfg = get_option($Iname);
                        if(empty($cfg['_Application'])){
                            $cfg['_Application'] = 'Base';
                        }
                        if(sanitize_title($cfg['_Application']) == $activeApp){
                            $GroupName = '__Ungrouped';
                            if(!empty($cfg['_ItemGroup'])){
                                $GroupName = $cfg['_ItemGroup'];
                            }
                            $Groups[$GroupName][] = $cfg;
                        }
                    }
                    ksort($Groups);
                    foreach($Groups as $Group=>$Clusters){

                        if($Group == '__Ungrouped'){
                            $Group = '<em>Ungrouped</em>';
                        }
                        ?>
                        <tr>
                            <th scope="row" colspan="5" class="highlight"><?php echo $Group; ?></th>
                        </tr>
                        <?php




                        foreach($Clusters as $Cluster){

                        $Config = unserialize(base64_decode($Cluster['Content']));
                        //vardump($cfg);
                        $API = str_replace('dt_intfc', '', $Cluster['ID']).'_'.md5(serialize($Config));

                        $Desc = '';
                        if(!empty($Config['_ClusterDescription'])) {
                           $Desc = $Config['_ClusterDescription'];
                        }

                        ?>

                <tr id="<?php echo $Cluster['ID']; ?>">
                    <td></td>
                    <td>
                        <strong><?php
                                    $titleName = 'Untitled Cluster';
                                    if(!empty($Cluster['_ClusterTitle'])) {
                                        $titleName = $Cluster['_ClusterTitle'];
                                    }
                                    _e($titleName); ?></strong>
                        <div><?php echo $Desc; ?></div>
                        <div class="row-actions"><a href="<?php echo $_SERVER['REQUEST_URI']; ?>&cluster=<?php echo $Cluster['ID']; ?>">Edit</a> | <a href="<?php echo $_SERVER['REQUEST_URI']; ?>&renderinterface=<?php echo $Cluster['ID']; ?>">View</a> | <a href="<?php echo $_SERVER['REQUEST_URI']; ?>&duplicateinterface=<?php echo $Cluster['ID']; ?>">Duplicate</a> | <a href="#" onclick="dt_deleteInterface('<?php echo $Cluster['ID']; ?>', 'cluster'); return false;">Delete</a></div></div>
                    </td>
                    <td><?php echo $Config['_main_table']; ?></td>
                    <td>[interface id="<?php echo $Cluster['ID']; ?>"]</td>
                </tr>
                        <?php
                    }
                   }
                }
                ?>
            </table>


            </div>
        </div>
        </div>