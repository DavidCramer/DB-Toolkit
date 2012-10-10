<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

if ($handle = opendir(DBT_PATH.'fieldtypes')) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                if(file_exists(DBT_PATH.'fieldtypes/'.$entry.'/conf.php')){
                    if(file_exists(DBT_PATH.'fieldtypes/'.$entry.'/functions.php')){
                        include_once DBT_PATH.'fieldtypes/'.$entry.'/functions.php';
                    }                    
                }
            }
        }
}

function dbt_interface_VersionCheck(){

    $defaults = get_option('dbt_default_interface');
    if(empty($defaults)){
        $defaultdata = unserialize('a:63:{s:18:"_ReportDescription";s:0:"";s:5:"_slug";s:0:"";s:26:"_ReportExtendedDescription";s:0:"";s:10:"_shortCode";s:0:"";s:10:"_ItemGroup";s:0:"";s:12:"_ReportTitle";s:0:"";s:9:"_ViewMode";s:4:"list";s:9:"_redirect";s:0:"";s:15:"_customRedirect";s:0:"";s:13:"_baseTemplate";s:7:"__app__";s:11:"_main_table";s:16:"__unconfigured__";s:15:"_Items_Per_Page";s:2:"40";s:12:"_autoPolling";s:0:"";s:14:"_enableToolbar";s:1:"1";s:8:"_addItem";s:1:"1";s:12:"_addTemplate";s:13:"__interface__";s:14:"_enableFilters";s:1:"1";s:19:"_keywordSearchLabel";s:6:"Search";s:9:"_showEdit";s:1:"1";s:13:"_editTemplate";s:13:"__interface__";s:9:"_showView";s:1:"1";s:13:"_viewTemplate";s:13:"__interface__";s:11:"_showDelete";s:1:"1";s:11:"_showFooter";s:1:"1";s:12:"_submitClass";s:0:"";s:12:"_updateClass";s:0:"";s:12:"_cancelClass";s:0:"";s:10:"_listClass";s:0:"";s:10:"_formClass";s:0:"";s:13:"_toolBarClass";s:0:"";s:15:"_filterBarClass";s:0:"";s:16:"_insertEntryText";s:9:"Add Entry";s:16:"_updateEntryText";s:12:"Update Entry";s:15:"_insertFailText";s:20:"Error Creating Entry";s:15:"_updateFailText";s:20:"Error Updating Entry";s:11:"_submitText";s:6:"Submit";s:11:"_updateText";s:6:"Update";s:12:"_addItemName";s:9:"Add Entry";s:12:"_addItemText";s:9:"Add Entry";s:15:"_addItemSubText";s:3:"Add";s:15:"_addItemDivider";s:1:"/";s:13:"_editFormText";s:10:"Edit Entry";s:16:"_editFormSubText";s:4:"Edit";s:16:"_editFormDivider";s:1:"/";s:14:"_viewEntryText";s:10:"View Entry";s:17:"_viewEntrySubText";s:4:"View";s:17:"_viewEntryDivider";s:1:"/";s:14:"_noResultsText";s:14:"No Items Found";s:20:"_inlineNotifications";s:1:"1";s:21:"_visibilityPermission";s:4:"null";s:17:"_addingPermission";s:4:"null";s:15:"_editPermission";s:4:"null";s:17:"_deletePermission";s:4:"null";s:10:"_formWidth";s:3:"960";s:16:"_TemplateWrapper";s:0:"";s:14:"_TemplateClass";s:0:"";s:15:"_layoutTemplate";a:4:{s:7:"_Header";s:0:"";s:8:"_Content";a:4:{s:5:"_name";a:1:{i:0;s:22:"Template-503c76b778a4c";}s:7:"_before";a:1:{i:0;s:0:"";}s:8:"_content";a:1:{i:0;s:0:"";}s:6:"_after";a:1:{i:0;s:0:"";}}s:7:"_Footer";s:0:"";s:10:"_noResults";s:0:"";}s:3:"_ID";s:16:"dbt503c77c05f4e3";s:4:"_app";s:6:"people";s:9:"_basePost";i:950;s:12:"_addItemPost";i:951;s:13:"_editItemPost";i:952;s:13:"_viewItemPost";i:953;}');
        update_option('dbt_default_interface', $defaultdata);
    }
    

}
/*Config & Edit Functions*/


function dbt_tableSelector($ID, $Name, $Type, $Title, $Config){

    global $wpdb;
    

    $Return = "<select id=\"_".$ID."\" name=\"data[_".$Name."]\">";
        $Return .= "<option value=\"__unconfigured__\">Select Database Table</option>";
        $Return .= "<option value=\"__unconfigured__\">---------------------</option>";

    //$Databases= $wpdb->get_results("SHOW DATABASES", ARRAY_N);
    //foreach($Databases as $Database){
        //if($Database[0] !== 'information_schema'){
            //$Tables = $wpdb->get_results("SHOW TABLES FROM `".$Database[0]."`", ARRAY_N);
            $Tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
                //$Return .= "<optgroup label=\"".$Database[0]."\">";
                $dbtTables = array();
                $wpTables = array();
                $otherTables = array();
                $wpsystables = array( 'posts', 'comments', 'links', 'options',
                    'postmeta','terms', 'term_taxonomy', 'term_relationships',
                    'commentmeta','users', 'usermeta','blogs', 'signups',
                    'site', 'sitemeta', 'sitecategories', 'registration_log',
                    'blog_versions' );
                foreach($wpsystables as $systable){
                    $systemTables[] = $wpdb->prefix.$systable;
                }
                foreach($Tables as $Table){
                    //$Return .= "<option value=\"`".$Database[0]."`.`".$Table[0]."`\">&nbsp;&nbsp;- ".$Table[0]." </option>";
                        $sel = '';
                        if(!empty($Config['_main_table'])){
                            if($Config['_main_table'] == "`".$Table[0]."`"){
                                $sel = 'selected="selected"';
                            }
                        }
                        if(substr($Table[0], strlen($Table[0])-4) == '_dbt'){
                            $dbtTables[] = "<option value=\"`".$Table[0]."`\" ".$sel.">&nbsp;- ".substr($Table[0], 0, strlen($Table[0])-4)." </option>";
                        }elseif(in_array($Table[0], $systemTables)){
                            $wpTables[] = "<option value=\"`".$Table[0]."`\" ".$sel.">&nbsp;- ".$Table[0]." </option>";
                        }else{
                            $otherTables[] = "<option value=\"`".$Table[0]."`\" ".$sel.">&nbsp;- ".$Table[0]." </option>";
                        }
                }
                
                //$Return .= "</optgroup>";
            //}
    //    }
                if(!empty($dbtTables)){
                    $Return .= "<optgroup label=\"DB-Toolkit\">";
                        $Return .= implode('', $dbtTables);
                    $Return .= "</optgroup>";
                }
                if(!empty($wpTables)){
                    $Return .= "<optgroup label=\"WordPress\">";
                        $Return .= implode('', $wpTables);
                    $Return .= "</optgroup>";
                }
                if(!empty($otherTables)){
                    $Return .= "<optgroup label=\"Other\">";
                        $Return .= implode('', $otherTables);
                    $Return .= "</optgroup>";
                }
                
    $Return .= "</select> <input type=\"button\" id=\"createNewTable\" value=\"New Table\" />" ;
return $Return;
}

function dbt_createNewTable($TableName){
    
    $user = wp_get_current_user();
    if(in_array('administrator', $user->roles)){
        global $wpdb;
        
        $table = $wpdb->prefix.sanitize_key($TableName).'_dbt';
        $sql = "CREATE TABLE `".$table."` (
          id int(11) NOT NULL AUTO_INCREMENT,
          UNIQUE KEY id (id)
        );";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $res = dbDelta($sql);
        if(!empty($res['`'.$table.'`'])){
            echo dbt_tableSelector('main_table', 'main_table', 'pushloaded', 'Database Table', array('_main_table'=>'`'.$table.'`'));
        }
    }
    
    die;
        
}

// Builds the fieldtype options panel.
function dbt_fieldTypeConfig($options, $field, $config=false){
    foreach($options as $key=>$option){
        echo dbt_configOption($key.'_'.$field, $key.'_'.$field, $option['type'], $option['label'], $config, $option['help']);
    }
    
}

function dbt_addSortingField($Table, $Config = false, $Index = false){
    global $wpdb, $footerscripts;
    
    $Fields = $wpdb->get_results("SHOW FIELDS FROM ".$Table, ARRAY_A);
    $ID = uniqid('sorting_');
    $Return = "<div id=\"".$ID."\" class=\"clear dbt_configOption\">";
        $Return .= "<div style=\"float:left;\">";
            $Return .= "Field: <select name=\"data[_sorting][Field][]\" >";
            foreach($Fields as $Field){
                $Sel = '';
                if(!empty($Config['_sorting']['Field'][$Index])){
                    if($Config['_sorting']['Field'][$Index] == $Field['Field']){
                        $Sel = 'selected="selected"';
                    }
                }
                $Return .= "<option value=\"".$Field['Field']."\" ".$Sel.">".$Field['Field']."</option>";
            }
            $Return .= "</select>";
            
                $Asc = '';
                if(!empty($Config['_sorting']['Field'][$Index])){
                    if($Config['_sorting']['Field'][$Index] == 'ASC'){
                        $Asc = 'selected="selected"';
                    }
                }
                $Desc = '';
                if(!empty($Config['_sorting']['Direction'][$Index])){
                    if($Config['_sorting']['Direction'][$Index] == 'DESC'){
                        $Desc = 'selected="selected"';
                    }
                }

            $Return .= " Direction: <select name=\"data[_sorting][Direction][]\" >";
                $Return .= "<option value=\"ASC\" ".$Asc.">Ascending</option>";
                $Return .= "<option value=\"DESC\" ".$Desc.">Descending</option>";
            $Return .= "</select>";

        $Return .= "</div> ";
        $Return .= "<div class=\"fbutton\" style=\"padding: 1px 1px 1px 7px; \">";
        $Return .= "<a class=\"button\" style=\"padding: 3px 4px\" onclick=\"jQuery('#".$ID."').remove();\">";
        $Return .= "<span class=\"icon-remove\"></span>";
        $Return .= "</a>";
        $Return .= "</div>";
    $Return .= "</div>";

    echo $Return;
}
function dbt_saveApp($Data){

    $appID = sanitize_title($Data['_appTitle']);
    $apps = get_option('dt_int_Apps');
    $apps[$appID]['name'] = $Data['_appTitle'];
    $apps[$appID]['description'] = $Data['_appDesc'];
    if(empty($Data['_state'])){
        $apps[$appID]['state'] = 'draft';
    }
    update_option('dt_int_Apps', $apps);
    flush_rewrite_rules();
    return $appID;
}
function dbt_updateInterface($Data){
    global $wpdb;
    
    $allApps = get_option('dt_int_Apps');
    $currentApp = get_option('_dbt_activeApp');
    $app = get_option('_'.$currentApp.'_app');
    if(empty($app['name'])){
        $app['name'] = $allApps[$currentApp]['name'];
    }
    if(empty($app['state'])){
        $app['state'] = $allApps[$currentApp]['state'];
    }
    
    if(empty($app['baseTemplate'])){
        $app['baseTemplate'] = 'default';
    }
    
    //dump($allApps);
    $postsArray= array();
    $appPost = array(
     'post_title' => $app['name'],
     //'post_content' => $ID,
     'post_status' => $app['state'],
     'comment_status' => 'closed',
     'ping_status' => 'closed',
     'post_name' => $currentApp,
     'post_author' => get_current_user_id(),
     'post_type' => 'page'
    );
    if(empty($app['basePage'])){
        $app['basePage'] = 0;
    }
    if(!get_post_meta($app['basePage'], '_dbt_app_page', true)){
        $app['basePage'] = wp_insert_post($appPost);
    }else{
        $appPost['ID'] = $app['basePage'];
        $app['basePage'] = wp_update_post($appPost);
    }
    
    $postsArray[] = $app['basePage'];
    
    switch ($Data['_baseTemplate']){
        case '__app__':
            $baseTemplate = $app['baseTemplate'];
            break;
        default:
            $baseTemplate = $Data['_baseTemplate'];
            break;
    }

    switch ($Data['_addTemplate']){
        case '__app__':
            $addTemplate = $app['baseTemplate'];
            break;
        case '__interface__':
            $addTemplate = $baseTemplate;
            break;
        default:
            $add§Template = $Data['_addTemplate'];
            break;
    }
    switch ($Data['_editTemplate']){
        case '__app__':
            $editTemplate = $app['baseTemplate'];
            break;
        case '__interface__':
            $editTemplate = $baseTemplate;
            break;
        default:
            $editTemplate = $Data['_editTemplate'];
            break;
    }
    switch ($Data['_viewTemplate']){
        case '__app__':
            $viewTemplate = $app['baseTemplate'];
            break;
        case '__interface__':
            $viewTemplate = $baseTemplate;
            break;
        default:
            $viewTemplate = $Data['_viewTemplate'];
            break;
    }
    
    if(empty($Data['_slug'])){
        $Data['_slug'] = sanitize_title($Data['_ReportDescription']);
    }
    if(empty($Data['_ID'])){
        $ID = uniqid('dbt');
        $Data['_ID'] = $ID;
        $app['interfaces'][$ID] = 'read';
        $app['slugs'][$Data['_slug']] = $ID;
        update_option('_'.$currentApp.'_app', $app);
    }else{
        $ID = $Data['_ID'];
        $app['slugs'][$Data['_slug']] = $ID;
        $app['interfaces'][$ID] = 'read';
        //$keys = 
        update_option('_'.$currentApp.'_app', $app);
        $ID = $Data['_ID'];
    }
    $Data['_app'] = $currentApp;
    if(!empty($app['landing'])){
        $landing = $app['landing'];
    }else{
        $landing = 'page';
    }
    update_post_meta($app['basePage'], '_dbt_app_page', $landing);
    update_post_meta($app['basePage'], '_wp_page_template', $app['baseTemplate']);

    $oldConfig = get_option($ID);
    /*if(!empty($Data['_ItemGroup'])){
        $categoryPost = array(
         'post_title' => $Data['_ItemGroup'],
         //'post_content' => $ID,
         'post_status' => $app['state'],
         'comment_status' => 'closed',
         'ping_status' => 'closed',
         'post_name' => sanitize_title($Data['_ItemGroup']),
         'post_author' => get_current_user_id(),
         'post_type' => 'page',
         'post_parent' => $app['basePage']
        );
        if(empty($oldConfig['_categoryPost'])){
            $oldConfig['_categoryPost'] = 0;
        }
        if(!get_post_meta($oldConfig['_categoryPost'], '_dbt_app_page', true)){
            $Data['_categoryPost'] = wp_insert_post($categoryPost);
        }else{
            $categoryPost['ID'] = $oldConfig['_categoryPost'];
            $Data['_categoryPost'] = wp_update_post($categoryPost);
        }
        $CategoryPostID = $Data['_categoryPost'];
        $postsArray[] = $Data['_categoryPost'];
        update_post_meta($Data['_categoryPost'], '_dbt_app_page', $landing);
        update_post_meta($Data['_categoryPost'], '_wp_page_template', $app['baseTemplate']);
    }else{
        if(!empty($oldConfig['_categoryPost'])){
            delete_post_meta($oldConfig['_categoryPost'], '_dbt_app_page');
            delete_post_meta($oldConfig['_categoryPost'], '_wp_page_template');
            wp_update_post($oldConfig['_categoryPost'], true);
        }*/
        $CategoryPostID = $app['basePage'];
    //}
    
    $interfacePost = array(
     'post_title' => $Data['_ReportDescription'],
     //'post_content' => $ID,
     'post_status' => $app['state'],
     'comment_status' => 'closed',
     'ping_status' => 'closed',
     'post_name' => $Data['_slug'],
     'post_author' => get_current_user_id(),
     'post_type' => 'page',
     'post_parent' => $CategoryPostID
    );

    if(empty($oldConfig['_basePost'])){
        $oldConfig['_basePost'] = 0;
    }
    if(!get_post_meta($oldConfig['_basePost'], '_dbt_app_page', true)){
        $Data['_basePost'] = wp_insert_post($interfacePost);
    }else{
        $interfacePost['ID'] = $oldConfig['_basePost'];
        $Data['_basePost'] = wp_update_post($interfacePost);
    }
    $postsArray[] = $Data['_basePost'];
    update_post_meta($Data['_basePost'], '_dbt_app_page', $ID);
    update_post_meta($Data['_basePost'], '_wp_page_template', $baseTemplate);
    
    if(!empty($Data['_addItem'])){
        $addItemPost = array(
         'post_title' => $Data['_addItemName'],
         //'post_content' => 'form',
         'post_status' => $app['state'],
         'comment_status' => 'closed',
         'ping_status' => 'closed',
         'post_name' => sanitize_title($Data['_addItemName']),
         'post_author' => get_current_user_id(),
         'post_type' => 'page',
         'post_parent' => $Data['_basePost']
        );
        if(empty($oldConfig['_addItemPost'])){
            $oldConfig['_addItemPost'] = 0;
        }
        if(!get_post_meta($oldConfig['_addItemPost'], '_dbt_app_page', true)){
            $Data['_addItemPost'] = wp_insert_post($addItemPost);
        }else{
            $addItemPost['ID'] = $oldConfig['_addItemPost'];
            $Data['_addItemPost'] = wp_update_post($addItemPost);
        }
        $postsArray[] = $Data['_addItemPost'];
        update_post_meta($Data['_addItemPost'], '_dbt_app_page', $ID);
        update_post_meta($Data['_addItemPost'], '_dbt_app_mode', 'form');
        update_post_meta($Data['_addItemPost'], '_wp_page_template', $addTemplate);
    }else{
        if(!empty($oldConfig['_addItemPost'])){
            wp_delete_post($oldConfig['_addItemPost'], true);
            delete_post_meta($Data['_addItemPost'], '_dbt_app_page');
            delete_post_meta($Data['_addItemPost'], '_dbt_app_mode');
            delete_post_meta($Data['_addItemPost'], '_wp_page_template');
        }
    }
    if(!empty($Data['_showEdit'])){
        $editItemPost = array(
         'post_title' => 'Edit Entry',
         //'post_content' => 'form',
         'post_status' => $app['state'],
         'comment_status' => 'closed',
         'ping_status' => 'closed',
         'post_name' => 'edit',
         'post_author' => get_current_user_id(),
         'post_type' => 'page',
         'post_parent' => $Data['_basePost']
        );

        if(empty($oldConfig['_editItemPost'])){
            $oldConfig['_editItemPost'] = 0;
        }
        if(!get_post_meta($oldConfig['_editItemPost'], '_dbt_app_page', true)){
            $Data['_editItemPost'] = wp_insert_post($editItemPost);
        }else{
            $editItemPost['ID'] = $oldConfig['_editItemPost'];
            $Data['_editItemPost'] = wp_update_post($editItemPost);
        }
        $postsArray[] = $Data['_editItemPost'];
        update_post_meta($Data['_editItemPost'], '_dbt_app_page', $ID);
        update_post_meta($Data['_editItemPost'], '_dbt_app_mode', 'form');
        update_post_meta($Data['_editItemPost'], '_wp_page_template', $editTemplate);
    }else{
        if(!empty($oldConfig['_editItemPost'])){
            wp_delete_post($oldConfig['_editItemPost'], true);
            delete_post_meta($Data['_editItemPost'], '_dbt_app_mode', 'form');
            delete_post_meta($Data['_editItemPost'], '_dbt_app_mode');
            delete_post_meta($Data['_editItemPost'], '_wp_page_template');
        }
    }
    if(!empty($Data['_showView'])){
        $viewItemPost = array(
         'post_title' => 'View Entry',
         //'post_content' => 'view',
         'post_status' => $app['state'],
         'comment_status' => 'closed',
         'ping_status' => 'closed',
         'post_name' => 'view',
         'post_author' => get_current_user_id(),
         'post_type' => 'page',
         'post_parent' => $Data['_basePost']
        );
        if(empty($oldConfig['_viewItemPost'])){
            $oldConfig['_viewItemPost'] = 0;
        }
        if(!get_post_meta($oldConfig['_viewItemPost'], '_dbt_app_page', true)){
            $Data['_viewItemPost'] = wp_insert_post($viewItemPost);
        }else{
            $viewItemPost['ID'] = $oldConfig['_viewItemPost'];
            $Data['_viewItemPost'] = wp_update_post($viewItemPost);
        }
        $postsArray[] = $Data['_viewItemPost'];
        update_post_meta($Data['_viewItemPost'], '_dbt_app_page', $ID);
        update_post_meta($Data['_viewItemPost'], '_dbt_app_mode', 'view');
        update_post_meta($Data['_viewItemPost'], '_wp_page_template', $viewTemplate);
    }else{
        if(!empty($oldConfig['_viewItemPost'])){
            wp_delete_post($oldConfig['_viewItemPost'], true);
            delete_post_meta($Data['_viewItemPost'], '_dbt_app_page');
            delete_post_meta($Data['_viewItemPost'], '_dbt_app_mode');
            delete_post_meta($Data['_viewItemPost'], '_wp_page_template');
        }
    }
    update_option($ID, $Data);
    if(!empty($postsArray)){
        $revistionCleanup = "DELETE a,b,c
                        FROM `".$wpdb->posts."` a
                        LEFT JOIN `".$wpdb->term_relationships."` b ON (a.ID = b.object_id)
                        LEFT JOIN `".$wpdb->postmeta."` c ON (a.ID = c.post_id)
                        WHERE a.post_type = 'revision' AND a.post_parent in (".implode(',',$postsArray).");";
        $wpdb->query($revistionCleanup);
    }
    
    // cleanup new fields and stuff
    $tableData = $wpdb->get_results("DESCRIBE ".$Data['_main_table'], ARRAY_A);
    $FieldConfig = array();
    foreach($tableData as $FieldData){
        $FieldConfig[$FieldData['Field']] = $FieldData['Type'];
    }
    //die;
    foreach($Data['_Field'] as $Field=>$Value){
        // Make Sure the Fields EXIST and is not a clone!
        if(substr($Field, 0, 2) != '__'){

            //Load Fields Base Type
            $type = explode('_', $Value);
            if(!empty($type[1])){
                if(file_exists(DBT_PATH.'fieldtypes/'.$type[0].'/conf.php')){
                    include (DBT_PATH.'fieldtypes/'.$type[0].'/conf.php');
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
                $alterLine = "ALTER TABLE ".$Data['_main_table']." ADD `".$Field."` ".$baseType." NULL ";
                $wpdb->query($alterLine);

            }else{
                // check types
                if($FieldConfig[$Field] != $baseType){
                    $alterLine = "ALTER TABLE ".$Data['_main_table']." CHANGE `".$Field."` `".$Field."` ".$baseType."";
                    //$wpdb->query($alterLine);
                }
            }
            unset($FieldConfig[$Field]);
        }
    }
    foreach($FieldConfig as $Field=>$set){
        $alterLine = "ALTER TABLE ".$Data['_main_table']." DROP COLUMN `".$Field."`";
        $wpdb->query($alterLine);
    }
    $indexData = $wpdb->get_results("SHOW INDEX FROM ".$Data['_main_table'], ARRAY_A);
    $indexlist = array();
    $iskey = '';
    foreach($indexData as $indexes){
        $indexlist[] = $indexes['Column_name'];
        if(empty($indexes['Non_unique']) && $indexes['Seq_in_index'] == 1){
            $iskey = $indexes['Column_name'];
        }        
    }
    if(!empty($Data['_IndexType'])){
        foreach($Data['_IndexType'] as $Field=>$set){
            if($Field != $iskey){
                if(!empty($set['Filter']) && !in_array($Field, $indexlist)){
                        $alterLine = "CREATE INDEX `".$Field."` ON ".$Data['_main_table']."(`".$Field."`)";
                        $wpdb->query($alterLine);
                }elseif(empty($set['Filter']) && in_array($Field, $indexlist)){
                        $alterLine = "DROP INDEX `".$Field."` ON ".$Data['_main_table'];
                        $wpdb->query($alterLine);
                }
            }

        }
    }
    return $ID;
}
function dbt_setBaseTemplate($template){
    $currentApp = get_option('_dbt_activeApp');
    $app = get_option('_'.$currentApp.'_app');
    $app['baseTemplate'] = $template;
    update_option('_'.$currentApp.'_app', $app);
    update_post_meta($app['basePage'], '_wp_page_template', $template);
    flush_rewrite_rules();
}
function dbt_setLanding($ID){
    $currentApp = get_option('_dbt_activeApp');
    $app = get_option('_'.$currentApp.'_app');
    $app['landing'] = $ID;
    update_post_meta($app['basePage'], '_dbt_app_page', $ID);
    update_option('_'.$currentApp.'_app', $app);
    flush_rewrite_rules();
}
function dbt_deleteInterface($ID){
    $currentApp = get_option('_dbt_activeApp');
    $app = get_option('_'.$currentApp.'_app');
    if(!empty($app['interfaces'][$ID])){
        unset($app['interfaces'][$ID]);
        $keys = array_keys($app['slugs'], $ID);
        if(!empty($keys)){
            foreach($keys as $slugid){
                unset($app['slugs'][$slugid]);
            }
        }
    }else{
        return false;
    }
    $Interface = get_option($ID);
    if(!empty($Interface['_basePost'])){
        wp_delete_post($Interface['_basePost'], true);
        delete_post_meta($Interface['_basePost'], '_dbt_app_page');
        delete_post_meta($Interface['_basePost'], '_dbt_app_mode');
    }
    if(!empty($Interface['_addItemPost'])){
        wp_delete_post($Interface['_addItemPost'], true);
        delete_post_meta($Interface['_addItemPost'], '_dbt_app_page');
        delete_post_meta($Interface['_addItemPost'], '_dbt_app_mode');
    }
    if(!empty($Interface['_editItemPost'])){
        wp_delete_post($Interface['_editItemPost'], true);
        delete_post_meta($Interface['_editItemPost'], '_dbt_app_page');
        delete_post_meta($Interface['_editItemPost'], '_dbt_app_mode');
    }
    if(!empty($Interface['_viewItemPost'])){
        wp_delete_post($Interface['_viewItemPost'], true);
        delete_post_meta($Interface['_viewItemPost'], '_dbt_app_page');
        delete_post_meta($Interface['_viewItemPost'], '_dbt_app_mode');
    }

    update_option('_'.$currentApp.'_app', $app);
    delete_option($ID);
    delete_option('filter_Lock_'.$ID);
    flush_rewrite_rules();
    return true;
}
function dbt_deleteApp($appID){

    $apps = get_option('dt_int_Apps');
    unset($apps[$appID]);
    update_option('dt_int_Apps', $apps);
    $app = get_option('_'.$appID.'_app');
    foreach($app['interfaces'] as $interface=>$read){
        delete_option($interface);
    }
    delete_option('_'.$appID.'_app');
    flush_rewrite_rules();

}
function dbt_setupTable($Table){
    global $wpdb, $footerscripts;
    
    $Fields = $wpdb->get_results("SHOW FIELDS FROM ".$Table, ARRAY_A);
    ob_start();
    ?>


    <?php
    $index = 1;
    foreach($Fields as $Field){
        $Config = false;
        if($index === 1){
            $Config['_primaryField'] = $Field['Field'];
        }
        dbt_buildFieldSetup($Field['Field'], $Config);
        $index++;
    }

    $output['html'] = ob_get_clean();
    $output['script'] = $footerscripts;
    

    return $output;
}
function dbt_buildFieldSetup($Field, $Config=false){
global $footerscripts;

$FieldType = 'auto';
$FieldTypeName = 'No Handler [Field Ignored]';
if(!empty($Config['_Field'][$Field])){
    $type = explode('_', $Config['_Field'][$Field]);
    if(count($type) == 2){
        $FieldType = $Config['_Field'][$Field];
        include DBT_PATH.'fieldtypes/'.$type[0].'/conf.php';
        $FieldTypeName = $FieldTypes[$type[1]]['name'];
    }
}
$primary = '';
if(!empty($Config['_primaryField'])){
    $primary = $Config['_primaryField'];
}
    
?>
<div class="itemField">
    <div class="dbt-elementItem" id="fieldSetup_<?php echo $Field; ?>">
        <span class="fbutton" style="float:left;">
            <a class="button fieldConfig" ref="<?php echo $Field; ?>">
                <span class="icon-cog"></span>
            </a>
        </span>

        <div class="dbt-elementInfoPanel start">
            <span id="<?php echo $Field.'_title'; ?>"><?php
            
            if(!empty($Config['_FieldTitle'][$Field])){
                echo $Config['_FieldTitle'][$Field];
            }else{
                echo $Field;
            }
            ?></span>
            <div class="dbt-elementInfoPanel description"><?php echo $Field; ?></div>
        </div>
        <div class="dbt-elementInfoPanel mid">
            <input type="hidden" id="fieldTypeSetting_<?php echo $Field; ?>" value="<?php echo $FieldType; ?>" name="data[_Field][<?php echo $Field; ?>]" />
            <span class="fbutton">
                <a id="setFieldConfig_<?php echo $Field; ?>" class="button fieldTypeConfig" ref="<?php echo $Field; ?>">
                    <span class="icon-cog"></span>
                </a>
            </span>
            <span class="fbutton">
                <a id="setFieldType_<?php echo $Field; ?>" class="button fieldTypeSelect" ref="<?php echo $Field; ?>">
                    <?php echo $FieldTypeName; ?>
                </a>
            </span>
        </div>
        <div class="dbt-elementInfoPanel last">
            <?php            
            echo dbt_toggleButton('displayTypeV_'.$Field, 'Visibility', $Field, 'Visible', $Config, 'icon-eye-open');
            echo dbt_toggleButton('displayTypeF_'.$Field, 'Filter', $Field, 'Searchable', $Config, 'icon-search');
            echo dbt_toggleButton('displayTypeU_'.$Field, 'Unique', $Field, 'Unique', $Config, 'icon-star');
            echo dbt_toggleButton('displayTypeR_'.$Field, 'Required', $Field, 'Required', $Config, 'icon-flag');
            echo dbt_toggleButton('displayTypeS_'.$Field, 'Sortable', $Field, 'Sortable', $Config, 'icon-random');
            echo dbt_toggleButton('passback_'.$Field, 'PassbackValue', $Field, 'Passback', $Config, 'icon-repeat');
            ?>            
        </div>
        <div class="dbt-elementInfoPanel primary" style="float:right;">
            <input type="radio" name="data[_primaryField]" value="<?php echo $Field; ?>" <?php if($Field == $primary){ echo 'checked="checked"'; } ?> />
        </div>
    </div>
    <div class="dbt-infopanel dbt-elementItem field">
        <div id="fieldConfigTray_<?php echo $Field; ?>" class="fieldBasic hidden">
            <h2 style="width:200px;">Configure <?php echo $Field; ?></h2>
            <?php
                $FieldTitle = $Field;
                if(!empty($Config['_FieldTitle'][$Field])){
                    $FieldTitle = $Config['_FieldTitle'][$Field];
                }
                $FieldCaption = '';
                if(!empty($Config['_FieldCaption'][$Field])){
                    $FieldCaption = $Config['_FieldCaption'][$Field];
                }
                $ListWidth = '';
                if(!empty($Config['_widthOverride'][$Field])){
                    $ListWidth = $Config['_widthOverride'][$Field];
                }
                $templateBefore = '';
                if(!empty($Config['_FieldTemplate'][$Field]['before'])){
                    $templateBefore = $Config['_FieldTemplate'][$Field]['before'];
                }
                $templateAfter = '';
                if(!empty($Config['_FieldTemplate'][$Field]['after'])){
                    $templateAfter = $Config['_FieldTemplate'][$Field]['after'];
                }

            ?>

            <div class="configControlForm">
                <div class="configControlLabel">
                    <label for="">Field Title</label>
                </div>
                <div class="configControlField">
                    <input type="text" name="data[_FieldTitle][<?php echo $Field; ?>]" value="<?php echo $FieldTitle; ?>" onkeyup="jQuery('#<?php echo $Field; ?>_title').html(this.value);">
                </div>
            </div>
            <div class="configControlForm">
                <div class="configControlLabel">
                    <label for="">Form Field Width</label>
                </div>
                <div class="configControlField">
                    <?php

                        $fieldWidth = '';
                        if(!empty($Config['_FormFieldWidth'][$Field])){
                            $fieldWidth = $Config['_FormFieldWidth'][$Field];
                        }


                    ?>
                    <select name="data[_FormFieldWidth][<?php echo $Field; ?>]" style="width:49%;">
                        <optgroup label="Preset Sizes">
                            <option value="">Auto</option>
                            <option value="input-mini" <?php if($fieldWidth == 'input-mini'){ echo 'selected="selected"';} ?>>Mini</option>
                            <option value="input-small" <?php if($fieldWidth == 'input-small'){ echo 'selected="selected"';} ?>>Small</option>
                            <option value="input-medium" <?php if($fieldWidth == 'input-medium'){ echo 'selected="selected"';} ?>>Medium</option>
                            <option value="input-large" <?php if($fieldWidth == 'input-large'){ echo 'selected="selected"';} ?>>Large</option>
                            <option value="input-xlarge" <?php if($fieldWidth == 'input-xlarge'){ echo 'selected="selected"';} ?>>XLarge</option>
                            <option value="input-xxlarge" <?php if($fieldWidth == 'input-xxlarge'){ echo 'selected="selected"';} ?>>XXLarge</option>
                        </optgroup><optgroup label="Incremental">
                            <option value="span1" <?php if($fieldWidth == 'span1'){ echo 'selected="selected"';} ?>>Span 1</option>
                            <option value="span2" <?php if($fieldWidth == 'span2'){ echo 'selected="selected"';} ?>>Span 2</option>
                            <option value="span3" <?php if($fieldWidth == 'span3'){ echo 'selected="selected"';} ?>>Span 3</option>
                            <option value="span4" <?php if($fieldWidth == 'span4'){ echo 'selected="selected"';} ?>>Span 4</option>
                            <option value="span5" <?php if($fieldWidth == 'span5'){ echo 'selected="selected"';} ?>>Span 5</option>
                            <option value="span6" <?php if($fieldWidth == 'span6'){ echo 'selected="selected"';} ?>>Span 6</option>
                            <option value="span7" <?php if($fieldWidth == 'span7'){ echo 'selected="selected"';} ?>>Span 7</option>
                            <option value="span8" <?php if($fieldWidth == 'span8'){ echo 'selected="selected"';} ?>>Span 8</option>
                            <option value="span9" <?php if($fieldWidth == 'span9'){ echo 'selected="selected"';} ?>>Span 9</option>
                            <option value="span10" <?php if($fieldWidth == 'span10'){ echo 'selected="selected"';} ?>>Span 10</option>
                            <option value="span11" <?php if($fieldWidth == 'span11'){ echo 'selected="selected"';} ?>>Span 11</option>
                            <option value="span12" <?php if($fieldWidth == 'span12'){ echo 'selected="selected"';} ?>>Span 12</option>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="clear"></div>
            
                <div class="configControlLabel">
                    <label for="">Caption</label>
                </div>
                <div class="configControlField">
                    <input type="text" name="data[_FieldCaption][<?php echo $Field; ?>]" value="<?php echo $FieldCaption; ?>" style="width:208px;">
                </div>
            
            <div class="configControlForm">
                <div class="configControlLabel">
                    <label for="">List Width</label>
                </div>
                <div class="configControlField">
                    <input type="text" name="data[_widthOverride][<?php echo $Field; ?>]" value="<?php echo $ListWidth; ?>">
                </div>
            </div>
            <div class="configControlForm">
                <div class="configControlLabel">
                    <label for="">List Justify</label>
                </div>
                <div class="configControlField">
                    <select name="data[_Justify][<?php echo $Field; ?>]">
                        <option value="left">Left</option>
                        <option value="Center">Center</option>
                        <option value="Right">Right</option>
                    </select>
                </div>
            </div>            

            
        </div>
        <div id="fieldTemplateTray_<?php echo $Field; ?>" class="fieldTemplate hidden">
            <h2>Field Template</h2>
            <div class="configControlLabel">
                <label for="">Before</label>
            </div>
            <div class="configControlField">
                <textarea name="data[_FieldTemplate][<?php echo $Field; ?>][before]" style="width:208px;"><?php echo $templateBefore; ?></textarea>
            </div>
            <div class="configControlLabel">
                <label for="">After</label>
            </div>
            <div class="configControlField">
                <textarea name="data[_FieldTemplate][<?php echo $Field; ?>][after]" style="width:208px;"><?php echo $templateAfter; ?></textarea>
            </div>
            <div class="confirmer hidden description configControlLabel">This is permanent, continue?</div>
            <span class="fbutton requester" onclick="jQuery(this).slideUp(180, function(){jQuery(this).parent().find('.confirmer').slideDown(180)});"><button class="button" type="button" >Delete Field</button></span>            
            <span class="fbutton confirmer hidden" onclick="jQuery(this).parent().find('.confirmer').slideUp(180, function(){jQuery(this).parent().find('.requester').slideDown(180)});"><button class="button" type="button" >Cancel</button></span>
            <span class="fbutton confirmer hidden" onclick="jQuery(this).parent().parent().parent().fadeOut(380, function(){jQuery(this).remove()});"><button class="button" type="button" >Delete Field</button></span>
        </div>
    </div>
    <div class="dbt-infopanel dbt-elementItem type">
        <div id="fieldTypeSelectTray_<?php echo $Field; ?>" class="hidden">
            <h2>Select FieldType</h2>
            <div id="">               
                <?php
                    dbt_getFieldTypes($Field, $Config);
                ?>
            </div>
        </div>
        <div id="fieldTypeConfigTray_<?php echo $Field; ?>" class="hidden">
            <h2>Configure FieldType</h2>
            <div id="fieldTypeConfigPanel_<?php echo $Field; ?>">
            <?php
            if(!empty($Config['_Field'])){
                $FieldType = explode('_', $Config['_Field'][$Field]);
                if(file_exists(DBT_PATH.'fieldtypes/'.$FieldType[0].'/conf.php')){
                    include DBT_PATH.'fieldtypes/'.$FieldType[0].'/conf.php';
                    include_once DBT_PATH.'fieldtypes/'.$FieldType[0].'/functions.php';
                    if(!empty($FieldType[1])){
                        if(!empty($FieldTypes[$FieldType[1]]['options'])){
                            echo dbt_fieldTypeConfig($FieldTypes[$FieldType[1]]['options'],$Field, $Config);
                        }else{
                            echo 'This fieldtype has no config options.';
                        }
                    }else{
                        echo 'This fieldtype will be ignored.';
                    }

                }
            }

            ?>
            </div>
        </div>
    </div>
</div>

<?php

$footerscripts .= "
    if(jQuery(\"#formField_".$Field."\").length == 0){
        jQuery('#formFields').append('<div id=\"formField_".$Field."\" class=\"button formFieldElement\"><i class=\"icon-remove formElementRemove\" style=\"cursor:pointer;\"></i> ".$FieldTitle."<input class=\"fieldLocationCapture\" type=\"hidden\" value=\"\" name=\"data[_fieldLayout][".$Field."]\" style=\"width:50px;\" /></div>');
    }
";

}

function dbt_buildNewFieldSetup($Field){
    global $footerscripts;
    
    $Field = sanitize_key($Field);
    
    ob_start();
    dbt_buildFieldSetup($Field, false);

    $out['html'] = ob_get_clean();
    $out['script'] = $footerscripts;
    
    return $out;    
}

function dbt_loadInterfaceFields($Config){
    if(empty($Config)){
        return 'Please select a table to start.';
    }
    global $footerscripts;
    if(!empty($Config['_Field'])){
        ?>
<?php
        foreach($Config['_Field'] as $Field=>$FieldType){
            dbt_buildFieldSetup($Field, $Config);
        }        
    }
    
        $footerscripts .= "
            
        jQuery(\"#dbt_container\").on('click','.fieldConfig',function(){
            jQuery(this).toggleClass('active');
            jQuery(\"#fieldConfigTray_\"+jQuery(this).attr('ref')).toggle();
            jQuery(\"#fieldTemplateTray_\"+jQuery(this).attr('ref')).toggle();
        });
        jQuery(\"#dbt_container\").on('click', '.fieldTypeSelect', function(){
            jQuery(this).toggleClass('active');
            jQuery(\"#setFieldConfig_\"+jQuery(this).attr('ref')).removeClass('active');

            jQuery(\".fieldTypes_types .button\").removeClass('active');
            jQuery(\".fieldTypes_cat .button\").removeClass('active');
            jQuery(\"#fieldTypeSelectTray_\"+jQuery(this).attr('ref')).toggle();
            jQuery(\"#fieldTypeConfigTray_\"+jQuery(this).attr('ref')).hide();
        });
        jQuery(\"#dbt_container\").on('click', '.fieldTypeConfig', function(){
            jQuery(this).toggleClass('active');
            jQuery(\"#setFieldType_\"+jQuery(this).attr('ref')).removeClass('active');
            jQuery(\"#fieldTypeSelectTray_\"+jQuery(this).attr('ref')).hide();
            jQuery(\"#fieldTypeConfigTray_\"+jQuery(this).attr('ref')).toggle();
        });";    
        


}
function dbt_getFieldTypes($Field, $Config){
    global $footerscripts;

    $leftPanel = '<div class="fieldTypes_cat">';
    $rightPanel = '<div class="fieldTypes_types">';    
    if ($handle = opendir(DBT_PATH.'fieldtypes')) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                if(file_exists(DBT_PATH.'fieldtypes/'.$entry.'/conf.php')){
                    include DBT_PATH.'fieldtypes/'.$entry.'/conf.php';
                    if(!empty($FieldTypes)){
                        $leftPanel .= '<div class="fieldTypeButton_'.$Field.' button" ref="'.$entry.'"><span class="icon-folder-open"></span> '.$FieldTypeTitle.'</div>';
                        $rightPanel .= '<div class="fieldTypeHolder hidden" id="'.$Field.'_fieldTypes_'.$entry.'">';
                            foreach($FieldTypes as $FieldType=>$FieldTypeName){                                                                
                                $rightPanel .= '<div class="button fieldTypeSelect_'.$Field.'" ref="'.$entry.'_'.$FieldType.'"><span class="icon-ok-circle"></span> '.$FieldTypeName['name'].'</div>';
                            }
                        $rightPanel .= '</div>';
                        
                    }
                }
            }
        }
        closedir($handle);
    }
    $leftPanel .= '</div>';
    $rightPanel .= '</div>';
    

    echo $leftPanel;
    echo $rightPanel;

    $footerscripts .= "

        jQuery(\".fieldTypeButton_".$Field."\").hover(function(){
            jQuery(\".fieldTypes_cat .button\").removeClass('active');
            jQuery(this).addClass('active');
            jQuery(\".fieldTypeHolder\").hide();
            jQuery(\"#".$Field."_fieldTypes_\"+jQuery(this).attr('ref')).show();
        });

        jQuery(\".fieldTypeSelect_".$Field."\").click(function(){
            jQuery(\".fieldTypes_types .button\").removeClass('active');
            jQuery(this).addClass('active');
            jQuery(\"#fieldTypeSetting_".$Field."\").val(jQuery(this).attr('ref'));
            jQuery(\"#setFieldType_".$Field."\").html(jQuery(this).html());
            jQuery(\"#fieldTypeSelectTray_".$Field."\").hide();
            jQuery(\"#setFieldConfig_".$Field."\").toggleClass('active');
            jQuery(\"#fieldTypeConfigTray_".$Field."\").show();
            jQuery(\"#setFieldType_".$Field."\").toggleClass('active');
            jQuery(\"#fieldTypeConfigPanel_".$Field."\").html('Loading FieldType Config');

                //dbt_loadfile(filename, filetype)
                var type = jQuery(this).attr('ref').split('_');
                dbt_loadfile('".DBT_URL."fieldtypes/'+type[0]+'/javascript.php', 'js');

                dbt_ajaxCall('dbt_loadFieldTypeConfig', '".$Field."', jQuery(this).attr('ref'), jQuery('#_main_table').val(), function(o){
                    jQuery(\"#fieldTypeConfigPanel_".$Field."\").html(o);
                });

        });

    ";

}
function dbt_loadFieldTypeConfig($Field, $FieldType, $Table, $Config = false){
    $type = explode('_', $FieldType);
    include_once DBT_PATH.'fieldtypes/'.$type[0].'/conf.php';
    if($FieldTypes[$type[1]]['func'] != 'null'){
        echo $FieldTypes[$type[1]]['func']($Field, $Table, $Config);
    }else{
        echo 'No config options available for this fieldtype';
    }
    //echo DBT_PATH.'fieldtypes/'.$type[0].'/'.$type[1];
}
function dbt_toggleButton($ID, $Name, $Field, $Title, $Config, $Icon=false, $straight = false){

    global $footerscripts;
    if(empty($Config)){
        $Config = array();
    }
    
    $sel = '';
    $active = '';
    if($Field === false){

        if(is_array($Name)){
            
            $preVal = $Config['_'.$Name[0]];
            $preName = implode('][', $Name);
            unset($Name[0]);
            foreach($Name as $part){
                if(!empty($preVal[$part])){
                    $preVal = $preVal[$part];
                }
            }
            if(!empty($preVal)){
                $sel = 'checked="checked"';
                $active = 'active';
            }
            $Name = $preName;
            
            
        }else{
            if(!empty($Config['_'.$Name])){
                $sel = 'checked="checked"';
                $active = 'active';
            }
        }
    }else{
        if(!empty($Config['_IndexType'][$Field][$Name])){
            $sel = 'checked="checked"';
            $active = 'active';
        }
    }
    
    $Return = '';
    $Return .= "<span id=\"".$ID."_button\" class=\"fbutton\">\n";
    //$Return .= "    <a href=\"#\">\n";
    $Return .= "        <div id=\"".$ID."_action\" class=\"button ".$active."\">\n";
    $titleView = "hidden";
    if(!empty($straight)){
        $titleView = "";
    }
    $Return .= "            <span class=\"".$Icon."\"></span> <span class=\"".$titleView."\">".$Title."</span>\n";
    $Return .= "        </div>\n";
    //$Return .= "    </a>\n";
    $Return .= "</span>\n";
    if($Field === false){
        $Return .= "<span class=\"hidden\"><input type=\"checkbox\" name=\"data[_".$Name."]\" id=\"".$ID."\" value=\"1\" ".$sel."/></span>\n";
    }else{
        $Return .= "<span class=\"hidden\"><input type=\"checkbox\" name=\"data[_IndexType][".$Field."][".$Name."]\" id=\"".$ID."\" value=\"1\" ".$sel."/></span>\n";
    }

    $footerscripts .= "        
        jQuery(\"#".$ID."_button\").click(function(){

            if(jQuery('#".$ID."').is(':checked') == false){
                jQuery('#".$ID."').attr('checked', 'checked');
                jQuery('#".$ID."_action').addClass('active');
                
            }else{
                jQuery('#".$ID."').removeAttr('checked');
                jQuery('#".$ID."_action').removeClass('active');
            }
        });
        ";
    if(empty($straight)){
       $footerscripts .= "
       jQuery(\"#".$ID."_button\").hover(function(){
            jQuery(this).find('.hidden').show();
       },function(){
            jQuery(this).find('.hidden').hide();
       });

    ";
    }

return $Return;
    

}
function dbt_listFormProcessors($Config){


    echo '<select id="formProcessors">';
    echo '<option value=""></option>';
    if ($handle = opendir(DBT_PATH.'processors/form')) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                if(file_exists(DBT_PATH.'processors/form/'.$entry.'/conf.php')){
                    include_once DBT_PATH.'processors/form/'.$entry.'/conf.php';
                    echo '<option value="'.$entry.'">'.$Title.'</option>';
                }
            }
        }
        closedir($handle);
    }
    echo '</select>';

}
function dbt_listlistProcessors($Config){


    echo '<select id="listProcessors">';
    echo '<option value=""></option>';
    if ($handle = opendir(DBT_PATH.'processors/list')) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                if(file_exists(DBT_PATH.'processors/list/'.$entry.'/conf.php')){
                    include_once DBT_PATH.'processors/list/'.$entry.'/conf.php';
                }
                echo '<option value="'.$entry.'">'.$ViewTitle.'</option>';
            }
        }
        closedir($handle);
    }
    echo '</select>';

}

function dbt_loadFormProcessor($Processor, $Table, $ProcessorID = false, $Config = false){

    global $footerscripts;

    if(!file_exists(DBT_PATH.'processors/form/'.$Processor.'/conf.php')){
        return 'Ye, that\'s not going to happen.';
    }
    if(file_exists(DBT_PATH.'processors/form/'.$Processor.'/functions.php')){
        include_once DBT_PATH.'processors/form/'.$Processor.'/functions.php';
    }
    include DBT_PATH.'processors/form/'.$Processor.'/conf.php';


    if(empty($ProcessorID)){
        $ProcessorID = uniqid('fp');
    }
    $Return = "<div class=\"itemField\" id=\"formProcessor".$ProcessorID."\">\n";
    $Return .= "<div class=\"dbt-elementItem\" >\n";
        $Return .= "<span id=\"edit_".$ProcessorID."\" class=\"fbutton buttonFormProcessor\"><span class=\"button\"><i class=\"icon-pencil\"></i></span></span>";
        $Return .= "<div class=\"dbt-elementInfoPanel\" style=\"width:360px;\">\n";
            $Return .= "<input type=\"hidden\" name=\"data[_formprocessor][".$ProcessorID."][processor]\" value=\"".$Processor."\" />";
            $Return .= "<div class=\"title\">".$Title."</div>\n";
            $Return .= "<div class=\"dbt-elementInfoPanel description\" style=\"width:360px;\">".$Desc."</div>\n";
        $Return .= "</div>\n";
        
        $Return .= "<div class=\"dbt-elementInfoPanel mid\">\n";

            // buttons            
            $Return .= dbt_toggleButton('insert_'.$ProcessorID, array('formprocessor',$ProcessorID,'insert'), false, 'On Insert', $Config, 'icon-circle-arrow-right', true);
            $Return .= dbt_toggleButton('update_'.$ProcessorID, array('formprocessor',$ProcessorID,'update'), false, 'On Update', $Config, 'icon-ok-sign', true);
            $Return .= dbt_toggleButton('delete_'.$ProcessorID, array('formprocessor',$ProcessorID,'delete'), false, 'On Delete', $Config, 'icon-remove-sign', true);

        $Return .= "</div>\n";
        $Return .= "<div class=\"dbt-elementInfoPanel last\">\n";

            // buttons
            
            $Return .= "<span id=\"remove_".$ProcessorID."\" class=\"fbutton butonFormProcessor\"><span class=\"button\"><i class=\"icon-remove-sign\"></i></span></span>";

            $Return .= "<div id=\"confirm_".$ProcessorID."\" class=\"dbt-elementInfoPanel last buttons_".$ProcessorID."\" style=\"display:none;\">\n";
                
                $Return .= "<span class=\"fbutton\"><a href=\"#\" onclick=\"jQuery('#formProcessor".$ProcessorID."').remove(); return false;\"><div class=\"button\"><span class=\"icon-ok\"></span></div></a></span>\n";
                $Return .= "<span class=\"fbutton\" id=\"ummno_".$ProcessorID."\"><a href=\"#\" class=\"confirm\" ><div class=\"button\"><span class=\"icon-share-alt\"></span> Cancel</div></a></span>\n";
            $Return .= "</div>\n";

            $footerscripts .= "
              jQuery('#edit_".$ProcessorID."').click(function(){

                jQuery('#processorConfig_".$ProcessorID."').slideToggle();

              });
                jQuery('#remove_".$ProcessorID."').click(function(){
                    jQuery('#remove_".$ProcessorID."').hide();
                    jQuery('#edit_".$ProcessorID."').hide();
                    jQuery('#confirm_".$ProcessorID."').slideToggle();
                });
                jQuery('#ummno_".$ProcessorID."').click(function(){
                    jQuery('#confirm_".$ProcessorID."').hide();
                    jQuery('#remove_".$ProcessorID."').slideToggle();
                    jQuery('#edit_".$ProcessorID."').slideToggle();
                });

            ";



        $Return .= "</div>\n";


    $Return .= "</div>\n";

    $Return .= "<div class=\"dbt-infopanel dbt-elementItem processor\">\n";
    $show = 'hidden';
    if(empty($Config)){
        $show = '';
    }
    $Return .= "    <div class=\"".$show."\" id=\"processorConfig_".$ProcessorID."\">\n";

        $func = 'config_'.$Processor;
        if(function_exists($func)){
            $Return .= $func($ProcessorID, $Table, $Config);
        }else{
            $Return .= 'This processor has no config options';
        }

    $Return .= "    </div>\n";
    $Return .= "</div>\n";

    $Return .= "</div>\n";



    $Output['html'] = $Return;
    $Output['scripts'] = $footerscripts;

    return $Output;


}
function dbt_loadListProcessor($Processor, $Table, $ProcessorID = false, $Config = false){

    global $footerscripts;

    if(!file_exists(DBT_PATH.'processors/list/'.$Processor.'/conf.php')){
        return 'Ye, that\'s not going to happen.';
    }
    if(file_exists(DBT_PATH.'processors/list/'.$Processor.'/functions.php')){
        include_once DBT_PATH.'processors/list/'.$Processor.'/functions.php';
    }
    include DBT_PATH.'processors/list/'.$Processor.'/conf.php';


    if(empty($ProcessorID)){
        $ProcessorID = uniqid('fp');
    }
    $Return = "<div class=\"itemField\" id=\"listProcessor".$ProcessorID."\">\n";
    $Return .= "<div class=\"dbt-elementItem\" >\n";
        $Return .= "<span id=\"edit_".$ProcessorID."\" class=\"fbutton buttonListProcessor\"><span class=\"button\"><i class=\"icon-pencil\"></i></span></span>";
        $Return .= "<div class=\"dbt-elementInfoPanel\" style=\"width:360px;\">\n";
            $Return .= "<input type=\"hidden\" name=\"data[_listprocessor][".$ProcessorID."][processor]\" value=\"".$Processor."\" />";
            $Return .= "<div class=\"title\">".$ViewTitle."</div>\n";
            $Return .= "<div class=\"dbt-elementInfoPanel description\" style=\"width:360px;\">".$ViewDesc."</div>\n";
        $Return .= "</div>\n";

        $Return .= "<div class=\"dbt-elementInfoPanel mid\">\n";

            // buttons
            $Return .= dbt_toggleButton('insert_'.$ProcessorID, array('listprocessor',$ProcessorID,'insert'), false, 'End-Point', $Config, 'icon-exclamation-sign', true);
            

        $Return .= "</div>\n";
        $Return .= "<div class=\"dbt-elementInfoPanel last\">\n";

            // buttons

            $Return .= "<span id=\"remove_".$ProcessorID."\" class=\"fbutton butonFormProcessor\"><span class=\"button\"><i class=\"icon-remove-sign\"></i></span></span>";

            $Return .= "<div id=\"confirm_".$ProcessorID."\" class=\"dbt-elementInfoPanel last buttons_".$ProcessorID."\" style=\"display:none;\">\n";

                $Return .= "<span class=\"fbutton\"><a href=\"#\" onclick=\"jQuery('#listProcessor".$ProcessorID."').remove(); return false;\"><div class=\"button\"><span class=\"icon-ok\"></span></div></a></span>\n";
                $Return .= "<span class=\"fbutton\" id=\"ummno_".$ProcessorID."\"><a href=\"#\" class=\"confirm\" ><div class=\"button\"><span class=\"icon-share-alt\"></span> Cancel</div></a></span>\n";
            $Return .= "</div>\n";

            $footerscripts .= "
              jQuery('#edit_".$ProcessorID."').click(function(){

                jQuery('#processorConfig_".$ProcessorID."').slideToggle();

              });
                jQuery('#remove_".$ProcessorID."').click(function(){
                    jQuery('#remove_".$ProcessorID."').hide();
                    jQuery('#edit_".$ProcessorID."').hide();
                    jQuery('#confirm_".$ProcessorID."').slideToggle();
                });
                jQuery('#ummno_".$ProcessorID."').click(function(){
                    jQuery('#confirm_".$ProcessorID."').hide();
                    jQuery('#remove_".$ProcessorID."').slideToggle();
                    jQuery('#edit_".$ProcessorID."').slideToggle();
                });

            ";



        $Return .= "</div>\n";


    $Return .= "</div>\n";

    $Return .= "<div class=\"dbt-infopanel dbt-elementItem processor\">\n";
    $show = 'hidden';
    if(empty($Config)){
        $show = '';
    }
    $Return .= "    <div class=\"".$show."\" id=\"processorConfig_".$ProcessorID."\">\n";

        $func = 'config_'.$Processor;
        if(function_exists($func)){
            $Return .= $func($ProcessorID, $Table, $Config);
        }else{
            $Return .= 'This processor has no config options';
        }

    $Return .= "    </div>\n";
    $Return .= "</div>\n";

    $Return .= "</div>\n";



    $Output['html'] = $Return;
    $Output['scripts'] = $footerscripts;

    return $Output;


}
function dbt_configOption($ID, $Name, $Type, $Title, $Config, $Caption=false, $function = false) {

    $Return = '';

    switch ($Type) {
        case 'hidden':
            $Val = '';
            if (!empty($Config['_' . $Name])) {
                $Val = htmlentities($Config['_' . $Name]);
            }
            $Return .= '<input type="hidden" name="data[_' . $Name . ']" id="' . $ID . '" value="' . $Val . '" />';
            return $Return;
            break;
        case 'custom':
            if(!function_exists($function)){
                return;
            }
            $Val = '';
            if (!empty($Config['_' . $Name])) {
                $Val = htmlentities($Config['_' . $Name]);
            }
            $Return .= '<div class="dbt_configTitle">'.$Title.'</div> <div class="dbt_configField">'.$function($ID, $Name, $Type, $Title, $Config).'<div class="dbt_configHelp">'.$Caption.'</div></div>';
            break;
        case 'text':
        case 'textfield':
            $Val = '';
            if (!empty($Config['_' . $Name])) {
                $Val = htmlentities($Config['_' . $Name]);
            }
            $Return .= '<div class="dbt_configTitle">'.$Title.'</div> <div class="dbt_configField"><input type="text" class="regular-text" name="data[_' . $Name . ']" id="' . $ID . '" value="' . $Val . '" /><div class="dbt_configHelp">'.$Caption.'</div></div>';
            break;
        case 'textarea':
            $Val = '';
            if (!empty($Config['_' . $Name])) {
                $Val = htmlentities($Config['_' . $Name]);
            }
            $Return .= '<div class="dbt_configTitle">'.$Title . '</div> <textarea name="data[_' . $Name . ']" id="' . $ID . '" cols="70" rows="25">' . htmlentities($Val) . '</textarea><div class="dbt_configHelp">'.$Caption.'</div>';
            break;
        case 'radio':
            $parts = explode('|', $Title);
            $options = explode(',', $parts[1]);
            $Return .= '<div class="dbt_configTitle">'.$parts[0].'</div>';
            $index = 1;
            $Return .= '<div class="dbt_configField">';
            foreach ($options as $option) {
                $sel = '';
                if (!empty($Config['_' . $Name])) {
                    if ($Config['_' . $Name] == strtolower($option)) {
                        $sel = 'checked="checked"';
                    }
                }
                if (empty($Config)) {
                    if ($index === 1) {
                        $sel = 'checked="checked"';
                    }
                }

                $Return .= ' <div><input type="radio" name="data[_' . $Name . ']" id="' . $ID . '" value="' . strtolower($option) . '" ' . $sel . '/> <label for="' . $ID . '_' . $index . '">' . ucwords($option) . '</label><div class="dbt_configHelp">'.$Caption.'</div></div>';
                $index++;
            }
            $Return .= '</div>';
            break;
        case 'select':
        case 'dropdown':
            $parts = explode('|', $Title);
            $options = explode(',', $parts[1]);
            $Return .= '<div class="dbt_configTitle">'.$parts[0].'</div>';
            $index = 1;
            $Return .= '<div class="dbt_configField"><select name="data[_' . $Name . ']" id="' . $ID . '">';
            foreach ($options as $option) {
                $option = explode(';', $option);
                if(empty($option[1])){
                    $label = ucwords($option[0]);
                    $option = $option[0];                    
                }else{
                    $label = $option[0];
                    $option = $option[1];
                }
                $sel = '';
                if (!empty($Config['_' . $Name])) {
                    if ($Config['_' . $Name] == strtolower($option)) {
                        $sel = 'selected="selected"';
                    }
                }
                if (empty($Config)) {
                    if ($index === 1) {
                        $sel = 'selected="selected"';
                    }
                }

                $Return .= ' <option value="'. strtolower($option).'" ' . $sel . '>' . ucwords($label) . '</option>';
                $index++;
            }
            $Return .= '</select><div class="dbt_configHelp">'.$Caption.'</div></div>';
            break;
        case 'checkbox':
                
                $sel = '';
                if (!empty($Config['_' . $Name])) {
                    $sel = 'checked="checked"';
                }

                $Return .= '<div class="dbt_configTitle">'.$Title . '</div> <div class="dbt_configField"><input type="checkbox" name="data[_' . $Name . ']" id="' . $ID . '" value="1" ' . $sel . '/> <div class="dbt_configHelp">'.$Caption.'</div></div>';
                
            break;
        case 'permission':

                $Return .= '<div class="dbt_configTitle">'.$Title.'</div>';


                $Return .= '<div class="dbt_configField"><select name="data[_' . $Name . ']">';
                $sel = '';
                if (!empty($Config['_' . $Name])) {
                    if($Config['_' . $Name] == 'null'){
                        $sel = 'checked="checked"';
                    }
                }
                $Return .= '<option value="null" '.$sel.'>Public</option>';

                        global $wp_roles;
                        foreach($wp_roles->roles as $key=>$role){
                            $Return .= '<optgroup label="'.$role['name'].'">';
                            ksort($role['capabilities']);
                            foreach($role['capabilities'] as $cap=>$null){
                                $sel = '';
                                if($Config['_' . $Name] == $cap){
                                    $sel = 'selected="selected"';
                                }
                                $Return .= '<option value="'.$cap.'" '.$sel.'>'.$cap.'</option>';
                            }
                        }


                $Return .= '</select>';
                $Return .= '</div>';
            break;
    }

    return '<div class="dbt_configOption">' . $Return . '<div class="clear"></div></div>';
}


function dbt_addListRowTemplate($Default = false){



ob_start();
                $show = 'block';
                if(!empty($Default)){
                    $show = 'block';
                }
                $rowTemplateID = uniqid('Template-');
                $Name = $rowTemplateID;
                if(!empty($Default['_name'])){
                    $Name = $Default['_name'];
                }
                $Header = '';
                if(!empty($Default['_before'])){
                    $Header = $Default['_before'];
                }
                $Content = '';
                if(!empty($Default['_content'])){
                    $Content = $Default['_content'];
                }
                $Footer = '';
                if(!empty($Default['_after'])){
                    $Footer = $Default['_after'];
                }

            ?>

                <div class="admin_list_row3 table_sorter postbox" id="dt_<?php echo $rowTemplateID; ?>">
                    <button class="button" align="absmiddle" style="float: right; margin: 2px; " onclick="jQuery('#dt_<?php echo $rowTemplateID; ?>').remove(); return false;">Remove</button>
                    <button class="button" align="absmiddle" style="float: right; margin: 2px; " onclick="jQuery('.<?php echo $rowTemplateID; ?>').slideToggle(); return false;">Toggle</button>
                    <h3 class="fieldTypeHandle"><?php echo $Name; ?></h3>
                    <div style="display: <?php echo $show; ?>;" class="admin_config_panel <?php echo $rowTemplateID; ?>">
                        <strong>Template Name:</strong> <input type="text" name="data[_layoutTemplate][_Content][_name][]" value="<?php echo $Name; ?>" />
                    </div>


                    <div style="display: <?php echo $show; ?>;" class="admin_config_panel <?php echo $rowTemplateID; ?>">
                        <strong>Before</strong> <span class="description">Placed before the content loop.</span>
                    </div>
                    <div style="display: <?php echo $show; ?>;" class="admin_config_panel fieldBeforeAfter <?php echo $rowTemplateID; ?>">
                        <textarea id="<?php echo $rowTemplateID; ?>_before" class="layoutTextArea" name="data[_layoutTemplate][_Content][_before][]"><?php echo $Header; ?></textarea>
                        <a href="#" onclick="jQuery('#<?php echo $rowTemplateID; ?>_before').height('400px'); return false;">Larger</a> |
                        <a href="#" onclick="jQuery('#<?php echo $rowTemplateID; ?>_before').height('80px'); return false;">Smaller</a>
                    </div>


                    <div style="display: <?php echo $show; ?>;" class="admin_config_panel <?php echo $rowTemplateID; ?>">
                        <strong>Content</strong>
                        <span class="description">Repeated with every row/entry.</span>
                    </div>
                    <div style="display: <?php echo $show; ?>;" class="admin_config_panel fieldBeforeAfter <?php echo $rowTemplateID; ?>">
                        <textarea id="<?php echo $rowTemplateID; ?>_content" class="layoutTextAreaLarge" name="data[_layoutTemplate][_Content][_content][]"><?php echo $Content; ?></textarea>
                        <a href="#" onclick="jQuery('#<?php echo $rowTemplateID; ?>_content').height('600px'); return false;">Larger</a> |
                        <a href="#" onclick="jQuery('#<?php echo $rowTemplateID; ?>_content').height('180px'); return false;">Smaller</a>
                    </div>


                    <div style="display: <?php echo $show; ?>;" class="admin_config_panel <?php echo $rowTemplateID; ?>">
                        <strong>After</strong> <span class="description">Placed after the content loop.</span>
                    </div>
                    <div style="display: <?php echo $show; ?>;" class="admin_config_panel fieldBeforeAfter <?php echo $rowTemplateID; ?>">
                        <textarea id="<?php echo $rowTemplateID; ?>_after" class="layoutTextArea" name="data[_layoutTemplate][_Content][_after][]"><?php echo $Footer; ?></textarea>
                        <a href="#" onclick="jQuery('#<?php echo $rowTemplateID; ?>_after').height('400px'); return false;">Larger</a> |
                        <a href="#" onclick="jQuery('#<?php echo $rowTemplateID; ?>_after').height('80px'); return false;">Smaller</a>

                    </div>


                    <div style="clear:both"></div>
                </div>

<?php

return ob_get_clean();

}

function dbt_addListFieldTemplate($Field, $Default = false){

ob_start();

    $fieldTemplateID = uniqid('Field-');
    $Name = $rowTemplateID;

    $show = 'block';
    if(!empty($Default)){
        $show = 'none';
    }
    $before = '';
    $after = '';

    if(!empty($Default['_before'])){
        $before = $Default['_before'];
    }
    if(!empty($Default['_after'])){
        $after = $Default['_after'];
    }


?>

    <div class="admin_list_row3 table_sorter postbox" id="dt_<?php echo $fieldTemplateID; ?>">
        <input type="button" align="absmiddle" style="float: right; padding: 5px;" onclick="jQuery('#dt_<?php echo $fieldTemplateID; ?>').remove();" />
        <input type="button" align="absmiddle" style="float: right; padding: 5px;" onclick="jQuery('.<?php echo $fieldTemplateID; ?>').toggle();" />
        <h3 class="fieldTypeHandle"><?php echo $Field; ?></h3>
        <div style="display: <?php echo $show; ?>;" class="admin_config_panel <?php echo $fieldTemplateID; ?>"></div>
        <div style="display: <?php echo $show; ?>;" class="admin_config_panel <?php echo $fieldTemplateID; ?>"><strong>Before</strong></div>
        <div style="display: <?php echo $show; ?>;" class="admin_config_panel fieldBeforeAfter <?php echo $fieldTemplateID; ?>">
            <textarea class="layoutTextArea" name="data[_layoutTemplate][_Fields][<?php echo $Field; ?>][_before]" id="<?php echo $fieldTemplateID; ?>_before" ><?php echo $before; ?></textarea>
            <a href="#" onclick="jQuery('#<?php echo $fieldTemplateID; ?>_before').height('400px'); return false;">Larger</a> |
            <a href="#" onclick="jQuery('#<?php echo $fieldTemplateID; ?>_before').height('80px'); return false;">Smaller</a>
        </div>
        <div style="display: <?php echo $show; ?>;" class="admin_config_panel <?php echo $fieldTemplateID; ?>"><strong>After</strong></div>
        <div style="display: <?php echo $show; ?>;" class="admin_config_panel fieldBeforeAfter <?php echo $fieldTemplateID; ?>">
            <textarea class="layoutTextArea" name="data[_layoutTemplate][_Fields][<?php echo $Field; ?>][_after]" id="<?php echo $fieldTemplateID; ?>_after" ><?php echo $after; ?></textarea>
            <a href="#" onclick="jQuery('#<?php echo $fieldTemplateID; ?>_after').height('400px'); return false;">Larger</a> |
            <a href="#" onclick="jQuery('#<?php echo $fieldTemplateID; ?>_after').height('80px'); return false;">Smaller</a>

        </div>
        <div style="clear:both"></div>
    </div>



<?php

return ob_get_clean();

}

?>