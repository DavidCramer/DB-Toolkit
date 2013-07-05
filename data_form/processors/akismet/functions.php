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
function akismet_scan($Data, $Setup, $Config){
    
    if(empty($Setup['_APIKey'])){
        return false;
    }

    include_once WP_PLUGIN_DIR.'/db-toolkit/data_form/processors/akismet/Akismet.class.php';
    $WordPressAPIKey = $Setup['_APIKey'];
    $MyBlogURL = get_bloginfo('url');

    $akismet = new Akismet($MyBlogURL ,$WordPressAPIKey);

    $akismet->setCommentAuthor($Data[$Setup['_Name']]);
    $akismet->setCommentAuthorEmail($Data[$Setup['_Email']]);
    $akismet->setCommentAuthorURL($Data[$Setup['_URL']]);
    $akismet->setCommentContent($Data[$Setup['_Text']]);
    $akismet->setUserIP($_SERVER['REMOTE_ADDR']);

    if($akismet->isCommentSpam()){
        return true;
    }else{
        return false;
    }
return false;
}

function pre_process_akismet($Data, $Setup, $Config){
    if(akismet_scan($Data, $Setup, $Config)){
        if(!empty($Setup['_DeleteSpam'])){
            $Data = array();
            $Data['__fail__'] = true;
            $Data['__error__'] = $Setup['_SpamMessage'];
            return $Data;
        }
        if(!empty($Setup['_SpamFlag'])){
            $Data[$Setup['_SpamFlag']] = 1;
            return $Data;
        }
        return $Data;
    }else{
        return $Data;
    }
}

function config_akismet($ProcessID, $Table, $Config = false){
    global $wpdb;
    $val = '';
    if(!empty($Config['_FormProcessors'][$ProcessID]['_APIKey'])){
        $val = $Config['_FormProcessors'][$ProcessID]['_APIKey'];
    }

    $Return = '<p>Akismet API Key: <input type="text" value="'.$val.'" name="Data[Content][_FormProcessors]['.$ProcessID.'][_APIKey]" /></p>';
    $Fields = $wpdb->get_results( "SHOW COLUMNS FROM `".$Table."`", ARRAY_N);

    $spam = '<option value=""></option>';
    $name = '<option value=""></option>';
    $email = '<option value=""></option>';
    $url = '<option value=""></option>';
    $text = '<option value=""></option>';
    
    foreach($Fields as $FieldData){
        
        $Sel = '';
        if($Config['_FormProcessors'][$ProcessID]['_SpamFlag'] == $FieldData[0]){
            $Sel = 'selected="selected"';
        }
        $spam .= '<option value="'.$FieldData[0].'" '.$Sel.'>'.$FieldData[0].'</option>';

        $Sel = '';
        if($Config['_FormProcessors'][$ProcessID]['_Name'] == $FieldData[0]){
            $Sel = 'selected="selected"';
        }
        $name .= '<option value="'.$FieldData[0].'" '.$Sel.'>'.$FieldData[0].'</option>';

        $Sel = '';
        if($Config['_FormProcessors'][$ProcessID]['_Email'] == $FieldData[0]){
            $Sel = 'selected="selected"';
        }
        $email .= '<option value="'.$FieldData[0].'" '.$Sel.'>'.$FieldData[0].'</option>';

        $Sel = '';
        if($Config['_FormProcessors'][$ProcessID]['_URL'] == $FieldData[0]){
            $Sel = 'selected="selected"';
        }
        $url .= '<option value="'.$FieldData[0].'" '.$Sel.'>'.$FieldData[0].'</option>';

        $Sel = '';
        if($Config['_FormProcessors'][$ProcessID]['_Text'] == $FieldData[0]){
            $Sel = 'selected="selected"';
        }
        $text .= '<option value="'.$FieldData[0].'" '.$Sel.'>'.$FieldData[0].'</option>';

    }

    $Return .= '<p>Spam Flag Field: <select name="Data[Content][_FormProcessors]['.$ProcessID.'][_SpamFlag]">'.$spam.'</select></p>';
    $Return .= '<p>Name Field: <select name="Data[Content][_FormProcessors]['.$ProcessID.'][_Name]">'.$name.'</select></p>';
    $Return .= '<p>Email Field: <select name="Data[Content][_FormProcessors]['.$ProcessID.'][_Email]">'.$email.'</select></p>';
    $Return .= '<p>URL Field: <select name="Data[Content][_FormProcessors]['.$ProcessID.'][_URL]">'.$url.'</select></p>';
    $Return .= '<p>Text Field: <select name="Data[Content][_FormProcessors]['.$ProcessID.'][_Text]">'.$text.'</select></p>';

    $Sel = '';
    if(!empty($Config['_FormProcessors'][$ProcessID]['_DeleteSpam'])){
        $Sel = 'checked="checked"';
    }
    $Return .= '<p>Delete Spam: <input type="checkbox" value="1" name="Data[Content][_FormProcessors]['.$ProcessID.'][_DeleteSpam]" '.$Sel.' /></p>';
    $Sel = 'Sorry, that entry looked a little to spammy and was rejected.';
    if(!empty($Config['_FormProcessors'][$ProcessID]['_SpamMessage'])){
        $Sel = $Config['_FormProcessors'][$ProcessID]['_SpamMessage'];
    }
    $Return .= '<p>Spam Deleted Message: <input type="text" value="'.$Sel.'" name="Data[Content][_FormProcessors]['.$ProcessID.'][_SpamMessage]" /></p>';
    
    return $Return;
}

?>