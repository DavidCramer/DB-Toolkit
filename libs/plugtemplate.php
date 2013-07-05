<?php
/*
Plugin Name: {{appName}}
Plugin URI: {{appURI}}
Description: {{appDescription}}
Author: {{appAuthor}}
Version: {{appVersion}}
Author URI: {{authorURI}}
*/


/* Please do not touch anything bellow this line. */
if(!in_array( 'db-toolkit/plugincore.php', apply_filters( 'active_plugins', get_option( 'active_plugins' )))){
    add_action('admin_init', 'adminInit{{appID}}');
    function adminInit{{appID}}(){
        add_action('admin_notices', 'dependancyNotice{{appID}}');
    }
    function dependancyNotice{{appID}}() {

        if ( !current_user_can( 'manage_options' ) || !empty($_GET['tab']))
                return;
        ?>
        <div id="message" class="error">
            <h3>{{appName}} requires DB-Toolkit to be installed and Activated. <a title="More information about DB Toolkit 0.3.0.133" class="thickbox" href="plugin-install.php?tab=plugin-information&amp;plugin=db-toolkit&amp;TB_iframe=true&amp;width=640&amp;height=496">Details</a></h3>
        </div>
        <?php
    }

}else{
    register_activation_hook(__FILE__, 'install{{appID}}');
    register_deactivation_hook(__FILE__, 'uninstall{{appID}}');
    function install{{appID}}(){
        global $wpdb;
        
        $data = "{{exportData}}";

        $installData = unserialize(base64_decode(urldecode($data)));
        $isInstalled = get_option('_installed_{{appID}}');
        if(empty($isInstalled)){
            if(!empty($installData['tables'])){
                foreach($installData['tables'] as $table){
                    $data = $wpdb->query(base64_decode($table));
                }
            }
            if(!empty($installData['entries'])){
                foreach($installData['entries'] as $tableSet){
                    foreach($tableSet as $entrySet){
                        $data = $wpdb->query(base64_decode($entrySet));
                    }
                }
            }
            update_option('_installed_{{appID}}', true);
        }

        $apps = get_option('dt_int_Apps');
        $installData['appInfo']['docked'] = 1;
        $appTitle = '{{appID}}';
        update_option('_'.$appTitle.'_app', $installData['appInfo']);
        //add to apps list;
        $apps[$appTitle]['state'] = $installData['appInfo']['state'];
        $apps[$appTitle]['name'] = $installData['appInfo']['name'];
        $apps[$appTitle]['docked'] = 1;
        update_option('dt_int_Apps', $apps);
        //die;

        foreach($installData['interfaces'] as $interface=>$cfg){
           $cfg['_Application'] = '{{appID}}';
            update_option($interface, $cfg);
        }
        //vardump($installData['application']);
        //die;

    }
    function uninstall{{appID}}(){

        $appName = sanitize_title('{{appName}}');
        $appTitle = '{{appID}}';

        delete_option('_'.$appTitle.'_app');
        $apps = get_option('dt_int_Apps');
        unset($apps[$appTitle]);
        update_option('dt_int_Apps', $apps);
        return;
    }
}

?>