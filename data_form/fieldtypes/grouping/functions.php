<?php

/*
functions to be used within the field type
these are the functions that can de defined in ajax and admin ajax.

function can be added as needed but there are some functions that are called when the field is used.
function call hooks:
 * [folder]_handleInput($FieldName, $SubmitedValue, $FieldType, $ElementConfig, $AllDataSubmitted);
    before Inserting all data into the database (for each field)
    return value is saved in database for that field

 * [folder]_postProcess($FieldName, $InputValue, $FieldType, $ElementConfig, $AllData, $ReturnFieldValue);
    after all data is inserted into the database (for each field)
    return void
	
function calls for viewing
	returned data is what is displayed in the report/list view
	[folder]_processValue($Value, $FieldType, $FieldName, $ElementConfig, $ElementID, $AllFieldsData)




fieldtype function naming convention
[folder]_functionname


*/

function grouping_processValue($Value, $Type, $Field, $Config, $EID, $Data){
	return $Value;
}

function grouping_handleInput($Field, $Input, $FieldType, $Config, $Data){
	return $Input;
}


// setup name varies according to conf.php
function grouping_setup($Field, $Table, $Config=false){
    
    
    global $wpdb;
    $Fields = $wpdb->get_results( "SHOW COLUMNS FROM `".$Table."`", ARRAY_N);
    $Return = '<div style="padding:3px;" class="list_row1">';
    $Return .= 'Grouping Action: <select name="Data[Content][_GroupingFields]['.$Field.'][Action]">';
    $Sel = '';
    if($Config['Content']['_GroupingFields'][$Field]['Action'] == 'sum'){
        $Sel = 'selected="selected"';
    }
    $Return .= '<option value="sum" '.$Sel.' >Sum</option>';
    $Sel = '';
    if($Config['Content']['_GroupingFields'][$Field]['Action'] == 'count'){
        $Sel = 'selected="selected"';
    }
    $Return .= '<option value="count" '.$Sel.'>Count</option>';
    $Sel = '';
    if($Config['Content']['_GroupingFields'][$Field]['Action'] == 'concat'){
        $Sel = 'selected="selected"';
    }
    $Return .= '<option value="concat" '.$Sel.'>Group Concat Fields</option>';
    //foreach($Fields as $FieldData){
        //vardump($FieldData);
        //$Return .= '<option value="'.$FieldData[0].'" >'.$FieldData[0].'</option>';
    //}





    $Return .= '</select>';
    $Return .= '</div>';
    
    $Return .= '<div style="padding:3px;">';
    $Return .= 'Action Field: <select name="Data[Content][_GroupingFields]['.$Field.'][Field]">';
    foreach($Fields as $FieldData){
       //vardump($FieldData);
        $Sel = '';
        if($Config['Content']['_GroupingFields'][$Field]['Field'] == $FieldData[0]){
            $Sel = 'selected="selected"';
        }

        $Return .= '<option value="'.$FieldData[0].'" '.$Sel.'>'.$FieldData[0].'</option>';

    }
    // add Clones
        if(!empty($Config)) {
            if(!empty ($Config['Content']['_CloneField'])) {
                $Return .= '<optgroup label="Cloned Fields">';
                foreach ($Config['Content']['_CloneField'] as $FieldKey=>$Array) {
                    $Sel = '';
                    if($Config['Content']['_GroupingFields'][$Field]['Field'] == $FieldKey) {
                        $Sel = 'selected="selected"';
                    }
                    if($FieldKey != $Field){
                        $Return .= '<option value="'.$FieldKey.'" '.$Sel.'>'.$Config['Content']['_FieldTitle'][$FieldKey].'</option>';
                    }
                }

            }
        }



    $Return .= '</select>';
    $Return .= '</div>';

    //foreach($Fields as $FieldData){
        //vardump($FieldData);
        //$Return .= '<option value="'.$FieldData[0].'" >'.$FieldData[0].'</option>';
    //}
    return $Return;
}
// Show Filters
/* adds a filter panel to the filters box. returned value is whats displayed.
function _folder_showFilter($FieldName, $FieldType, $AllData, $ElementConfig, $ElementID){
	return false;
}
*/



?>