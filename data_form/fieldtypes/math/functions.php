<?php
$calcs = array();
function math_processValue($Value, $Type, $Field, $Config, $EID, $Data, $Caller = 'list'){
	//echo $Value;
        global $calcs;
        if($Type == 'percentage'){

            //return $Field;
            //return $Value;
            //echo $Field;
            //dump($Data);
            //echo $_SESSION['queries'][$EID];


            $Res = mysql_query($_SESSION['queries'][$EID]);
            //mysql_data_seek($Res, 0);
            while($postData = mysql_fetch_assoc($Res)){
                $Pre[] = $postData[$Field];
                //dump($postData);
                //echo $Field;
            }
            if(empty($calcs[md5($_SESSION['queries'][$EID])])){
                $listTotal = array_sum($Pre);
            }else{
                $listTotal = $calcs[md5($_SESSION['queries'][$EID])];
            }

            $Return = round(($Value/$listTotal)*100, 2);
            unset($Pre);

            $Out = '<div style="width:'.($Return).'%; background:#009922; float:left;">&nbsp;</div>&nbsp;'.$Return.'%';

            if($Caller == 'list'){
                return $Out;
            }
            return $Return;
            //return $_SESSION['queries'][$EID];


        }

	if($Type == 'multiply'){
            //dump($Data);
            //dump($Config['_multiply']);
                $Opp = '*';
                
                if(!empty($Config['_multiply'][$Field]['opperator'])){
                    $Opp = $Config['_multiply'][$Field]['opperator'];
                }
                
            $mathString = $Data[$Config['_multiply'][$Field]['A']].$Opp.$Data[$Config['_multiply'][$Field]['B']];
            $DoCalc = create_function("", "return " . $mathString . ";" );
            return number_format($DoCalc(true));

            //return $Value;
	}
	if($Type == 'datediff'){

                if($Config['_dateDiff'][$Field]['A'] == 'NOW'){
                    $DateA = date('Y-m-d H:i:s');
                }else{
                    $DateA = $Data[$Config['_dateDiff'][$Field]['A']];
                }
                if($Config['_dateDiff'][$Field]['B'] == 'NOW'){
                    $DateB = date('Y-m-d H:i:s');
                }else{
                    $DateB = $Data[$Config['_dateDiff'][$Field]['B']];
                }



		return math_timeDuration($DateA, $DateB, $Config['_dateDiff'][$Field]['prefix'], $Config['_dateDiff'][$Field]['suffix']);
	}
        if($Config['_mathMysqlFunc'][$Field] == 'sumtotal'){
            $_SERVER['fieldMath'][$Field] = $_SERVER['fieldMath'][$Field]+$Value;
            return $_SERVER['fieldMath'][$Field];
        }
        return $Value;
}

function math_postProcess($Field, $Input, $FieldType, $Config, $Data, $ID){
}
function math_handleInput($Field, $Input, $FieldType, $Config, $Data){
	if($FieldType == 'multiply'){
                $Opp = '*';
                if(!empty($Config['Content']['_multiply'][$Field]['opperator'])){
                    $Opp = $Config['Content']['_multiply'][$Field]['opperator'];
                }
		$mathString = $Data[$Config['Content']['_multiply'][$Field]['A']].$Opp.$Data[$Config['Content']['_multiply'][$Field]['B']];
                $DoCalc = create_function("", "return (" . $mathString . ");" );
                return number_format($DoCalc(true));

		//return 'pi';//$Value;
	}
	if($FieldType == 'datediff'){
		return math_timeDuration($Data[$Config['Content']['_dateDiff'][$Field]['A']], $Data[$Config['Content']['_dateDiff'][$Field]['B']]);
	}

}

// Args = $Field, $table, ElementConfig
function math_multiplysetup($Field, $Table, $ElementConfig = false){
        $Defaults = false;
        if(!empty($ElementConfig)){
                $Defaults = $ElementConfig['Content']['_multiply'][$Field];
            }
		$Return .= math_loadfields($Table, $Field, $Defaults, $ElementConfig);
	return $Return;
}
function math_datesetup($Field, $Table, $ElementConfig = false){
		$Return .= math_loaddates($Table, $Field, $ElementConfig);
	return $Return;
}

function math_loadfields($Table, $Field, $Defaults = false, $Media = false){

        
        $Config = $Media['Content'];       
	$result = mysql_query("SHOW COLUMNS FROM `".$Table."`");
	if (mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_assoc($result)){
			$Sel = '';
                        
			if(!empty($Defaults['A'])){
				if($Defaults['A'] == $row['Field']){
					$Sel = 'selected="selected"';                                        
				}
			}
			$ValueReturn .= '<option value="'.$row['Field'].'" '.$Sel.'>'.$row['Field'].'</option>';
			$Sel = '';
			if(!empty($Defaults['B'])){
				if($Defaults['B'] == $row['Field']){
					$Sel = 'selected="selected"';
				}
			}
			$IDReturn .= '<option value="'.$row['Field'].'" '.$Sel.'>'.$row['Field'].'</option>';
		}
	}
	$IReturn = '<div class="list_row1" style="padding:3px;"><select name="Data[Content][_multiply]['.$Field.'][A]" id="Ref_'.$Table.'">';
		$IReturn .= $IDReturn;

                if(!empty($Config)) {
                    if(!empty ($Config['_CloneField'])) {
                        $IReturn .= '<optgroup label="Cloned Fields">';
                        foreach ($Config['_CloneField'] as $FieldKey=>$Array) {
                            $Sel = '';
                            if($Defaults['A'] == $FieldKey) {
                                $Sel = 'selected="selected"';
                            }
                            if($FieldKey != $Field){
                                $IReturn .= '<option value="'.$FieldKey.'" '.$Sel.'>'.$Config['_FieldTitle'][$FieldKey].'</option>';
                            }
                        }
                        foreach ($Config['_CloneField'] as $FieldKey=>$Array) {
                            $Sel = '';
                            if($Defaults['B'] == $FieldKey) {
                                $Sel = 'selected="selected"';
                            }
                            if($FieldKey != $Field){
                                $VReturn .= '<option value="'.$FieldKey.'" '.$Sel.'>'.$Config['_FieldTitle'][$FieldKey].'</option>';
                            }
                        }

                    }
                }

	$IReturn .= '</select>';

        $VReturn .= '<select name="Data[Content][_multiply]['.$Field.'][opperator]" id="Ref_'.$Table.'">';

            $sel = '';
            if(!empty($Defaults['opperator'])){
                if($Defaults['opperator'] == '+'){
                    $sel = 'selected="selected"';
                }
            }
            $VReturn .= '<option value="+" '.$sel.'>+</option>';
            $sel = '';
            if(!empty($Defaults['opperator'])){
                if($Defaults['opperator'] == '-'){
                    $sel = 'selected="selected"';
                }
            }
            $VReturn .= '<option value="-" '.$sel.'>-</option>';
            $sel = '';
            if(!empty($Defaults['opperator'])){
                if($Defaults['opperator'] == '*'){
                    $sel = 'selected="selected"';
                }
            }
            $VReturn .= '<option value="*" '.$sel.'>*</option>';
            $sel = '';
            if(!empty($Defaults['opperator'])){
                if($Defaults['opperator'] == '/'){
                    $sel = 'selected="selected"';
                }
            }
            $VReturn .= '<option value="/" '.$sel.'>/</option>';

        $VReturn .= '</select>';

	$VReturn .= '<select name="Data[Content][_multiply]['.$Field.'][B]" id="Ref_'.$Table.'">';
		$VReturn .= $ValueReturn;

            if(!empty($Config)) {
                if(!empty ($Config['_CloneField'])) {
                    $VReturn .= '<optgroup label="Cloned Fields">';
                    foreach ($Config['_CloneField'] as $FieldKey=>$Array) {
                        $Sel = '';
                        if($Defaults[$Field]['ID'] == $FieldKey) {
                            $Sel = 'selected="selected"';
                        }
                        $VReturn .= '<option value="'.$FieldKey.'" '.$Sel.'>'.$Config['_FieldTitle'][$FieldKey].'</option>';
                    }

                }
            }

	$VReturn .= '</select></div>';


return $IReturn.$VReturn;
}
function math_loaddates($Table, $Field, $Config){

	$result = mysql_query("SHOW COLUMNS FROM `".$Table."`");
	if (mysql_num_rows($result) > 0) {
                $IDReturn .= '<option value="NOW" >NOW</option>';
                $ValueReturn .= '<option value="NOW" >NOW</option>';
		while ($row = mysql_fetch_assoc($result)){
			$Sel = '';
			if(!empty($Config['Content']['_dateDiff'][$Field]['B'])){
				if($Config['Content']['_dateDiff'][$Field]['B'] == $row['Field']){
					$Sel = 'selected="selected"';
				}
			}
			$ValueReturn .= '<option value="'.$row['Field'].'" '.$Sel.'>'.$row['Field'].'</option>';
			$Sel = '';
			if(!empty($Config['Content']['_dateDiff'][$Field]['A'])){
				if($Config['Content']['_dateDiff'][$Field]['A'] == $row['Field']){
					$Sel = 'selected="selected"';
				}
			}
			$IDReturn .= '<option value="'.$row['Field'].'" '.$Sel.'>'.$row['Field'].'</option>';
		}


                if(!empty ($Config['Content']['_CloneField'])) {
                    $ValueReturn .= '<optgroup label="Cloned Fields">';
                    $IDReturn .= '<optgroup label="Cloned Fields">';
                    foreach ($Config['Content']['_CloneField'] as $FieldKey=>$Array) {
                        if($FieldKey != $Field){
                            $Sel = '';
                            if($Config['Content']['_dateDiff'][$Field]['A'] == $FieldKey) {
                                $Sel = 'selected="selected"';
                            }
                            $IDReturn .= '<option value="'.$FieldKey.'" '.$Sel.'>'.$Config['Content']['_FieldTitle'][$FieldKey].'</option>';
                            $Sel = '';
                            if($Config['Content']['_dateDiff'][$Field]['B'] == $FieldKey) {
                                $Sel = 'selected="selected"';
                            }
                            $ValueReturn .= '<option value="'.$FieldKey.'" '.$Sel.'>'.$Config['Content']['_FieldTitle'][$FieldKey].'</option>';
                        }
                    }
                }

	}
	$IReturn = '<div class="list_row1" style="padding:3px;">Start Date<select name="Data[Content][_dateDiff]['.$Field.'][A]" id="Ref_'.$Table.'">';
		$IReturn .= $IDReturn;
	$IReturn .= '</select> End Date ';
	$VReturn .= '<select name="Data[Content][_dateDiff]['.$Field.'][B]" id="Ref_'.$Table.'">';
		$VReturn .= $ValueReturn;
	$VReturn .= '</select></div>';


    $Pre = '';
    $Suf = '';

    if(!empty($Config['Content']['_dateDiff'][$Field]['prefix'])) {
        $Pre = $Config['Content']['_dateDiff'][$Field]['prefix'];
    }
    if(!empty($Config['Content']['_dateDiff'][$Field]['suffix'])) {
        $Suf = $Config['Content']['_dateDiff'][$Field]['suffix'];
    }

    $Return .= 'Prefix: <input type="text" name="Data[Content][_dateDiff]['.$Field.'][prefix]" value="'.$Pre.'" class="textfield" size="5" />&nbsp;';
    $Return .= ' Suffix: <input type="text" name="Data[Content][_dateDiff]['.$Field.'][suffix]" value="'.$Suf.'" class="textfield" size="5" />';


return $IReturn.$VReturn.$Return;
}

function math_timeDuration($date1, $date2, $pre = '', $suf = '')
{



    if(empty($date1) || $date1 == '0000-00-00 00:00:00') {
        return ".--";
    }
    if(empty($date2) || $date2 == '0000-00-00 00:00:00') {
        return "--.";
    }

    $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths         = array("60","60","24","7","4.35","12","10");

    $now             = strtotime($date2);
    $unix_date         = strtotime($date1);

       // check validity of date
    if(empty($unix_date)) {
        return "Bad date";
    }

    // is it future date or past date
    if($now > $unix_date) {
        $difference     = $now - $unix_date;
        $tense         = "ago";

    } else {
        $difference     = $unix_date - $now;
        $tense         = "from now";
    }

    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }

    $difference = round($difference);

    if($difference != 1) {
        $periods[$j].= "s";
    }
    return $pre.$difference.' '.$periods[$j].$suf;
}


function math_mysqlfunc($Field, $Table, $ElementConfig = false){

        $Return = 'Function: <select name="Data[Content][_mathMysqlFunc]['.$Field.']" id="" >';
	$sel = '';
        if($ElementConfig['Content']['_mathMysqlFunc'][$Field] == 'sum'){
		$sel = 'selected="selected"';
	}
        $Return .= '<option value="sum" '.$sel.'>sum()</option>';
        $sel = '';
        if($ElementConfig['Content']['_mathMysqlFunc'][$Field] == 'sumtotal'){
		$sel = 'selected="selected"';
	}
        $Return .= '<option value="sumtotal" '.$sel.'>count() incremental</option>';
        $sel = '';
        if($ElementConfig['Content']['_mathMysqlFunc'][$Field] == 'count'){
		$sel = 'selected="selected"';
	}
        $Return .= '<option value="count" '.$sel.'>count()</option>';
        $sel = '';
        if($ElementConfig['Content']['_mathMysqlFunc'][$Field] == 'avg'){
		$sel = 'selected="selected"';
	}
        $Return .= '<option value="avg" '.$sel.'>avg()</option>';

        $sel = '';
        if($ElementConfig['Content']['_mathMysqlFunc'][$Field] == '_custom'){
		$sel = 'selected="selected"';
	}
        $Return .= '<option value="_custom" '.$sel.'>Custom</option>';

        $Return .= '</select>';



        $custom = '';
        
	if(!empty($ElementConfig['Content']['_mathMysqlCustomFunc'][$Field])){
		$custom = $ElementConfig['Content']['_mathMysqlCustomFunc'][$Field];
	}
        $Return .= '<div style="padding:3px;"><strong>If custom:</strong></div>';
        $Return .= '<br />Custom Function: <input type="text" name="Data[Content][_mathMysqlCustomFunc]['.$Field.']" value="'.$custom.'" class="textfield" /> e.g. myfunc() or myfunc &nbsp;<br /> Make sure you have spelled the function name correctly and that it does exists. If the funciton does now exists, no results will be returnd.';

return $Return;

}
?>