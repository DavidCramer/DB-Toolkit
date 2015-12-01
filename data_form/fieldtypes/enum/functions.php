<?php
// Functions - Args = $Field, $ELementConfig 

function enum_showFilter($Field, $Type, $Default, $Config, $EID){
	$FieldTitle = df_parseCamelCase($Field);
	if(!empty($Config['_FieldTitle'][$Field])){
		$FieldTitle = $Config['_FieldTitle'][$Field];
	}

	$Return .= '<div class="filterField" '.$Class.'"><h2>'.$FieldTitle.'</h2>';
	// ------ //
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
	$Out .= '<select class="filterBoxes" id="filter_'.$Field.'" name="reportFilter['.$EID.']['.$Field.'][]" multiple="multiple" size="1">';
	$Out .= '<option value="">Select All</option>';
	foreach($Vals[1] as $Value){
		$Sel = '';
		if(!empty($Default[$Field])){
			if(in_array($Value, $Default[$Field])){
				$Sel = 'selected="selected"';
			}
		}
		$Out .= '<option value="'.$Value.'" '.$Sel.' >'.$Value.'</option>';
	}
	$Return .= $Out.'</select>&nbsp;&nbsp;&nbsp;</div>';

$_SESSION['dataform']['OutScripts'] .= "
        jQuery(\"#filter_".$Field."\").multiselect();
";
return $Return;
}

?>