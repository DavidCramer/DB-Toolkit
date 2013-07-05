<?php
// Functions
function select_handleInput($Field, $Input, $FieldType, $Config, $Default){
	if($FieldType == 'presettext'){
		return $Config['Content']['_Preset'][$Field];
	}
        if($FieldType == 'password'){
            return md5($Input);
        }
	return $Input;
}
function select_processValue($Value, $Type, $Field, $Config, $EID, $Data){
    if($data = unserialize($Value)){
        return implode(',', $data);
    }
    return $Value;
}

function select_postProcess($Field, $Input, $Type, $Element, $Data, $ReturnField){
    //if(is_array($Input)){
    //    return implode(',',$Input);
    //}
return $Input;
}



function select_setup($Field, $Table, $Config = false){

        
        $Return = '<div style="padding:5px;">';

            $Return .= 'Display Type: <select name="Data[Content][_DisplayType]['.$Field.']" >';

            $sel = '';
            if(!empty($Config['Content']['_DisplayType'][$Field])){
                if($Config['Content']['_DisplayType'][$Field] == 'stacked'){
                    $sel = 'selected="selected"';
                }
            }
            $Return .= '<option value="stacked" '.$sel.'>Stacked</option>';

            $sel = '';
            if(!empty($Config['Content']['_DisplayType'][$Field])){
                if($Config['Content']['_DisplayType'][$Field] == 'inline'){
                    $sel = 'selected="selected"';
                }
            }
            $Return .= '<option value="inline" '.$sel.'>Inline</option>';

            
            $Return .= '</select>';
        $Return .= '</div>';
        $Return .= '<div style="padding:5px;">';

            $Return .= 'Select Type: <select name="Data[Content][_SelectType]['.$Field.']" >';

            $sel = '';
            if(!empty($Config['Content']['_SelectType'][$Field])){
                if($Config['Content']['_SelectType'][$Field] == 'dropdown'){
                    $sel = 'selected="selected"';
                }
            }
            $Return .= '<option value="dropdown" '.$sel.'>Dropdown</option>';

            $sel = '';
            if(!empty($Config['Content']['_SelectType'][$Field])){
                if($Config['Content']['_SelectType'][$Field] == 'radio'){
                    $sel = 'selected="selected"';
                }
            }
            $Return .= '<option value="radio" '.$sel.'>Radio</option>';

            $sel = '';
            if(!empty($Config['Content']['_SelectType'][$Field])){
                if($Config['Content']['_SelectType'][$Field] == 'checkbox'){
                    $sel = 'selected="selected"';
                }
            }
            $Return .= '<option value="checkbox" '.$sel.'>Checkbox</option>';
            //$Return .= '<option value="selectbox">SelectBox</option>';

            $Return .= '</select>';
        $Return .= '</div>';

        $Return .= '<div id="optionsSetup'.$Field.'">';
        $Return .= '<div style="list_row3"><input type="button" value="Add Option" onclick="select_addOption(\''.$Field.'\');" /></div>';
        if(empty($Config['Content']['_SelectOptions'][$Field])){
            $ID = uniqid();
            $Return .= '<div style="padding:5px;" id="option'.$ID.'">Option: <input type="text" name="Data[Content][_SelectOptions]['.$Field.'][]" value="" class="textfield" size="5" style="width:100px" /> [<span style="cursor:pointer;" onclick="jQuery(\'#option'.$ID.'\').remove()">remove</span>]</div>';
        }else{
            foreach($Config['Content']['_SelectOptions'][$Field] as $option){
                $ID = uniqid();
                $Return .= '<div style="padding:5px;" id="option'.$ID.'">Option: <input type="text" name="Data[Content][_SelectOptions]['.$Field.'][]" value="'.$option.'" class="textfield" size="5" style="width:100px" /> [<span style="cursor:pointer;" onclick="jQuery(\'#option'.$ID.'\').remove()">remove</span>]</div>';
            }
        }

        $Return .= '</div>';
return $Return;
}

function select_showFilter($Field, $Type, $Default, $Config, $EID) {

    

    $FieldTitle = '';
    if(!empty($Config['_FieldTitle'][$Field])) {
        $FieldTitle = df_parseCamelCase($Config['_FieldTitle'][$Field]);
    }
    $Class = '';
    $text = '';
    if(!empty($Default[$Field])) {
        $Class = 'class="highlight"';
        $text = $Default[$Field];
    }
    $UID = uniqid(rand(1,999));
    $Return = '<div class="filterField '.$Class.'"><h2>'.$FieldTitle.'</h2>';

    $Return .= '<select type="text" name="reportFilter['.$EID.']['.$Field.'][]" multiple="multiple" size="1" class="filterBoxes" id="filter_'.$EID.'_'.$UID.'">';

    foreach($Config['_SelectOptions'][$Field] as $optionValue){
        $sel = '';
        if(!empty($Default[$Field])){
            if(in_array($optionValue, $Default[$Field])){
                $sel = 'selected="selected"';
            }
        }
        $Return .= '<option value="'.$optionValue.'" '.$sel.'>'.$optionValue.'</option>';
    }

    $Return .= '</select>';

    $Return .= '</div>';

    $_SESSION['dataform']['OutScripts'] .= "
        jQuery(\"#filter_".$EID."_".$UID."\").multiselect();
    ";


    
    return $Return;

}

?>