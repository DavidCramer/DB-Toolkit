<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$EID = $Element['ID'];
echo '<div id="entry_'.$EID.'_'.$Field.'_display" style="padding:6px 0 5px;"></div>';

$path = WP_PLUGIN_URL.'/db-toolkit/data_form/fieldtypes/raty/img/';
$scoreName = 'dataForm['.$EID.']['.$Field.']';
$Default = '';
if(!empty($Defaults[$Field])){
        $Default = ', start: '.$Defaults[$Field];
}
$number = ', number: 5';
if(!empty($Config['_raty'][$Field]['_stars'])){
        $Config['_raty'][$Field]['_stars'] = $Config['_raty'][$Field]['_stars']*1;
    if(is_int($Config['_raty'][$Field]['_stars'])){
        $number = ', number: '.$Config['_raty'][$Field]['_stars'];
    }
}

$_SESSION['dataform']['OutScripts'] .= "

    jQuery('#entry_".$EID."_".$Field."_display').raty({
        path: '".$path."',
        scoreName: '".$scoreName."'
        ".$Default."
        ".$number."
    });

";

?>
