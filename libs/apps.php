<?php
/* 
 * Apps Browser system
 * (C) copyright David Cramer 2011
 * DB-Toolkit
 */



/*
 * Fetch App Categories
 *
 */

function app_dockApp($App){
    
    $Apps = get_option('dt_int_Apps');
    $appConfig = get_option('_'.sanitize_title($App).'_app');
    
    if(empty($appConfig['docked'])){
        $appConfig['docked'] = true;
        $Return['docked'] = true;
    }else{
        $appConfig['docked'] = false;
        $Return['docked'] = false;
    }
    $appConfig = update_option('_'.sanitize_title($App).'_app', $appConfig);


    return $Return;
}

function app_SaveDesc($desc){

    parse_str($desc, $data);

    $active = get_option('_dbt_activeApp');

    $Apps = get_option('dt_int_Apps');


    $Apps[sanitize_title($active)]['description'] = stripslashes_deep($data['pluginDesc']);
    $Apps[sanitize_title($active)]['name'] = stripslashes_deep($data['pluginName']);
    $appConfig = get_option('_'.sanitize_title($active).'_app');

    $appConfig['name'] = stripslashes_deep($data['pluginName']);
    $appConfig['description'] = stripslashes_deep($data['pluginDesc']);
    $appConfig['pluginURI'] = stripslashes_deep($data['pluginURI']);
    $appConfig['pluginVersion'] = stripslashes_deep($data['pluginVersion']);
    $appConfig['pluginAuthor'] = stripslashes_deep($data['pluginAuthor']);
    $appConfig['pluginAuthorURI'] = stripslashes_deep($data['pluginAuthorURI']);


    update_option('_'.sanitize_title($active).'_app', $appConfig);
    update_option('dt_int_Apps', $Apps);
    return 'Details Saved';
}

function app_update($name, $interface = false, $access = false){

    if(empty($name) || strtolower($name) == 'base')
        return;
    $Apps = get_option('dt_int_Apps');
    if(!empty($Apps)){
        if(!empty($Apps[$name])){
            unset($Apps[$name]);
        }
    }
    $newApp = get_option('_'.sanitize_title($name).'_app');
    //if(empty($newApp)){
        $Apps[sanitize_title($name)]['state'] = 'open';
        $Apps[sanitize_title($name)]['name'] = $name;
        update_option('dt_int_Apps', $Apps);
        //$newApp['Author'] =
        //$user = wp_get_current_user();
        $newApp = array(
            'state'=>'open',
            'name'=>$name
            //'author'=>$user->data->first_name.' '.$user->data->last_name,
            //'author email'=>$user->data->user_email
        );

        // select interfaces and add them.
        global $wpdb;
        $Len = strlen($name);
        $appString = 's:12:"_Application";s:'.$Len.':"'.$name.'"';
        $interfaces = $wpdb->get_results( "SELECT option_name FROM ".$wpdb->options." WHERE `option_value` LIKE '%".$appString ."%'");
        foreach($interfaces as $single){
            $cfg = get_option($single->option_name);
            $newApp['interfaces'][$cfg['ID']] = $cfg['_menuAccess'];
        }

    //}
    if(!empty($interface)){
        $newApp['interfaces'][$interface] = $access;
    }

    update_option('_'.sanitize_title($name).'_app', $newApp);
    
}

function app_doCall($url, $post = false){

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
    curl_setopt($ch, CURLOPT_URL, $url);

    if(!empty($post)){
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }

    $output = curl_exec($ch);
    
    if($data = json_decode($output, true)){
        return $data;
    }else{
        return $output;
    }
    
}

function app_marketLogin($user, $pass){
    //setup your postvars
    $vars = array(
        "user"=>$user,
        "pass"=>$pass
    );

    // do the call
    $response = app_doCall('http://localhost/wordpress/categories/auth/json', $vars);
    
    if($response['result'] == 'success'){
        update_option('_app_marketid_'.get_current_user_id(), $response['token']);
    }
    return $response;
}

function app_fetchCategories($token){
    $response = app_doCall('http://localhost/wordpress/categories/'.$token.'/list/json');//, $vars);
    return $response['entries'];
}

function app_fetchApps($cat){
    $token = get_option('_app_marketid_'.get_current_user_id());
    $vars = array(
        //"appmarket_categoriesID"=>$cat
        "itemID"=>$cat
    );
    
    $response = app_doCall('http://localhost/wordpress/apps/'.$token.'/fetch/html', $vars);//, $vars);
    $out = trim(strip_tags($response));
    if(empty($out)){
        return 'This category is empty';
    }
    return $response;
}

function app_launcher(){
    if(substr($_GET['page'],0,4) == 'app_'){
        $app = substr($_GET['page'], 4);
        $app = get_option('_'.$app.'_app');
    }elseif(substr($_GET['page'],0,8) == 'dt_intfc'){
        $Interface = get_option($_GET['page']);
        $app = get_option('_'.sanitize_title($Interface['_Application']).'_app');
        $_GET['renderinterface'] = $_GET['page'];
    }    
    include DB_TOOLKIT.'dbtoolkit_launcher.php';
}

function app_setLanding($app, $inf){

    $appcfg = get_option('_'.sanitize_title($app).'_app');
    //vardump($app);
    //vardump($inf);
    $appcfg['landing'] = $inf;
    update_option('_'.sanitize_title($app).'_app', $appcfg);
    

}

function app_exists($app){

    $Apps = get_option('dt_int_Apps');
    if(!empty($Apps[$app])){
        return true;
    }
    return false;
}

function app_createApplication($name, $desc = false){

    if(empty($name)){
        $out['error'] = 'You need an application name first.';
        return $out;
    }

    $cleanName = sanitize_title($name);
    if(app_exists($cleanName)){
        $out['error'] = 'Application "'.$name.'" already exists';
        return $out;
    }

    $newApp = array();
    $newApp['state'] = 'open';
    $newApp['name'] = $name;
    if(!empty($desc)){
        $newApp['description'] = $desc;
    }


    if(update_option('_'.$cleanName.'_app', $newApp)){
        $apps = get_option('dt_int_Apps');
        $apps[$cleanName] = $newApp;
        if(update_option('dt_int_Apps', $apps)){
            if(update_option('_dbt_activeApp', $cleanName)){
                return true;
            }
        }
    }
    $out['error'] = 'There was an error creating the app. Sorry.';
    return $out;
    
}
?>
