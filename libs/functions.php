<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$footerscripts = '';
$headerscripts = '';


if(is_admin ()){
    require_once DBT_PATH.'libs/adminfunctions.php';    
}

require_once DBT_PATH.'libs/utilities.php';


function dbt_ajaxloadForm($ID){
    include_once DBT_PATH . 'libs/renderfunctions.php';

    $Config = get_option($ID);
    //vardump($Config);
    dbt_buildFormView($Config, 'form');
    exit;
}



/* Action Functions */
function dbt_query_vars($public_query_vars) {

        $public_query_vars[] = "app";
	$public_query_vars[] = "interface";
	$public_query_vars[] = "vars";

	return $public_query_vars;

}

function dbt_psudoPostTypes(){
    return;
    
    $apps = get_option('dt_int_Apps');    
    foreach($apps as $app=>$set){
        $appData = get_option('_'.$app.'_app');        
        $slug = $app;
        $landing = array_search($appData['landing'], $appData['slugs']);
        if(!empty($landing)){
            $slug= $landing;
        }
        $labels = array(
            'name' => _x( $set['name'], 'db-toolkit' ),
        );
        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => false,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug'=>$app),
            'capability_type' => 'page',
            'has_archive' => true,
            'hierarchical' => true,
            'menu_position' => null,
            'supports' => array( 'title', 'editor', 'page-attributes')
        );
        register_post_type( $app, $args );
    }
}
function dbt_add_rewrite_rules( $wp_rewrite ){
    return;
        global $wpdb;
        $apps = get_option('dt_int_Apps');

        foreach($apps as $app=>$set){
            $labels = array(
                'name' => _x( $app, 'db-toolkit' ),
                'singular_name' => _x( $app, 'db-toolkit' ),
                'add_new' => _x( $app, 'db-toolkit' ),
                'add_new_item' => _x( $app, 'db-toolkit' ),
                'edit_item' => _x( $app, 'db-toolkit' ),
                'new_item' => _x( $app, 'db-toolkit' ),
                'view_item' => _x( $app, 'db-toolkit' ),
                'search_items' => _x( $app, 'db-toolkit' ),
                'not_found' => _x( $app, 'db-toolkit' ),
                'not_found_in_trash' => _x( $app, 'db-toolkit' ),
                'parent_item_colon' => _x( $app, 'db-toolkit' ),
                'menu_name' => _x( $app, 'db-toolkit' ),
            );
            $args = array(
                'labels' => $labels,
                'public' => true,
                'publicly_queryable' => false,
                'show_ui' => false,
                'show_in_menu' => true,
                'query_var' => false,
                'rewrite' => false,
                'capability_type' => 'page',
                'has_archive' => false,
                'hierarchical' => false,
                'menu_position' => null,
                'supports' => array( 'title', 'editor')
                    );
           register_post_type( $app, $args );

            $setup = get_option('_'.$app.'_app');
            //vardump($setup);
            $basePage = '';
            if(!empty($setup['basePage'])){
                $pageID = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE `ID`='".$setup['basePage']."'");
                if(!empty($pageID)){
                    $basePage = 'page_id='.$pageID.'&';
                }
            }
            $new_rules = array(
                    $app.'/?$' => 'index.php?'.$basePage.'app='.
                    $app
            );
            $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
            $new_rules = array(
                    $app.'/([^/]+)/?$' => 'index.php?'.$basePage.'app='.
                    $app.'&interface='.
                    $wp_rewrite->preg_index(1)
            );
            $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
            $new_rules = array(
                    $app.'/([^/]+)/([^/]+)/?$' => 'index.php?'.$basePage.'app='.
                    $app.'&interface='.
                    $wp_rewrite->preg_index(1).'&vars='.
                    $wp_rewrite->preg_index(2)
            );
            $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
            if(!empty($setup['slugs'])){
                foreach($setup['slugs'] as $slug=>$id){
                    $infc = get_option($id);
                    if(!empty($infc['_basePage'])){

                        $pageID = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE `ID`='".$infc['_basePage']."'");
                        if(!empty($pageID)){
                            $basePage = 'page_id='.$pageID.'&';
                            $new_rules = array(
                                    $app.'/'.$slug.'/?$' => 'index.php?'.$basePage.'app='.
                                    $app.'&interface='.$id
                            );
                            $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
                            $new_rules = array(
                                    $app.'/'.$slug.'/([^/]+)/?$' => 'index.php?'.$basePage.'app='.
                                    $app.'&interface='.$id.'&vars='.
                                    $wp_rewrite->preg_index(1)
                            );
                            $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
                        }
                    }
                }
            }

        }
}

function dbt_start(){
    global $wp_rewrite;
    dbt_add_rewrite_rules($wp_rewrite);
}
function dbt_process(){
    
    if(!empty($_POST['_createApp'])){
        if(!empty($_POST['_appTitle'])){
            $_GET['loadapp'] = dbt_saveApp($_POST);
        }
    }
    if(!empty($_GET['loadapp'])){
        update_option('_dbt_activeApp', $_GET['loadapp']);
        wp_redirect('?page=app_builder');
        exit;
    }
    if(!empty($_GET['togglepub'])){
        $app = get_option('_'.$_GET['togglepub'].'_app');
        if(empty($app)){
            return;
        }
        $apps = get_option('dt_int_Apps');
        if($app['state'] == 'publish'){
            $app['state'] = 'draft';
        }else{
            $app['state'] = 'publish';
        }
        $apps[$_GET['togglepub']]['state'] = $app['state'];
        wp_update_post(array('ID'=>$app['basePage'], 'post_status'=>$app['state']));
        foreach($app['interfaces'] as $Key=>$Access){
            $cfg = get_option($Key);
            if(!empty($cfg['_basePost'])){
                wp_update_post(array('ID'=>$cfg['_basePost'], 'post_status'=>$app['state']));
            }
            if(!empty($cfg['_addItemPost'])){
                wp_update_post(array('ID'=>$cfg['_addItemPost'], 'post_status'=>$app['state']));
            }
            if(!empty($cfg['_editItemPost'])){
                wp_update_post(array('ID'=>$cfg['_editItemPost'], 'post_status'=>$app['state']));
            }
            if(!empty($cfg['_viewItemPost'])){
                wp_update_post(array('ID'=>$cfg['_viewItemPost'], 'post_status'=>$app['state']));
            }
        }
        update_option('dt_int_Apps', $apps);
        update_option('_'.$_GET['togglepub'].'_app', $app);
        wp_redirect('?page=app_builder');
        exit;
    }
    if(!empty($_GET['closeapp'])){
        update_option('_dbt_activeApp', '');
        wp_redirect('?page=app_builder');
        exit;
    }
    if(!empty($_POST['data'])){
        if(!wp_verify_nonce($_POST['_wpnonce'],'dbt-interface-edit')){
            return;
        }
        if(!check_admin_referer('dbt-interface-edit')){
            return;
        }
        
        $ID = dbt_updateInterface(stripslashes_deep($_POST['data']));
        wp_redirect('?page=app_builder&ref='.$ID);
        exit();
    }
    
    
    
    
    // Process entry deleting
    if((!empty($_GET['_cb']) && !empty($_GET['delsel']) && !empty($_GET['interface'])) || (!empty($_POST['_cb']) && !empty($_POST['delsel']))){
        if(is_admin()){
            if(wp_verify_nonce($_GET['delsel'],'dbt_nounce_delete')){
                if(is_array($_GET['_cb'])){
                    $Config = get_option($_GET['interface']);
                    foreach($_GET['_cb'] as $entryID){
                        $result = dbt_deleteEntry($entryID, $Config);
                    }
                }
            }
        }else{
            add_action('get_header', 'dbt_processFrontend');
        }

    }
    if(!empty($_GET['_cb']) && !empty($_GET['delsel'])){
        if(wp_verify_nonce($_GET['delsel'],'dbt_nounce_delete')){
            add_action('get_header', 'dbt_processFrontend');
        }else{
            echo 'not valid';
        }
    }
    if(!empty($_POST['dbt_filter'])){
        $url = parse_url($_SERVER['HTTP_REFERER']);
        $gets = array();
        if(!empty($url['query'])){
            parse_str($url['query'], $gets);
        }
        $gets = array_merge($gets, $_POST['dbt_filter']);
        wp_redirect(strtok($_SERVER['HTTP_REFERER'], '?').'?'.http_build_query($gets));
        die;
        //vardump($_SERVER['HTTP_REFERER']);
        //vardump($_SERVER);
    }
    
    // new form process hook
    if(!empty($_POST['_dbt_nounce'])){        
        //check the referer is the insert page
        if(!empty($_POST['_wp_http_referer'])){
            $postID = url_to_postid($_POST['_wp_http_referer']);
        }else{
            return;
        }
        //return if the referer cannot be found
        if(empty($postID)){
            return;
        }
        // get the interface from the page mete
        $interface = get_post_meta($postID,'_dbt_app_page', true);
        //get the interface config
        $Config = get_option($interface);
        
        // Verify an Insert or Update
        if(wp_verify_nonce($_POST['_dbt_nounce'],'_dbt_insert')){
            // unset the nounce and referrer
            unset($_POST['_dbt_nounce']);
            unset($_POST['_wp_http_referer']);
            //push data ro be processed
            $result = dbt_processInput($_POST, $Config, 'insert');
        }elseif(wp_verify_nonce($_POST['_dbt_nounce'],'_dbt_update')){
            // get the primary value from the referer.
            $primaryID = basename($_POST['_wp_http_referer']);
            // unset the nounce and referrer
            unset($_POST['_dbt_nounce']);
            unset($_POST['_wp_http_referer']);
            //push data ro be processed
            $result = dbt_processInput($_POST, $Config, 'update', $primaryID);
        }else{        
            return;
        }
                
        // After a result has been issued, redirect to result page
        if($Config['_redirect'] != '_EDIT' && $Config['_redirect'] != '_VIEW' && $Config['_redirect'] != '_LIST' && $Config['_redirect'] != '_ADD'){
            if($Config['_redirect'] == '_URL' && !empty($Config['_customRedirect'])){                    
                $redirect = $Config['_customRedirect'];
            }else{
                $redirectTo = get_option($Config['_redirect']);
                if(!empty($redirectTo['_basePost'])){
                    $redirect = get_permalink($redirectTo['_basePost']);
                }else{
                    $redirect = get_permalink($Config['_basePost']);
                }
            }
        }else{
            switch ($Config['_redirect']){
                case '_EDIT':
                    $redirect = get_permalink($Config['_editItemPost']).$result['passback'][$Config['_primaryField']].'/';
                    unset($result['passback'][$Config['_primaryField']]);
                    break;
                case '_VIEW':
                    $redirect = get_permalink($Config['_viewItemPost']).$result['passback'][$Config['_primaryField']].'/';
                    unset($result['passback'][$Config['_primaryField']]);
                    break;
                case '_ADD':
                    $redirect = get_permalink($Config['_addItemPost']);
                    break;
                case '_LIST':
                    $redirect = get_permalink($Config['_basePost']);
                    break;

            }
        }
        $url = parse_url($redirect);
        if(!empty($url['query'])){
            parse_str($url['query'], $oldvars);
            $result['passback'] = array_merge($oldvars, $result['passback']);
            $redirect = $url['scheme'].'://'.$url['host'].$url['path'];
        }
        if(!empty($result['passback'])){
            $passbackvars = http_build_query($result['passback']);
            $redirect = $redirect.'?'.$passbackvars;
        }
        //dump($result);
        //dump($Config);
        //dump($redirect);
        wp_redirect($redirect);
        die;        
        // update processes
        
    }
    //post meta - _dbt_app_page
    
    
    
    
    
    if(!empty($_POST['dataForm'])){
        if(is_admin()){
            if(wp_verify_nonce($_POST['_wpnonce'],'dbt-interface-form')){
                if(!check_admin_referer('dbt-interface-form')){
                    return;
                }
                $return = dbt_processInput(stripslashes_deep($_POST), 'insert');
                if($return['status'] == 'validate'){
                    return;
                }
                if($return['status'] == 'invalid'){
                    return;
                }
                $Config = get_option($_POST['master']);
                // Get redirect rules
                $url = parse_url($_POST['_wp_http_referer']);
                parse_str($url['query'], $gets);

                $gets = array_merge($gets, $return['passback']);
                unset($gets['mode']);
                wp_redirect($url['path'].'?'.http_build_query($gets));
                exit;
            }
        }else{
            add_action('get_header', 'dbt_processFrontend');
        }

        if(wp_verify_nonce($_POST['_wpnonce'],'dbt-interface-form-update')){
            if(is_admin()){
                if(!check_admin_referer('dbt-interface-form-update')){
                    return;
                }
                $return = dbt_processInput(stripslashes_deep($_POST), 'update');
                if($return['status'] == 'validate'){
                    return;
                }
                $Config = get_option($_POST['master']);
                // Get redirect rules
                $url = parse_url($_POST['_wp_http_referer']);
                parse_str($url['query'], $gets);
                    $gets = array_merge($gets, $return['passback']);
                    unset($gets['mode']);
                    wp_redirect($url['path'].'?'.http_build_query($gets));
                exit;
            }else{
                add_action('get_header', 'dbt_processFrontend');
            }
        }
    }
}
function dbt_processFrontend(){
    
    
    if(empty($_POST['interface']) || empty($_GET['interface'])){
        return;
    }
    
    if(wp_verify_nonce($_GET['delsel'],'dbt_nounce_delete')){
        if(is_array($_GET['_cb'])){
            $Config = get_option($_GET['interface']);
            foreach($_GET['_cb'] as $entryID){
                $result = dbt_deleteEntry($entryID, $Config);
            }
            wp_redirect(get_permalink($Config['_basePost']));
            exit;
        }
    }    
    if(wp_verify_nonce($_POST['delsel'],'dbt_nounce_delete')){
        if(is_array($_POST['_cb']) && isset($_POST['interface'])){
            $Config = get_option($_POST['interface']);
            foreach($_POST['_cb'] as $entryID){
                $result = dbt_deleteEntry($entryID, $Config);
            }
            wp_redirect(get_permalink($Config['_basePost']));
            exit;
        }
    }

}


function dbt_deleteEntry($ID, $Config){
    global $wpdb;    
    $Data = $wpdb->get_row("SELECT * FROM ".$Config['_main_table']." WHERE `".$Config['_primaryField']."` = '".$ID."';", ARRAY_A);
    if(empty($Data)){
        return false;
    }
    foreach($Config['_Field'] as $Field=>$Type){
        $fieldType = explode('_', $Type);
        
        if(file_exists(DBT_PATH.'fieldtypes/'.$fieldType[0].'/functions.php')){
            include_once DBT_PATH.'fieldtypes/'.$fieldType[0].'/functions.php';
        }
        $func = $fieldType[0].'_processDelete';
        if(function_exists($func)){
            $func($Field, $Data[$Field], $fieldType[1], $Config, $Data);
        }
    }    
    if(!$wpdb->query("DELETE FROM ".$Config['_main_table']." WHERE `".$Config['_primaryField']."` = '".$ID."';")){
        $deleted = false;
    }
    if(!empty($Config['_formprocessor'])){
        foreach($Config['_formprocessor'] as $key=>$processor){
            if(!empty($processor['delete'])){
                include_once DBT_PATH.'processors/form/'.$processor['processor'].'/functions.php';
                $func = 'pre_process_'.$processor['processor'];
                if(function_exists($func)){
                    $func($Data, $processor['setup'], $Config);
                }
            }
        }
    }
    return true;

}
function dbt_processInput($Data, $Config, $processType = false, $primary = false){
    global $wpdb;
    
    if($processType == 'update'){
        // Get original data.
        // bring in the render functions to get access to dbt_buildQuery.
        include_once DBT_PATH . 'libs/renderfunctions.php';        
        $currentData = dbt_buildQuery($Config, 'rawdata', false, false, false, false, $primary);
        //dump($currentData);
    }
    if($processType == 'insert' && empty($Config['_addItem'])){
        // need to make a clean fail
        $return['status'] = 'invalid';
        return $return;
    }

    $required = array();
    $passBackFields = array();
    
    // run validation on fields to ensure they have been entered correctly.
    foreach($Config['_IndexType'] as $Field=>$Indexes){
        if(!empty($Config['_IndexType'][$Field]['Required'])){
            if(empty($Data[$Field])){
                $type = explode('_', $Config['_Field'][$Field]);
                if(!empty($type[1])){
                    if(file_exists(DBT_PATH.'fieldtypes/'.$type[0].'/conf.php')){
                        include DBT_PATH.'fieldtypes/'.$type[0].'/conf.php';
                        if(!empty($FieldTypes[$type[1]]['display'])){
                            $required[$Field] = 1;
                        }
                    }
                }
            }
        }
        // add passback fields to return list
        if(!empty($Config['_IndexType'][$Field]['PassbackValue'])){
            $passBackFields[$Field] = 1;
        }
    }

    if(!empty($required)){
        $return['status'] = 'validate';
        $return['interface'] = $Data['master'];
        $return['missing'] = $required;
        return $return;
    }

    // place data into an array to prepare for a for a future feature
    // of having a form within a form. so it can handle both forms in a single
    // submit
   
    $DataSets[]=$Data;
    foreach($DataSets as $Data){
        //$Config = get_option($IID);
        // form pre-processors
        if(!empty($Config['_formprocessor'])){
            foreach($Config['_formprocessor'] as $ProcessorID=>$Processor){
                if(!empty($Processor[$processType])){
                    //vardump($Processor);
                    include_once DBT_PATH.'processors/form/'.$Processor['processor'].'/functions.php';

                    $func = 'pre_process_'.$Processor['processor'];
                    if(function_exists($func)){
                        $Data = $func($Data, $Processor['setup'], $Config);
                        if(!empty($Data['__ERROR__'])){
                            $Return['status'] = 'fail';
                        }
                    }
                }
            }
        }

        switch ($processType){
            case 'insert':
                $wpdb->insert(str_replace('`', '', $Config['_main_table']), $Data);
                $Data[$Config['_primaryField']] = $wpdb->insert_id;
                $passBackFields[$Config['_primaryField']] = $wpdb->insert_id;
                break;
            case 'update':
                $where = array($Config['_primaryField'] => $primary);
                $wpdb->update(str_replace('`', '', $Config['_main_table']), $Data, $where);
                $Data[$Config['_primaryField']] = $primary;
                $passBackFields[$Config['_primaryField']] = $primary;
                break;

        }
        
        if(empty($primaryReturn)){
            $primaryReturn = $Data[$Config['_primaryField']];
            foreach($Data as $Field=>$Val){
                if(!empty ($passBackFields[$Field])){
                    $passBackFields[$Field] = $Val;
                }
            }            
        }
        $_GET = $Data;
        
        // form post-processors
        if(!empty($Config['_formprocessor'])){
            foreach($Config['_formprocessor'] as $ProcessorID=>$Processor){
                if(!empty($Processor[$processType])){
                    include_once DBT_PATH.'processors/form/'.$Processor['processor'].'/functions.php';

                    $func = 'post_process_'.$Processor['processor'];
                    if(function_exists($func)){
                        $Data = $func($Data, $Processor['setup'], $Config);
                        if(!empty($Data['__ERROR__'])){
                            $Return['status'] = 'fail';
                        }
                    }
                }
            }
        }
    }
    $Return['status'] = 'success';
    $Return['passback'] = $passBackFields;
    return $Return;
}
function dbt_menus(){
    add_menu_page("Application Builder", "DB-Toolkit", 'activate_plugins', "app_builder", "dbt_adminPage");
    $adminPage = add_submenu_page("app_builder", 'App Builder', 'App Builder', 'activate_plugins', "app_builder", 'dbt_adminPage');

    add_action('admin_print_styles-edit.php', 'dbt_styles');
    add_action('admin_print_styles-' . $adminPage, 'dbt_styles');
    add_action('admin_print_scripts-' . $adminPage, 'dbt_scripts');
    if(is_admin ()){
        if(!empty($_GET['action'])){
            if($_GET['action'] == 'render' && !empty($_GET['interface'])){

                $Config = get_option($_GET['interface']);
                foreach($Config['_Field'] as $Field=>$FieldType){
                    $Config['_fieldType'][$Field] = explode('_', $FieldType);
                    if(empty($Config['_fieldType'][$Field][1])){
                        $FieldType = 'auto';
                    }else{
                        $FieldType = $Config['_fieldType'][$Field][1];
                    }
                    if(file_exists(DBT_PATH.'fieldtypes/'.$Config['_fieldType'][$Field][0].'/header.php')){
                        include_once DBT_PATH.'fieldtypes/'.$Config['_fieldType'][$Field][0].'/header.php';
                    }
                    if(file_exists(DBT_PATH.'fieldtypes/'.$Config['_fieldType'][$Field][0].'/'.$FieldType.'-header.php')){
                        include_once DBT_PATH.'fieldtypes/'.$Config['_fieldType'][$Field][0].'/'.$FieldType.'-header.php';
                    }
                }
                if(!empty($Config['_assetURL'])){
                    foreach($Config['_assetURL'] as $assetKey=>$assetURL){
                        if(empty($Config['_assetLabel'][$assetKey])){
                            $Config['_assetLabel'][$assetKey] = uniqid('inc-');
                        }
                        switch ($Config['_assetType'][$assetKey]){
                            case 'script_header':
                                wp_enqueue_script($Config['_assetLabel'][$assetKey], $Config['_assetURL'][$assetKey]);
                                break;
                            case 'script_footer':
                                wp_enqueue_script($Config['_assetLabel'][$assetKey], $Config['_assetURL'][$assetKey], false, false, true);
                                break;
                            case 'css':
                                wp_enqueue_style($Config['_assetLabel'][$assetKey], $Config['_assetURL'][$assetKey]);
                                break;
                        }
                    }
                }
            }
        }
    }

}

function dbt_header($template){
    if(!is_admin()){
        global $wp_query, $wp_rewrite, $post, $pages, $page, $wp_scripts, $wp_styles, $wpdb;

            wp_enqueue_style('bootstrap-grid', DBT_URL . 'styles/grid.bootstrap.min.css');
            wp_enqueue_style('bootstrap-form', DBT_URL . 'styles/form.bootstrap.min.css');

        //wp_enqueue_script('dbt-frontJS', 'http://scritps!', false, false, true);
        //wp_enqueue_style('dbt-frontCSS', 'http://styles!');

            
        // DBT currently requires permalinks to be on.
        // will do the other option is its off later
        if ($wp_rewrite->using_permalinks()) {            
            //$wp_query->is_404 = '';
            //vardump($wp_query);            
            if(!$Interface = get_post_meta($post->ID, '_dbt_app_page', true)){
                return $template;
            }
            if($Interface == 'page'){
                return $template;
            }
            $Config = get_option($Interface);            
            if(empty($Config)){
                return $template;
            }
            $app = get_option('_'.$Config['_app'].'_app');
            if(empty($app)){
                return $template;
            }
            $_GET['interface'] = $Config['_ID'];
            $_GET['app'] = $Interface;
            
            include_once DBT_PATH . 'libs/utilities.php';
            include_once DBT_PATH . 'libs/renderfunctions.php';
            
            if(!empty($wp_query->query_vars['page'])){
                $_GET[$Config['_primaryField']] = $wp_query->query_vars['page'];
                $wp_query->query_vars['page'] = 0;
                $page = 1;
            }
            if($mode = get_post_meta($post->ID, '_dbt_app_mode', true)){
                $_GET['mode'] = $mode;
                // if the interface allows viewing an entry and the primary is set , set mode to view
                if($mode == 'view' && !empty($Config['_showView']) && !empty($_GET[$Config['_primaryField']])){
                    // load entry for viewing
                    $Data = dbt_buildQuery($Config, 'data', false, false, false, false, $_GET[$Config['_primaryField']]);
                    if(!empty($Data[0])){
                        foreach($Data[0] as $field=>$entry){
                            $Config['_viewEntryText'] = str_replace('{{'.$field.'}}', $entry, $Config['_viewEntryText']);
                        }
                    }
                    // Set the page title
                    $post->post_title = $Config['_viewEntryText'];
                    $wp_query->posts[0]->post_title = $Config['_viewEntryText'];
                    $wp_query->queried_object->post_title = $Config['_viewEntryText'];
                    
                }
                // if the interface allows editing and the primary value is set, the set the mode to edit
                if($mode == 'form' && !empty($Config['_showEdit']) && !empty($_GET[$Config['_primaryField']])){
                    $_GET['mode'] = 'edit';
                    // load entry data for editing
                    $Data = dbt_buildQuery($Config, 'rawdata', false, false, false, false, $_GET[$Config['_primaryField']]);
                    if(!empty($Data[0])){
                        foreach($Data[0] as $field=>$entry){
                            $Config['_editFormText'] = str_replace('{{'.$field.'}}', $entry, $Config['_editFormText']);
                        }
                    }
                    // Set the page title
                    $post->post_title = $Config['_editFormText'];
                    $wp_query->posts[0]->post_title = $Config['_editFormText'];
                    $wp_query->queried_object->post_title = $Config['_editFormText'];
                }
            }
            
            
            if(!empty($Config['_includeBootstrap'])){
                wp_enqueue_style('dbt-frontend', DBT_URL . 'styles/frontend.bootstrap.min.css');
            }
            foreach($Config['_Field'] as $Field=>$FieldType){
                $Config['_fieldType'][$Field] = explode('_', $FieldType);
                if(empty($Config['_fieldType'][$Field][1])){
                    $FieldType = 'auto';
                }else{
                    $FieldType = $Config['_fieldType'][$Field][1];
                }
                if(file_exists(DBT_PATH.'fieldtypes/'.$Config['_fieldType'][$Field][0].'/header.php')){
                    include_once DBT_PATH.'fieldtypes/'.$Config['_fieldType'][$Field][0].'/header.php';
                }
                if(file_exists(DBT_PATH.'fieldtypes/'.$Config['_fieldType'][$Field][0].'/'.$FieldType.'-header.php')){
                    include_once DBT_PATH.'fieldtypes/'.$Config['_fieldType'][$Field][0].'/'.$FieldType.'-header.php';
                }
            }
            if(!empty($Config['_modalForm'])){
                wp_enqueue_script('dbt-modal', DBT_URL.'libs/modal/modal.js', array('jquery'));
                wp_enqueue_style('dbt-modal', DBT_URL.'libs/modal/modal.css');
                add_action('wp_footer', 'dbt_ajax_javascript');
                
            }
            if(!empty($Config['_assetURL'])){
                foreach($Config['_assetURL'] as $assetKey=>$assetURL){
                    if(empty($Config['_assetLabel'][$assetKey])){
                        $Config['_assetLabel'][$assetKey] = uniqid('inc-');
                    }
                    switch ($Config['_assetType'][$assetKey]){
                        case 'script_header':
                            wp_enqueue_script($Config['_assetLabel'][$assetKey], $Config['_assetURL'][$assetKey]);
                            break;
                        case 'script_footer':
                            wp_enqueue_script($Config['_assetLabel'][$assetKey], $Config['_assetURL'][$assetKey], false, false, true);
                            break;
                        case 'css':
                            wp_enqueue_style($Config['_assetLabel'][$assetKey], $Config['_assetURL'][$assetKey]);
                            break;
                    }
                }
            }

            $baseTemplate = get_stylesheet_directory().'/'.$app['baseTemplate'];
            if(!file_exists($baseTemplate)){
                $baseTemplate = get_stylesheet_directory().'/page.php';
            }
            if(!empty($Config['_baseTemplate'])){
                $preTemplate = get_stylesheet_directory().'/'.$Config['_baseTemplate'];
                if(file_exists($preTemplate)){
                    $template = $preTemplate;
                }else{
                    $template = $baseTemplate;
                }
            }else{
                $template = $baseTemplate;
            }
            ob_start();
            include DBT_PATH . 'render.php';

            $content = ob_get_clean();
            $newContent = $post->post_content.$content;
            // set the queried object content to the interface
            $post->post_content = $newContent;
            $wp_query->posts[0]->post_content = $newContent;
            $wp_query->queried_object->post_content = $newContent;
            
            if(!empty($pages[0])){
                $pages[0] .= $content;
            }
            
            return $template;
        }


            
    }
}
function dbt_styles(){
    if(is_admin()){
        if(!empty($_GET['post_type'])){
            wp_enqueue_style('dbt_adminStyle', DBT_URL . 'styles/core.css');
            return;
        }
    }
    wp_enqueue_style('dbt_adminStyle', DBT_URL . 'styles/core.css');
    wp_enqueue_style('jquery-ui-custom', DBT_URL . 'styles/jqueryui/jquery-ui.css');
    wp_enqueue_style('bootstrap-grid', DBT_URL . 'styles/grid.bootstrap.min.css');
    if(!empty($_GET['action'])){
        if($_GET['action'] == 'render'){
            wp_enqueue_style('bootstrap-form', DBT_URL . 'styles/form.bootstrap.min.css');
        }
    }

}
function dbt_scripts(){
    wp_enqueue_script("jquery");
    wp_enqueue_script("jquery-ui-core");
    wp_enqueue_script("jquery-ui-dialog");
    wp_enqueue_script("jquery-ui-sortable");
    wp_enqueue_script("jquery-ui-tabs");
    if(is_admin()){
        if(!empty($_GET['action'])){
            if($_GET['action'] == 'edit' && !empty($_GET['interface'])){
                $interface = get_option($_GET['interface']);
                $includes = array();
                if(!empty($interface['_Field'])){
                    foreach($interface['_Field'] as $Field){
                        $type = explode('_', $Field);
                        if(file_exists(DBT_PATH.'fieldtypes/'.$type[0].'/javascript.php')){
                            $includes[$type[0]] = DBT_URL.'fieldtypes/'.$type[0].'/javascript.php';
                        }
                    }
                }
                foreach($includes as $key=>$include){
                    wp_enqueue_script("dbt-fieldtype-".$key, $include);
                }
            }

        }
    }
}
function dbt_footer(){


    global $footerscripts;
    if(!empty($footerscripts)){
?>
<script>
    jQuery(document).ready(function(){
    <?php
        echo $footerscripts;
    ?>
    })
</script>
<?php
    }
}
function dbt_doShortcode(){

}
function dbt_adminPage(){
    if (!empty($_GET['action'])) {
        switch ($_GET['action']) {

            case 'edit':
                include DBT_PATH . 'edit.php';
                break;
            case 'render':
                include_once DBT_PATH . 'libs/renderfunctions.php';
                echo '<div class="wrap">';
                include DBT_PATH . 'render.php';
                echo '</div>';
                break;
            default:
                include DBT_PATH . 'admin.php';
                break;
        }
    } else {
        $currentApp = get_option('_dbt_activeApp');
        if(!empty($currentApp)){
            $app = get_option('_'.$currentApp.'_app');
            $appInfo = get_option('dt_int_Apps');
            $appInfo = $appInfo[$currentApp];
            include DBT_PATH . 'app.php';
        }else{
            include DBT_PATH . 'apps.php';
        }
    }
}



function dbt_ajaxCall(){

    global $wpdb,$ajaxAllowedFunctions;

    $ref = parse_url(basename($_SERVER['HTTP_REFERER']));

    if(!empty($_POST['func'])) {
        $func = $_POST['func'];
        if (!empty($_POST['FARGS'])) {
            $func_args = $_POST['FARGS'];
        } else {
            $func_args = array();
        }
        if(empty($ajaxAllowedFunctions[$func]) && !(is_admin())){
            echo 'Access to \''.$func.'\' is restricted.';
            exit();
        }
    }
    if (!empty($func) && function_exists($func)) {
        header ("Expires: Mon, 21 Nov 1997 05:00:00 GMT");    // Date in the past
        header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
        header ("Pragma: no-cache");                          // HTTP/1.0
        $Output = call_user_func_array($func, $func_args);
        if(is_array($Output)) {
            header('Content-type: application/json; charset=UTF-8');
            //header('Content-type: text/html; charset=UTF-8');
            echo json_encode($Output);
        }else {
            //header('Content-type: text/html; charset=UTF-8');
            echo $Output;
        }
        exit();
    }

}
function dbt_ajax_javascript(){
?>
<script type="text/javascript" >
    function dbt_ajaxCall(){
            var data = {
                    action: 'dbt_ajaxCall', func: dbt_ajaxCall.arguments[0]
            };
            for(i=1;dbt_ajaxCall.arguments.length-1>i; i++) {
                data['FARGS[' + i + ']'] = dbt_ajaxCall.arguments[i];
            }
            var callBack = dbt_ajaxCall.arguments[dbt_ajaxCall.arguments.length-1];
            var ajaxurl = '<?php echo admin_url('admin-ajax.php', 'relative'); ?>';
            jQuery.post(ajaxurl, data, function(response) {
                callBack(response);
            });
    }
</script>
<?php
}

?>