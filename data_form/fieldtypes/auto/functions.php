<?php
// Functions
/*function auto_postProcess($Field, $Input, $Type, $Element, $Data){
	
		switch($Type){
		default:
			return $Input;
		}

}*/
function auto_preForm($Field, $Type, $Element, $Config){
    switch($Type){
		case 'userbase':
                    if(!empty($Config['_UserBaseFilter'][$Field])){
                        global $user_ID;                        
                        $_GET[$Field] = $user_ID;
                    }
		break;
	}
}

function auto_postForm($Field, $Type, $Element, $Config){
    switch($Type){
		case 'userbase':
                    if(!empty($Config['_UserBaseFilter'][$Field])){
                        //global $user_ID;
                        //get_currentuserinfo();
                        unset($_GET[$Field]);// = $user_ID;
                    }
		break;
	}
}
function auto_processValue($Value, $Type, $Field, $Config, $EID){
	switch($Type){
		case 'userbase':
			return $Value;
		break;
		case 'session':
			return $Value;
		break;
		case 'ipaddress':
			return $Value;
		break;
		case 'autovalue':
			return $Value;
		default:
			return $Value;
	}
}
function auto_handleInput($Field, $Input, $FieldType, $Element, $Data){

	switch($FieldType){
		case 'userbase':
                global $user_ID;
                get_currentuserinfo();
                    if($Element['_ActiveProcess'] != 'update' || !empty($Element['Content']['_UserBaseFilterCapture'][$Field])){
			return $user_ID;
                    }
                    return $Data[$Field];
		break;
		case 'session':
			return $_SESSION[$Element['Content']['_SessionValue'][$Field]];
		break;
		case 'UUID':
			return uniqid();
		break;
		case 'GETValue':
		if(!empty($_SERVER['HTTP_REFERER'])){
			$QString = explode('?', ($_SERVER['HTTP_REFERER']));
			if(!empty($QString[1])){
				parse_str($QString[1], $GETS);
				if(!empty($GETS[$Element['Content']['_AutoGet'][$Field]])){
					return $GETS[$Element['Content']['_AutoGet'][$Field]];
				}
			}
		}
		return 'NULL';
		break;
		case 'ipaddress':
			return $_SERVER['REMOTE_ADDR'];
		break;
		case 'autovalue':
        		return $Element['Content']['_AutoValue'][$Field];
	}
}
function auto_preset($Field, $Table, $Config = false){
	$Pre = '';
	if(!empty($Config['Content']['_AutoValue'][$Field])){
		$Pre = $Config['Content']['_AutoValue'][$Field];
	}
	$Return = 'Session Value: <input type="text" name="Data[Content][_AutoValue]['.$Field.']" value="'.$Pre.'" class="textfield" size="8" /> ';
return $Return;
}
function auto_get($Field, $Table, $Config = false){
	$Pre = '';
	if(!empty($Config['Content']['_AutoGet'][$Field])){
		$Pre = $Config['Content']['_AutoGet'][$Field];
	}
	$Return = 'Get Value: <input type="text" name="Data[Content][_AutoGet]['.$Field.']" value="'.$Pre.'" class="textfield" size="8" /> ';
return $Return;
}
function session_config($Field, $Table, $Config = false){

	$filter = '';
	if(!empty($Config['Content']['_UserBaseFilter'][$Field])){
		$filter = 'checked="checked"';
	}
	$filterCap = '';
	if(!empty($Config['Content']['_UserBaseFilterCapture'][$Field])){
		$filterCap = 'checked="checked"';
	}
	$Return = 'Capture Updates: <input type="checkbox" name="Data[Content][_UserBaseFilterCapture]['.$Field.']" value="1" '.$filterCap.' />';
	$Return .= '<br />&nbsp;Apply Hard Filter: <input type="checkbox" name="Data[Content][_UserBaseFilter]['.$Field.']" value="1" '.$filter.' />';

return $Return;
}

function session_value($Field, $Table, $Config = false){
	$Pre = '';
	if(!empty($Config['Content']['_SessionValue'][$Field])){
		$Pre = $Config['Content']['_SessionValue'][$Field];
	}
	$Return = 'Session Value: <input type="text" name="Data[Content][_SessionValue]['.$Field.']" value="'.$Pre.'" class="textfield" size="8" /> ';
return $Return;
}


?>