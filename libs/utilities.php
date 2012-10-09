<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/* Utility Functions */
function dbt_getVersion(){
    if (!function_exists( 'get_plugins' )){
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    $plugin_folder = get_plugins( '/' . basename(DBT_PATH) );
    return $plugin_folder['plugincore.php']['Version'];
}
if(!function_exists('vardump')){
    function vardump($a){
        echo '<pre>';
        print_r($a);
        echo '</pre>';
    }
}
?>
