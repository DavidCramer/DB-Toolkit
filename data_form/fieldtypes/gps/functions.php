<?php
// Functions
function gps_handleInput($Field, $Input, $FieldType, $Config){
	if(is_array($Input)){
		return implode(',',$Input);
	}else{
		$Out = unserialize($Input);	
		return implode(',',$Out);
	}
}
function gps_processValue($Value, $Type, $Field, $Config, $EID){
	if(!empty($Value)){
		$Out = explode(',', $Value);
		$Out['Lat'] = $Out[0];
		$Out['Lon'] = $Out[1];
		$marker = '';
		if(!empty($Config['_inline_map']['_showMarker'][$Field])){
			$marker = '&markers='.$Out['Lat'].','.$Out['Lon'].',midred';
		}
		$Img = 'http://maps.google.com/staticmap?center='.$Out['Lat'].','.$Out['Lon'].'&zoom='.$Config['_inline_map']['_zoom'][$Field].'&size='.$Config['_inline_map']['_MapX'][$Field].'x'.$Config['_inline_map']['_MapY'][$Field].$marker.'&maptype='.$Config['_inline_map']['_mapType'][$Field].'&key='.$Config['_googleMaps_Key'].'&sensor=false';
		$Img = '<img src="'.$Img.'" width="'.$Config['_inline_map']['_MapX'][$Field].'" height="'.$Config['_inline_map']['_MapY'][$Field].'" />';
		return $Img;
	}else{
		return 'Not Available';	
	}
}


function gps_setup($Field, $Table, $Config = false){
	$inlineX = 200;	
	$inlineY = 80;	
	$inlineZoom = 3;
	$marker = 'checked="checked"';
	$type = '';
	$apikey = '';
	if(!empty($Config)){
		$inlineX = $Config['Content']['_inline_map']['_MapX'][$Field];
		$inlineY = $Config['Content']['_inline_map']['_MapY'][$Field];
		$inlineZoom = $Config['Content']['_inline_map']['_zoom'][$Field];
		$marker = '';
		if(!empty($Config['Content']['_inline_map']['_showMarker'][$Field])){
			$marker = 'checked="checked"';
		}
		$type = $Config['Content']['_inline_map']['_mapType'][$Field];
		if(!empty($Config['Content']['_googleMaps_Key'])){
			$apikey = $Config['Content']['_googleMaps_Key'];
		}
	}


	$Return = '';
	$Return .= '<br /><strong>Inline Map Size</strong><br />';
	$Return .= 'Width:<input type="text" name="Data[Content][_inline_map][_MapX]['.$Field.']" value="'.$inlineX.'" class="textfield" size="5" />&nbsp;';
	$Return .= 'Height:<input type="text" name="Data[Content][_inline_map][_MapY]['.$Field.']" value="'.$inlineY.'" class="textfield" size="5" />&nbsp;';
	$Return .= 'Zoom Level:<input type="text" name="Data[Content][_inline_map][_zoom]['.$Field.']" value="'.$inlineZoom.'" class="textfield" size="10" />&nbsp;';
	$Return .= 'Map Type:<select name="Data[Content][_inline_map][_mapType]['.$Field.']">';
		$Sel = '';
		if($type == 'roadmap'){
			$Sel = 'selected="selected"';
		}
		$Return .= '<option value="roadmap" '.$Sel.'>roadmap</option>';
		$Sel = '';
		if($type == 'mobile'){
			$Sel = 'selected="selected"';
		}
		$Return .= '<option value="mobile" '.$Sel.'>mobile</option>';
		$Sel = '';
		if($type == 'satellite'){
			$Sel = 'selected="selected"';
		}
		$Return .= '<option value="satellite" '.$Sel.'>satellite</option>';
		$Sel = '';
		if($type == 'terrain'){
			$Sel = 'selected="selected"';
		}
		$Return .= '<option value="terrain" '.$Sel.'>terrain</option>';
		$Sel = '';
		if($type == 'hybrid'){
			$Sel = 'selected="selected"';
		}
		$Return .= '<option value="hybrid" '.$Sel.'>hybrid</option>';
		$Sel = '';
		if($type == 'mapmaker-roadmap'){
			$Sel = 'selected="selected"';
		}
		$Return .= '<option value="mapmaker-roadmap" '.$Sel.'>mapmaker-roadmap</option>';
		$Sel = '';
		if($type == 'mapmaker-hybrid'){
			$Sel = 'selected="selected"';
		}
		$Return .= '<option value="mapmaker-hybrid" '.$Sel.'>mapmaker-hybrid</option>';
	$Return .= '</select>&nbsp;';
	$Return .= 'Show Marker: <input type="checkbox" name="Data[Content][_inline_map][_showMarker]['.$Field.']" value="1" '.$marker.' />';

	$viewX = 400;
	$viewY = 200;
	$viewZoom = 10;
	$marker = 'checked="checked"';
	$type = '';
	if(!empty($Config)){
		$viewX = $Config['Content']['_view_map']['_MapX'][$Field];
		$viewY = $Config['Content']['_view_map']['_MapY'][$Field];
		$inlineZoom = $Config['Content']['_view_map']['_zoom'][$Field];
		$marker = '';
		if(!empty($Config['Content']['_view_map']['_showMarker'][$Field])){
			$marker = 'checked="checked"';
		}
		$type = $Config['Content']['_view_map']['_mapType'][$Field];
	}
	
	$Return .= '<br /><strong>View Item Map Size</strong><br />';
	$Return .= 'Width:<input type="text" name="Data[Content][_view_map][_MapX]['.$Field.']" value="'.$viewX.'" class="textfield" size="5" />&nbsp;';
	$Return .= 'Height:<input type="text" name="Data[Content][_view_map][_MapY]['.$Field.']" value="'.$viewY.'" class="textfield" size="5" />&nbsp;';
	$Return .= 'Zoom Level:<input type="text" name="Data[Content][_view_map][_zoom]['.$Field.']" value="'.$viewZoom.'" class="textfield" size="10" />';
	$Return .= 'Map Type:<select name="Data[Content][_view_map][_mapType]['.$Field.']">';
		$Sel = '';
		if($type == 'roadmap'){
			$Sel = 'selected="selected"';
		}
		$Return .= '<option value="roadmap" '.$Sel.'>roadmap</option>';
		$Sel = '';
		if($type == 'mobile'){
			$Sel = 'selected="selected"';
		}
		$Return .= '<option value="mobile" '.$Sel.'>mobile</option>';
		$Sel = '';
		if($type == 'satellite'){
			$Sel = 'selected="selected"';
		}
		$Return .= '<option value="satellite" '.$Sel.'>satellite</option>';
		$Sel = '';
		if($type == 'terrain'){
			$Sel = 'selected="selected"';
		}
		$Return .= '<option value="terrain" '.$Sel.'>terrain</option>';
		$Sel = '';
		if($type == 'hybrid'){
			$Sel = 'selected="selected"';
		}
		$Return .= '<option value="hybrid" '.$Sel.'>hybrid</option>';
		$Sel = '';
		if($type == 'mapmaker-roadmap'){
			$Sel = 'selected="selected"';
		}
		$Return .= '<option value="mapmaker-roadmap" '.$Sel.'>mapmaker-roadmap</option>';
		$Sel = '';
		if($type == 'mapmaker-hybrid'){
			$Sel = 'selected="selected"';
		}
		$Return .= '<option value="mapmaker-hybrid" '.$Sel.'>mapmaker-hybrid</option>';
	$Return .= '</select>&nbsp;';
	$Return .= 'Show Marker: <input type="checkbox" name="Data[Content][_view_map][_showMarker]['.$Field.']" value="1" '.$marker.' />';

	$Return .= '<br /><strong>Google Maps API</strong><br />';
	$Return .= 'API Key:<input type="text" name="Data[Content][_googleMaps_Key]" value="'.$apikey.'" class="textfield" size="20" /><br />';
	$Return .= '<span class="captions">Only need to set once per report</span><br />';	
	$Return .= 'Localhost key: ABQIAAAA81C8oOguYr_IQYmOAtGXwxT2yXp_ZAY8_ufC3CFXhHIE1NvwkxRom61Gn7EoUNHKcIZZ9W-SSWDijg';
	
return $Return;
}

?>