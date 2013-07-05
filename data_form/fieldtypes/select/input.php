<?php
/// This creates the actual input fields for capturing. this will handle the occurance of the setting



switch ($Config['_SelectType'][$Field]){
    case 'dropdown':
        echo '<select name="dataForm['.$Element['ID'].']['.$Field.']" id="entry_'.$Element['ID'].'_'.$Field.'" class="'.$Req.'" >';
        foreach($Config['_SelectOptions'][$Field] as $optionValue){
            $sel = '';
            if(!empty($Val)){
                if($Val == $optionValue){
                    $sel = 'selected="selected"';
                }
            }
            echo '<option value="'.$optionValue.'" '.$sel.'>'.$optionValue.'</option>';
        }
        echo '</select>';
        break;
    case 'radio':
        foreach($Config['_SelectOptions'][$Field] as $optionValue){
            $sel = '';
            if(!empty($Val)){
                if($Val == $optionValue){
                    $sel = 'checked="checked"';
                }
            }

            $dislpayType = '';
            
            if(!empty($Config['_DisplayType'][$Field])){
                if($Config['_DisplayType'][$Field] == 'inline'){
                    $dislpayType = 'inline';
                }

            }

            $id= uniqid();
            echo '<label class="radio '.$dislpayType.'" for="entry_'.$Element['ID'].'_'.$Field.'_'.$id.'"><input type="radio" value="'.$optionValue.'" name="dataForm['.$Element['ID'].']['.$Field.']" id="entry_'.$Element['ID'].'_'.$Field.'_'.$id.'" '.$sel.' class="'.$Req.'" /> '.$optionValue.'</label>';
        }
        break;
    case 'checkbox':
        if(!empty($Val)){
            $valData = unserialize($Val);
        }
        
        foreach($Config['_SelectOptions'][$Field] as $optionValue){
            $sel = '';
            if(!empty($valData)){
                
                if(in_array($optionValue, $valData)){
                    $sel = 'checked="checked"';
                }
            }

            $dislpayType = '';

            if(!empty($Config['_DisplayType'][$Field])){
                if($Config['_DisplayType'][$Field] == 'inline'){
                    $dislpayType = 'inline';
                }

            }
            $id= uniqid();
            echo '<label class="checkbox '.$dislpayType.'" for="entry_'.$Element['ID'].'_'.$Field.'_'.$id.'"><input type="checkbox" value="'.$optionValue.'" name="dataForm['.$Element['ID'].']['.$Field.'][]" id="entry_'.$Element['ID'].'_'.$Field.'_'.$id.'" '.$sel.' class="'.$Req.'" /> '.$optionValue.'</label>';
        }
        break;
}



?>