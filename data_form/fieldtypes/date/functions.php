<?php
// Functions
function date_handleInput($Field, $Input, $FieldType, $Config, $Data){
	
	//Min - Hour - Date - Month - Day
	if($FieldType == 'scheduleMin'){
		if($Config['_ActiveProcess'] == 'update'){
			$Input = unserialize($Input);
		}
		if(empty($Input)){
			$Input['all'] = 1;
			$Input[0] = 5;
		}
		if(!isset($Input[0])){
			$Input['all'] = 1;
			$Input[0] = 5;
		}
		$All = false;
		if(!empty($Input['all'])){
			if($Input[0] == '0'){
				$Input[0] = 1;
			}
			unset($Input['all']);
			$All = true;
		}
		$Min = array();
		foreach($Input as $Key=>$Value){
			$Min[$Value] = $Value;
			if(!empty($All)){
				//$Min['*'] = '*';
				$Start = $Value+$Value;
				for($m = $Start; $m <= 60; $m += $Value){
					if($m == 60){
						$m = 0;
						$Min[$m] = $m;
						break;
					}
					$Min[$m] = $m;	
				}
			}
		}
		/// min list output
		ksort($Min);
		//dump($Min);
		return '||'.implode('||',$Min).'||';
	}
	if($FieldType == 'scheduleHour'){
		if($Config['_ActiveProcess'] == 'update'){
			$Input = unserialize($Input);
		}
		if(empty($Input)){
			$Input['all'] = 1;
			$Input[0] = 1;
		}
		if(empty($Input[0])){
			$Input['all'] = 1;
			$Input[0] = 1;
		}
		$All = false;
		if(!empty($Input['all'])){
			unset($Input['all']);
			$All = true;
		}
		$Hour = array();
		$Start = $Value+$Value;
		$End = 24;
		if(!empty($Input['am']) && empty($Input['pm'])){
			$Hour['am'] = 'am';
			unset($Input['am']);
			$AM = true;
		}elseif(empty($Input['am']) && !empty($Input['pm'])){
			$Hour['am'] = 'pm';
			$PM = true;
			unset($Input['pm']);
		}elseif(!empty($Input['am']) && !empty($Input['pm'])){
			$Hour['am'] = 'am';
			$Hour['pm'] = 'pm';
			unset($Input['am']);
			unset($Input['pm']);
		}
		foreach($Input as $Key=>$Value){
			if(!empty($AM) && empty($PM)){
				$End = 11;	
				$Hour[$Value] = $Value;
			}elseif(empty($AM) && !empty($PM)){
				$Start = 12+$Value;
				$NewValue = $Value+12;
				if($NewValue == 24){
					$NewValue = 0;	
				}
				$Hour[$NewValue] = $NewValue;
				
			}elseif(!empty($Input['am']) && !empty($Input['pm'])){
				unset($Input['am']);
				unset($Input['pm']);
				$Hour[$Value] = $Value;
			}elseif(empty($Input['am']) && empty($Input['pm'])){
				$Hour[$Value] = $Value;
			}
			if(!empty($All)){
				for($h = $Start; $h <= $End; $h += $Value){
					if($h == 24){
						$h = 0;
						$Hour[$h] = $h;
						break;
					}
					$Hour[$h] = $h;	
				}
			}
		}
		/// Hour output
		ksort($Hour);
		return '||'.implode('||',$Hour).'||';
	}
	if($FieldType == 'scheduleDay'){
		if($Config['_ActiveProcess'] == 'update'){
			$Input = unserialize($Input);
		}
		if(empty($Input)){
			$Input['all'] = 1;
			$Input[0] = 1;
		}
		if(!isset($Input[0])){
			$Input['all'] = 1;
			$Input[0] = 1;
		}
		$All = false;
		if(!empty($Input['all'])){
			unset($Input['all']);
			$All = true;
		}
		$Day = array();
		foreach($Input as $Key=>$Value){
			$Day[$Value] = $Value;
			if(!empty($All)){
				$Start = 0;
				for($d = $Start; $d <= 7; $d ++){
					if($d == 7){
						$d = 0;
						$Day[$d] = $d;
						break;
					}
					$Day[$d] = $d;	
				}
			}
		}
		/// Day list output
		ksort($Day);
		return '||'.implode('||',$Day).'||';
	}
	if($FieldType == 'timestamp'){
		return date('Y-m-d H:i:s');	
	}
	if($FieldType == 'datetime'){
            if(!is_array($Input)){
                $Input = unserialize($Input);
            }
		return implode(' ', $Input);
	}
return $Input;
}

function date_processValue($Value, $Type, $Field, $Config, $EID){
	
	//return $Value;

	//if($Type == 'timestamp'){
	//	return date('Y-m-d H:i:s');	
	//}
	if($Type == 'scheduleMin'){
		$Value = str_replace('||', ', ', $Value);
		$Value = substr($Value,2,strlen($Value)-4);
		if($Value == '0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55'){
			return 'Every 5 Minutes';	
		}elseif($Value == '10, 20, 30, 40, 50'){
			return 'Every 10 Minutes';	
		}elseif($Value == '15, 30, 45'){
			return 'Every 15 Minutes';	
		}elseif($Value == '20, 40'){
			return 'Every 20 Minutes';	
		}elseif($Value == '0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59'){
			return 'Every Minute';
		}
		return $Value;
	}
	if($Type == 'scheduleHour'){
		$Value = str_replace('||', ', ', $Value);
		$Value = substr($Value,2,strlen($Value)-4);
		if($Value == 'am, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12'){
			return 'Every Hour (am only)';	
		}elseif($Value == 'pm, 0, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23'){		
			return 'Every Hour (pm only)';	
		}elseif($Value == '0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23' || $Value == 'am, pm, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23'){
			return 'Every Hour';	
		}elseif($Value == '1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12' || $Value == 'am, pm, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12'){
			return 'Every Hour (1am - 12pm)';
		}elseif($Value == '0, 2, 4, 6, 8, 10, 12, 14, 16, 18, 20, 22' || $Value == 'am, pm, 0, 2, 4, 6, 8, 10, 12, 14, 16, 18, 20, 22'){
			return 'Every 2 Hours';	
		}elseif($Value == '0, 3, 6, 9, 12, 15, 18, 21' || $Value == 'am, pm, 0, 3, 6, 9, 12, 15, 18, 21'){
			return 'Every 3 Hours';	
		}elseif($Value == '0, 4, 8, 12, 16, 20' || $Value == 'am, pm, 0, 4, 8, 12, 16, 20'){
			return 'Every 4 Hours';	
		}elseif($Value == '0, 5, 10, 15, 20' || $Value == 'am, pm, 0, 5, 10, 15, 20'){
			return 'Every 5 Hours';	
		}
		if(strstr($Value, 'am, pm, ')){
			return str_replace('am, pm, ', '', $Value).', am pm';
		}
		if(strstr($Value, 'am, ')){
			return str_replace('am, ', '', $Value).' am';
		}
		if(strstr($Value, 'pm, ')){
			return str_replace('pm, ', '', $Value);
		}
		return $Value;
	}
	if($Type == 'scheduleDay'){
		$Value = str_replace('||', ',', $Value);
		$Values = explode(',', substr($Value,1,strlen($Value)-2));
		$Days = array(0=>'Sun',1=>'Mon',2=>'Tue',3=>'Wed',4=>'Thu',5=>'Fri',6=>'Sat');
		$Value = array();
		foreach($Values as $Day){
			$Value[] = $Days[$Day];
		}
		$Value = implode(', ',$Value);
		if($Value == 'Sun, Mon, Tue, Wed, Thu, Fri, Sat'){
			return 'Every Day';
		}elseif($Value == 'Sun, Sat'){
			return 'Weekends';
		}elseif($Value == 'Mon, Tue, Wed, Thu, Fri'){
			return 'Week Days';
		}
		return $Value;
	}
	$outValue = date($Config['_dateFormat'][$Field], strtotime($Value));
        if($Value == '0000-00-00 00:00:00' || $Value == '0000-00-00' || $Value == ''){
            return '--.';
        }
        return $outValue;

}

function date_config($Field, $Table, $Config = false){
	
	$format = 'Y-m-d';
	$min = '';
	$max = '';
	$today = '';
	if(!empty($Config['Content']['_TodayDefault'][$Field])){
		$today = 'checked="checked"';
	}
	if(!empty($Config['Content']['_dateFormat'][$Field])){
		$format = $Config['Content']['_dateFormat'][$Field];
	}
	if(!empty($Config['Content']['_dateMin'][$Field])){
		$min = $Config['Content']['_dateMin'][$Field];
	}
	if(!empty($Config['Content']['_dateMax'][$Field])){
		$max = $Config['Content']['_dateMax'][$Field];
	}

	$Return = '&nbsp;Default to Today: <input type="checkbox" name="Data[Content][_TodayDefault]['.$Field.']" value="1" /><br />';
	$Return .= "<h3>Min/Max Dates</h3><div class=\"admin_config_panel\">As a numeric offset from today (-20), or as a string of periods and units ('+1M +10D'). For the last, use 'D' for days, 'W' for weeks, 'M' for months, or 'Y' for years</div>.<br />";
	$Return .= 'Min: <input type="text" name="Data[Content][_dateMin]['.$Field.']" value="'.$min.'" class="textfield" /><br />';
	$Return .= 'Max: <input type="text" name="Data[Content][_dateMax]['.$Field.']" value="'.$max.'" class="textfield" /><br />';
	$Return .= 'Format: <input type="text" name="Data[Content][_dateFormat]['.$Field.']" value="'.$format.'" class="textfield" />';

return $Return;
}

function date_showFilter($Field, $Type, $Default, $Config, $EID){
    
			$FieldTitle = '';
			if(!empty($Config['_FieldTitle'][$Field])){
				$FieldTitle = df_parseCamelCase($Field);	
                        }
			$Class = '';
			$DateFrom = '';//date('Y-m-d', strtotime('last week'));
			if(!empty($Default[$Field][0])){
				$DateFrom = $Default[$Field][0];
				$Class = 'highlight';
			}
			$DateTo = '';//date('Y-m-d', strtotime('next week'));
			if(!empty($Default[$Field][1])){
				$DateTo = $Default[$Field][1];
			}
			$UID = uniqid(rand(1,999));
                        
			$Return = '<div class="filterField '.$Class.'"><h2>'.$FieldTitle.'</h2>';
			$Return .= '<input type="text" name="reportFilter['.$EID.']['.$Field.'][]" class="filterSearch" id="startRange_'.$EID.'_'.$UID.'" value="'.$DateFrom.'" size="12" style="width: 100px;" /> to';
			$Return .= '<input type="text" class="filterSearch" name="reportFilter['.$EID.']['.$Field.'][]" id="endRange_'.$EID.'_'.$UID.'" value="'.$DateTo.'" size="12" style="width: 100px;" />&nbsp;';
                        $Return .= '</div>';
			
			$_SESSION['dataform']['OutScripts'] .= "
				jQuery.getScript(\"".DBT_URL."/data_form/fieldtypes/date/js/bootstrap-datepicker.js\", function(data, textStatus, jqxhr) {
					jQuery('#startRange_".$EID."_".$UID."').datepicker({
						format: 'yyyy-mm-dd',
					}).on('changeDate', function(ev){
						jQuery('#startRange_".$EID."_".$UID."').datepicker('hide');
					});
					jQuery('#endRange_".$EID."_".$UID."').datepicker({
						format: 'yyyy-mm-dd'
					}).on('changeDate', function(ev){
						jQuery('#endRange_".$EID."_".$UID."').datepicker('hide');
					});
				});
			
			";
			
		
return $Return;

}
?>