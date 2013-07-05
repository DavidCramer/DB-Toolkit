<?php
/// This creates the actual view item field for displaying a view style form.
// output is echoed not returned.

/* 

 Available Variables:
 
 $Config - Element Config
 $FieldSet - array(0=>fieldfolder, 1=>fieldtype);
 $Defaults - array with all fields and all data for each field
 $Field - the current field name
 $Element - Element data
 $Req - the form validation class, sets the field to required if configured to do so
 
*/
//vardump($Data);
foreach($Data as $Key=>$Gets){
    $_GET[$Key] = $Gets;
}
$Out .= dt_renderInterface($Config['_includeInterface'][$Field]);
//$Out = 'ping';

?>