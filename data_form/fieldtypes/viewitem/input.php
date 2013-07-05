<?php
/// This creates the actual input fields for capturing. this will handle the occurance of the setting
//vardump($_GET);//

if(empty($Val)){
    if(!empty($Config['_overRide'][$Field])){
        $setValue = $_GET[$Config['_overRide'][$Field]];
    }else{
        if(!empty($_GET[$Field])){
            $setValue = $_GET[$Field];
        }
    }
}else{
    $setValue = $Val;
}



echo '<input name="dataForm['.$Element['ID'].']['.$Field.']" type="hidden" id="entry_'.$Element['ID'].'_'.$Field.'" value="'.$setValue.'" />';
    
?>