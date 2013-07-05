<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function raty_processValue($Value, $Type, $Field, $Config, $EID){
    $Value = $Value*1;
    $Out = '';
    //if(!empty($Value)){
        for($i=1; $i<=$Config['_raty'][$Field]['_stars']; $i++){
            if($Value >0){
                $Icon = 'star-on.png';
            }else{
                $Icon = 'star-off.png';
            }
            $Out .= '<img alt="'.$i.'" src="'.WP_PLUGIN_URL.'/db-toolkit/data_form/fieldtypes/raty/img/'.$Icon.'">';
            $Value--;
        }
    //}
    return $Out;
    //<img title="bad" alt="1" src="http://localhost/wordpress/wp-content/plugins/db-toolkit/data_form/fieldtypes/raty/img/star-off.png">
}

function raty_setup($Field, $Table, $Config = false){
        
    // Defaults
    $Return = '';
    $Stars = 5;
    if(!empty($Config['Content']['_raty'][$Field]['_stars'])){
        $Stars = $Config['Content']['_raty'][$Field]['_stars'];
    }  
    
    $Return = 'Stars:<input type="text" name="Data[Content][_raty]['.$Field.'][_stars]" value="'.$Stars.'" class="textfield" size="5" />&nbsp;';

return $Return;
}

?>
