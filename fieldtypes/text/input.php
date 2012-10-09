<?php
/// This creates the actual input fields for capturing. this will handle the occurance of the setting

if($type[1] == 'integer'){      
	echo '<input tabindex="'.$columnNo.'" name="dataForm['.$Config['_ID'].']['.$Field.']" type="text" id="entry_'.$Config['_ID'].'_'.$Field.'" value="'.$Val.'" class="'.$Req.' '.$Span.'" '.$disabled.' />';
}
if($type[1] == 'singletext'){        
	echo '<input tabindex="'.$columnNo.'" name="dataForm['.$Config['_ID'].']['.$Field.']" type="text" id="entry_'.$Config['_ID'].'_'.$Field.'" value="'.$Val.'" class="'.$Req.' '.$Span.'" '.$disabled.' />';
}
if($type[1] == 'emailaddress'){
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
    if(empty($Config['_FormFieldWidth'][$Field])){
       $Span = 'span'.(str_replace('span', '', $Span)-2);
    }


    $Return = '<div class="input-append">';
    $Return .= '<input tabindex="'.$columnNo.'" name="dataForm['.$Config['_ID'].']['.$Field.']" type="textfield" id="entry_'.$Config['_ID'].'_'.$Field.'" value="'.$Val.'" class="'.$Req.' '.$Span.'" '.$disabled.' />';
    //$Return .= "<button type=\"button\" class=\"btn\">Add New Item</button>\n";
    $Return .= '<span class="add-on"><i class="icon-envelope"></i></span>';
    $Return .= '</div>';

    echo $Return;
}
if($type[1] == 'textarea'){        
	echo '<textarea tabindex="'.$columnNo.'" id="entry_'.$Config['_ID'].'_'.$Field.'" name="dataForm['.$Config['_ID'].']['.$Field.']" class="'.$Req.' '.$Span.'" style="height:109px" '.$disabled.' >'.$Val.'</textarea>';
}

?>