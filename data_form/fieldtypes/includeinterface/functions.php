<?php

/*
functions to be used within the field type
these are the functions that can de defined in ajax and admin ajax.

function can be added as needed but there are some functions that are called when the field is used.
function call hooks:
	before Inserting all data into the database (for each field)
	[folder]_handleInput($FieldName, $SubmitedValue, $FieldType, $ElementConfig, $AllDataSubmitted);
	return value is saved in database for that field
	
	after all data is inserted into the database (for each field)
	[folder]_postProcess($FieldName, $InputValue, $FieldType, $ElementConfig, $AllData, $ReturnFieldValue);
	return void
	
function calls for viewing
	returned data is what is displayed in the report/list view
	[folder]_processValue($Value, $FieldType, $FieldName, $ElementConfig, $ElementID, $AllFieldsData)




fieldtype function naming convention
[folder]_functionname


*/

function includeinterface_processValue($Value, $Type, $Field, $Config, $EID, $Data){
    //vardump($Data);
    if(!empty($Data)){
        foreach($Data as $Key=>$Gets){
            $_GET[$Key] = $Gets;
        }
    }    
    return dt_renderInterface($Config['_includeInterface'][$Field]);
    //return $Value;
}

//function _folder_postProcess($Field, $Input, $FieldType, $Config, $Data, $ID){
//}
//function _folder_handleInput($Field, $Input, $FieldType, $Config, $Data){
//	return $Input;
//}

function includeinterface_setup($Field, $Table, $Config = false){
    global $wpdb;
    $interfaces = $wpdb->get_results("SELECT option_name FROM $wpdb->options WHERE `option_name` LIKE 'dt_intfc%' ", ARRAY_A);

    $Return = 'Interface: <select name="Data[Content][_includeInterface]['.$Field.']" >';
    
    foreach($interfaces as $Interface){
        $Data = get_option($Interface['option_name']);
        //echo $Data['_interfaceName'].'<br />';
        $Sel = '';
        if(!empty($Config['Content']['_includeInterface'][$Field])){
            if($Config['Content']['_includeInterface'][$Field] == $Interface['option_name']){
                $Sel = 'selected="selected"';
            }
        }
        $Return .= '<option value="'.$Interface['option_name'].'" '.$Sel.'>'.$Data['_ReportDescription'].'</option>';

        //vardump($Data);
    }
    $Return .= '</select>';

    //vardump($interfaces);
    return $Return;

}


// Show Filters
/* adds a filter panel to the filters box. returned value is whats displayed.
function _folder_showFilter($FieldName, $FieldType, $AllData, $ElementConfig, $ElementID){
	return false;
}
*/



?>