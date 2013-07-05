<?php
// Functions
function text_handleInput($Field, $Input, $FieldType, $Config, $Default){
	if($FieldType == 'presettext'){
		return $Config['Content']['_Preset'][$Field];
	}
        if($FieldType == 'sanitized'){
            return sanitize_title($Default[$Config['Content']['_sanitizeField'][$Field]]);
        }
        if($FieldType == 'password'){
            if(empty($Input) && !empty($Default[$Field])){
                return $Default[$Field];
            }
            return md5($Input);
        }
	return $Input;
}
function text_processValue($Value, $Type, $Field, $Config, $EID, $Data){

switch ($Type){
		case 'textarealarge':
			return nl2br($Value);
			break;
		case 'phpcodeblock':
				return '<input type="button" id="codeRun_'.$Data['_return_'.$Config['_ReturnFields'][0]].'" value="Run Code" onclick="text_runCode(\''.$Field.'\', \''.$EID.'\', \''.$Data['_return_'.$Config['_ReturnFields'][0]].'\');" />';
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


function text_sanitize($Field, $Table, $Config = false){
    global $wpdb;

    $columns = $wpdb->get_results("SHOW COLUMNS FROM `".$Table."`", ARRAY_A);
    $Return = 'Field to Sanitize: <select name="Data[Content][_sanitizeField]['.$Field.']" >';
    foreach($columns as $Column){
	$Sel = '';
        if(!empty($Config['Content']['_sanitizeField'][$Field])){

		if($Config['Content']['_sanitizeField'][$Field] == $Column['Field']){
                    $Sel = 'selected="selected"';
                }
	}
        $Return .= '<option value="'.$Column['Field'].'" '.$Sel.'>'.$Column['Field'].'</option>';
    }
    $Return .= '</select><span class="description"> A Sanitized version of the selected field will be saved.</span>';

    return $Return;

}

function text_presuff($Field, $Table, $Config = false){

	$PreLen = '98%';
	$Pre = '';
	$Suf = '';

	if(!empty($Config['Content']['_FieldLength'][$Field])){
		$PreLen = $Config['Content']['_FieldLength'][$Field];
	}
	if(!empty($Config['Content']['_Prefix'][$Field])){
		$Pre = $Config['Content']['_Prefix'][$Field];
	}
	if(!empty($Config['Content']['_Suffix'][$Field])){
		$Suf = $Config['Content']['_Suffix'][$Field];
	}


	$Return = 'Length: <input type="text" name="Data[Content][_FieldLength]['.$Field.']" value="'.$PreLen.'" class="textfield" size="5" style="width:100px" />&nbsp;<br />';
	$Return .= 'Prefix: <input type="text" name="Data[Content][_Prefix]['.$Field.']" value="'.$Pre.'" class="textfield" size="5" style="width:100px" />&nbsp;<br />';
	$Return .= ' Suffix: <input type="text" name="Data[Content][_Suffix]['.$Field.']" value="'.$Suf.'" class="textfield" size="5" style="width:100px" /><br />';
        $Return .= 'Type: <select name="Data[Content][_fieldType]['.$Field.']">';
            $type = 'text';

                $sel = 'selected="selected"';
                if($Config['Content']['_fieldType'][$Field] != 'text'){
                    $sel = '';
                }
                $Return .= '<option value="text" '.$sel.'>Textfield</option>';
                $sel = '';
                if($Config['Content']['_fieldType'][$Field] == 'password'){
                    $sel = 'selected="selected"';
                }
                $Return .= '<option value="password" '.$sel.'>Password</option>';


        $Return .= '</select><br />';
        $Return .= 'Filter Mode: <select name="Data[Content][_filterMode]['.$Field.']">';
            

                $sel = 'selected="selected"';
                if($Config['Content']['_filterMode'][$Field] != 'mid'){
                    $sel = '';
                }
                $Return .= '<option value="mid" '.$sel.'>% query %</option>';

                $sel = 'selected="selected"';
                if($Config['Content']['_filterMode'][$Field] != 'before'){
                    $sel = '';
                }
                $Return .= '<option value="before" '.$sel.'>% query</option>';
                
                $sel = 'selected="selected"';
                if($Config['Content']['_filterMode'][$Field] != 'after'){
                    $sel = '';
                }
                $Return .= '<option value="after" '.$sel.'>query %</option>';



        $Return .= '</select>';


return $Return;
}
function text_chartotal($Field, $Table, $Config = false){


        $chars = '';

        if(!empty($Config['Content']['_CharLength'][$Field])){
		$chars = $Config['Content']['_CharLength'][$Field];
	}

        $Return = 'Max Length (Characters): <input type="text" name="Data[Content][_CharLength]['.$Field.']" value="'.$chars.'" class="textfield" size="5" />&nbsp;Leave blank for unlimited.';


return $Return;
}

function text_preset($Field, $Table, $Config = false){

	$Preset = '';
	$PreLen = '';
	if(!empty($Config['Content']['_FieldLength'][$Field])){
		$PreLen = $Config['Content']['_Prefix'][$Field];
	}
	if(!empty($Config['Content']['_Preset'][$Field])){
		$Preset = $Config['Content']['_Preset'][$Field];
	}
	$Return = 'Length: <input type="text" name="Data[Content][_FieldLength]['.$Field.']" value="'.$PreLen.'" class="textfield" size="5" />&nbsp;';
	$Return .= '&nbsp;Preset Value: <input type="text" name="Data[Content][_Preset]['.$Field.']" value="'.$Preset.'" class="textfield" /> ';
return $Return;
}

function text_emailSetup($Field, $Table, $Config = false){


        $sel = '';
	if(!empty($Config['Content']['_forwardResult'][$Field])){
		$sel = 'checked="checekd"';
	}

        $Return .= '&nbsp;Forward result to this address: <input type="checkbox" name="Data[Content][_forwardResult]['.$Field.']" value="1" '.$sel.' /><br />';

        $Pre = 'Confirmation of Submitted data';
	if(!empty($Config['Content']['_emailForwardSubject'][$Field])){
		$Pre = $Config['Content']['_emailForwardSubject'][$Field];
	}
        $Return .= 'Email Subject: <input type="text" name="Data[Content][_emailForwardSubject]['.$Field.']" value="'.$Pre.'" class="textfield" />&nbsp;';


        $Pre = 'db-toolkit';
	if(!empty($Config['Content']['_emailSender'][$Field])){
		$Pre = $Config['Content']['_emailSender'][$Field];
	}
        $Return .= 'Email Sender: <input type="text" name="Data[Content][_emailSender]['.$Field.']" value="'.$Pre.'" class="textfield" />&nbsp;';

return $Return;
}


function text_runCode($Field, $EID, $ID){
	$Element = getelement($EID);
	$Config = $Element['Content'];
	//dump($Config);
	$Res = mysql_query("SELECT ".$Field." FROM ".$Config['_main_table']." where `".$Config['_ReturnFields'][0]."` = '".$ID."' LIMIT 1;");
	$Data = mysql_fetch_assoc($Res);
	ob_start();
	eval($Data[$Field]);
	return ob_get_clean();
}


function text_wysiwygsetup($Field, $Table, $Config = false){



        $Buttons = array('Source','Templates',
        'Cut','Copy','Paste','PasteText','PasteFromWord','Print', 'SpellChecker', 'Scayt',
        'Undo','Redo','Find','Replace','SelectAll','RemoveFormat',
        'Bold','Italic','Underline','Strike','Subscript','Superscript',
        'NumberedList','BulletedList','Outdent','Indent','Blockquote','CreateDiv',
        'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock',
        'BidiLtr', 'BidiRtl',
        'Link','Unlink','Anchor',
        'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak',
        'Styles','Format','Font','FontSize',
        'TextColor','BGColor',
        'Maximize', 'ShowBlocks','About');




        $Return = '<h5>Select Buttons for Toolbar <span class="button-primary">Selected</span> <span class="button">Not Selected</span></h5>';
        $Return .= '';
        $Return .= '<div style="clear:both;"></div>';
        if(!empty($Config['Content']['_activatedButtons'][$Field])){
            $Defaults = $Config['Content']['_activatedButtons'][$Field];
        }else{
            $Defaults = array('Source','Templates',
                        'PasteFromWord','Print', 'SpellChecker',
                        'SelectAll',
                        'Bold','Italic','Underline',
                        'NumberedList','BulletedList','Outdent','Indent',
                        'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock',

                        'Link','Unlink',
                        'Image',
                        'Styles','Format','Font','FontSize',
                        'TextColor','BGColor',
                        'Maximize');
        }

        foreach($Buttons as $Button){

            ob_start();

            $ID = uniqid('togglebutton_');

            if(in_array($Button, $Defaults)){
                $Selected = 'button-primary';
                $Checked = 'checked="checked"';
            }else{
                $Selected = 'button';
                $Checked = '';
            }


            ?>
            <div title="Sortable" onclick="df_setToggle('<?php echo $ID; ?>');" id="<?php echo $ID; ?>" class="<?php echo $Selected; ?> cke_skin_kama" style="float:left; margin: 2px;">
                <span class="cke_button">
                <span class="cke_button_<?php echo strtolower($Button); ?>" style=" cursor: pointer;">
                <span class="cke_icon" style="margin: 1px 1px;"></span>
                <input type="checkbox" id="<?php echo $ID; ?>_check" <?php echo $Checked; ?> value="<?php echo $Button; ?>" name="Data[Content][_activatedButtons][<?php echo $Field; ?>][]" style="display: none;"> <?php echo $Button; ?>
                </span>
                </span>
            </div>&nbsp;
            <?php
            $Return .= ob_get_clean();
        }
        $Return .= '<div style="clear:both;"></div>';
        return $Return;

}


function text_showFilter($Field, $Type, $Default, $Config, $EID) {


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

    $Return .= '<div class="filterField '.$Class.'"><h2>'.$FieldTitle.'</h2>';
if($Type == 'integer'){
    $Return .= '<input type="text" name="reportFilter['.$EID.']['.$Field.'][]" class="filterSearch" id="filter_'.$EID.'_'.$UID.'from" value="'.$text[0].'"  size="12" style="width: 100px;" /> to ';
    $Return .= '<input type="text" name="reportFilter['.$EID.']['.$Field.'][]" class="filterSearch" id="filter_'.$EID.'_'.$UID.'to" value="'.$text[1].'"  size="12" style="width: 100px;" />';
}else{

    $Return .= '<input type="text" name="reportFilter['.$EID.']['.$Field.']" class="filterSearch" id="filter_'.$EID.'_'.$UID.'" value="'.$text.'" />';
}
    $Return .= '</div>';



    return $Return;

}

?>