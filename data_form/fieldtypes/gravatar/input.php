<?php
/// This creates the actual input fields for capturing. this will handle the occurance of the setting


if($FieldSet[1] == 'gravatarimg'){
	$WidthOverride = '';
        
	if(!empty($Config['_FieldLength'][$Field])){
		$WidthOverride = 'style="width:'.$Config['_FieldLength'][$Field].';"';
	}
	echo '<input name="dataForm['.$Element['ID'].']['.$Field.']" type="text" id="entry_'.$Element['ID'].'_'.$Field.'" value="'.$Val.'" class="'.$Req.' text" '.$WidthOverride.' />';
}
?>