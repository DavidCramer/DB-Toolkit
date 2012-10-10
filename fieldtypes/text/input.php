<?php
/// This creates the actual input fields for capturing. this will handle the occurance of the setting

if($type[1] == 'integer'){      
	echo '<input tabindex="'.$columnNo.'" name="'.$Field.'" type="text" id="'.$Field.'" value="'.$Val.'" class="'.$Req.' span12" '.$disabled.' />';
}
if($type[1] == 'singletext'){        
	echo '<input tabindex="'.$columnNo.'" name="'.$Field.'" type="text" id="'.$Field.'" value="'.$Val.'" class="'.$Req.' span12" '.$disabled.' />';
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
       //$Span = 'span10';
    }


    $Return = '<div class="input-append">';
    $Return .= '<input tabindex="'.$columnNo.'" name="'.$Field.'" type="text" id="'.$Field.'" value="'.$Val.'" class="'.$Req.' span12" '.$disabled.' />';
    //$Return .= "<button type=\"button\" class=\"btn\">Add New Item</button>\n";
    $Return .= '<span class="add-on"><i class="icon-envelope"></i></span>';
    $Return .= '</div>';

    echo $Return;
}
if($type[1] == 'textarea'){        
	echo '<textarea tabindex="'.$columnNo.'" id="'.$Field.'" name="'.$Field.'" class="'.$Req.' span12" style="height:109px" '.$disabled.' >'.$Val.'</textarea>';
}

?>