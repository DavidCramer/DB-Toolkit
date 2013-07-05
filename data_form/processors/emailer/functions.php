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

function post_process_emailer($Data, $Setup, $Config){


    if(empty($Setup['_recipient'])){
        return $Data;
    }

    $default_headers = array(
        'Version' => 'Version'
    );
    $Sender = $Setup['_recipient'];
    if(!empty($Setup['_SenderEmail'])){
        if(!empty($Data[$Setup['_SenderEmail']])){
            $Sender = $Data[$Setup['_SenderEmail']];
        }
    }

    $version = get_file_data(WP_PLUGIN_DIR.'/db-toolkit/plugincore.php', $default_headers, 'db-toolkit-fieldtype');
    $Headers = 'From: '.$Sender . "\r\n" .
               'Reply-To: '.$Sender . "\r\n" .
               'X-Mailer: DB-Toolkit/'.$version['Version'];

    $Body = "Submitted Data from ".date("r")."\r\n";
    $Body .= "=============================\r\n";
    if(empty($Data)){
        return false;
    }
    foreach($Data as $FieldKey=>$FieldValue){
        if(strpos($FieldKey, '_control_') === false){
            $Body .= $FieldKey.": ".$FieldValue."\r\n";
        }
    }
    $Body .= "=============================\r\n";
    $Body .= "Powered By DB-Toolkit\r\n";
    mail($Setup['_recipient'], $Setup['_subject'], $Body, $Headers);


return $Data;
}

//function pre_process_emailer($Data, $Setup, $Config){

//    return $Data;
//}

function config_emailer($ProcessID, $Table, $Config = false){
    global $wpdb;
    
    $rval = '';
    if(!empty($Config['_FormProcessors'][$ProcessID]['_recipient'])){
        $rval = $Config['_FormProcessors'][$ProcessID]['_recipient'];
    }
    $sval = '';
    if(!empty($Config['_FormProcessors'][$ProcessID]['_subject'])){
        $sval = $Config['_FormProcessors'][$ProcessID]['_subject'];
    }

    $Return = '<p>Email Address: <input type="text" value="'.$rval.'" name="Data[Content][_FormProcessors]['.$ProcessID.'][_recipient]" /></p>';
    $Return .= '<p>Email Subject: <input type="text" value="'.$sval.'" name="Data[Content][_FormProcessors]['.$ProcessID.'][_subject]" /></p>';

    $Fields = $wpdb->get_results( "SHOW COLUMNS FROM `".$Table."`", ARRAY_N);
    $Sender = '<option value="">Self</option>';

    
    foreach($Fields as $FieldData){
        
        $Sel = '';
        if($Config['_FormProcessors'][$ProcessID]['_SenderEmail'] == $FieldData[0]){
            $Sel = 'selected="selected"';
        }
        $Sender .= '<option value="'.$FieldData[0].'" '.$Sel.'>'.$FieldData[0].'</option>';

    }

    $Return .= '<p>Sender Field: <select name="Data[Content][_FormProcessors]['.$ProcessID.'][_SenderEmail]">'.$Sender.'</select></p>';


    return $Return;
}

?>
