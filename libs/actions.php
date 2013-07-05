<?php
/*
 * Core Actions Library - DB Toolkit
 * (C) David Cramer 2010 - 2011
 *
 */

// admin menus ! :D
add_action( 'admin_bar_menu', 'dt_adminMenus', 1000);

// Assign Actions
add_action('init', 'dt_start');
add_action('admin_init', 'dt_admin_init');
add_action('admin_menu', 'dt_menus');
add_action('wp_ajax_dt_ajaxCall', 'dt_ajaxCall');
add_filter('the_content', 'dt_bindInterfaces');

function dt_bindInterfaces($content){
    global $post;
    if($isBound = get_option('_dbtbinding_'.$post->ID)){
        return $content.dt_renderInterface($isBound);
    }
    return $content;
}


// Hook into the page loading to get the scripts and
// styles for the shortcodes used on a post or page.
add_action('wp_head', 'shortcodesOnPost');
function shortcodesOnPost(){
    // get the post data
    global $post;
    // get the shortcode regex
    $pattern = get_shortcode_regex();
    // run regex on the content to find all the shortcodes being used
    preg_match_all('/'.$pattern.'/s', $post->post_content, $matches);
    // only if there are matches
    if(!empty($matches)){

        //loop through the results
        //$matches[3] contains the atts
        //$matches[2] contains the shortcode name
        //$matches[5] contains the shortcode Data
        foreach($matches[3] as $key=>$arg){

            $shortcode = $matches[2][$key];
            //check to see if the found code is mine :)
            if($shortcode == 'myShortCode'){
                // Parse the attributes to an array
                $data = shortcode_parse_atts($arg);
                // get the shortcode content
                $content = $matches[5][$key];

                // wp_enqueue_script
                // wp_enqueue_style
                // for the specific shortcodes used
            }

        }
    }
}




// Add actions to front end
if(basename($_SERVER['PHP_SELF']) == 'index.php'){
    add_action('wp_head', 'dt_headers');
    add_action('wp_print_styles', 'dt_styles');
    add_action('wp_print_scripts', 'dt_scripts');
    add_action('wp_footer', 'dt_footers');
    add_action('wp_dashboard_setup', 'dt_dashboard_widgets' );
    add_action('wp_dashboard_setup', 'dt_remove_dashboard_widgets' );
}

/*
add_action('admin_init', 'dbt_stealthMode');
function dbt_stealthMode(){
    add_settings_field('dbt_stealth' , 'Enable DBT Stealth Mode' ,'dbtForm_Stealth' , 'general' , 'default');
    register_setting('general','dbtStealth');
}

function dbtForm_Stealth(){

    $Default = get_option('dbtStealth');
    
    $sel = '';
    if(!empty($Default)){
        $sel= 'checked="checked"';
    }
    echo '<input type="checkbox" value="1" id="dbtStealth" name="dbtStealth" '.$sel.'> Put DB-Toolkit into Stealth Mode. (Disabled the Builder and Editor. Docked apps still active.)';
}



add_filter('page_row_actions','filter_Enhance', 1, 2);

    function filter_Enhance($actions, $post){

        $newAction['enhance'] = '<a title="Edit this item" href="edit.php?post_type=page&page=dais&post='.$post->ID.'">Enhance</a>';
        return array_slice($actions, 0, 2) + $newAction + array_slice($actions, 1);
       
    }
    */
?>
