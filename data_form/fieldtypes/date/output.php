<?php
	if(!empty($Types)){
		$FieldSet = $Types;
	}
	if($FieldSet[1] == 'scheduleMin' || $FieldSet[1] == 'scheduleHour' || $FieldSet[1] == 'scheduleDay'){
		$Out .= date_processValue($Data[$Field], $FieldSet[1], $Field, $Config, $Element['ID']);
	}else{
		$OutDate = $Data[$Field];
		if(!empty($Config['_TodayDefault'][$Field])){
			$OutDate = date($Config['_TodayDefault'][$Field], $Data[$Field]);
		}
		$Out .= $OutDate;
	}
?>