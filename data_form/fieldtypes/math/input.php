<?php

if($FieldSet[1] == 'multiply'){
	
//	dump();
	echo '<span id="'.$Field.'_mult">'.$Val.'</span>';
	
	$_SESSION['dataform']['OutScripts'] .="
	
	jQuery('#entry_".$Element['ID']."_".$Config['_multiply'][$Field]['B']."').unbind();
	jQuery('#entry_".$Element['ID']."_".$Config['_multiply'][$Field]['B']."').bind('keyup', function(){
		aval = this.value;
		bval = jQuery('#entry_".$Element['ID']."_".$Config['_multiply'][$Field]['A']."').val();
		newval = math_CurrencyFormatted(aval*bval);
		jQuery('#".$Field."_mult').html(newval);
	});
	jQuery('#entry_".$Element['ID']."_".$Config['_multiply'][$Field]['A']."').bind('keyup', function(){
		aval = this.value;
		bval = jQuery('#entry_".$Element['ID']."_".$Config['_multiply'][$Field]['B']."').val();
		newval = math_CurrencyFormatted(aval*bval);
		jQuery('#".$Field."_mult').html(newval);
	});
	
	";
}

?>