<?php
/// This creates the actual input fields for capturing. this will handle the occurance of the setting

if($FieldSet[1] == 'coordinates'){
	$Out['Lat'] = '';
	$Out['Lon'] = '';
        $Out = explode('|', $Val);
	if(count($Out) == 2){
		
		$Out['Lat'] = $Out[0];
		$Out['Lon'] = $Out[1];                
	}
	echo '<input name="dataForm['.$Element['ID'].']['.$Field.'][Lat]" type="text" id="entry_'.$Element['ID'].'_'.$Field.'" value="'.$Out['Lat'].'" class="'.$Req.' span'.ceil($columnSpan/3).'" placeholder="Latitude" /> ';
	echo '<input name="dataForm['.$Element['ID'].']['.$Field.'][Lon]" type="text" id="entry_'.$Element['ID'].'_'.$Field.'" value="'.$Out['Lon'].'" class="'.$Req.' span'.ceil($columnSpan/3).'" placeholder="Longitude" />';
}
?>