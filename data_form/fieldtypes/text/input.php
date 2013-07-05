<?php
/// This creates the actual input fields for capturing. this will handle the occurance of the setting

if($FieldSet[1] == 'integer'){
	$WidthOverride = '';
        if(!empty($Req)){
            $Req = 'validate[required,custom[onlyNumber]]';
        }
	if(!empty($Config['_FieldLength'][$Field])){
		$WidthOverride = 'style="width:'.$Config['_FieldLength'][$Field].';"';
	}
        $fieldType = 'text';
        if(!empty($Config['_fieldType'][$Field])){
            $fieldType = $Config['_fieldType'][$Field];
        }
        if($fieldType == 'text'){
            $fieldType = 'text';
        }
        
	echo '<input name="dataForm['.$Element['ID'].']['.$Field.']" type="'.$fieldType.'" id="entry_'.$Element['ID'].'_'.$Field.'" value="'.$Val.'" class="'.$Req.' '.$Config['_FormFieldWidth'][$Field].'" />';
}
if($FieldSet[1] == 'singletext'){
	$WidthOverride = '';

	if(!empty($Config['_FieldLength'][$Field])){
		$WidthOverride = 'style="width:'.$Config['_FieldLength'][$Field].';"';
	}
        $fieldType = 'text';
        if(!empty($Config['_fieldType'][$Field])){
            $fieldType = $Config['_fieldType'][$Field];
        }
        if($fieldType == 'text'){
            $fieldType = 'text';
        }

	echo '<input name="dataForm['.$Element['ID'].']['.$Field.']" type="'.$fieldType.'" id="entry_'.$Element['ID'].'_'.$Field.'" value="'.$Val.'" class="'.$Req.' '.$Config['_FormFieldWidth'][$Field].'" />';
}
if($FieldSet[1] == 'password'){

    if(!empty($Val)){
        $Val = '';
        $Req = '';
    }

    $Return = '<span class="input-append">';
    $Return .= '<input name="dataForm['.$Element['ID'].']['.$Field.']" type="password" id="entry_'.$Element['ID'].'_'.$Field.'" value="" class="'.$Req.' '.$Config['_FormFieldWidth'][$Field].'" style="-moz-box-sizing: inherit;" />';
    $Return .= '<span class="add-on"><i class="icon-lock"></i></span>';
    $Return .= '</span>';
    echo $Return;
}
if($FieldSet[1] == 'emailaddress'){
    $disabled = '';
    if(!empty($Req)){
        if(!empty($Config['_Unique'][$Field])) {
            $Req = 'validate[required,custom[email], ajax[ajaxUnique]]';
            if(!empty($Val)){
                $Req = 'disabled';
                $disabled = 'disabled="disabled"';
            }
        }else{
            $Req = 'validate[required,custom[email]]';
        }
    }
    $Return = '<div class="input-append">';
    $Return .= '<input name="dataForm['.$Element['ID'].']['.$Field.']" type="text" '.$disabled.' id="entry_'.$Element['ID'].'_'.$Field.'" value="'.$Val.'" class="'.$Req.' '.$Config['_FormFieldWidth'][$Field].'"/>';
    $Return .= '<span class="add-on"><i class="icon-envelope"></i></span>';
    $Return .= '</div>';

    echo $Return;
}
if($FieldSet[1] == 'telephonenumber'){
    if(!empty($Req)){
        if(!empty($Config['_Unique'][$Field])) {
            $Req = 'validate[required,custom[telephone], ajax[ajaxUnique]]';
        }else{
            $Req = 'validate[required,custom[telephone]]';
        }
    }
	echo '<input name="dataForm['.$Element['ID'].']['.$Field.']" type="text" id="entry_'.$Element['ID'].'_'.$Field.'" value="'.$Val.'" class="'.$Req.' '.$Config['_FormFieldWidth'][$Field].'" />';
}
if($FieldSet[1] == 'textarea'){
        
	echo '<textarea id="entry_'.$Element['ID'].'_'.$Field.'" name="dataForm['.$Element['ID'].']['.$Field.']" class="'.$Req.' '.$Config['_FormFieldWidth'][$Field].'" style="height:100px">'.$Val.'</textarea>';

        if(!empty($Config['_CharLength'][$Field])){
        $_SESSION['dataform']['OutScripts'] .="

            max = ".$Config['_CharLength'][$Field].";

            len = jQuery('#entry_".$Element['ID']."_".$Field."').val().length;
            jQuery('#entry_".$Element['ID']."_".$Field."').next().html(len+' Characters');

            jQuery('#entry_".$Element['ID']."_".$Field."').bind('keyup', function(h){
                len = jQuery('#entry_".$Element['ID']."_".$Field."').val().length;
                if(len <= max){
                    jQuery('#entry_".$Element['ID']."_".$Field."').next().html(len+' Characters');                    
                }else{
                    curr = jQuery('#entry_".$Element['ID']."_".$Field."').val().substr(0,max);
                    jQuery('#entry_".$Element['ID']."_".$Field."').val(curr);
                    jQuery('#entry_".$Element['ID']."_".$Field."').next().html(max+' Characters');
                }
            });
            
        ";
        }

}
if($FieldSet[1] == 'textarealarge'){
	echo '<textarea id="entry_'.$Element['ID'].'_'.$Field.'" name="dataForm['.$Element['ID'].']['.$Field.']" class="'.$Req.' '.$Config['_FormFieldWidth'][$Field].' " style="height:200px">'.$Val.'</textarea>';
}
if($FieldSet[1] ==  'phpcodeblock'){
	echo '<textarea id="entry_'.$Element['ID'].'_'.$Field.'" name="dataForm['.$Element['ID'].']['.$Field.']" class="'.$Req.' '.$Config['_FormFieldWidth'][$Field].' " style="height:200px">'.$Val.'</textarea>';
}
if($FieldSet[1] == 'wysiwyg'){

    $idCount = uniqid();

    echo '<textarea id="entry_'.$Element['ID'].'_'.$Field.'_'.$idCount.'" name="dataForm['.$Element['ID'].']['.$Field.']" class="'.$Req.'  '.$Config['_FormFieldWidth'][$Field].'">'.$Val.'</textarea>';
    
    
    $Buttons = implode("' , '",  $Config['_activatedButtons'][$Field]);
    
    $_SESSION['dataform']['OutScripts'] .="


        CKEDITOR.replace('entry_".$Element['ID']."_".$Field."_".$idCount."', {
            toolbar: [
                ['".$Buttons."']
            ]
        });;
        
    ";
}
if($FieldSet[1] == 'url'){
	echo '<input name="dataForm['.$Element['ID'].']['.$Field.']" type="text" id="entry_'.$Element['ID'].'_'.$Field.'" value="'.$Val.'" class="'.$Req.' '.$Config['_FormFieldWidth'][$Field].'" />';
}
if($FieldSet[1] == 'colourpicker'){

    $Return = '<div class="input-append">';
    $Return .= '<input name="dataForm['.$Element['ID'].']['.$Field.']" type="text" id="entry_'.$Element['ID'].'_'.$Field.'" value="'.$Val.'" class="'.$Req.' input-small" style="width:80px;" '.$WidthOverride.' />';
    $Return .= '</div>';
    echo $Return;


    $_SESSION['dataform']['OutScripts'] .="
        jQuery('#entry_".$Element['ID']."_".$Field."').miniColors({
            letterCase: 'uppercase'
        });
    ";
}
?>