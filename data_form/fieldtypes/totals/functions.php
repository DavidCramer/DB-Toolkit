<?php

function totals_processValue($Value, $Type, $Field, $Config, $EID){
	// Totals Function Process
	if($Config['_TotalsFields'][$Field]['Function'] != 'none'){
		//$totalFunc = 'totals_'.$Config['_TotalsFields'][$Field]['Function'];
		$ValID = date('ymdhis').rand(0,9999);
		$GLOBALS['Totals'][$EID][$ValID]['Value'] = $Value;
		$GLOBALS['Totals'][$EID][$ValID]['Field'] = $Field;
		$GLOBALS['Totals'][$EID][$ValID]['Function'] = $Config['_TotalsFields'][$Field]['Function'];
		if($Config['_TotalsFields'][$Field]['Function'] == 'averages'){
			$GLOBALS['TotalsAverages'][$EID][$Field]['PreAverage'][] = $Value;
		}
		$Value = $ValID;
	}
return $Value;
}

function totals_vat($In){
	return ($In/100)*14;
}
function totals_addvat($In){
	$Vat = ($In/100)*14;
	return $In+$Vat;
}

function totals_percent($In, $Total){
	$Percent = round((($In/$Total)*100), 1).'%';
	$LinePercent = round((($In/$Total)*50), 1).'%';
	$Return = '<div><div style="background-color:#9C0; float:left; width:'.$LinePercent.'; display:block; overflow:hidden;" title="'.$Percent.'">&nbsp;</div>';
	$Return .= '<span style="position:related;">'.$Percent.'</span></div>';

return $Return;

}
function totals_averages($In, $Total){
	$Diff = $In-$Total;
	
	$Percent = round((($Diff/$Total)*100), 1).'%';
	
	if($Diff < 0){
		$LinePercent = round((($Diff/$Total)*20), 1);
		$LinePercent = str_replace('-', '', $LinePercent );
		$LinePercent = $LinePercent+0;
		if($LinePercent > 50){
			//$LinePercent = 49;
		}
		$Percent = str_replace('-', '', $Percent);
		$Left = '<div style="background-color:#C30; float:right; width:'.$LinePercent.'%; display:block; overflow:hidden;" title="'.$Percent.'">&nbsp;</div><span><span style="position:reletive;">'.$Percent.'</span></span>';	
		$Right = '&nbsp;';
	}elseif($Diff > 0){
		$LinePercent = round((($Diff/$Total)*20), 1);
		$LinePercent = str_replace('-', '', $LinePercent );
		$LinePercent = $LinePercent+0;
		if($LinePercent > 50){
			//$LinePercent = 49;
		}
		$Right = '<div style="background-color:#9C0; float:left; width:'.$LinePercent.'%; display:block; overflow:hidden;" title="'.$Percent.'">&nbsp;</div><span style="position:absolute;">'.$Percent.'</span>';
		$Left = '&nbsp;';
	}elseif($Diff == 0){
		return '<div style="text-align:center; overflow:hidden;">-</div>';
	}
	
	$Return = '
	<div>
	<div style="width:50%; float:left; text-align:right; overflow:hidden;">'.$Left.'</div>
	<div style="width:50%; float:left; text-align:left;overflow:hidden;">'.$Right.'</div>
	<div style="clear:both;"></div>
	</div>';

return $Return;

}

?>