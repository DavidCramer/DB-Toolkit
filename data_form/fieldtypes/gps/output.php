<?php
	if(!empty($Data[$Field])){
		$sOut = explode(',', $Data[$Field]);
		$sOut['Lat'] = $sOut[0];
		$sOut['Lon'] = $sOut[1];
		$marker = '';
		if(!empty($Config['_view_map']['_showMarker'][$Field])){
			$marker = '&markers='.$sOut['Lat'].','.$sOut['Lon'].',midred';
		}
		$Img = 'http://maps.google.com/staticmap?center='.$sOut['Lat'].','.$sOut['Lon'].'&zoom='.$Config['_view_map']['_zoom'][$Field].'&size='.$Config['_view_map']['_MapX'][$Field].'x'.$Config['_view_map']['_MapY'][$Field].'&maptype='.$Config['_view_map']['_mapType'][$Field].'&key='.$Config['_googleMaps_Key'].'&sensor=false'.$marker;
		$Img = '<img src="'.$Img.'" width="'.$Config['_view_map']['_MapX'][$Field].'" height="'.$Config['_view_map']['_MapY'][$Field].'" />';
		//return $Img;
		$Out .= $Img;
		//$Out .= '<div class="captions">'.$FieldTitle.'</div>';
	}else{
		$Out .= 'Not Available';
		//$Out .= '<div class="captions">'.$FieldTitle.'</div>';
	}
	return;
	//$Out .= '<div class="'.$Row.'"><strong>'.$FieldTitle.'</strong> : '.$Data[$Field].'</div>';
?>