<?php
/// This creates the actual input fields for capturing.
// output is echoed not returned.

/* 

 Available Variables:
 
 $Config - Element Config
 $FieldSet - array(0=>fieldfolder, 1=>fieldtype);
 $Defaults - array with all fields and all data for each field
 $Field - the current field name
 $Element - Element data
 $Req - the form validation class, sets the field to required if configured to do so
 
 field names are : name="dataForm['.$Element['ID'].']['.$Field.']"
 fieled ID's are : id="entry_'.$Element['ID'].'_'.$Field.'"
*/

$Sel = '';
if($Defaults[$Field] == 1){
	$Sel = 'checked="checked"';
}
echo '<input type="text" id="entry_'.$Element['ID'].'_'.$Field.'" name="dataForm['.$Element['ID'].']['.$Field.']" value="'.$Defaults[$Field].'" '.$Sel.' />';

$autoComplete = '';
if(!empty($Config['_tagBase'][$Field])){
        $autoComplete = "autocomplete_url:'?taggingAction=".$Element['ID']."&field=".$Field."&nounc=".$_SESSION['taggingNounc']."',";
}

$_SESSION['taggingNounc'] = uniqid('tag');
$_SESSION['dataform']['OutScripts'] .="
    jQuery('#entry_".$Element['ID']."_".$Field."').tagsInput({
            width: 'auto',
            ".$autoComplete."
            'defaultText':'".$Config['_tagText'][$Field]."'
            
});
";

?>