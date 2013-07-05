<?php
/*
 * Core Shortcode Library - DB Toolkit
 * (C) David Cramer 2010 - 2011
 *
 */

// Enable shortcode
add_shortcode("interface", "dt_renderInterface");
add_shortcode("visibility", "dt_publicReg");

// fetch custom shortcodes
global $wpdb;
$customShortCodes = $wpdb->get_results("SELECT `option_name`, `option_value` FROM $wpdb->options WHERE `option_name` LIKE 'dt_intfc%' AND `option_value` LIKE '%_shortCode%' ", ARRAY_A);
if(!empty($customShortCodes)){
    function dt_customShortcode($args, $content, $code){
        global $wpdb;

        $customShortCodes = $wpdb->get_results("SELECT `option_name`, `option_value` FROM $wpdb->options WHERE `option_name` LIKE 'dt_intfc%' AND `option_value` LIKE '%_shortCode%' AND `option_value` LIKE '%".$code."%'  ", ARRAY_A);

        if(!empty($customShortCodes[0])){
            //$_GET = $args;            
            if(is_array($args)){
               $_GET = array_merge($_GET, $args);
            }
            return dt_renderInterface($customShortCodes[0]['option_name']);
        }
    }
    if(phpversion() >= 5.2){
        foreach($customShortCodes as $shortCode){
            $inf = get_option($shortCode['option_name']);
            add_shortcode($inf['_shortCode'], 'dt_customShortcode');            
        }
    }
}


// enable shortcode in widgets
add_filter('widget_text', 'do_shortcode');



?>
