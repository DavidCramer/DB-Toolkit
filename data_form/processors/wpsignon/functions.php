<?php
/* 
 * emailer functions
 * function naming:
 * 
 *      post_process_{{folder}}($Data)
 *      pre_process_{{folder}}($Data)
 *      config_{{folder}}($Config = false)
 *
 */

function pre_process_wpsignon($Data, $Setup, $Config){

    $creds = array();
    $creds['user_login'] = $Data[$Setup['_wpsignon']['user']];
    $creds['user_password'] = $Data[$Setup['_wpsignon']['pass']];
    $creds['remember'] = true;
    $user = wp_signon( $creds, false );
    if ( is_wp_error($user) ){
        $Data['__fail__'] = true;
        $Data['__error__'] = $user->get_error_message();
        return $Data;
    }
    $Data[$Setup['_wpsignon']['pass']] = '************';
    
return $Data;
}

//function pre_process_emailer($Data, $Setup, $Config){

//    return $Data;
//}

function config_wpsignon($ProcessID, $Table, $Config = false){
    
    global $wpdb;

    $Fields = $wpdb->get_results( "SHOW COLUMNS FROM `".$Table."`", ARRAY_N);
    
    $Return .= '<h2>Login Fields</h2>';

    $user = '';
    $pass = '';
    
    foreach($Fields as $FieldName){
        
        $check = '';
        if(!empty($Config['_FormProcessors'][$ProcessID]['_wpsignon']['user'])){
            if($Config['_FormProcessors'][$ProcessID]['_wpsignon']['user'] == $FieldName[0]){
                $check = 'selected="selected"';
            }
        }
        $user .= "<option value=\"".$FieldName[0]."\" ".$check.">".$FieldName[0]."</option>";

        $check = '';
        if(!empty($Config['_FormProcessors'][$ProcessID]['_wpsignon']['pass'])){
            if($Config['_FormProcessors'][$ProcessID]['_wpsignon']['pass'] == $FieldName[0]){
                $check = 'selected="selected"';
            }
        }
        $pass .= "<option value=\"".$FieldName[0]."\" ".$check.">".$FieldName[0]."</option>";

    }    


    $Return .= 'Username Field:<select name="Data[Content][_FormProcessors]['.$ProcessID.'][_wpsignon][user]">';
        $Return .= $user;
    $Return .= '</select>';

    $Return .= 'Password Field:<select name="Data[Content][_FormProcessors]['.$ProcessID.'][_wpsignon][pass]">';
        $Return .= $pass;
    $Return .= '</select>';

    return $Return;
}

?>
