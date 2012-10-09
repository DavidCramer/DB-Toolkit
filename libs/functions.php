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
    dbt_buildFormView($Config, 'form', true);
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
    
    // process form
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
function dbt_processInput($Data, $processType = false){
    global $wpdb;
    $Interface = get_option($Data['master']);
    if($processType == 'update'){
        $Primary = $Data['primary'];
    }
    if($processType == 'insert' && empty($Interface['_addItem'])){
        $return['status'] = 'invalid';
        return $return;
    }

    $required = array();
    $passBackFields = array();
    
    foreach($Interface['_IndexType'] as $Field=>$Indexes){
        if(!empty($Interface['_IndexType'][$Field]['Required'])){
            if(empty($Data['dataForm'][$Data['master']][$Field])){
                $type = explode('_', $Interface['_Field'][$Field]);
                if(!empty($type[1])){
                    if(file_exists(DBT_PATH.'fieldtypes/'.$type[0].'/conf.php')){
                        include DBT_PATH.'fieldtypes/'.$type[0].'/conf.php';
                        if(!empty($FieldTypes[$type[1]]['visible'])){
                            $required[$Field] = 1;
                        }
                    }
                }
            }
        }        
        if(!empty($Interface['_IndexType'][$Field]['PassbackValue'])){
            $passBackFields[$Field] = 1;
        }
    }

    if(!empty($required)){
        $return['status'] = 'validate';
        $return['interface'] = $Data['master'];
        $return['missing'] = $required;
        return $return;
    }
    // Reset Into Data Order
    $DataSets = array();
    $DataSets[$Data['master']] = $Data['dataForm'][$Data['master']];
    unset($Data['dataForm'][$Data['master']]);
    if(!empty($Data['dataForm'])){
        foreach($Data['dataForm'] as $IID=>$DataSet){
            $DataSets[$IID] = $DataSet;
        }
    }

    foreach($DataSets as $IID=>$Data){
        $Config = get_option($IID);
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
                break;
            case 'update':
                //workout the WHERE struct
                $where = array($Config['_primaryField'] => $Primary);
                $wpdb->update(str_replace('`', '', $Config['_main_table']), $Data, $where);
                $Data[$Config['_primaryField']] = $Primary;
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
    return$Return;
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
        global $wp_query, $wp_rewrite, $post, $pages, $page, $wp_scripts, $wp_styles;

            wp_enqueue_style('bootstrap-grid', DBT_URL . 'styles/grid.css');
            wp_enqueue_style('bootstrap-form', DBT_URL . 'styles/form.css');

        //wp_enqueue_script('dbt-frontJS', 'http://scritps!', false, false, true);
        //wp_enqueue_style('dbt-frontCSS', 'http://styles!');

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
            if($mode = get_post_meta($post->ID, '_dbt_app_mode', true)){
                $_GET['mode'] = $mode;
            }
            if(!empty($wp_query->query_vars['page'])){
                $_GET[$Config['_primaryField']] = $wp_query->query_vars['page'];
                $wp_query->query_vars['page'] = 0;
                $page = 1;
            }
            
            if(!empty($Config['_includeBootstrap'])){
                wp_enqueue_style('dbt-frontend', DBT_URL . 'styles/dbt_frontend.css');
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
            include_once DBT_PATH . 'libs/utilities.php';
            include_once DBT_PATH . 'libs/renderfunctions.php';
            include DBT_PATH . 'render.php';

            $content = ob_get_clean();
            $post->post_content .= $content;            
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
    wp_enqueue_style('bootstrap-grid', DBT_URL . 'styles/grid.css');
    wp_enqueue_style('bootstrap-form', DBT_URL . 'styles/form.css');

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