<?php
/*
 * DB-Toolkit actions library
 * (C) 2012 - David Cramer
 */
add_action( 'init', 'dbt_psudoPostTypes' );
add_action('wp_query', 'dbt_start');
add_action('wp_loaded', 'dbt_process');
add_action('admin_menu', 'dbt_menus');
add_action('wp_footer', 'dbt_footer');
add_action('admin_footer', 'dbt_footer');

add_shortcode('interface', 'dbt_doShortcode');

add_filter('query_vars', 'dbt_query_vars');

add_filter('single_template', 'dbt_header', 1000);
add_filter('archive_template', 'dbt_header', 1000);
add_action('get_header', 'dbt_header');

$ajaxAllowedFunctions['dbt_ajaxloadForm'] = true;
add_action('wp_ajax_dbt_ajaxCall', 'dbt_ajaxCall');
if(is_admin()){

    $ajaxAllowedFunctions['dbt_deleteInterface'] = true;
    $ajaxAllowedFunctions['dbt_deleteApp'] = true;
    $ajaxAllowedFunctions['dbt_createApp'] = true;
    $ajaxAllowedFunctions['dbt_setupTable'] = true;
    $ajaxAllowedFunctions['dbt_loadFieldTypeConfig'] = true;
    $ajaxAllowedFunctions['dbt_createNewTable'] = true;
    $ajaxAllowedFunctions['dbt_addSortingField'] = true;
    $ajaxAllowedFunctions['dbt_buildNewFieldSetup'] = true;
    $ajaxAllowedFunctions['dbt_loadFormProcessor'] = true;
    $ajaxAllowedFunctions['dbt_loadListProcessor'] = true;
    $ajaxAllowedFunctions['dbt_addListRowTemplate'] = true;
    $ajaxAllowedFunctions['dbt_setLanding'] = true;
    $ajaxAllowedFunctions['dbt_setBasePage'] = true;
    $ajaxAllowedFunctions['dbt_setBaseTemplate'] = true;

    
    add_action('admin_head', 'dbt_ajax_javascript');

    add_action('generate_rewrite_rules', 'dbt_add_rewrite_rules');



    


add_filter('wp_setup_nav_menu_item', 'test_menuthing');
function test_menuthing($item){

    if(!empty($item->post_type)){
        if($item->post_type == 'page'){
            if($isMeta = get_post_meta($item->ID, '_dbt_app_page', true)){
                $item->title = $item->title.' (App Page)';
            }
        }
        if($item->post_type == 'nav_menu_item'){
            if($isMeta = get_post_meta($item->object_id, '_dbt_app_page', true)){
                $item->title = str_replace(' (App Page)', '', $item->title);
            }
        }
    }
    return $item;
}

add_action( 'pre_get_posts' ,'exclude_this_page' );
function exclude_this_page( $query ) {
	if(!is_admin()){
            return $query;
        }

	global $pagenow;
	if( 'edit.php' == $pagenow && ( get_query_var('post_type') && 'page' == get_query_var('post_type'))){
            if(!empty($_GET['post_status'])){
                if($_GET['post_status'] == 'dbt_app'){
                    $query->set('meta_key', '_dbt_app_page');
                }
            }
        }        
        return $query;
}


    add_filter('views_edit-page', 'dbt_addviews');
    function dbt_addviews($views){
        global $wpdb;
        $preViews = array();       
        foreach($views as $key=>$view){
            $preViews[$key] = $view;
            if($key =='publish'){

                $sql = "SELECT count(DISTINCT pm.post_id)
                FROM $wpdb->postmeta pm
                JOIN $wpdb->posts p ON (p.ID = pm.post_id)
                WHERE pm.meta_key = '_dbt_app_page'
                AND p.post_type = 'page'
                ";
                $count = $wpdb->get_var($sql);
                $Class = '';
                if(!empty($_GET['post_status'])){
                    if($_GET['post_status'] == 'dbt_app'){
                        $Class= 'class="current"';
                    }
                }
                $preViews['DB-Toolkit'] = '<a '.$Class.' href="edit.php?post_status=dbt_app&post_type=page">DB-Toolkit <span class="count">('.$count.')</span></a>';
            }

        }
        return $preViews;
        
        
        return $views;
    }



    // Add the posts and pages columns filter. They can both use the same function.
    add_filter('manage_pages_columns', 'dbt_add_post_thumbnail_column', 5);

    // Add the column
    function dbt_add_post_thumbnail_column($cols){
      $precols = array();
      foreach($cols as $key=>$col){
          $precols[$key] = $col;
          if($key == 'comments'){
            $precols['dbt_app_page'] = 'DB-Toolkit';
          }

      }
      return $precols;
    }

    // Hook into the posts an pages column managing. Sharing function callback again.
    add_action('manage_pages_custom_column', 'dbt_display_dbt_page', 5, 2);

    // Grab featured-thumbnail size post thumbnail and display it.
    function dbt_display_dbt_page($col, $id){
      if($col == 'dbt_app_page'){
        if(get_post_meta($id, '_dbt_app_page', true)){
            echo '<span class="icon-cog"></span>';
        }
      }
    }




}

?>