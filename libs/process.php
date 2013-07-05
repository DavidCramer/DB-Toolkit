<?php
/*
 * Core Admin Processors Library - DB Toolkit
 * (C) David Cramer 2010 - 2011
 *
 */

function dt_saveInterface($str){
    parse_str(urldecode($str), $vars);
    $vars = stripslashes_deep($vars);
    return dt_saveCreateInterface($vars);
}

if(!empty($_GET['exportApp'])){
    $activeApp = get_option('_dbt_activeApp');
    if(empty($activeApp))
        return;

    $app = get_option('_'.$activeApp.'_app');
    if($app['state'] == 'open'){
        $publish = false;
        if(!empty($_GET['plugin'])){
            $publish = true;
        }
        exportApp($app, $publish);
    }
    return;
}
    // dumplicate interface hack
if(is_admin()) {
    
    $activeApp = get_option('_dbt_activeApp');
    if(empty($activeApp))
        return;

    $app = get_option('_'.$activeApp.'_app');
    
    if(!empty($_GET['duplicateinterface'])){
        $dupvar = get_option($_GET['duplicateinterface']);
        $oldOption = $dupvar;
        if($oldOption['Type'] == 'Cluster'){
            $NewName = uniqid($oldOption['_ClusterTitle'].' ');
            $oldOption['_ClusterTitle'] = $NewName;
            $newTitle = uniqid('dt_clstr');
            $hash = '&r=y#clusters';
            $app['clusters'][$newTitle] = $oldOption['_menuAccess'];
        }else{
            $NewName = uniqid($oldOption['_ReportDescription'].' ');
            $oldOption['_ReportDescription'] = $NewName;
            $newTitle = uniqid('dt_intfc');
            $hash = '';
            $app['interfaces'][$newTitle] = $oldOption['_menuAccess'];
        }
        $oldOption['ID'] = $newTitle;
        $oldOption['ParentDocument'] = $newTitle;
        if(update_option($newTitle, $oldOption)){
            update_option('_'.$activeApp.'_app', $app);
        }
        header( 'Location: '.$_SERVER['HTTP_REFERER'].$hash);
        exit;
    }
}

    
//// FROM dbtoolkit_admin.php
//// LINE 95
if(!empty($_POST['Data'])) {

    dt_saveCreateInterface($_POST);
    
}

function dt_saveCreateInterface($saveData){
    global $wpdb, $user;
    
    $activeApp = get_option('_dbt_activeApp');
    
    if(empty($activeApp))
        return;


    $app = get_option('_'.$activeApp.'_app');
    if(empty($app))
        return;


    $saveData = stripslashes_deep($saveData);
    //vardump($saveData);
    
    if(!empty($saveData['Data']['ID'])){
        $optionTitle = $saveData['Data']['ID'];
        $newCFG = get_option($optionTitle);
        //vardump($newCFG);
    }else{

        $optionTitle = uniqid('dt_intfc');        
        if(isset($saveData['Data']['Content']['_clusterLayout'])){
            $optionTitle = uniqid('dt_clstr');
        }

        $newOption = array();
        $newCFG['ID'] = $optionTitle;
        if(empty($saveData['dt_newInterface']))
            $saveData['dt_newInterface'] = '';


        $newCFG['_interfaceName'] = $saveData['dt_newInterface'];
        $newCFG['_interfaceType'] = 'unconfigured';
        $newCFG['_interfaceDate'] = date('Y-m-d H:i:s');
        $newCFG['ID'] = $optionTitle;
        $newCFG['Element'] = 'data_report';
        //$newCFG['Content'] = base64_encode(serialize(array()));
        $newCFG['ParentDocument'] = $optionTitle;
        $newCFG['Position'] = 0;
        $newCFG['Column'] = 0;
        $newCFG['Row'] = 0;
    }

    // Clear Session data for the interface
    unset($_SESSION['report_' . $optionTitle]);


    if(!empty($saveData['Data']['Content']['_shortCode'])){
        $newCFG['_shortCode'] = $saveData['Data']['Content']['_shortCode'];
    }else{
        if(!empty($newCFG['_shortCode'])){
            unset($newCFG['_shortCode']);
        }
    }
    if(isset($saveData['Data']['Content']['_clusterLayout'])){
        $app['clusters'][$optionTitle] = 'null';
    }else{
        $app['interfaces'][$optionTitle] = $saveData['Data']['Content']['_menuAccess'];
    }
    // Setup Index_Show's
    //echo '<br><br><br>';
    
    $Indexes = array();
    if(!empty($saveData['Data']['Content']['_Field'])){

        
        // Fetch Fields
        $tableData = $wpdb->get_results("DESCRIBE `".$saveData['Data']['Content']['_main_table']."`", ARRAY_A);
        $FieldConfig = array();
        foreach($tableData as $FieldData){
            $FieldConfig[$FieldData['Field']] = $FieldData['Type'];
        }
        
        foreach($saveData['Data']['Content']['_Field'] as $Field=>$Value){
            // Make Sure the Fields EXIST and is not a clone!
            if(substr($Field, 0, 2) != '__'){

                //Load Fields Base Type
                $type = explode('_', $saveData['Data']['Content']['_Field'][$Field]);
                
                if(!empty($type[1])){
                    if(file_exists(DB_TOOLKIT.'data_form/fieldtypes/'.$type[0].'/conf.php')){
                        include (DB_TOOLKIT.'data_form/fieldtypes/'.$type[0].'/conf.php');
                        if(!empty($FieldTypes[$type[1]]['baseType'])){
                            $baseType = $FieldTypes[$type[1]]['baseType'];
                        }else{
                            $baseType = 'VARCHAR( 255 )';
                        }
                    }
                }else{
                    $baseType = 'int(11)';
                }
                if(empty($FieldConfig[$Field])){                   
                    $wpdb->query("ALTER TABLE `".$saveData['Data']['Content']['_main_table']."` ADD `".$Field."` ".$baseType." NOT NULL ");

                }else{
                    // check types
                    if($FieldConfig[$Field] != $baseType){
                        //$wpdb->query("ALTER TABLE `".$saveData['Data']['Content']['_main_table']."` CHANGE `".$Field."` `".$Field."` ".$baseType."");
                    }
                }
                unset($FieldConfig[$Field]);

                $Indexes[$Field]['Visibility'] = 'hide';
                $Indexes[$Field]['Indexed'] = 'noindex';
            }
        }
        // Drop unwanted Fields
        //
        // DISABLED - very very dangerous as you'll loose data!
        //
        //vardump($FieldConfig);
        //die;
        if(!empty($saveData['Data']['Content']['_IndexType'])){
            foreach($saveData['Data']['Content']['_IndexType'] as $Field=>$Setting){
                if(!empty($Setting['Visibility'])){
                    $Indexes[$Field]['Visibility'] = $Setting['Visibility'];
                }
                if(!empty($Setting['Indexed'])){
                    $Indexes[$Field]['Indexed'] = $Setting['Indexed'];
                }
            }
        }
        foreach($saveData['Data']['Content']['_Field'] as $Field=>$Value){
            $saveData['Data']['Content']['_IndexType'][$Field] = $Indexes[$Field]['Indexed'].'_'.$Indexes[$Field]['Visibility'];
        }
    }
    
    if(!empty($saveData['Data']['Content']['_customJSLibrary'])){
        $newCFG['_CustomJSLibraries'] = $saveData['Data']['Content']['_customJSLibrary'];
    }
    if(!empty($saveData['Data']['Content']['_customCSSSource'])){
        $newCFG['_CustomCSSSource'] = $saveData['Data']['Content']['_customCSSSource'];
    }
    // Sanatize Stuff
    $saveData['Data']['Content']['_APICallName'] = sanitize_title($saveData['Data']['Content']['_APICallName']);
    //sanitize_title($title);

    $newCFG['Content'] = base64_encode(serialize($saveData['Data']['Content']));
    $newCFG['_interfaceType'] = 'Configured';
    $newCFG['_Application'] = $activeApp;
    $newCFG['_interfaceName'] = $saveData['Data']['Content']['_ReportTitle'];
    if(!empty($saveData['Data']['Content']['_SetMenuItem'])) {
        $newCFG['_isMenu'] = true;
    }else {
        $newCFG['_isMenu'] = false;
    }
    if(!empty($saveData['Data']['Content']['_SetDashboard'])) {
        $newCFG['_Dashboard'] = true;
    }else {
        $newCFG['_Dashboard'] = false;
    }
    if(!isset($saveData['Data']['Content']['_clusterLayout'])){
        $newCFG['_ReportDescription'] = $saveData['Data']['Content']['_ReportDescription'];
        $newCFG['_ReportExtendedDescription'] = $saveData['Data']['Content']['_ReportExtendedDescription'];
        $newCFG['Type'] = 'Plugin';
    }else{
        $newCFG['_ClusterTitle'] = $saveData['Data']['Content']['_ClusterTitle'];
        $newCFG['_ClusterDescription'] = $saveData['Data']['Content']['_ClusterDescription'];
        $newCFG['Type'] = 'Cluster';
    }


    $newCFG['_ItemGroup'] = $saveData['Data']['Content']['_ItemGroup'];
    $newCFG['_menuAccess'] = $saveData['Data']['Content']['_menuAccess'];
    if(!empty($saveData['Data']['Content']['_SetAdminMenu'])){
        $newCFG['_SetAdminMenu'] = $saveData['Data']['Content']['_SetAdminMenu'];
    }
    if(!empty($saveData['Data']['Content']['_Icon'])){
        $newCFG['_Icon'] = $saveData['Data']['Content']['_Icon'];
    }
    if(!empty($saveData['Data']['Content']['_ProcessImport'])){
        $imported = unserialize(base64_decode($saveData['Data']['_SerializedImport']));
        $imported['Content'] = base64_encode(serialize($imported['Content']));
        $imported['ID'] = $optionTitle;
        $imported['_Application'] = $newCFG['_Application'];        
        update_option($optionTitle, $imported);
        header('location: '.$_SERVER['REQUEST_URI'].'&interface='.$optionTitle);
        die;
    }

    // check Page Bindings
    if(!empty($saveData['Data']['Content']['_ItemBoundPage'])){
        //check previous binding and remove//
        $oldOptions = get_option($optionTitle);
        if(!empty($oldOptions)){
            // remove bindings if there are any
            $oldOptions = unserialize(base64_decode($oldOptions['Content']));
            if(!empty($oldOptions['_ItemBoundPage'])){
                delete_option('_dbtbinding_'.$oldOptions['_ItemBoundPage']);
            }
        }
        //check previous binding
        $OldBinding = get_option('_dbtbinding_'.$saveData['Data']['Content']['_ItemBoundPage']);
        if(!empty($OldBinding)){
            //unbind old interface
            $prvBindingCFG = get_option($OldBinding);
            $prvBindingCFG['Content'] = unserialize(base64_decode($prvBindingCFG['Content']));
            if($prvBindingCFG['Content']['_ItemBoundPage'] == $saveData['Data']['Content']['_ItemBoundPage']){
                $prvBindingCFG['Content']['_ItemBoundPage'] = 0;
                $prvBindingCFG['Content'] = base64_encode(serialize($prvBindingCFG['Content']));
                update_option($OldBinding, $prvBindingCFG);
            }
        }
        update_option('_dbtbinding_'.$saveData['Data']['Content']['_ItemBoundPage'], $optionTitle, false);
        $newCFG['_ItemBound'] = $saveData['Data']['Content']['_ItemBoundPage'];
    }else{
        $oldOptions = get_option($optionTitle);
        if(!empty($oldOptions)){
            // remove bindings if there are any
            $oldOptions = unserialize(base64_decode($oldOptions['Content']));
            if(!empty($oldOptions['_ItemBoundPage'])){
                delete_option('_dbtbinding_'.$oldOptions['_ItemBoundPage']);
            }
        }
        unset($newCFG['_ItemBound']);
    }


    update_option($optionTitle, $newCFG);
    update_option('_'.$activeApp.'_app', $app);
    //vardump($app);

    return $optionTitle;
    
}


?>
