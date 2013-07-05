<?php
/*
 * Core Functions Library - DB Toolkit
 * (C) David Cramer 2010 - 2011
 *
 */

require_once(DB_TOOLKIT.'libs/lib.php');
require_once(DB_TOOLKIT.'daiselements.class.php');
require_once(DB_TOOLKIT.'data_form/class.php');
require_once(DB_TOOLKIT.'data_report/class.php');
require_once(DB_TOOLKIT.'data_itemview/class.php');


function interface_VersionCheck() {
    $defaults = unserialize('a:38:{s:11:"_FormLayout";s:0:"";s:9:"_ViewMode";s:4:"list";s:15:"_New_Item_Title";s:9:"Add Entry";s:15:"_Items_Per_Page";s:2:"20";s:12:"_autoPolling";s:0:"";s:13:"_Hide_Toolbar";s:1:"1";s:13:"_Show_Filters";s:1:"1";s:15:"_toggle_Filters";s:1:"1";s:20:"_Show_KeywordFilters";s:1:"1";s:14:"_Keyword_Title";s:6:"Search";s:11:"_showReload";s:1:"1";s:12:"_Show_Export";s:1:"1";s:13:"_Show_Plugins";s:1:"1";s:12:"_orientation";s:1:"P";s:12:"_Show_Select";s:1:"1";s:12:"_Show_Delete";s:1:"1";s:10:"_Show_Edit";s:1:"1";s:10:"_Show_View";s:1:"1";s:19:"_Show_Delete_action";s:1:"1";s:12:"_Show_Footer";s:1:"1";s:18:"_SubmitButtonClass";s:0:"";s:18:"_UpdateButtonClass";s:0:"";s:15:"_ListTableClass";s:0:"";s:10:"_FormClass";s:0:"";s:13:"_toolbarClass";s:0:"";s:15:"_filterbarClass";s:0:"";s:21:"_filterbuttonbarClass";s:0:"";s:14:"_InsertSuccess";s:27:"Entry inserted successfully";s:14:"_UpdateSuccess";s:26:"Entry updated successfully";s:11:"_InsertFail";s:22:"Could not insert entry";s:11:"_UpdateFail";s:22:"Could not update entry";s:17:"_SubmitButtonText";s:6:"Submit";s:17:"_UpdateButtonText";s:6:"Submit";s:13:"_EditFormText";s:10:"Edit Entry";s:13:"_ViewFormText";s:10:"View Entry";s:14:"_NoResultsText";s:13:"Nothing Found";s:10:"_ShowReset";s:1:"1";s:16:"_SubmitAlignment";s:4:"left";}');
    update_option('_dbtoolkit_defaultinterface', $defaults, NULL, 'No');
}

function dt_start() {
    // I like sessions
    if(!session_id()) {

            @session_start();

    }
    // Include Libraries
    if(empty($_SESSION['dataform']['OutScripts'])){
            $_SESSION['dataform']['OutScripts'] = "";
    }

    if(!empty($_POST['reportFilter'])){
            $RedirectLocation = $_SERVER['REQUEST_URI'];
            //echo $RedirectLocation;
            foreach($_POST['reportFilter'] as $EID=>$FilterSet){

                    $InterfaceID = $EID;
                    $Element = getelement($EID);
                    $Config = $Element['Content'];
                    //echo $RedirectLocation;
                    if(!empty($Config['_targetInterfaceFilter']) && !empty($Config['_ItemViewInterface'])){
                        if(!empty($Config['_ItemViewPage'])){
                            $RedirectLocation = get_permalink($Config['_ItemViewPage']);
                        }
                        $InterfaceID = $Config['_ItemViewInterface'];
                    }


                    if(!empty($_POST['reportFilter']['ClearFilters'])){
                            unset($_SESSION['reportFilters'][$InterfaceID]);
                    }else{
                            unset($_SESSION['reportFilters'][$InterfaceID]);
                            $_SESSION['reportFilters'][$InterfaceID] = $FilterSet;
                    }
            break;
            }
            if(!empty($_POST['reportFilter']['reportFilterLock'])){
                    dr_lockFilters($_POST['reportFilter']['reportFilterLock']);
                    //dump($_POST['reportFilter']);
                    //dump($EID);
            }
            if(!empty($_POST['reportFilter']['reportFilterUnlock'])){
                    dr_unlockFilters($_POST['reportFilter']['reportFilterUnlock']);
                    //dr_lockFilters($_POST['reportFilter']['reportFilterLock']);
                    //dump($_POST['reportFilter']);
                    //dump($EID);
            }

        wp_redirect($RedirectLocation);
        exit;
    }


    /*
    require_once(DB_TOOLKIT.'libs/lib.php');
    require_once(DB_TOOLKIT.'daiselements.class.php');
    require_once(DB_TOOLKIT.'data_form/class.php');
    require_once(DB_TOOLKIT.'data_report/class.php');
    require_once(DB_TOOLKIT.'data_itemview/class.php');
    */


    // Ajax processing
    dt_process();
}

//Header
function dt_headers() {

    include_once(DB_TOOLKIT.'data_form/headers.php');
    include_once(DB_TOOLKIT.'data_report/headers.php');

    ?>
<script type="text/javascript" >

    <?php
    if(!is_admin()) {
        echo 'var ajaxurl_dbt = \'./index.php?dbtoolkit\';';
    }else {
        ?>
        ajaxurl_dbt = ajaxurl;
            function dt_deleteInterface(interfaceID, type){
                hash = '';
                if(type == 'cluster'){
                    hash = '&r=y#clusters';
                }
                if(confirm('Are you sure you want to delete this interface?')){

                    ajaxCall('dt_removeInterface', interfaceID, function(x){
                        if(x == true){
                            jQuery('#'+interfaceID).fadeOut('slow', function(){
                                jQuery(this).remove();
                                //window.location = '<?php echo $_SERVER['REQUEST_URI']; ?>'+hash;
                            });
                        }else{
                            alert('strange, that should have worked '+x);
                        }
                    });

                }

            }


        <?php
    }

    ?>
        function ajaxCall() {
    <?php
    if(is_admin()) {
        ?>
                var vars = { action : 'dt_ajaxCall',func: ajaxCall.arguments[0]};
        <?php
    }else {
        ?>
                var vars = { action : 'wp_dt_ajaxCall',func: ajaxCall.arguments[0]};
        <?php
    }
    ?>

            for(i=1;ajaxCall.arguments.length-1>i; i++) {
                vars['FARGS[' + i + ']'] = ajaxCall.arguments[i];
            }

            var callBack = ajaxCall.arguments[ajaxCall.arguments.length-1];
            jQuery.post(ajaxurl_dbt,vars, function(data){
                callBack(data);
            });
        }
</script>
    <?php
}

//styles
function dt_styles($preIs = false) {


    if(!is_admin()){
        global $post;

        $isBound = get_option('_dbtbinding_'.$post->ID);
        if(!empty($isBound)){
            $preIs[] = $isBound;
        }

        $pattern = get_shortcode_regex();

        $texts = get_option('widget_text');
        if(empty($preIs)){
            $preIs = array();
        }
        foreach($texts as $text){

        $ilc_widget_active = get_option('sidebars_widgets');

        unset($ilc_widget_active['wp_inactive_widgets']);

            if($ilc_widget_active){
                foreach($ilc_widget_active as $sidebar){
                    if(is_array($sidebar))
                    foreach($sidebar as $widget){
                        if(substr($widget, 0, 5) == 'text-'){

                            /// filter each item
                            //vardump($text['text']);
                            preg_match_all('/'.$pattern.'/s', $texts[substr($widget, 5)]['text'], $matches);
                            //vardump($matches);
                            if(!empty($matches[3])){
                                foreach($matches[3] as $preInterface){
                                   $preIs[] = shortcode_parse_atts($preInterface);
                                }
                            }
                        }
                    }
                }
            }
        }
        if(!empty($ilc_widget_active)){
            foreach($ilc_widget_active as $sidebar=>$widgets){
                if(is_array($widgets)){
                    foreach($widgets as $widget){
                        if(is_active_sidebar($sidebar)){
                            if(substr($widget, 0, 10) == 'interface-'){
                                $interfaces = get_option('widget_interface');
                                $preIs[] = $interfaces[substr($widget,10)]['Interface'];
                            }
                        }
                    }
                }
            }
        }
        if(!empty($post)){
        preg_match_all('/'.$pattern.'/s', $post->post_content, $matches);

        $shortCodes = array('interface');

        global $wpdb;
        $customShortCodes = $wpdb->get_results("SELECT `option_name`, `option_value` FROM $wpdb->options WHERE `option_name` LIKE 'dt_intfc%' AND `option_value` LIKE '%_shortCode%' ", ARRAY_A);
        foreach($customShortCodes as $shortCodesIn){
            $data = unserialize($shortCodesIn['option_value']);
            if(!empty($data['_shortCode'])){

                if (in_array($data['_shortCode'], $matches[2])) {
                    $preIs[] = $shortCodesIn['option_name'];
                }

            }
        }

        if (in_array('interface', $matches[2])) {
            foreach($matches[3] as $preInterface){
                $args = shortcode_parse_atts($preInterface);
                $op = get_option($args['id']);
                if(!empty($op)){
                    $preIs[] = $args['id'];
                }
            }
        }
        }



    }

    if(!empty($preIs) || is_admin()){

    // add styles for reports grid
    $themeDir = get_theme_root().'/'.get_template();
    $themeURL = get_bloginfo('template_url');

    if(!file_exists($themeDir.'/uicustom')){
            wp_register_style('jqueryUI-core', WP_PLUGIN_URL . '/db-toolkit/jqueryui/jquery-ui.css');
    }else{
            wp_register_style('jqueryUI-core', $themeURL.'/uicustom/jquery-ui.css');
    }

    wp_register_style('jquery-multiselect', WP_PLUGIN_URL . '/db-toolkit/libs/ui.dropdownchecklist.css');
    wp_register_style('jquery-validate', WP_PLUGIN_URL . '/db-toolkit/libs/validationEngine.jquery.css');

    wp_enqueue_style('jqueryUI-core');
    wp_enqueue_style('jquery-multiselect');
    wp_enqueue_style('jquery-validate');
    
    //picker
    wp_enqueue_style('datepicker-bs', DBT_URL."/data_form/fieldtypes/date/css/datepicker.css");

    // report
    if(file_exists($themeDir.'/table.css') && !is_admin()) {
        wp_register_style('interface_table_styles', $themeURL.'/table.css');
    }else{
        wp_register_style('interface_table_styles', WP_PLUGIN_URL . '/db-toolkit/data_report/css/table.css');
    }
    wp_enqueue_style('interface_table_styles');
    if(file_exists($themeDir.'/toolbar.css') && !is_admin()){
        wp_register_style('custom_toolbar_style', $themeURL.'/toolbar.css');
    }else{
        wp_register_style('custom_toolbar_style', WP_PLUGIN_URL.'/db-toolkit/data_report/css/style.css');
    }
    wp_enqueue_style('custom_toolbar_style');

    // form

	if(file_exists($themeDir.'/form.css') && !is_admin()){
            wp_register_style('form_style', $themeURL.'/form.css');
            wp_enqueue_style('form_style');
        }else{
            wp_register_style('form_style', WP_PLUGIN_URL.'/db-toolkit/data_form/css/form.css');
            wp_enqueue_style('form_style');
        }

        /*
        //<link rel="stylesheet" type="text/css" media="screen" href="<?php echo WP_PLUGIN_URL; ?>/db-toolkit/data_form/css/ui.timepickr.css" />
        <script type="text/javascript" src="<?php echo WP_PLUGIN_URL; ?>/db-toolkit/data_form/js/ui.timepickr.js"></script>
        */

	$Types = loadFolderContents(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes');
	foreach($Types[0] as $Type){
		if(file_exists(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$Type[1].'/header.php')){
			include_once(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$Type[1].'/header.php');
		}
	}
}


    // load interface specifics
    if(is_admin()){
        if(!empty($_GET['page']))
        if(substr($_GET['page'],0,4) == 'app_' || substr($_GET['page'],0,8) == 'dt_intfc'){
            wp_register_style('interface_setup_styles', WP_PLUGIN_URL . '/db-toolkit/data_report/css/launcher.css');
        }else{
            wp_register_style('interface_setup_styles', WP_PLUGIN_URL . '/db-toolkit/data_report/css/setup.css');
        }
        wp_enqueue_style('interface_setup_styles');

        if(!empty($_GET['page']) || !empty($_GET['renderinterface'])){
            if(!empty($_GET['page'])){
                if(substr($_GET['page'],0,8) == 'dt_intfc'){
                    $isInterface = $_GET['page'];
                }
            }
            if(!empty($_GET['renderinterface'])){
                if(substr($_GET['renderinterface'],0,8) == 'dt_intfc'){
                    $isInterface = $_GET['renderinterface'];
                }
            }
            if(substr($_GET['page'],0,4) == 'app_'){
                $app= get_option('_'.substr($_GET['page'],4).'_app');
                if(!empty($app['landing'])){
                    $isInterface = $app['landing'];
                }else{
                    foreach($app['interfaces'] as $interface=>$access){
                        $isInterface = $interface;
                    }
                }

            }

            if(!empty($isInterface)){
               $preInterface = get_option($isInterface);
               if(!empty($preInterface['_CustomCSSSource'])){
                   // load scripts
                   // setup scripts and styles
                   foreach($preInterface['_CustomCSSSource'] as $handle=>$CSS){
                       wp_register_style($handle, $CSS['source']);
                       wp_enqueue_style($handle);
                   }
               }
               $Config = unserialize(base64_decode($preInterface['Content']));
               if(!empty($Config['_ViewProcessors'])){
                   foreach($Config['_ViewProcessors'] as $viewProcess){
                       if(file_exists(DB_TOOLKIT.'data_report/processors/'.$viewProcess['_process'].'/styles.php')){
                           include_once(DB_TOOLKIT.'data_report/processors/'.$viewProcess['_process'].'/styles.php');
                       }
                   }
               }

            }

        }
    }else{

        if(!empty($preIs)){
            $stylesAdded = array();
            foreach($preIs as $interface){

                   if(!is_array($interface)){

                   $preInterface = get_option($interface);
                   if(!empty($preInterface['_CustomCSSSource'])){
                       // load scripts
                       // setup scripts and styles
                       foreach($preInterface['_CustomCSSSource'] as $handle=>$CSS){
                           if(array_search($CSS['source'], $stylesAdded) === false){
                               wp_register_style($handle, $CSS['source']);
                               wp_enqueue_style($handle);
                               $stylesAdded[] = $CSS['source'];
                           }
                       }
                   }
                   $Config = unserialize(base64_decode($preInterface['Content']));
                   if(!empty($Config['_ViewProcessors'])){
                       foreach($Config['_ViewProcessors'] as $viewProcess){
                           if(file_exists(DB_TOOLKIT.'data_report/processors/'.$viewProcess['_process'].'/styles.php')){
                               include_once(DB_TOOLKIT.'data_report/processors/'.$viewProcess['_process'].'/styles.php');
                           }
                       }
                   }
                   }
            }
        }
    }

}

//Scripts
function dt_scripts($preIs = false) {

    if(!is_admin()){
        global $post;
        //$te = wp_get_sidebars_widgets();
        // check page bindings
        $isBound = get_option('_dbtbinding_'.$post->ID);
        if(!empty($isBound)){
            $preIs[] = $isBound;
        }
        $pattern = get_shortcode_regex();
        $texts = get_option('widget_text');
        if(!empty($texts)){
            if(empty($preIs)){
                $preIs = array();
            }
        $ilc_widget_active = get_option('sidebars_widgets');

        unset($ilc_widget_active['wp_inactive_widgets']);

            if($ilc_widget_active){
                foreach($ilc_widget_active as $sidebar){
                    if(is_array($sidebar))
                    foreach($sidebar as $widget){
                        if(substr($widget, 0, 5) == 'text-'){

                            /// filter each item
                            //vardump($text['text']);
                            preg_match_all('/'.$pattern.'/s', $texts[substr($widget, 5)]['text'], $matches);
                            //vardump($matches);
                            if(!empty($matches[3])){
                                foreach($matches[3] as $preInterface){
                                   $preIs[] = shortcode_parse_atts($preInterface);
                                }
                            }
                        }
                    }
                }
            }
        }
        if(!empty($ilc_widget_active)){
            foreach($ilc_widget_active as $sidebar=>$widgets){
                if(is_array($widgets)){
                    foreach($widgets as $widget){

                        if(substr($widget, 0, 10) == 'interface-'){
                            if(is_active_sidebar($sidebar)){
                                $interfaces = get_option('widget_interface');
                                $preIs[] = $interfaces[substr($widget,10)]['Interface'];
                            }
                        }
                    }
                }
            }
        }

        preg_match_all('/'.$pattern.'/s', $post->post_content, $matches);
        //vardump($matches);
        $shortCodes = array('interface');

        global $wpdb;
        $customShortCodes = $wpdb->get_results("SELECT `option_name`, `option_value` FROM $wpdb->options WHERE `option_name` LIKE 'dt_intfc%' AND `option_value` LIKE '%_shortCode%' ", ARRAY_A);
        foreach($customShortCodes as $shortCodesIn){
            $data = unserialize($shortCodesIn['option_value']);
            if(!empty($data['_shortCode'])){

                if (in_array($data['_shortCode'], $matches[2])) {
                    $preIs[] = $shortCodesIn['option_name'];
                }

            }
        }
        if (in_array('interface', $matches[2])) {
            foreach($matches[3] as $preInterface){
                $args = shortcode_parse_atts($preInterface);
                $op = get_option($args['id']);

                if(!empty($op)){
                    $preIs[] = $args['id'];
                }
                //vardump($_SERVER);
            }
        }
    }

    if(!empty($preIs) || is_admin()){
        //vardump($preIs);
    // queue & register scripts

        if(is_admin ()){
            wp_register_script('dbt_jslib', WP_PLUGIN_URL . '/db-toolkit/libs/jslib.js', false, false, true);
            wp_enqueue_script("dbt_jslib");
        }

        wp_register_script('data_report', WP_PLUGIN_URL . '/db-toolkit/data_form/javascript.php', false, false, true);
        wp_register_script('data_form', WP_PLUGIN_URL . '/db-toolkit/data_report/javascript.php', false, false, true);
        //wp_register_script('jquery-ui');// , WP_PLUGIN_URL . '/db-toolkit/jqueryui/jquery-ui.min.js');
        wp_register_script('jquery-multiselect', WP_PLUGIN_URL . '/db-toolkit/libs/ui.dropdownchecklist-min.js', false, false, true);
        wp_register_script('jquery-validate', WP_PLUGIN_URL . '/db-toolkit/libs/jquery.validationEngine.js');

        wp_enqueue_script("jquery");
        wp_enqueue_script("jquery-ui-core");
        wp_enqueue_script("jquery-ui-dialog");
        wp_enqueue_script("jquery-ui-sortable");
        wp_enqueue_script("jquery-ui-tabs");
        wp_enqueue_script('jquery-multiselect');
        wp_enqueue_script('data_report');
        wp_enqueue_script('data_form');
        wp_enqueue_script('jquery-validate');

        wp_enqueue_script('swfobject');

    }
        /*$Types = loadFolderContents(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes');
	foreach($Types[0] as $Type){
		if(file_exists(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$Type[1].'/javascript.php')){
                        //wp_register_script('fieldType_'.$Type[1], WP_PLUGIN_URL.'/db-toolkit/data_form/fieldtypes/'.$Type[1].'/javascript.php', false, false, true);
                        //wp_enqueue_script('fieldType_'.$Type[1]);
                }
	}*/

    // load interface specifics

    if(is_admin()){

        if(!empty($_GET['page']) || !empty($_GET['renderinterface'])){
            if(!empty($_GET['page'])){
                if(substr($_GET['page'],0,8) == 'dt_intfc'){
                    $isInterface = $_GET['page'];
                }
                if(substr($_GET['page'],0,4) == 'app_'){
                    $app= get_option('_'.substr($_GET['page'],4).'_app');
                    if(!empty($app['landing'])){
                        $isInterface = $app['landing'];
                    }else{
                        foreach($app['interfaces'] as $interface=>$access){
                            $isInterface = $interface;
                        }
                    }

                }

            }
            if(!empty($_GET['renderinterface'])){
                if(substr($_GET['renderinterface'],0,8) == 'dt_intfc'){
                    $isInterface = $_GET['renderinterface'];
                }
            }

            if(!empty($isInterface)){
               $preInterface = get_option($isInterface);
               if(!empty($preInterface['_CustomJSLibraries'])){
                   // load scripts
                   // setup scripts and styles
                   foreach($preInterface['_CustomJSLibraries'] as $handle=>$script){
                       $in_footer = false;
                       if($script['location'] == 'foot'){
                           $in_footer = true;
                       }
                       wp_register_script($handle, $script['source'], false, false, $in_footer);
                       wp_enqueue_script($handle);
                   }
               }
               $Config = unserialize(base64_decode($preInterface['Content']));
               if(!empty($Config['_ViewProcessors'])){
                   foreach($Config['_ViewProcessors'] as $viewProcess){
                       if(file_exists(DB_TOOLKIT.'data_report/processors/'.$viewProcess['_process'].'/scripts.php')){
                           include_once(DB_TOOLKIT.'data_report/processors/'.$viewProcess['_process'].'/scripts.php');
                       }
                   }
               }
                if(!empty($Config['_customFooterJavaScript'])){
                    //$_SESSION['dataform']['OutScripts'] .= stripslashes_deep($Config['_customFooterJavaScript']);
                }
            }
        }
    }else{

        if(!empty($preIs)){

            $scriptsAdded = array();
            foreach($preIs as $interface){



             if(!is_array($interface)){
               $preInterface = get_option($interface);
               if(!empty($preInterface['_CustomJSLibraries'])){
                   // load scripts
                   // setup scripts and styles
                   foreach($preInterface['_CustomJSLibraries'] as $handle=>$script){
                       if(array_search($script['location'], $scriptsAdded) === false){
                           $in_footer = false;
                           if($script['location'] == 'foot'){
                               $in_footer = true;
                           }
                           wp_register_script($handle, $script['source'], false, false, $in_footer);
                           wp_enqueue_script($handle);
                           $scriptsAdded[] = $script['location'];
                       }
                   }
               }
               $Config = unserialize(base64_decode($preInterface['Content']));
               if(!empty($Config['_ViewProcessors'])){
                   foreach($Config['_ViewProcessors'] as $viewProcess){
                       if(file_exists(DB_TOOLKIT.'data_report/processors/'.$viewProcess['_process'].'/scripts.php')){
                           include_once(DB_TOOLKIT.'data_report/processors/'.$viewProcess['_process'].'/scripts.php');
                       }
                   }
               }
                if(!empty($Config['_customFooterJavaScript'])){
                    //$_SESSION['dataform']['OutScripts'] .= stripslashes_deep($Config['_customFooterJavaScript']);
                }

            }
            }
        }
    }

}

//Menus

function add_admin_menu_separator($position) {
  global $menu;
  $index = 0;
  foreach($menu as $offset => $section) {
    if (substr($section[2],0,9)=='separator')
      $index++;
    if ($offset>=$position) {
      $menu[$position] = array('','read',"dbt_separator".rand(111,99999),'','wp-menu-separator');
      break;
    }
  }
}


function dt_menus() {

    global $wpdb;
    global $menu;

    //$inStealth = get_option('dbtStealth');

    $user = wp_get_current_user();


        // Create the new separator
        //$menu['26'] = array( '', 'read', 'separator-dbtoolkit1', '', 'wp-menu-separator' );
        //$menu['30.99'] = array( '', 'read', 'separator-dbtoolkit1', '', 'wp-menu-separator' );

        // Create the new top-level Menu
        //$market = add_menu_page ('Application Marketplace', 'App Market', 'manage_options','appmarket', 'dt_appMarket', WP_PLUGIN_URL.'/db-toolkit/images/cart.png', '2.1');
        //$appMarket = add_submenu_page("appmarket", 'App Market', 'Browse Market', 'read', "appmarket");
        //$launcher = add_submenu_page("appmarket", 'Applications', 'Applications', 'read', "app_launcher", 'app_launcher');
        //add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function);
        //add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);
        //if(empty($inStealth)){
        add_menu_page("DB-Toolkit", "DB-Toolkit", 'activate_plugins', "dbt_builder", "dbtoolkit_admin", WP_PLUGIN_URL.'/db-toolkit/data_report/cog.png');


        //$adminPage = add_submenu_page("Database_Toolkit_Welcome", 'Manage Interfaces', 'Interfaces & Clusters', 'activate_plugins', "Database_Toolkit", 'dbtoolkit_admin');
        $adminPage = add_submenu_page("dbt_builder", 'Application Builder', 'App Builder', 'activate_plugins', "dbt_builder", 'dbtoolkit_admin');

        $addNew = add_submenu_page("dbt_builder", 'Create New Interface', 'New Interface', 'activate_plugins', "Add_New", 'dbtoolkit_admin');
        $NewCluster = add_submenu_page("dbt_builder", 'Create New Cluster Interface', 'New Cluster', 'activate_plugins', "New_Cluster", 'dbtoolkit_admin');
        //$Manager = add_submenu_page("Database_Toolkit_Welcome", 'Application Masnagement', 'App Management', 'activate_plugins', "manage_apps", 'dbtoolkit_appman');
        $Import = add_submenu_page("dbt_builder", 'Import Application', 'Install Application', 'activate_plugins', "dbtools_importer", 'dbtoolkit_import');
        $setup = add_submenu_page("dbt_builder", 'General Settings', 'General Settings', 'activate_plugins', "dbtools_setup", 'dbtoolkit_setup');

        $Dashboard = add_submenu_page("dbt_builder", 'DB-Toolkit News', 'Donate', 'activate_plugins', "Database_Toolkit_Welcome", 'dbtoolkit_dashboard');

        //$setup = add_submenu_page("Database_Toolkit", 'Bug Report', 'Bug Report', 'activate_plugins', "dbtools_bugreport", 'dbtoolkit_bugreport');
        //$setup = add_submenu_page("Database_Toolkit", 'Documentation A', 'Documention B', 'activate_plugins', "dbtools_manual", 'dbtoolkit_manual');


            add_action('admin_print_styles-'.$adminPage, 'dt_styles');
            add_action('admin_head-'.$adminPage, 'dt_headers');
            add_action('admin_print_scripts-'.$adminPage, 'dt_scripts');
            add_action('admin_footer-'.$adminPage, 'dt_footers');

            //add_action('admin_print_styles-'.$market, 'dt_styles');
            //add_action('admin_head-'.$market, 'dt_headers');
            //add_action('admin_print_scripts-'.$market, 'dt_scripts');
            //add_action('admin_footer-'.$market, 'dt_footers');

            //add_action('admin_print_styles-'.$launcher, 'dt_styles');
            //add_action('admin_head-'.$launcher, 'dt_headers');
            //add_action('admin_print_scripts-'.$launcher, 'dt_scripts');
            //add_action('admin_footer-'.$launcher, 'dt_footers');

            add_action('admin_print_styles-'.$NewCluster, 'dt_styles');
            add_action('admin_head-'.$NewCluster, 'dt_headers');
            add_action('admin_print_scripts-'.$NewCluster, 'dt_scripts');
            add_action('admin_footer-'.$NewCluster, 'dt_footers');

            add_action('admin_print_styles-'.$addNew, 'dt_styles');
            add_action('admin_head-'.$addNew, 'dt_headers');
            add_action('admin_print_scripts-'.$addNew, 'dt_scripts');
            add_action('admin_footer-'.$addNew, 'dt_footers');

            //add_action('admin_print_styles-'.$Manager, 'dt_styles');
            //add_action('admin_head-'.$Manager, 'dt_headers');
            //add_action('admin_print_scripts-'.$Manager, 'dt_scripts');
            //add_action('admin_footer-'.$Manager, 'dt_footers');

            add_action('admin_print_styles-'.$Import, 'dt_styles');
            add_action('admin_head-'.$Import, 'dt_headers');
            add_action('admin_print_scripts-'.$Import, 'dt_scripts');
            add_action('admin_footer-'.$Import, 'dt_footers');

            add_action('admin_print_styles-'.$setup, 'dt_styles');
            add_action('admin_head-'.$setup, 'dt_headers');
            add_action('admin_print_scripts-'.$setup, 'dt_scripts');
            add_action('admin_footer-'.$setup, 'dt_footers');

            add_action('admin_print_styles-'.$Dashboard, 'dt_styles');
            add_action('admin_head-'.$Dashboard, 'dt_headers');
            add_action('admin_print_scripts-'.$Dashboard, 'dt_scripts');
            add_action('admin_footer-'.$Dashboard, 'dt_footers');

	////add_submenu_page("Database_Toolkit", 'Setup', 'Setup', 'read', "General Settings", 'dbtoolkit_setup');
       // }

            $apps = get_option('dt_int_Apps');
            unset($apps['base']);
            unset($apps['Base']);

            $base = 1;
            if(!empty($apps)){
                foreach($apps as $app=>$data){
                    $MainSubs = array();
                    $Groups = array();
                    $appSettings = get_option('_'.$app.'_app');

                    // Create app menu
                    //add seperator
                    //add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position)
                    if(!empty($appSettings['docked'])){
                        add_admin_menu_separator('25.'.$base++);
                        $appPage = add_menu_page($data['name'], $data['name'], 'read', 'app_'.$app, "app_launcher", WP_PLUGIN_URL.'/db-toolkit/data_report/table.png', '25.'.$base++);

                        add_action('admin_head-'.$appPage, 'dt_headers');
                        add_action('admin_print_scripts-'.$appPage, 'dt_scripts');
                        add_action('admin_print_styles-'.$appPage, 'dt_styles');
                        add_action('admin_footer-'.$appPage, 'dt_footers');
                        if(!empty($appSettings['interfaces'])){
                            foreach($appSettings['interfaces'] as $interface=>$access){
                                // load interface settings and check for menus
                                $cfg = get_option($interface);

                                if(!empty($cfg['_ItemGroup'])){
                                    //vardump($cfg).'<br>';
                                    $Groups[$cfg['_ItemGroup']][] = $cfg;
                                }else{
                                    //vardump($cfg);
                                    if(!empty($cfg['_interfaceName'])){
                                        $MainSubs[] = $cfg;
                                    }
                                }
                                //vardump($cfg);

                            }
                        }

                    ksort($Groups);
                    if(!empty($Groups)){
                            //vardump($Groups);

                        $m = 1;
                        foreach($Groups as $Group=>$Interfaces){

                            //vardump($Interfaces);
                            $icon = WP_PLUGIN_URL.'/db-toolkit/data_report/table_branch.png';
                            if($m >= count($Groups)){
                                $icon = WP_PLUGIN_URL.'/db-toolkit/data_report/table_end.png';
                            }
                            $pageName = $Interfaces[0]['ID'];

                            if($Interfaces[0]['_menuAccess'] == 'null'){
                                $Interfaces[0]['_menuAccess'] = 'read';
                            }

                            $groupPage = add_menu_page($Group, $Group, $Interfaces[0]['_menuAccess'], $pageName, "app_launcher", $icon, '25.'.$base++);
                            add_submenu_page($pageName, $Interfaces[0]['_interfaceName'], $Interfaces[0]['_interfaceName'], $Interfaces[0]['_menuAccess'], $pageName, 'app_launcher');//admin.php?page=Database_Toolkit&renderinterface='.$interface['option_name']);

                            add_action('admin_head-'.$groupPage, 'dt_headers');
                            add_action('admin_print_scripts-'.$groupPage, 'dt_scripts');
                            add_action('admin_print_styles-'.$groupPage, 'dt_styles');
                            add_action('admin_footer-'.$groupPage, 'dt_footers');

                            for($i = 1; $i <= count($Interfaces)-1; $i++){
                                if($Interfaces[$i]['_menuAccess'] == 'null'){
                                    $Interfaces[$i]['_menuAccess'] = 'read';
                                }                            //vardump($Interfaces[$i]);
                                if(!empty($Interfaces[$i]['_interfaceName'])){
                                    $Title = $Interfaces[$i]['_interfaceName'];
                                }else{
                                    $Title = $Interfaces[$i]['_ReportDescription'];
                                }
                                $subPage = add_submenu_page($pageName, $Title, $Title, $Interfaces[$i]['_menuAccess'], $Interfaces[$i]['ID'], 'app_launcher');//admin.php?page=Database_Toolkit&renderinterface='.$interface['option_name']);

                                add_action('admin_head-'.$subPage, 'dt_headers');
                                add_action('admin_print_scripts-'.$subPage, 'dt_scripts');
                                add_action('admin_print_styles-'.$subPage, 'dt_styles');
                                add_action('admin_footer-'.$subPage, 'dt_footers');

                            }

                        $m++;
                        }

                    }
                    // Add Menues from THe Main Subs
                    // These are items without a group.


                    if(!empty($MainSubs)){
                        // find landing page
                        foreach($MainSubs as $Key=>$Interface){
                            if($Interface['ID'] == $appSettings['landing']){
                                if(!empty($Interface['_interfaceName'])){
                                    $Title = $Interface['_interfaceName'];
                                }else{
                                    $Title = $Interface['_ReportDescription'];
                                }
                                $subPage = add_submenu_page('app_'.$app, $Title, $Title, $Interface['_menuAccess'], 'app_'.$app, 'app_launcher');//admin.php?page=Database_Toolkit&renderinterface='.$interface['option_name']);
                                add_action('admin_head-'.$subPage, 'dt_headers');
                                add_action('admin_print_scripts-'.$subPage, 'dt_scripts');
                                add_action('admin_print_styles-'.$subPage, 'dt_styles');
                                add_action('admin_footer-'.$subPage, 'dt_footers');
                                unset($MainSubs[$Key]);
                            }
                        }
                        foreach($MainSubs as $Interface){
                            if(!empty($Interface['_interfaceName'])){
                                $Title = $Interface['_interfaceName'];
                            }else{
                                $Title = $Interface['_ReportDescription'];
                            }

                            $subPage = add_submenu_page('app_'.$app, $Title, $Title, $Interface['_menuAccess'], $Interface['ID'], 'app_launcher');//admin.php?page=Database_Toolkit&renderinterface='.$interface['option_name']);
                            add_action('admin_head-'.$subPage, 'dt_headers');
                            add_action('admin_print_scripts-'.$subPage, 'dt_scripts');
                            add_action('admin_print_styles-'.$subPage, 'dt_styles');
                            add_action('admin_footer-'.$subPage, 'dt_footers');

                        }
                    }
                  }

                }
            }
            ksort($menu);
            //vardump($menu);
            return;

    $interfaces = $wpdb->get_results("SELECT option_name FROM $wpdb->options WHERE `option_name` LIKE 'dt_intfc%' ", ARRAY_A);
    if(!empty($interfaces)) {
        foreach($interfaces as $interface) {

            $cfg = get_option($interface['option_name']);
             if($cfg['_menuAccess'] == 'null'){
                $cfg['_menuAccess'] = 'read';
            }

            if(!empty($user->allcaps[$cfg['_menuAccess']])){
                if(!empty($cfg['_ItemGroup'])) {
                    $Groups[$cfg['_ItemGroup']][] = $cfg;
                }

            }

        }
        if(empty($Groups)){
            return;
        }
        $base = 1;
        foreach($Groups as $Group=>$Interfaces){

            $pageName = str_replace("'",'', '_grp_'.$Group);
            $pageName = str_replace("+",'_', $pageName);
            $pageName = str_replace(" ",'_', $pageName);

            $pageName = $Interfaces[0]['ID'];


            add_admin_menu_separator('25.'.$base++);

            $groupPage = add_menu_page($Group, $Group, $Interfaces[0]['_menuAccess'], $pageName, "dbtoolkit_viewinterface", WP_PLUGIN_URL.'/db-toolkit/data_report/table.png', '25.'.$base++);
            add_submenu_page($pageName, $Interfaces[0]['_interfaceName'], $Interfaces[0]['_interfaceName'], $Interfaces[0]['_menuAccess'], $pageName, 'dbtoolkit_viewinterface');//admin.php?page=Database_Toolkit&renderinterface='.$interface['option_name']);

            for($i = 1; $i <= count($Interfaces)-1; $i++){

                $subPage = add_submenu_page($pageName, $Interfaces[$i]['_interfaceName'], $Interfaces[$i]['_interfaceName'], $Interfaces[$i]['_menuAccess'], $Interfaces[$i]['ID'], 'dbtoolkit_viewinterface');//admin.php?page=Database_Toolkit&renderinterface='.$interface['option_name']);

                add_action('admin_head-'.$subPage, 'dt_headers');
                add_action('admin_print_scripts-'.$subPage, 'dt_scripts');
                add_action('admin_print_styles-'.$subPage, 'dt_styles');
                add_action('admin_footer-'.$subPage, 'dt_footers');


            }

                add_action('admin_head-'.$groupPage, 'dt_headers');
                add_action('admin_print_scripts-'.$groupPage, 'dt_scripts');
                add_action('admin_print_styles-'.$groupPage, 'dt_styles');
                add_action('admin_footer-'.$groupPage, 'dt_footers');
        }
        ksort($menu);
        //vardump($menu);
    }
}

function dt_adminMenus() {
    global $wp_admin_bar, $wpdb;

    if (!is_admin_bar_showing() )
    return;

    $user = wp_get_current_user();
    //vardump($user);



    $interfaces = $wpdb->get_results("SELECT option_name FROM $wpdb->options WHERE `option_name` LIKE 'dt_intfc%' ", ARRAY_A);

    $Apps = get_option('dt_int_Apps');
    if(is_array($Apps)){
        foreach($Apps as $App=>$dta){

            $AppData = get_option('_'.$App.'_app');

            //if(!empty($AppData['docked'])){

                // add to menu list
                if(is_array($AppData['interfaces'])){
                    foreach($AppData['interfaces'] as $interface=>$access){
                        if(!empty($cfg)){
                            if($cfg['_menuAccess'] == 'null'){
                               $cfg['_menuAccess'] = 'read';
                            }
                            $cfg = get_option($interface);
                            if(!empty($AppData['docked'])){
                                $cfg['_Docked'] = $AppData['docked'];
                            }
                            if(!empty($user->allcaps[$cfg['_menuAccess']])){
                                if(!empty($cfg['_ItemGroup']) && !empty($cfg['_SetAdminMenu'])) {
                                    $Groups[$cfg['_ItemGroup']][] = $cfg;
                                }

                            }
                        }
                    }
                }
            //}

        }
    }




        if(!empty($Groups)){

            foreach($Groups as $Group=>$Interfaces){

                // check capability
                if(current_user_can($Interfaces[0]['_menuAccess']) && !empty($Interfaces[0]['_SetAdminMenu'])){
                    // group link
                    //$groupPage = add_object_page($Group, $Group, $Interfaces[0]['_menuAccess'], $pageName, "dbtoolkit_viewinterface", WP_PLUGIN_URL.'/db-toolkit/data_report/table.png');
                    //add_submenu_page($pageName, $Interfaces[0]['_interfaceName'], $Interfaces[0]['_interfaceName'], $Interfaces[0]['_menuAccess'], $pageName, 'dbtoolkit_viewinterface');//admin.php?page=Database_Toolkit&renderinterface='.$interface['option_name']);
                    //echo $Group.' - ';
                    if(!empty($Interfaces[0]['_Docked'])){
                        $pageLink = 'admin.php?page=';
                    }else{
                        $pageLink = 'admin.php?page=dbt_builder&renderinterface=';
                    }

                    $wp_admin_bar->add_menu( array( 'id' => $Interfaces[0]['ID'], 'title' => $Group, 'href' => get_admin_url().$pageLink.$Interfaces[0]['ID'] ) );

                    for($i = 0; $i <= count($Interfaces)-1; $i++){

                        if(!empty($Interfaces[$i]['_Docked'])){
                            $pageLink = 'admin.php?page=';
                        }else{
                            $pageLink = 'admin.php?page=dbt_builder&renderinterface=';
                        }

                        if(current_user_can($Interfaces[$i]['_menuAccess']) && !empty($Interfaces[0]['_SetAdminMenu'])){
                            $wp_admin_bar->add_menu( array( 'parent' => $Interfaces[0]['ID'], 'title' => $Interfaces[$i]['_interfaceName'], 'href' => get_admin_url().$pageLink.$Interfaces[$i]['ID'] ) );
                        }
                    }
                }
            }
        }

}

//Footers
function dt_footers() {
    require_once(DB_TOOLKIT.'data_report/footers.php');
    require_once(DB_TOOLKIT.'data_form/footers.php');
    require_once(DB_TOOLKIT.'footers.php');
}

// Ajax System
function dt_ajaxCall() {

    if(!function_exists('list_files')){
        function list_files( $folder = '', $levels = 100 ) {
                if ( empty($folder) )
                        return false;

                if ( ! $levels )
                        return false;

                $files = array();
                if ( $dir = @opendir( $folder ) ) {
                        while (($file = readdir( $dir ) ) !== false ) {
                                if ( in_array($file, array('.', '..') ) )
                                        continue;
                                if ( is_dir( $folder . '/' . $file ) ) {
                                        $files2 = list_files( $folder . '/' . $file, $levels - 1);
                                        if ( $files2 )
                                                $files = array_merge($files, $files2 );
                                        else
                                                $files[] = $folder . '/' . $file . '/';
                                } else {
                                        $files[] = $folder . '/' . $file;
                                }
                        }
                }
                @closedir( $dir );
                return $files;
        }
    }


    global $ajaxAllowedFunctions;
    do_action('dt_ajaxCall');
    // Allowed php funcitons
    // This protect the system from getting calls to malicios php functions

    // include processors libraries
    $formProcessors = list_files(WP_PLUGIN_DIR.'/db-toolkit/data_form/processors');
    foreach($formProcessors as $file){
        if(basename($file) == 'functions.php'){
            include_once($file);
        }
    }

    $viewProcessors = list_files(WP_PLUGIN_DIR.'/db-toolkit/data_report/processors');
    foreach($viewProcessors as $file){
        if(basename($file) == 'functions.php'){
           include_once($file);
        }
    }



    $ref = parse_url(basename($_SERVER['HTTP_REFERER']));

    global $wpdb;

    if(!empty($_POST['func'])) {
        $func = $_POST['func'];
        if (!empty($_POST['FARGS'])) {
            $func_args = $_POST['FARGS'];
        } else {
            $func_args = array();
        }
        if(empty($ajaxAllowedFunctions[$func])){
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

// Page Loading Functions
// Load application
function dbtoolkit_admin() {
    global $user_ID;
    if(!empty($_GET['open'])){
        if(app_exists($_GET['open'])){
            update_option('_dbt_activeApp', $_GET['open']);
        }
    }
    $activeApp = get_option('_dbt_activeApp');
    if(!empty($_GET['close'])){
        if(!empty($activeApp)){
            if($activeApp == $_GET['close']){
                update_option('_dbt_activeApp', false);
                $activeApp = false;
            }
        }
    }
    if(!empty($_GET['delete'])){
        if(!empty($activeApp)){
            if($activeApp == $_GET['delete']){
                if($_GET['delete'] == $activeApp){
                    $Apps = get_option('dt_int_Apps');
                    $appConfig = get_option('_'.$activeApp.'_app');
                    if(!empty($appConfig['interfaces'])){
                        foreach($appConfig['interfaces'] as $inf=>$val){
                            dt_removeInterface($inf);
                        }
                    }
                    if(!empty($appConfig['clusters'])){
                        foreach($appConfig['clusters'] as $inf=>$val){
                            dt_removeInterface($inf);
                        }
                    }
                    if(!empty($appConfig['imageFile'])){
                        if(file_exists($appConfig['imageFile'])){
                            unlink($appConfig['imageFile']);
                        }
                    }
                    delete_option('_'.$activeApp.'_app');
                    unset($Apps[$activeApp]);
                    update_option('dt_int_Apps', $Apps);
                    update_option('_dbt_activeApp', false);
                    $activeApp = false;
                }
                //update_option('_dbt_activeApp', false);
                ///$activeApp = false;
            }
        }
    }
    if(!empty($_GET['renderinterface'])){
        include_once(DB_TOOLKIT.'dbtoolkit_launcher.php');
        return;
    }
    if(empty($activeApp)){
        update_option('_dbt_activeApp', false);
        $activeApp = false;
        include_once(DB_TOOLKIT.'dbtoolkit_builder.php');
        return;
    }
    include_once(DB_TOOLKIT.'dbtoolkit_admin.php');
}

function dbtoolkit_cluster() {
    global $user_ID;
    include_once(DB_TOOLKIT.'dbtoolkit_cluster.php');
}

function dbtoolkit_appman() {
    global $user_ID, $wpdb;

    include_once(DB_TOOLKIT.'dbtoolkit_apps.php');
}

function dbtoolkit_dashboard() {
    global $user_ID;
    include_once(DB_TOOLKIT.'dbtoolkit_welcome.php');
}

function dbtoolkit_setup() {
    global $user_ID;
    include_once(DB_TOOLKIT.'dbtoolkit_settings.php');
}
function dbtoolkit_import() {
    global $user_ID;
    include_once(DB_TOOLKIT.'dbtoolkit_import.php');
}
function dbtoolkit_manual() {
    global $user_ID;
    include_once(DB_TOOLKIT.'manual/index.php');
}

// Interface View Functions

function dbtoolkit_viewinterface(){
    $Interface = get_option($_GET['page']);
    $Title = $Interface['_interfaceName'];
    if(!empty($Interface['_ReportDescription'])) {
       $Title = $Interface['_ReportDescription'];
    }


    ?>
<div class="wrap">
    <div id="icon-themes" class="icon32"></div><h2><?php _e($Title); ?><a class="button add-new-h2" href="admin.php?page=dbt_builder&interface=<?php echo $_GET['page']; ?>">Edit</a></h2>

    <?php
    $fset = get_option('dt_set_'.$Interface['ID']);
    if(!empty($fset)){
    ?>
    	<ul class="subsubsub">

                <?php

                    $tablen = count($fset);
                    $index = 1;
                    $link = explode('&ftab', $_SERVER['REQUEST_URI']);
                    echo '<li><a href="'.$link[0].'">All</a> | </li>';
                    foreach($fset as $tab){
                        $break = '';
                        $counter = '';
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
                        echo '<li><a href="'.$link[0].'&ftab='.$tab['code'].'">'.$tab['Title'].$counter.'</a>'.$break.'</li>';
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
    echo dt_renderInterface($_GET['page']);
    echo '</div>';
    echo '</div>';

}

// Processeing and Rendering of interfaces in wp frontend
function dt_process() {

    if(!empty($_POST['func']) && !empty($_POST['action'])) {
        if($_POST['action'] == 'wp_dt_ajaxCall') {
            dt_ajaxCall();
            exit;
        }
    }

    if(!empty($_POST['processKey'])) {


    $_POST = stripslashes_deep($_POST);
        if($_POST['processKey'] == $_SESSION['processKey']) {

            include_once(DB_TOOLKIT.'daiselements.class.php');
            include_once(DB_TOOLKIT.'data_form/class.php');
            include_once(DB_TOOLKIT.'data_report/class.php');

            unset($_SESSION['processKey']);
            $_SESSION['DF_Post'] = array();


            if(!empty($_POST['dr_update'])) {
                $EID = $_POST['dataForm']['EID'];
                $Setup = getelement($EID);
                unset($_POST['dataForm']['dr_update']);
                unset($_POST['dataForm']['EID']);
                $Return = df_processUpdate($_POST['dataForm'], $EID);
                if(!empty($Return['_fail_'])){
                    $_SESSION['failedProcess'][$EID]['Data'] = $Data;
                    $_SESSION['failedProcess'][$EID]['Fields'] = $Return['_fail_'];
                    $_SESSION['DF_Notification'] = $Return['_error_'];
                    $_SESSION['DF_NotificationTypes'][] = 'error';

                    header('Location: '.$_SERVER['HTTP_REFERER']);
                    exit;
                }
                if(!empty($Return['Value'])){
                    dr_trackActivity('Update', $EID, $Return['Value']);
                    $_SESSION['DF_Post_returnID'] = $Return['Value'];
                    $_SESSION['DF_Post_EID'] = $EID;
                }
                if(empty($Setup['Content']['_NotificationsOff'])) {
                    if(!empty($Setup['Content']['_inlineNotifications'])){
                        $_SESSION['DF_Notification'][] = $Return['Message'];
                        $_SESSION['DF_NotificationTypes'][] = $Return['noticeType'];
                    }else{
                        $_SESSION['DF_Post'][] = $Return['Message'];
                    }
                }


            }else {
                foreach($_POST['dataForm'] as $EID=>$Data) {
                    $Return = df_processInsert($EID, $Data);
                    if(!empty($Return['_fail_'])){
                        $_SESSION['failedProcess'][$EID]['Data'] = $Data;
                        $_SESSION['failedProcess'][$EID]['Fields'] = $Return['_fail_'];
                        $_SESSION['DF_NotificationTypes'][] = 'error';

                        header('Location: '.$_SERVER['HTTP_REFERER']);
                        exit;
                    }
                    // Track Activity
                    if(!empty($Return['Value']))
                    dr_trackActivity('Insert', $EID, $Return['Value']);

                    $Setup = getelement($EID);
                    if(empty($Setup['Content']['_NotificationsOff'])) {
                        if(!empty($Setup['Content']['_inlineNotifications'])){
                            $_SESSION['DF_Notification'][] = $Return['Message'];
                            $_SESSION['DF_NotificationTypes'][] = $Return['noticeType'];
                        }else{
                            $_SESSION['DF_Post'][] = $Return['Message'];
                        }
                    }
                }
            }
            $Redirect = $_SERVER['HTTP_REFERER'];
            if(!empty($Return['Value'])) {
                $ReturnValue = $Return['Value'];
            }
            if(is_admin()){
                if(!empty($Setup['Content']['_ItemViewInterface'])){
                    $Location = 'admin.php';
                }else{
                    $Location = $_SERVER['HTTP_REFERER'];
                }
            }else{
                if(!empty($Setup['Content']['_ItemViewPage'])) {
                    $Location = get_permalink($Setup['Content']['_ItemViewPage']);
                }else{
                    $Location = $_SERVER['HTTP_REFERER'];
                }
            }
            //echo $Location;
            //exit;
            if(!empty($ReturnValue)) {

                $url = parse_url($_SERVER['HTTP_REFERER']);
                $returntoken = '?';
                if(!empty($url['query'])) {
                    if(empty($Setup['Content']['_ItemViewPage'])) {
                        $Location = str_replace('?'.$url['query'], '', $_SERVER['HTTP_REFERER']);
                    }

                    parse_str($url['query'], $gets);
                    parse_str($ReturnValue, $returngets);
                    if(!empty($Setup['Content']['_ItemViewInterface'])){
                       $RedirInterface = get_option($Setup['Content']['_ItemViewInterface']);
                       if(!empty($RedirInterface['_ItemGroup'])){
                           $app = get_option('_'.$RedirInterface['_Application'].'_app');
                           if(!empty($app['docked'])){
                                $gets['page'] = $Setup['Content']['_ItemViewInterface'];
                           }else{
                                $gets['page'] = 'dbt_builder';
                                $gets['renderinterface'] = $Setup['Content']['_ItemViewInterface'];
                           }
                       }else{
                           $gets['page'] = 'dbt_builder';
                           $gets['renderinterface'] = $Setup['Content']['_ItemViewInterface'];
                       }
                    }
                    $ReturnValue = htmlspecialchars_decode(@http_build_query(array_merge($gets, $returngets)));
                }else{
                    if(!empty($Setup['Content']['_ItemViewInterface'])){
                       $RedirInterface = get_option($Setup['Content']['_ItemViewInterface']);
                       if(!empty($RedirInterface['_ItemGroup'])){
                           $app = get_option('_'.$RedirInterface['_Application'].'_app');
                           if(!empty($app['docked'])){
                                $gets['page'] = $Setup['Content']['_ItemViewInterface'];
                           }else{
                                $gets['page'] = 'dbt_builder';
                                $gets['renderinterface'] = $Setup['Content']['_ItemViewInterface'];
                           }
                       }else{
                           $gets['page'] = 'dbt_builder';
                           $gets['renderinterface'] = $Setup['Content']['_ItemViewInterface'];
                       }
                    }
                    $ReturnValue = htmlspecialchars_decode(@http_build_query($gets, $returngets));

                }
                $Redirect = $Location.$returntoken.$ReturnValue;
            }
            //echo $Redirect;
            //exit;
            header('Location: '.$Redirect);
            exit;
        }
    }
    //vardump($_POST);
    if(!empty($_POST['importKey'])) {

        $_POST = stripslashes_deep($_POST);
        if(empty($_FILES['fileImport']['size'])){
            $_SESSION['dataform']['OutScripts'] .= "
              df_buildImportForm('".$_POST['importInterface']."');
            ";
            $Redirect = $_SERVER['HTTP_REFERER'];
            header('Location: '.$Redirect);
            exit;
        }


        $path = wp_upload_dir();

		// set filename and paths
		$Ext = pathinfo($_FILES['fileImport']['name']);
		$newFileName = $_POST['importInterface'].'.'.$Ext['extension'];
		$newLoc = $path['path'].'/'.$newFileName;

        $_SESSION['import_'.$_POST['importInterface']]['import'] = wp_upload_bits($newFileName, null, file_get_contents($_FILES['fileImport']['tmp_name']));

        $_SESSION['dataform']['OutScripts'] .= "
          df_buildImportManager('".$_POST['importInterface']."');
        ";

        $Redirect = $_SERVER['HTTP_REFERER'];
        header('Location: '.$Redirect);
        exit;
    }

    if(!empty($_POST['importPrepairKey'])) {
        $Element = getelement($_POST['importInterface']);
        $_SESSION['import_'.$_POST['importInterface']]['import']['table'] = $Element['Content']['_main_table'];
        $_SESSION['import_'.$_POST['importInterface']]['import']['delimiter'] = $_POST['importDelimeter'];
        if(!empty($_POST['importSkipFirst'])){
            $_SESSION['import_'.$_POST['importInterface']]['import']['importSkipFirst'] = $_POST['importSkipFirst'];
        }
        $_SESSION['import_'.$_POST['importInterface']]['import']['map'] = $_POST['importMap'];
        $_SESSION['dataform']['OutScripts'] .= "
            df_processImport('".$_POST['importInterface']."');
        ";

        $Redirect = $_SERVER['HTTP_REFERER'];
        header('Location: '.$Redirect);
        exit;
    }

    // API Call
    //vardump($_SERVER);
    $pattern = API_getInterfaceRegex();
    if(!empty($pattern)){
        if(preg_match('/'.$pattern['regex'].'/s', $_SERVER['REQUEST_URI'], $matches)){
            include_once(DB_TOOLKIT.'libs/api_engine.php');
            exit;
        }
    }
/// EXPORT

    foreach($_GET as $PDFExport=>$Val) {
        if(!is_array($Val)) {
            if(strstr($PDFExport, 'format_')) {
                $export = explode('_dt_', $PDFExport);
                $exportFormat = $Val;
                $Media['ID'] = 'dt_'.$export[1];
                $Element = getElement($Media['ID']);
                $Config = $Element['Content'];
            }
        }
    }

    //error_reporting(E_ALL);
    //ini_set('display_errors','On');

//esds

    if(!empty($exportFormat)) {

        if($exportFormat == 'pdf') {

            include_once(DB_TOOLKIT.'daiselements.class.php');
            include_once(DB_TOOLKIT.'data_form/class.php');
            include_once(DB_TOOLKIT.'data_report/class.php');
            include_once(DB_TOOLKIT.'data_itemview/class.php');

            include_once(DB_TOOLKIT.'libs/fpdf.php');
            include_once(DB_TOOLKIT.'libs/pdfexport.php');


            $input_params["return"] = isset($input_params["return"]) ? $input_params["return"] : false;
            if(empty($Config['_orientation'])) {
                $Config['_orientation'] = 'P';
            }

        $report = new PDFReport($Config['_orientation'], $Config['_ReportTitle']);
        //you should use loadlib here
        //dump($_SESSION['reportFilters'][$Media['ID']]);
        if(!empty($Config['_FilterMode'])){
        $Res = mysql_query("SELECT ID, Content FROM `dais_elements` WHERE `Element` = 'data_report' AND `ParentDocument` = ".$Element['ParentDocument']." AND `ID` != '".$Media['ID']."';");

        while($element = mysql_fetch_assoc($Res)){            //dump($element);
            $eConfig = unserialize($element['Content']);
            $preReport['ID'] = $element['ID'];
            $preReport['Config'] = $eConfig;
            $reportExports[] = $preReport;
        }
        }else{
            $preReport['ID'] = $Media['ID'];
            $preReport['Config'] = $Config;
            $reportExports[] = $preReport;
        }

        $input_params["return"] = isset($input_params["return"]) ? $input_params["return"] : false;


        foreach($reportExports as $key=>$reportExport){
            //dump($_SESSION);
            $Continue = true;
            $Media['ID'] = $reportExport['ID'];
            $Config = $reportExport['Config'];

            foreach($reportExport['Config']['_Field'] as $Key=>$Value){
                if($Value == 'viewitem_filter'){
                    if(empty($_SESSION['viewSelector_'.$Media['ID']])){
                        $Continue = false;
                    }
                }
            }

            if(!empty($Continue)){
                $limit = 'full';
                if(!empty($_GET['limit'])) {
                    $limit = $_GET['limit'];
                }

                $OutData = dr_BuildReportGrid($Media['ID'], false, $_SESSION['report_'.$Media['ID']]['SortField'], $_SESSION['report_'.$Media['ID']]['SortDir'], 'pdf', $limit );
                //vardump($OutData);
                $CountStat = array();
                if(is_array($OutData)){
                    if($key > 0){
                        $report->addPage();
                    }

                // outdata - Headings

                    $report->cf_report_headersMain($OutData, $Config);

                    if(!empty($OutData['Totals'])) {
                        foreach($OutData['Totals'] as $Field=>$Value) {
                            sort($fieldset);
                            $totalData[$Field] = $Value;
                        }
                        $report->cf_report_datagrid($totalData, 7);
                        unset($OutData['Totals']);
                    }
                    }
                    $report->cf_report_spacer();
                    $Headers = array();
                    if(!empty($OutData[0])){
                        foreach($OutData[0] as $Header=>$v) {
                            if(strpos($Config['_IndexType'][$Header], 'hide') === false){
                                if(!empty($Config['_FieldTitle'][$Header])) {
                                    $Headers[] = $Config['_FieldTitle'][$Header];
                                }else{
                                    $Headers[] = $Header;
                                }
                            }
                        }

                        $Total = count($OutData)-1;
                        $Body = array();
                        $Counter = 1;
                        for($i = 0; $i<= $Total; $i++) {
                            if(is_array($OutData[$i])){
                                foreach($OutData[$i] as $Field=>$v){
                                    if(strpos($Config['_IndexType'][$Field], 'hide') === false){
                                        $Body[$i][] = str_replace('&nbsp;','',html_entity_decode($v));
                                    }
                                }
                            }
                        }
                    }


                    $options["width"] = "100%";
                    $report->cf_report_data_col_grid($Headers, $Body, $OutData, $Config);
                    $report->cf_report_spacer();


                //break;
                }
            }
            $report->cf_report_generate_output();
            mysql_close();
            exit;
        }




		if($exportFormat == 'csv'){

                $CSVout = fopen('php://output', 'w');






				$prequery = explode('LIMIT', $_SESSION['queries'][$Media['ID']]);
				$sql_query = $prequery[0];
			 	$filename = uniqid(date('mdHis')).'.csv';
                                $out = '';
				// Gets the data from the database
				$result = mysql_query($sql_query);
				$fields_cnt = mysql_num_fields($result);

                                //dump($Config['_Field']);

                                //dump($Config);
                                //exit;
                                $VisibleFields = array();
                                $FieldHeaders = array();
                                foreach($Config['_Field'] as $Field=>$Value){
                                    if($Config['_IndexType'][$Field] == 'index_show' || $Config['_IndexType'][$Field] == 'noindex_show'){
                                        $VisibleFields[] = $Field;
                                        $FieldHeaders[] = $Config['_FieldTitle'][$Field];
                                    }
                                }

                                ob_start();
                                fputcsv($CSVout, $FieldHeaders, ';')."\r\n";
                                $out .= ob_get_clean();

                                while($exportData = mysql_fetch_assoc($result)){

                                    // run each field type on the result
                                    $Row = array();
                                    foreach($Config['_Field'] as $Field=>$Value){
                                        $FieldType = explode('_', $Value);

                                        if(in_array($Field, $VisibleFields)){
                                            if(count($FieldType) ==2){
                                                // include fieldtype
                                                if(file_exists(DB_TOOLKIT.'/data_form/fieldtypes/'.$FieldType[0].'/functions.php')){
                                                    include_once(DB_TOOLKIT.'/data_form/fieldtypes/'.$FieldType[0].'/functions.php');
                                                }
                                                // [type_processValue($Value, $Type, $Field, $Config, $EID, $Data)
                                                $Func = $FieldType[0].'_processvalue';
                                                //$FieldValue =
                                                $outRow = $exportData[$Field];

                                                if(function_exists($Func)){
                                                   // echo 'yes there is '.$Func.'<br>';
                                                   $Row[] = trim(strip_tags(str_replace('<br />', "\r\n", $Func($outRow, $FieldType[1], $Field, $Config, $Media['ID'], $exportData))));
                                                }else{
                                                    $Row[] = $outRow;
                                                }
                                                //dump($FieldType);
                                            }else{
                                                $Row[] = $exportData[$Field];
                                            }
                                        }
                                    }

                                    //combine row
                                    ob_start();
                                    fputcsv($CSVout, $Row, ';')."\r\n";
                                    $out .= ob_get_clean();

                                }
                                //while($export)


				// Format the data





				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				//header("Content-Length: " . strlen($out));
				// Output to browser with appropriate mime type, you choose ;)
				//header("Content-type: text/x-csv");
				//header("Content-type: text/csv");
				header("Content-type: application/csv charset=UTF-8");
				header("Content-Disposition: attachment; filename=$filename");
                                //echo '<pre>';

                                echo $out;

                                //echo '</pre>';
                                fclose($CSVout);
                                mysql_close();
				exit;


		}


                if($exportFormat == 'template'){
                    echo dt_renderInterface($Media['ID']);
                    exit;
                }


        if($exportFormat != 'pdf') {
            $Element = getelement($Media['ID']);
            $Config = $Element['Content'];
            if(!empty($Config['_Show_Plugins'])) {
                // to do : configure adding plugins to the tool bar
                if(file_exists(DB_TOOLKIT.'data_report/plugins/'.$exportFormat.'/functions.php')) {
                    include_once(DB_TOOLKIT.'data_report/plugins/'.$exportFormat.'/functions.php');
                    mysql_close();
                    exit;
                }
            }
        }

    }




}



function dt_rendercluster($cluster){

    $Interface = get_option($cluster);
    $cfg = unserialize(base64_decode($Interface['Content']));
    parse_str($cfg['_clusterLayout'], $layout);

    // Build Layout Array First...

    if(is_admin ()){
    echo '<div class="wrap">';
        echo '<div id="poststuff">';
        //echo '<div class="metabox-holder">';
    }
        foreach($cfg['_grid'] as $row=>$cols){

            echo '<div id="clusterRow cluster-'.$row.'" style="width:100%; overflow:hidden;" class="formRow">';

                foreach($cols as $col=>$width){

                    echo '<div class="clusterColumn cluster-'.$row.'-'.$col.'" id="'.$row.'_'.$col.'" style="width: '.$width.'; float: left;">';
                    echo '<div class="clusterItem">';
                        $content = array_keys($layout, $row.'_'.$col);
                        if(!empty($content)){
                            $output = '';
                            foreach($content as $render){
                                $output .= dt_renderInterface($render);
                            }
                            echo $output;
                        }else{
                            echo '&nbsp;';
                        }

                    echo '</div>';
                    echo '</div>';

                }

            echo '<div style="clear:both;"></div>';
            echo '</div>';

        }
    if(is_admin ()){
            //echo '</div>';
        echo '</div>';
    echo '</div>';
    }

    return;
}



// Render interface from shortcode to front end and view
function dt_renderInterface($interface){

    if(is_array($interface)) {
        if(!empty($interface['id'])){
            unset($_SESSION['viewitemFilter'][$interface['id']]);
            $ID = $interface['id'];
            unset($interface['id']);
        }
        if(!empty($interface['filter']) && !empty($interface['by'])){
            $_GET[$interface['filter']] = $interface['by'];
            unset($interface['filter']);
            unset($interface['by']);
        }
    }else {
        unset($_SESSION['viewitemFilter'][$interface]);
        $ID = $interface;
    }
    $Media = get_option($ID);
    if(empty($Media)) {
        return;
    }

    if($Media['Type'] == 'Cluster'){
        ob_start();
        dt_rendercluster($ID);
        $Return = ob_get_clean();
        $Return = do_shortcode(do_shortcode(do_shortcode($Return)));

        $order   = array("\r\n", "\n", "\r", "\n\n", "\r\r", "  ");
        $replace = "";
        $Return = str_replace($order, $replace, $Return);
        return $Return;

    }

    if($Media['_menuAccess'] != 'null'){
        $user = wp_get_current_user();
        if(empty($user->allcaps[$Media['_menuAccess']])){
            return;
        }
    }
    $Media['Content'] = unserialize(base64_decode($Media['Content']));
    $Config = $Media['Content'];

    // get the $_GET['returnVars']
    if(!empty($_GET['_returnVars'])){

        if(!empty($Config['_ReturnFields'])){
            foreach($Config['_ReturnFields'] as $returnKey=>$ReturnVal){
                $_GET[$ReturnVal] = $_GET['_returnVars'][$returnKey];
            }

        }

    }

    $Return = '';
    //check if there is a table
    if(empty($Config['_main_table'])){
        $Return = '<div id="interfaceError" class="notice" style="padding:5px;">No table Specified.</div>';
        return str_replace("\r\n", '', $Return);
    }
    if($Config['_ViewMode'] == 'API'){
        include('api_details.php');
        $Return = do_shortcode(do_shortcode(do_shortcode($Return)));
        return str_replace("\r\n", '', $Return);
    }

        if(empty($_SESSION['report_'.$Media['ID']]['limitOveride'])){
            $_SESSION['report_'.$Media['ID']]['limitOveride'] = false;
        }

        if(!empty($_GET['limit'])){
            $_SESSION['report_'.$Media['ID']]['limitOveride'] = floatval($_GET['limit']);
        }

    ob_start();
        include(DB_TOOLKIT.'data_report/element.def.php');

    $toolBar = ob_get_clean();


    // Load ToolBar
    if(empty($Render)){
        return '<div id="reportPanel_'.$Media['ID'].'"></div>';
    }
    if($Config['_ViewMode'] == 'list'){
        ob_start();
            include(DB_TOOLKIT.'data_report/toolbar.php');
            include(DB_TOOLKIT.'data_report/filters.php');
        $Return .= ob_get_clean();
    }
    if($Config['_ViewMode'] == 'filter'){
        $Config['_Show_Filters'] = true;
        $Config['_toggle_Filters'] = false;
        ob_start();
            include(DB_TOOLKIT.'data_report/filters.php');
        $Return .= ob_get_clean();
    }


    // Determine Mode
    if(empty($Config['_ViewMode'])){
        $Config['_ViewMode'] = 'list';
    }

    if(!empty($_GET['npage'])){
        $newPage = floatval($_GET['npage']);
        if(is_array($interface)){
            $_SESSION['report_'.$interface['id']]['LastPage'] = $newPage;
        }else{
            $_SESSION['report_'.$interface]['LastPage'] = $newPage;
        }
    }

    switch ($Config['_ViewMode']){

        case 'list':
            ob_start();
                include(DB_TOOLKIT.'data_report/listmode.php');
            $Return .= ob_get_clean();
            break;
        case 'view':
            ob_start();
                include(DB_TOOLKIT.'data_report/viewmode.php');
            $Return .= ob_get_clean();
            break;
        case 'form':
            ob_start();
                include(DB_TOOLKIT.'data_report/formmode.php');
            $Return .= ob_get_clean();
            break;
        case 'search':

            //_useToolbarTemplate _layoutTemplate
            if(!empty($_SESSION['DF_Notification'])){
                ob_start();
                foreach($_SESSION['DF_Notification'] as $Key=>$Notice){
                $uid = uniqid();
                ?>
                    <div class="alert alert-<?php echo $_SESSION['DF_NotificationTypes'][$Key]; ?>" id="<?php echo $uid; ?>">
                    <a class="close" onClick="jQuery('#<?php echo $uid; ?>').fadeOut('slow');">?</a>
                    <?php echo $Notice; ?>
                    </div>
                <?php
                }
                unset($_SESSION['DF_Notification']);
                $Return .= ob_get_clean();
            }

            ob_start();
                include(DB_TOOLKIT.'data_report/searchmode.php');
            $Return .= ob_get_clean();
            break;
    }


    if($error = mysql_error() && $Config['_ViewMode'] != 'form'){
        if(is_admin()){
            $InterfaceData = get_option($Media['ID']);
            $InterfaceDataraw = base64_encode(serialize($InterfaceData));

            if(empty($_SESSION['errorReport'][$Media['ID']][md5($InterfaceDataraw)])){

                ob_start();
                echo '<h4>Error</h4>';
                echo $error;
                echo '<h4>Queries</h4>';

                $error = ob_get_clean();



                if(!empty($Config['_UserQueryOveride']) && !empty($Config['_QueryOveride'])){

                    echo '<div id="interfaceError" class="notice" style="padding:5px;">'.mysql_error().'</div>';
                }else{
                    echo '<div id="interfaceError" class="notice" style="padding:5px;">An error has been detected while building this interface. Would you like to submit an error report to the developer? <input type="button" class="button" value="Send Report" onclick="dbt_sendError(\''.$Media['ID'].'\', \''.  base64_encode($error).'\');" /></div>';



                }
            }
        }
    }

    $Return = do_shortcode(do_shortcode(do_shortcode($Return)));

    $order   = array("\r\n", "\n", "\r", "\n\n", "\r\r", "  ");
    $replace = "";
    $Return = str_replace($order, $replace, $Return);
    return $Return;

}

function dbt_sendError($Interface, $ErrorData){

    global $current_user;
    get_currentuserinfo();



$InterfaceData = get_option($Interface);
$InterfaceDataraw = base64_encode(serialize($InterfaceData));
$InterfaceData['Content'] = unserialize(base64_decode($InterfaceData['Content']));

    unset($_SESSION['errorReport'][$Interface]);
    $_SESSION['errorReport'][$Interface][md5($InterfaceDataraw)] = true;

    vardump($InterfaceData);
   // exit;


$to = 'DB-Toolkit Support <support@dbtoolkit.co.za>';
$subject = 'DB-Toolkit Error Report';
ob_start();
echo "<h4>Wordpress Details</h4>";
vardump('Site Name:'.get_bloginfo('name'));
vardump('Site URL:'.get_bloginfo('siteurl'));
vardump('Admin Email:'.get_bloginfo('admin_email'));
vardump('Wordpress Version:'.get_bloginfo('version'));
echo "<h4>Query Error</h4>";
vardump(base64_decode($ErrorData));
echo "<h4>Config</h4>";
vardump($InterfaceData);
echo '<h4>Raw Config</h4>';
echo $InterfaceDataraw;

$message = ob_get_clean();
$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'From: '.$current_user->display_name.' <'.$current_user->user_email.'>' . "\r\n";


return mail($to, $subject, $message, $headers);

}


// delete interface
function dt_removeInterface($Interface) {

    $activeApp = get_option('_dbt_activeApp');
    if(empty($activeApp))
        return false;

    $app = get_option('_'.$activeApp.'_app');

    if(!empty($app['interfaces'])){

        if(key_exists($Interface,  $app['interfaces'])){
            unset($app['interfaces'][$Interface]);
            // delete extras

            //delete bindings
            $infCfg = get_option($Interface);
            if(!empty($infCfg['_ItemBound'])){
                delete_option('_dbtbinding_'.$infCfg['_ItemBound']);
            }
            delete_option($Interface);
            delete_option('filter_Lock_'.$Interface);
            delete_option('dt_set_'.$Interface);

        }
    }

    if(!empty($app['clusters'])){
        if(key_exists($Interface,  $app['clusters'])){
            unset($app['clusters'][$Interface]);

            //delete bindings
            $infCfg = get_option($Interface);
            if(!empty($infCfg['_ItemBound'])){
                delete_option('_dbtbinding_'.$infCfg['_ItemBound']);
            }
            delete_option($Interface);
            delete_option('filter_Lock_'.$Interface);
            delete_option('dt_set_'.$Interface);

        }
    }

    update_option('_'.$activeApp.'_app', $app);
    return true;
}


function dt_admin_init(){
	if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
		if ( in_array(basename($_SERVER['PHP_SELF']), array('post-new.php', 'page-new.php', 'post.php', 'page.php') ) ) {
			add_filter('mce_buttons', 'dt_mce_button');
			add_filter('mce_external_plugins', 'dt_mce_plugin');
			//add_action('admin_head','add_simple_buttons');
			add_action('edit_form_advanced', 'dt_advanced_buttons');
			add_action('edit_page_form', 'dt_advanced_buttons');
		}
	}
}

function dt_mce_button($buttons) {
	array_push( $buttons, '|', 'db_toolkitInterface');

	return $buttons;
}

function dt_mce_plugin($plugins) {
	$plugins['db_toolkit'] = WP_PLUGIN_URL. '/db-toolkit/libs/editor_plugin.js';

	return $plugins;
}

function dt_advanced_buttons(){ ?>
	<script type="text/javascript">
		var defaultSettings = {},
			outputOptions = '',
			selected ='',
			content = '';

		defaultSettings['toolkitInterface'] = {
			caption: {
				name: 'Caption',
				defaultvalue: 'Caption goes here',
				description: 'Caption title goes here',
				type: 'text'
			},
			state: {
				name: 'State',
				defaultvalue: 'close',
				description: 'Select between expanded and closed state',
				type: 'select',
				options: 'open|close'
			},
			content: {
				name: 'Content',
				defaultvalue: 'Content goes here',
				description: 'Content text or html',
				type: 'textarea'
			}
		};


                function ajaxCall() {
                    ajaxurl_dbt = ajaxurl;
                    var vars = { action : 'dt_ajaxCall',func: ajaxCall.arguments[0]};
                    for(i=1;ajaxCall.arguments.length-1>i; i++) {
                        vars['FARGS[' + i + ']'] = ajaxCall.arguments[i];
                    }
                    var callBack = ajaxCall.arguments[ajaxCall.arguments.length-1];
                    jQuery.post(ajaxurl_dbt,vars, function(data){
                        callBack(data);
                    });
                }


		function insertInterface(tag){

			tbWidth = 500;
			tbHeight = 104;
                        if(jQuery('#db_tookitInsertInterface_Panel').length == 0){
                            var tbOptions = "<div id='db_tookitInsertInterface_Panel'>";
                            tbOptions += '<div id="dbToolkitInterfaceApps">Loading Apps</div>';
                            tbOptions += '<div style="clear:both;"></div>';
                            tbOptions += '</div>';

                            var form = jQuery(tbOptions);
                            var table = form.find('table');
                            form.appendTo('body').hide();
                        }else{
                            tb_show( 'Insert Interface', '#TB_inline?inlineId=db_tookitInsertInterface_Panel' );
                            return;
                        }

                    tb_show( 'Insert Interface', '#TB_inline?inlineId=db_tookitInsertInterface_Panel' );

                    ajaxCall('dt_listApps', function(d){

                        jQuery('#dbToolkitInterfaceApps').html(d.html);
                        jQuery('#dbtoolkit_AppList').change(function(){
                            jQuery('#dbtoolkit_InterfaceList').html('<h3>Loading....</h3>');
                            dtLoadApp(this.value);
                        });
                    });
		}

                function dtLoadApp(app){
                    ajaxCall('dt_listInterfaces', app, function(i){
                        jQuery('#dbtoolkit_InterfaceList').html(i);
                        jQuery('.interfaceInserter').click(function(){
                            //alert(this.value);
                            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, ' [interface id="'+this.id+'"] ');
                            tb_remove();
                        });
                    });
                }
		jQuery(document).ready(function(){

                   // alert('pong');

                });
	</script>
<?php }

function dt_publicReg($a, $b, $c){
    global $current_user;

    if(!empty($a[0])){
        // check permissions for public
        if($a[0] == 'public'){
            // check the user is not signed in
            if(empty($current_user->id)){
                return do_shortcode($b);
            }
        }
        if($a[0] == 'private'){
            // check the user is signed in
            if(!empty($current_user->id)){
                return do_shortcode($b);
            }
        }

    }
    return;
}

/// Dashboard Widgets

// Create the function to output the contents of our Dashboard Widget

function dt_renderDashboardWidget($a, $b) {
    //vardump($b);
    echo dt_renderInterface($b['id']);

}

// Create the function use in the action hook

function dt_dashboard_widgets() {
    global $wpdb;
    $dashBoardWidgets = $wpdb->get_results("SELECT option_name FROM $wpdb->options WHERE `option_name` LIKE 'dt_intfc%' ", ARRAY_A);
    if(!empty($dashBoardWidgets)){
        add_action('admin_head', 'dt_headers');
        add_action('admin_print_scripts', 'dt_scripts');
        add_action('admin_print_styles', 'dt_styles');
        add_action('admin_footer', 'dt_footers');
    }
    foreach($dashBoardWidgets as $widget) {

        $myWidget = get_option($widget['option_name']);
        if(!empty($myWidget['_Dashboard'])) {

            $Title = $myWidget['_interfaceName'];
            $Show = true;
            if(!empty($myWidget['_ReportDescription'])) {
                $Title = $myWidget['_ReportDescription'];
            }
            if($myWidget['_menuAccess'] != 'null'){
                $user = wp_get_current_user();
                if(empty($user->allcaps[$myWidget['_menuAccess']])){
                    $Show = false;
                }
            }
            if(!empty($Show)){

                wp_add_dashboard_widget($myWidget['ID'], $Title, 'dt_renderDashboardWidget', 'alert');
            }
        }
    }

}

function dt_remove_dashboard_widgets() {
	// Globalize the metaboxes array, this holds all the widgets for wp-admin
        // chose to keep these as the user can remove the defaults if they so choose.
        // perhaps i'll make a setting to keep remove defaults

                $defaults = get_option('_dbtoolkit_defaultinterface');
                if(empty($defaults['_DisableDashboardDefaults'])){
                    return;
                }

    global $wp_meta_boxes;



        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);

        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
}


function dt_iconSelector($default = false){
    $dir = WP_PLUGIN_DIR.'/db-toolkit/images/icons';
    $icons_dir = opendir($dir);
    ob_start();
    while (($icon = readdir($icons_dir)) !== false) {
        if($icon != '.' && $icon != '..') {
            $dt = pathinfo(WP_PLUGIN_DIR.'/db-toolkit/images/icons/'.$icon);

            if(strtolower($dt['extension']) == 'png'){
                $IconID = uniqid('icon_');
                $Sel = '';
                if($default == $dt['basename']){
                    $Sel = 'checked="checked"';
                }
                echo '<div style="padding:4px; float:left;">';
                echo '<label for="'.$IconID.'"><img src="'.WP_PLUGIN_URL.'/db-toolkit/images/icons/'.$dt['basename'].'" width="16" height="16" /></label>';
                echo '<input type="radio" name="selectedIcon" id="'.$IconID.'" value="'.WP_PLUGIN_URL.'/db-toolkit/images/icons/'.$dt['basename'].'" '.$Sel.' />';
                echo '</div>';
            }
        }
    }
    echo '<br class="clearfix">';
    return ob_get_clean();
}

function dt_appMarket(){
    include(DB_TOOLKIT.'marketplace.php');
}

// Hoook into the 'wp_dashboard_setup' action to register our function

function dbtoolkit_bugreport(){
    include(DB_TOOLKIT.'bugreport.php');
}

function dt_saveFilterLock($Interface, $Settings = false){

    if(!empty($Settings)){
        $Title = $Settings[0]['value'];
        $Count = 'no';
        if(!empty($Settings[1]['value'])){
            $Count = 'yes';
        }
        $Newset = get_option('filter_Lock_'.$Interface);
        $fset = get_option('dt_set_'.$Interface);
        if(!empty($Title)){
            $set['Title'] = $Title;
            $set['code'] = uniqid();
            $set['ShowCount'] = $Count;
            $set['Filters'] = $Newset;
            $fset[] = $set;
            update_option('dt_set_'.$Interface, $fset);
            return true;
        }
        ob_start();
        ?>
            <div style="padding: 0pt 0.7em;" class="ui-state-error ui-corner-all">
                <p><span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-alert"></span>
                <strong>Alert:</strong> You need to provide a <strong>Set Name</strong>.</p>
            </div>
        <?php
        $error = ob_get_clean();
    }

    ob_start();
    if(!empty($error)){
        echo $error;
    }
    echo dais_customfield('text', 'Set Title', '_SetTitle', '_SetTitle', 'list_row1' , '' , false);
    echo dais_customfield('checkbox', 'Show Count', '_ShowCount', '_ShowCount', 'list_row2' , '1' , false);
    $Out = ob_get_clean();
    return $Out;

}

function core_loadSupportFeed($url){

    include_once(ABSPATH . WPINC . '/feed.php');

    // Get a SimplePie feed object from the specified feed source.
    $rss = fetch_feed($url);
    if (!is_wp_error( $rss ) ) : // Checks that the object is created correctly
        // Figure out how many total items there are, but limit it to 5.
        $maxitems = $rss->get_item_quantity(5);

        // Build an array of all the items, starting with element 0 (first element).
        $rss_items = $rss->get_items(0, $maxitems);
    endif;
    ?>

    <ul>
        <?php if ($maxitems == 0) echo '<li>No items.</li>';
        else
        // Loop through each feed item and display each item as a hyperlink.
        foreach ( $rss_items as $item ) : ?>
        <li>
            <a class="rsswidget" href='<?php echo $item->get_permalink(); ?>'
            title='<?php echo 'Posted '.$item->get_date('j F Y | g:i a'); ?>'>
            <?php echo $item->get_title(); ?></a><span class="rss-date"><?php echo $item->get_date('j F Y | g:i a'); ?></span><br />
            <?php echo str_replace('<hr />' , '<br />', $item->get_description()); ?>
        </li>
        <?php endforeach; ?>
    </ul>
<?php
}

function dr_callInterface($EID, $str){


    parse_str($str, $_GET);
    return dt_renderInterface($EID);

}

/// System Utilities

function core_cleanSystemTables(&$value, $key){
    global $wpdb;
    $value = str_replace($wpdb->prefix, '{{wp_prefix}}', $value);
}

function core_applySystemTables(&$value, $key){
    global $wpdb;
    $value = str_replace('{{wp_prefix}}',$wpdb->prefix, $value);
}

/// App Exporter


function exportApp($app, $publish=false){

    global $wpdb;
    $output = array();

    // Export Main App Definition
    $allApps = get_option('dt_int_Apps');
    $appKey = sanitize_title($app['name']);
    $output['MainApp'] = serialize($allApps[$appKey]);


    // Export Application Settings
    $output['AppSettings'] = serialize($app);

    // Export Interfaces and Filter Locks
    $output['Interfaces'] = array();
    $output['FilterLocks'] = array();
    if(!empty($app['interfaces'])){
        foreach($app['interfaces'] as $InterfaceID=>$Access){
            $output['Interfaces'][$InterfaceID] = get_option($InterfaceID);
            // check for filter locks
            if($lockCheck = get_option('filter_Lock_'.$InterfaceID)){
                $output['FilterLocks']['filter_Lock_'.$InterfaceID] = serialize(get_option('filter_Lock_'.$InterfaceID));
            }
        }
    }

    // Export Clusters
    $output['Clusters'] = array();
    if(!empty($app['clusters'])){
        foreach($app['clusters'] as $InterfaceID=>$Access){
            $output['Clusters'][$InterfaceID] = serialize(get_option($InterfaceID));
        }
    }

    // Export Logo
    if(empty($app['imageFile'])){
        $app['imageFile'] = '';
    }
    if(file_exists($app['imageFile'])){
        $output['Logo'] = base64_encode(file_get_contents($app['imageFile']));
    }

    // Export Tables, Prepare Interfaces and Page Bindings
    $tables = array();
    $systemtables = array($wpdb->prefix.'commentmeta', $wpdb->prefix.'comments',$wpdb->prefix.'dbt_wplogin',$wpdb->prefix.'links',$wpdb->prefix.'options',$wpdb->prefix.'postmeta',$wpdb->prefix.'posts',$wpdb->prefix.'term_relationships',$wpdb->prefix.'term_taxonomy',$wpdb->prefix.'terms',$wpdb->prefix.'usermeta',$wpdb->prefix.'users');
    foreach($app['interfaces'] as $interface=>$Access){


        $cfg = unserialize(base64_decode($output['Interfaces'][$interface]['Content']));
        if(!in_array($cfg['_main_table'], $systemtables)){
            $tables[str_replace($wpdb->prefix, '{{wp_prefix}}', $cfg['_main_table'])] = $cfg['_main_table'];
        }

        if(!empty($cfg['_Linkedfields'])){
            foreach($cfg['_Linkedfields'] as $Field=>$Value){
                if(empty($tables[$Value['Table']])){
                    $tables[str_replace($wpdb->prefix, '{{wp_prefix}}', $Value['Table'])] = $Value['Table'];
                }
            }
        }
        if(!empty($cfg['_Linkedfilterfields'])){
            foreach($cfg['_Linkedfilterfields'] as $Field=>$Value){
                if(!in_array($Value['Table'], $systemtables)){
                    if(empty($tables[$Value['Table']])){
                        $tables[str_replace($wpdb->prefix, '{{wp_prefix}}', $Value['Table'])] = $Value['Table'];
                    }
                }
            }
        }

        // Export Page Bindings
        if(!empty($cfg['_ItemBoundPage'])){
            $page = get_page($cfg['_ItemBoundPage']);
            $output['Bindings'][$interface] = $page->post_name;
            unset($cfg['_ItemBoundPage']);
        }
        //vardump($cfg);
        //die;
        // Export Page Dependencies
        if(!empty($cfg['_ItemViewPage'])){
            $page = get_page($cfg['_ItemViewPage']);
            $output['Dependencies'][$page->post_name][] = $interface;
            //unset($cfg['_ItemViewPage']);
        }

        array_walk_recursive($cfg, 'core_cleanSystemTables');
        $output['Interfaces'][$interface]['Content'] = base64_encode(serialize($cfg));
        $output['Interfaces'][$interface] = serialize($output['Interfaces'][$interface]);



    }
    $output['Tables'] = $tables;

        // Export Table Structures and Data
        if(!empty($tables)){
            $output['Data'] = array();
            foreach($tables as $tableKey=>$table){
                $tableCreates = $wpdb->get_row("SHOW CREATE TABLE ".$table, ARRAY_N);
                $output['Tables'][$tableKey] = base64_encode(str_replace($wpdb->prefix, '{{wp_prefix}}', $tableCreates[1]));
                $result = $wpdb->get_results("SELECT * FROM `".$tableCreates[0]."`", ARRAY_A);
                foreach($result as $entries){

                    $Fields = array();
                    $Values = array();
                    foreach ($entries as $field=>$value){
                        $Fields[] = '`'.$field.'`';
                        $Values[] = "'".mysql_real_escape_string($value)."'";
                    }
                    $output['Data'][] = base64_encode("INSERT INTO `".$tableKey."` (".implode(',', $Fields).") VALUES (".implode(',', $Values).");");
                }
            }
        }


        $fileName = sanitize_file_name($app['name'].'.dbt');


        if(empty($publish)){
            $output = gzdeflate(base64_encode(serialize($output)),9);
            header ("Expires: Mon, 21 Nov 1997 05:00:00 GMT");    // Date in the past
            header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
            header ("Pragma: no-cache");                          // HTTP/1.0
            header('Content-type: application/dbt');
            header('Content-Disposition: attachment; filename="'.$fileName.'"');
            print($output);
            exit;
        }else{
            $appID = uniqid('dbt');
            $output = base64_encode(serialize($output));
            $template = file_get_contents(__dir__.'/plugtemplate.php');
            $template = str_replace('{{appID}}', $appID, $template);
            $template = str_replace('{{appName}}', $app['name'], $template);
            $template = str_replace('{{appURI}}', $app['pluginURI'], $template);
            $template = str_replace('{{appAuthor}}', $app['pluginAuthor'], $template);
            $template = str_replace('{{appVersion}}', $app['pluginVersion'], $template);
            $template = str_replace('{{authorURI}}', $app['pluginAuthorURI'], $template);

            if(!empty($app['description'])){
                $template = str_replace('{{appDescription}}', $app['description'], $template);
            }else{
                $template = str_replace('{{appDescription}}', 'No Description Given', $template);
            }

            $output = str_replace('{{exportData}}', $output, $template);

            header ("Expires: Mon, 21 Nov 1997 05:00:00 GMT");    // Date in the past
            header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
            header ("Pragma: no-cache");                          // HTTP/1.0
            header('Content-type: application/php');
            header('Content-Disposition: attachment; filename="'.$fileName.'.php"');


            //vardump($current_user);
            //vardump($export);
            //vardump($output);

            print($output);
            exit;
            //$src = wp_upload_bits($fileName.'.gz', null, $output);
            //return $src;
            //vardump($upload);
            //die;

        }
}

function core_createInterfaces($Installer){

    $apps = get_option('dt_int_Apps');
    $data = file_get_contents($Installer);
    if($predata = @gzinflate($data)){
        $data = $predata;
    }
    $data = unserialize(base64_decode($data));

    if(empty($apps[sanitize_title($data['application'])])){
        $apps[sanitize_title($data['application'])]['state'] = 'open';
        $apps[sanitize_title($data['application'])]['name'] = $data['application'];
        update_option('dt_int_Apps', $apps);
    }



    if(!empty($data['interfaces'])){
        foreach($data['interfaces'] as $interface=>$configData){
            //vardump($interface);
            //$Config = unserialize(base64_decode($configData));
            $Config = $configData;
            array_walk_recursive($Config, 'core_applySystemTables');
            update_option($interface, $Config);
            app_update($data['application'], $interface, $Config['_menuAccess']);
        }

        // Update App Config

        return true;
    }else{
        unlink($Installer);
        unset($_SESSION['appInstall']);
        return false;
    }
    unlink($Installer);
    unset($_SESSION['appInstall']);
    return false;
    //vardump($data);
}

function core_createTables($Installer){

    if(is_admin ()){
        global $wpdb;
        $user = wp_get_current_user();
        if(empty($user->caps['administrator'])){
            return false;
        }


        $apps = get_option('dt_int_Apps');
        $data = file_get_contents($Installer);
        if($predata = @gzinflate($data)){
            $data = $predata;
        }
        $data = unserialize(base64_decode($data));

        if(!empty($data['tables'])){
            foreach($data['tables'] as $table=>$configData){
                $Query = base64_decode($configData);
                echo $Query;
                $wpdb->query($Query);
            }
            return true;
        }else{
            return true;
        }
        unlink($Installer);
        unset($_SESSION['appInstall']);
        return false;
    }

    return false;
    //vardump($data);
}

function core_populateApp($Installer){

    global $wpdb;
    $apps = get_option('dt_int_Apps');
    $data = file_get_contents($Installer);
    if($predata = @gzinflate($data)){
        $data = $predata;
    }
    $data = unserialize(base64_decode($data));

    if(!empty($data['entries'])){
        foreach($data['entries'] as $table=>$entries){
            foreach($entries as $entry){
                $Query = base64_decode($entry);
                $wpdb->query($Query);
            }
        }
        unlink($Installer);
        unset($_SESSION['appInstall']);
        dr_rebuildApps();
        return true;
    }else{
        unlink($Installer);
        unset($_SESSION['appInstall']);
        dr_rebuildApps();
        return true;
    }
    unlink($Installer);
    unset($_SESSION['appInstall']);
    dr_rebuildApps();
    return false;
}

// Rebuild Application Indexes
function dr_rebuildApps(){

    global $wpdb;

    $apps = get_option('dt_int_Apps');
    if(empty($apps)){
        return ' NOT';
    }

    $newStructure = array();
    foreach($apps as $title=>$app){

        if(!is_array($app)){
            $appConfig = array();
            $key = sanitize_title($title);
            $newStructure[$key]['state'] = $app;
            $newStructure[$key]['name'] = $title;
            $newStructure[$key]['description'] = '';

            $appConfig['state'] = $app;
            $appConfig['name'] = $title;
            $appConfig['description'] = '';

            $interfaces = $wpdb->get_results("SELECT `option_value` FROM `".$wpdb->options."` WHERE `option_value` LIKE '%_Application\";s:".strlen($title).":\"".$title."\"%'", ARRAY_A);

            foreach($interfaces as $interface){
                $interfaceData = unserialize($interface['option_value']);
                if($interfaceData['Type'] != 'Cluster'){
                    $appConfig['interfaces'][$interfaceData['ID']] = $interfaceData['_menuAccess'];
                    $appConfig['interfaces'][$interfaceData['ID']] = $interfaceData['_menuAccess'];
                }else{
                    $appConfig['clusters'][$interfaceData['ID']] = $interfaceData['_menuAccess'];
                    $appConfig['clusters'][$interfaceData['ID']] = $interfaceData['_menuAccess'];
                }
            }
            update_option('_'.$key.'_app', $appConfig);
        }else{
            $key = sanitize_title($title);
            $newStructure[$key] = $app;
            $appConfig = get_option('_'.$key.'_app');
            $interfaces = $wpdb->get_results("SELECT `option_value` FROM `".$wpdb->options."` WHERE `option_value` LIKE '%_Application\";s:".strlen($title).":\"".$title."\"%'", ARRAY_A);
            foreach($interfaces as $interface){
                $interfaceData = unserialize($interface['option_value']);
                if($interfaceData['Type'] != 'Cluster'){
                    $appConfig['interfaces'][$interfaceData['ID']] = $interfaceData['_menuAccess'];
                    $appConfig['interfaces'][$interfaceData['ID']] = $interfaceData['_menuAccess'];
                }else{
                    $appConfig['clusters'][$interfaceData['ID']] = $interfaceData['_menuAccess'];
                    $appConfig['clusters'][$interfaceData['ID']] = $interfaceData['_menuAccess'];
                }
            }
            update_option('_'.$key.'_app', $appConfig);
        }

    }
    //Update the structure
    update_option('dt_int_Apps', $newStructure);


    return 'done';

}

function API_getCurrentUsersKey(){
    return rtrim(base64_encode(urlencode(base64_encode(gzdeflate(get_current_user_id().'::'.get_user_by('id', get_current_user_id())->user_pass)))), '=');
}

function API_decodeUsersAPIKey($key){
    $str = base64_decode(urldecode(base64_decode($key)));
    if($str = @gzinflate($str)){
        $det = explode('::', $str);
        return array('id'=>$det[0], 'pass_word'=>$det[1]);
    }else{
        return false;
    }
}

function API_getInterfaceRegex(){
        global $wpdb;
        $interfaces = $wpdb->get_results("SELECT option_name FROM $wpdb->options WHERE `option_name` LIKE 'dt_intfc%' ", ARRAY_A);
        $list = array();
        foreach($interfaces as $interface){
            $interface = get_option($interface['option_name']);
            $Config = unserialize(base64_decode($interface['Content']));
            if($Config['_ViewMode'] == 'API' || !empty($Config['_DataSourceFieldMap'])){

                if(!empty($Config['_APICallName'])){
                    $list[] = '\/'.$Config['_APICallName'].'\/';
                    $output['interfaces'][$Config['_APICallName']] = $interface['ID'];
                }else{
                    $list[] = '\/'.$interface['ID'].'\/';
                }

            }
        }
        if(!empty($list)){
            $output['regex'] = implode('|', $list);
            return $output;
        }
return false;
}

?>