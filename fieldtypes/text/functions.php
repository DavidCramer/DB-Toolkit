<?php
// Functions
function text_viewValue($Value, $Type, $Field, $Config, $EID, $Data){

switch ($Type){
		case 'textarealarge':
			return nl2br($Value);
			break;
		default:
			$Pre = '';
			$Suf = '';

			if(!empty($Config['_Prefix'][$Field])){
				$Pre = $Config['_Prefix'][$Field];
			}
			if(!empty($Config['_Suffix'][$Field])){
				$Suf = $Config['_Suffix'][$Field];
			}

			if(strlen($Value) == 100 && empty($_GET['format_'.$EID])){
				$outText = '<span title="'.htmlentities($Value).'" name="'.htmlentities($Value).'">'.substr($Value, 0 ,100).'&hellip;</span>';
			}

			return $Pre.$Value.$Suf;
			break;

		}
}
function text_processDelete($Field, $Input, $FieldType, $Config, $Data){
    return $Input;
}
function text_process($Field, $Input, $FieldType, $Config, $Default){
	if($FieldType == 'presettext'){
		return $Config['_Preset'][$Field];
	}
        if($FieldType == 'sanitized'){
            return sanitize_title($Default[$Config['_sanitizeField'][$Field]]);
        }
        if($FieldType == 'password'){
            if(empty($Input) && !empty($Default[$Field])){
                return $Default[$Field];
            }
            return md5($Input);
        }
	return $Input;
}
function text_postProcess($Field, $Input, $Type, $Element, $Data, $ReturnField){
    if($Type == 'emailaddress'){



        if(!empty($Element['Content']['_forwardResult'][$Field])){


            $default_headers = array(
                'Version' => 'Version'
            );
            $version = get_file_data(WP_PLUGIN_DIR.'/db-toolkit/plugincore.php', $default_headers, 'db-toolkit-fieldtype');
            $Headers = 'From: '.$Element['Content']['_emailSender'][$Field] . "\r\n" .
                       'Reply-To: '.$Element['Content']['_emailSender'][$Field] . "\r\n" .
                       'X-Mailer: DB-Toolkit/'.$version['Version'];

            $Body = "Submitted Data from ".date("r")."\r\n";
            $Body .= "=============================\r\n";
            foreach($Data as $FieldKey=>$FieldValue){
                if(strpos($FieldKey, '_control_') === false){
                    $Body .= $FieldKey.": ".$FieldValue."\r\n";
                }
            }
            $Body .= "=============================\r\n";
            $Body .= "Powered By DB-Toolkit\r\n";
           mail($Data[$Field], $Element['Content']['_emailForwardSubject'][$Field], $Body, $Headers);
        }

    }
    //vardump($Data);
}

function text_chartotal($Field, $Table, $Config = false){

        $PreLen = '';
	if(!empty($Config['_FieldLength'][$Field])){
		$PreLen = $Config['_FieldLength'][$Field];
	}

    	$Return = 'Number of Characters: <input type="text" name="data[_FieldLength]['.$Field.']" value="'.$PreLen.'" class="textfield" size="5" style="width:100px" /><br /><span class="description">Set the number of allowed characters. Leave Blank for unlimited.</span>';

        return $Return;

}
function text_presuff($Field, $Table, $Config = false){

	$PreLen = '98%';
	$Pre = '';
	$Suf = '';

	if(!empty($Config['_FieldLength'][$Field])){
		$PreLen = $Config['_FieldLength'][$Field];
	}
	if(!empty($Config['_Prefix'][$Field])){
		$Pre = $Config['_Prefix'][$Field];
	}
	if(!empty($Config['_Suffix'][$Field])){
		$Suf = $Config['_Suffix'][$Field];
	}


	$Return = 'Length: <input type="text" name="data[_FieldLength]['.$Field.']" value="'.$PreLen.'" class="textfield" size="5" style="width:100px" />&nbsp;<br />';
	$Return .= 'Prefix: <input type="text" name="data[_Prefix]['.$Field.']" value="'.$Pre.'" class="textfield" size="5" style="width:100px" />&nbsp;<br />';
	$Return .= ' Suffix: <input type="text" name="data[_Suffix]['.$Field.']" value="'.$Suf.'" class="textfield" size="5" style="width:100px" /><br />';
        $Return .= 'Type: <select name="data[_fieldType]['.$Field.']">';
            $type = 'text';

                $sel = 'selected="selected"';
                if($Config['_fieldType'][$Field] != 'text'){
                    $sel = '';
                }
                $Return .= '<option value="text" '.$sel.'>Textfield</option>';
                $sel = '';
                if($Config['_fieldType'][$Field] == 'password'){
                    $sel = 'selected="selected"';
                }
                $Return .= '<option value="password" '.$sel.'>Password</option>';


        $Return .= '</select><br />';
        $Return .= 'Filter Mode: <select name="data[_filterMode]['.$Field.']">';
            

                $sel = 'selected="selected"';
                if($Config['_filterMode'][$Field] != 'mid'){
                    $sel = '';
                }
                $Return .= '<option value="mid" '.$sel.'>% query %</option>';

                $sel = 'selected="selected"';
                if($Config['_filterMode'][$Field] != 'before'){
                    $sel = '';
                }
                $Return .= '<option value="before" '.$sel.'>% query</option>';
                
                $sel = 'selected="selected"';
                if($Config['_filterMode'][$Field] != 'after'){
                    $sel = '';
                }
                $Return .= '<option value="after" '.$sel.'>query %</option>';



        $Return .= '</select>';


return $Return;
}


function text_emailSetup($Field, $Table, $Config = false){


        $sel = '';
	if(!empty($Config['_forwardResult'][$Field])){
		$sel = 'checked="checekd"';
	}

        $Return .= '&nbsp;Forward result to this address: <input type="checkbox" name="data[_forwardResult]['.$Field.']" value="1" '.$sel.' /><br />';

        $Pre = 'Confirmation of Submitted data';
	if(!empty($Config['_emailForwardSubject'][$Field])){
		$Pre = $Config['_emailForwardSubject'][$Field];
	}
        $Return .= 'Email Subject: <input type="text" name="data[_emailForwardSubject]['.$Field.']" value="'.$Pre.'" class="textfield" /><br />';


        $Pre = 'db-toolkit';
	if(!empty($Config['_emailSender'][$Field])){
		$Pre = $Config['_emailSender'][$Field];
	}
        $Return .= 'Email Sender: <input type="text" name="data[_emailSender]['.$Field.']" value="'.$Pre.'" class="textfield" />&nbsp;';

return $Return;
}



function text_showFilter($Field, $Type, $Default, $Config, $EID) {



    if(!empty($Default[$Field])) {
        $Class = 'class="highlight"';
        $text = $Default[$Field];
    }
    $UID = uniqid(rand(1,999));
if($Type == 'integer'){
    $Return .= '<input type="text" name="'.$Field.'[]" class="filterSearch" id="filter_'.$EID.'_'.$UID.'from" value="'.$text[0].'"  size="12" style="width: 100px;" /> to ';
    $Return .= '<input type="text" name="'.$Field.'[]" class="filterSearch" id="filter_'.$EID.'_'.$UID.'to" value="'.$text[1].'"  size="12" style="width: 100px;" />';
}else{
    $Return .= '<input type="text" name="dbt_filter['.$Field.']" class="filterSearch" id="filter_'.$EID.'_'.$UID.'" value="'.$text.'" />';
}



    return $Return;

}

?>