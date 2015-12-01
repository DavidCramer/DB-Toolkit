<?php
/// This creates the actual input fields for capturing. this will handle the occurance of the setting
//$Data['_main_table'], $ElementID, $Field, $Data[$Data[$Field]]['Type'], false, $Req
if($FieldSet[1] == 'enum'){
	$result = mysqli_query("SHOW COLUMNS FROM `".$Config['_main_table']."`");
	if(mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)){
			if($row['Field'] == $Field){
				$Enum = $row['Type'];
			}
		}
	}else{
		return;	
	}
	preg_match_all("/'(.*?)'/", $Enum, $Vals);
	$Out = '<select name="dataForm['.$Element['ID'].']['.$Field.']" id="entry_'.$Element['ID'].'_'.$Field.'" class="'.$Req.' '.$Config['_FormFieldWidth'][$Field].'">';
	$Out .= '<option value="">Select an option</option>';
	foreach($Vals[1] as $Value){
		$Sel = '';
		if($Value == $Val){
			$Sel = 'selected="selected"';	
		}
		$Out .= '<option value="'.$Value.'" '.$Sel.' >'.$Value.'</option>';
	}
	$Out .= '</select>';

echo $Out;
}
?>