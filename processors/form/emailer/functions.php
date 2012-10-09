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

function pre_process_emailer($Data, $Setup, $Config){

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

    $version = dbt_getVersion();
    $Headers = 'From: '.$Sender . "\r\n" .
               'Reply-To: '.$Sender . "\r\n" .
               'X-Mailer: DB-Toolkit/'.$version;

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
    $Body .= "Powered By DB-Toolkit ".$version."\r\n";
    mail($Setup['_recipient'], $Setup['_subject'], $Body, $Headers);


return $Data;
}

//function pre_process_emailer($Data, $Setup, $Config){

//    return $Data;
//}

function config_emailer($ProcessID, $Table, $Config = false){
    global $wpdb;

    //data[_formprocessor][fp4fab82ddefc2f][insert]

    $rval = '';
    if(!empty($Config['_formprocessor'][$ProcessID]['setup']['_recipient'])){
        $rval = $Config['_formprocessor'][$ProcessID]['setup']['_recipient'];
    }
    $sval = '';
    if(!empty($Config['_formprocessor'][$ProcessID]['setup']['_subject'])){
        $sval = $Config['_formprocessor'][$ProcessID]['setup']['_subject'];
    }

    $Return = '<p>Email Address: <input type="text" value="'.$rval.'" name="data[_formprocessor]['.$ProcessID.'][setup][_recipient]" /></p>';
    $Return .= '<p>Email Subject: <input type="text" value="'.$sval.'" name="data[_formprocessor]['.$ProcessID.'][setup][_subject]" /></p>';

    $Fields = $wpdb->get_results( "SHOW COLUMNS FROM ".$Table, ARRAY_N);
    $Sender = '<option value="">Self</option>';

    
    foreach($Fields as $FieldData){
        
        $Sel = '';
        if($Config['_formprocessor'][$ProcessID]['setup']['_SenderEmail'] == $FieldData[0]){
            $Sel = 'selected="selected"';
        }
        $Sender .= '<option value="'.$FieldData[0].'" '.$Sel.'>'.$FieldData[0].'</option>';

    }

    $Return .= '<p>Sender Field: <select name="data[_formprocessor]['.$ProcessID.'][setup][_SenderEmail]">'.$Sender.'</select></p>';


    return $Return;
}

?>
