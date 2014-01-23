<?php

global $ajaxAllowedFunctions;
$ajaxAllowedFunctions['linked_setReloadField'] = 1;

function linked_processValue($Value, $Type, $Field, $Config, $EID, $Data){
 
	return $Value;
}

function linked_postProcess($Field, $Input, $FieldType, $Config, $Data, $ID){
    //return;

	if($Config['Content']['_Linkedfields'][$Field]['Type'] == 'checkbox'){
                global $wpdb;

		$LinkingTable = $wpdb->prefix.'__'.str_replace($wpdb->prefix.'dbt_', '', $Config['Content']['_main_table']).str_replace($wpdb->prefix.'dbt_', '', $Config['Content']['_Linkedfields'][$Field]['Table']);
		$wpdb->query("DELETE FROM `".$LinkingTable."` WHERE `from` = '".$ID."' ");
		$wpdb->query("UPDATE `".$LinkingTable."` SET `from` = '".$ID."' WHERE `control` = '".$_SESSION['LinkingControl'][$Config['ID']]."' ");
		unset($_SESSION['LinkingControl'][$Config['ID']]);
	}
}
function linked_handleInput($Field, $Input, $FieldType, $Config, $Data){

        global $wpdb;

	if($Config['Content']['_Linkedfields'][$Field]['Type'] == 'checkbox'){
            
            if($Config['_ActiveProcess'] == 'update'){
                $Input = unserialize($Input);
            }

        

        //$InputArray = unserialize($Input);
	$_SESSION['LinkingControl'][$Config['ID']] = md5(uniqid(date('YmdHis')));
	$LinkingTable = $wpdb->prefix.'__'.str_replace($wpdb->prefix.'dbt_', '', $Config['Content']['_main_table']).str_replace($wpdb->prefix.'dbt_', '', $Config['Content']['_Linkedfields'][$Field]['Table']);
        
	$Query = "CREATE TABLE `".$LinkingTable."` (`from` INT NOT NULL ,`to` INT NOT NULL, `control` VARCHAR( 100 ) ,INDEX ( `from` , `to`, `control` )) ENGINE = InnoDB";
        $wpdb->query($Query);
		
		$Inserts = array();
		$Inputs = array();
		//dump($Input);
		//die;
                if(!is_array($Input)){
                    $Input = unserialize($Input);
                }
		foreach($Input as $Value){
			$Inserts[] = '(\''.$Value.'\', \''.$_SESSION['LinkingControl'][$Config['ID']].'\')';
			$Inputs[] = $Value;
		}
		$Insert = implode(',',$Inserts);
		$InsertQuery = "INSERT INTO `".$LinkingTable."` (`to`, `control` ) VALUES ".$Insert." ;";
                $wpdb->query($InsertQuery);
		$Input = implode(',',$Inputs);
	//echo $LinkingTable;
	//die;
	}
	return $Input;
}

// Args = $Field, $table, ElementConfig 
function linked_tableSetup($Field, $Table, $ElementConfig = false){
	global $wpdb;
	$Data = $wpdb->get_results( "SHOW TABLES", ARRAY_N);
	
	$Return = 'Table: <select name="Data[Content][_Linkedfields]['.$Field.'][Table]" id="linkedField_'.$Field.'" onchange="linked_loadfields(this.value, \''.$Field.'\', \''.$Table.'\');">';
	$Return .= '<option value="">Select table to link to</option>';
	$Default = '';
	if(is_array($ElementConfig)){
		if(!empty($ElementConfig['Content']['_Linkedfields'][$Field]['Table'])){
			$Default = $ElementConfig['Content']['_Linkedfields'][$Field]['Table'];
		}
	}
	foreach($Data as $Tables){
		//vardump($Tables);
		$Value = $Tables[0];
		$Sel = '';
		if($Default == $Value){
			$Sel = 'selected="selected"';	
		}
        	$List[] = $Value;
		$Return .= '<option value="'.$Value.'" '.$Sel.'>'.$Value.'</option>';
	}
	$Return .= '</select><br /><span id="linkingConfig_'.$Field.'">';
	if(is_array($ElementConfig)){
		if(!empty($ElementConfig['Content']['_Linkedfields'][$Field]['Table'])){
			$Return .= linked_loadfields($ElementConfig['Content']['_Linkedfields'][$Field]['Table'], $Field, $Table, $ElementConfig['Content']['_Linkedfields']);
		}
	}
	$Return .= '</span><br />';
		$SingleCheck = '';
		if(!empty($ElementConfig['Content']['_Linkedfields'][$Field]['SingleSelect'])){
			$SingleCheck = 'checked="checked"';
		}
	$Return .= '<input type="checkbox" name="Data[Content][_Linkedfields]['.$Field.'][SingleSelect]" value="1" id="allowSingle_'.$Field.'" '.$SingleCheck.' /> <label for="allowSingle_'.$Field.'">Single Select</label>';
	
	// Advanced
	$Return .= '<div class="admin_config_panel" style="text-align:right;" id="AdvancedExtraSetting_'.$Field.'">';
	$Return .= '<div id="'.$Field.'_configPanel_advanced" class="admin_list_row3" style="text-align: left; display: none;">';
	$Return .= '<h3>Advanced Config</h3>';
	$Return .= '<div class="admin_config_panel">';

        // Associate an Interface for adding a new item.
        //*
        $Return .= '<div class="admin_config_panel"><span class="Description">Associate an Interface for adding new items inline.</span></div>';

        $addInt = '';
        if(!empty($ElementConfig['Content']['_Linkedfields'][$Field]['_addInterface'])){
            $addInt = $ElementConfig['Content']['_Linkedfields'][$Field]['_addInterface'];
        }

        $Return .= linked_interfaceBrowser($Field,$ElementConfig['ID'], $addInt );
        //*/

		// Content
		//Join Type
		$Return .= '<div class="list_row1"> Join Type: ';
			$Return .= '<select	name="Data[Content][_Linkedfields]['.$Field.'][JoinType]" >';
				$Sel = '';
				if($ElementConfig['Content']['_Linkedfields'][$Field]['JoinType'] == 'LEFT JOIN'){
					$Sel = 'selected="selected"';	
				}
				$Return .= '<option value="LEFT JOIN" '.$Sel.'>Left Join</option>';
				$Sel = '';
				if($ElementConfig['Content']['_Linkedfields'][$Field]['JoinType'] == 'RIGHT JOIN'){
					$Sel = 'selected="selected"';	
				}
				$Return .= '<option value="RIGHT JOIN" '.$Sel.'>Right Join</option>';
				$Sel ='';
				if($ElementConfig['Content']['_Linkedfields'][$Field]['JoinType'] == 'JOIN'){
					$Sel = 'selected="selected"';	
				}
				$Return .= '<option value="JOIN" '.$Sel.'>Join</option>';
			$Return .= '</select>';
		$Return .= '</div>';
		// Filter WHERE Type
		
	$Return .= '</div></div>';
	$Return .= '<input type="button" class="button" style="margin-top:5px;" value="Advanced" onclick="toggle(\''.$Field.'_configPanel_advanced\');" />';
	$Return .= '</div>';
	
	
	
return $Return;
}


function linked_interfaceBrowser($Field, $current, $defaultInterface = false){

    global $wpdb;
    $interfaces = $wpdb->get_results("SELECT option_name FROM $wpdb->options WHERE `option_name` LIKE 'dt_intfc%' ", ARRAY_A);



    $Return = 'Interface: <select name="Data[Content][_Linkedfields]['.$Field.'][_addInterface]" >';
    $Return .= '<option value=""></option>';
    foreach($interfaces as $Interface){

        if($Interface['option_name'] != $current){
            $Data = get_option($Interface['option_name']);
            //echo $Data['_interfaceName'].'<br />';
            $Sel = '';
            if(!empty($defaultInterface)){
                if($defaultInterface == $Interface['option_name']){
                    $Sel = 'selected="selected"';
                }
            }
            $Return .= '<option value="'.$Interface['option_name'].'" '.$Sel.'>'.$Data['_Application'].' => '.$Data['_ReportDescription'].'</option>';
        }
        //vardump($Data);
    }
    $Return .= '</select>';

    //vardump($interfaces);
    return $Return;

}

function linked_tablefilteredSetup($Field, $Table, $ElementConfig = false){

        global $wpdb;
	$Data = $wpdb->get_results( "SHOW TABLES", ARRAY_N);
        
    	$Return = 'Table: <select name="Data[Content][_Linkedfilterfields]['.$Field.'][Table]" id="linkedField_'.$Field.'" onchange="linked_loadfilterfields(this.value, \''.$Field.'\');">';
	$Return .= '<option value="">Select table to link to</option>';
	$Default = '';
	if(is_array($ElementConfig)){
		if(!empty($ElementConfig['Content']['_Linkedfilterfields'][$Field]['Table'])){
			$Default = $ElementConfig['Content']['_Linkedfilterfields'][$Field]['Table'];
		}
	}
	foreach($Data as $Tables){
            //vardump($Tables);
		$Value = $Tables[0];
		$Sel = '';
		if($Default == $Value){
			$Sel = 'selected="selected"';	
		}
		//if(substr($Value, 0, 5) != 'dais_'){
			$List[] = $Value;
			$Return .= '<option value="'.$Value.'" '.$Sel.'>'.$Value.'</option>';
		
	}
	$Return .= '</select><br /><span id="linkingConfig_'.$Field.'">';
	if(is_array($ElementConfig)){
		if(!empty($ElementConfig['Content']['_Linkedfilterfields'][$Field]['Table'])){
			$Return .= linked_loadfilterfields($ElementConfig['Content']['_Linkedfilterfields'][$Field]['Table'], $Field, $ElementConfig['Content']['_main_table'], $ElementConfig['Content']['_Linkedfilterfields']);
		}
	}
	$Return .= '</span>';
	
	// Advanced
	$Return .= '<div class="admin_config_panel" style="text-align:right;" id="AdvancedExtraSetting_'.$Field.'">';
	$Return .= '<div id="'.$Field.'_configPanel_advanced" class="admin_list_row3" style="text-align: left; display: none;">';
	$Return .= '<h3>Advanced Config</h3>';
	$Return .= '<div class="admin_config_panel">';
		// Content
		$Return .= '<div class="list_row1"> Join Type: ';
			$Return .= '<select	name="Data[Content][_Linkedfilterfields]['.$Field.'][JoinType]" >';
				$Sel = '';
				if($ElementConfig['Content']['_Linkedfilterfields'][$Field]['JoinType'] == 'LEFT JOIN'){
					$Sel = 'selected="selected"';	
				}
				$Return .= '<option value="LEFT JOIN" '.$Sel.'>Left Join</option>';
				$Sel = '';
				if($ElementConfig['Content']['_Linkedfilterfields'][$Field]['JoinType'] == 'NNER JOIN'){
					$Sel = 'selected="selected"';	
				}
				$Return .= '<option value="INNER JOIN" '.$Sel.'>Inner Join</option>';
				$Sel = '';
				if($ElementConfig['Content']['_Linkedfilterfields'][$Field]['JoinType'] == 'RIGHT JOIN'){
					$Sel = 'selected="selected"';	
				}
				$Return .= '<option value="RIGHT JOIN" '.$Sel.'>Right Join</option>';
				$Sel = '';
				if($ElementConfig['Content']['_Linkedfilterfields'][$Field]['JoinType'] == 'OUTER JOIN'){
					$Sel = 'selected="selected"';	
				}
				$Return .= '<option value="JOIN" '.$Sel.'>Join</option>';
			$Return .= '</select>';
		$Return .= '</div>';
	$Return .= '</div></div>';
	$Return .= '<input type="button" class="button" style="margin-top:5px;" value="Advanced" onclick="toggle(\''.$Field.'_configPanel_advanced\');" />';
	$Return .= '</div>';
return $Return;
}

function linked_loadfields($Table, $Field, $MainTable, $Defaults = false){
        global $wpdb;
    $WhereField = '<option value=""></option>';
    $SortField = '<option value=""></option>';
    $IDReturn = '';
    $ValueReturn = '';

	$result = mysql_query("SHOW COLUMNS FROM `".$Table."`");
	if (mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_assoc($result)){
			$Sel = '';
			if(!empty($Defaults[$Field]['Value'])){
				if($Defaults[$Field]['Value'][0] == $row['Field']){
					$Sel = 'selected="selected"';
				}
			}
			$ValueReturn .= '<option value="'.$row['Field'].'" '.$Sel.'>'.$row['Field'].'</option>';
			$Sel = '';
			if(!empty($Defaults[$Field]['ID'])){
				if($Defaults[$Field]['ID'] == $row['Field']){
					$Sel = 'selected="selected"';
				}
			}
			$IDReturn .= '<option value="'.$row['Field'].'" '.$Sel.'>'.$row['Field'].'</option>';
			$Sel = '';
			if(!empty($Defaults[$Field]['_Filter'])){
				if($Defaults[$Field]['_Filter'] == $row['Field']){
					$Sel = 'selected="selected"';
				}
			}
			$WhereField .= '<option value="'.$row['Field'].'" '.$Sel.'>'.$row['Field'].'</option>';
			$Sel = '';
			if(!empty($Defaults[$Field]['_Sort'])){
				if($Defaults[$Field]['_Sort'] == $row['Field']){
					$Sel = 'selected="selected"';
				}
			}else{
                            if(!empty($Defaults[$Field]['Value'])){
                                    if($Defaults[$Field]['Value'][0] == $row['Field']){
                                            $Sel = 'selected="selected"';
                                    }
                            }
                        }
			$SortField .= '<option value="'.$row['Field'].'" '.$Sel.'>'.$row['Field'].'</option>';

		}
	}
	$IReturn = '<div class="list_row1" style="padding:3px;">Reference Field: <select name="Data[Content][_Linkedfields]['.$Field.'][ID]" id="Ref_'.$Table.'">';
		$IReturn .= $IDReturn;
	$IReturn .= '</select></div>';
        $VReturn = '<div class="list_row1" style="padding:3px;">Value Field:<select name="Data[Content][_Linkedfields]['.$Field.'][Value][]" id="Ref_'.$Table.'">';
		$VReturn .= $ValueReturn;
	$VReturn .= '</select> <img src="'.WP_PLUGIN_URL.'/db-toolkit/images/nadd.png" width="16" width="16" id="addbtn_'.$Field.'" onclick="linked_addReturn(\''.$Table.'\', \''.$Field.'\', 0);" /></div>';
	$VReturn .= '<div id="'.$Field.'_additionalValues">';
		$TotalValues = count($Defaults[$Field]['Value'])-1;
		if($TotalValues >= 1){
			for($VF = 1; $VF <= $TotalValues; $VF++){
				$VReturn .= linked_loadAdditionalValue($Table, $Field, $Defaults[$Field]['Value'][$VF]);
			}
		}
	$VReturn .= '</div>';


                $VReturn .= '<div class="list_row1" style="padding:3px;"> Join WHERE: ';

                $VReturn .= '<select name="Data[Content][_Linkedfields]['.$Field.'][_Filter]" id="linkedField_'.$Field.'_filter" >';
                    $VReturn .= $WhereField;
                $VReturn .= '</select> ';

                $VReturn .= '<select name="Data[Content][_Linkedfields]['.$Field.'][_FilterType]" id="linkedField_'.$Field.'_filterType" >';
                    $sel = '';
                    if(!empty($Defaults[$Field]['_FilterType'])){
                        if($Defaults[$Field]['_FilterType'] == '='){
                            $sel = 'selected="selected"';
                        }
                    }
                    $VReturn .= '<option value="=" '.$sel.'>is equal to</option>';
                    $sel = '';
                    if(!empty($Defaults[$Field]['_FilterType'])){
                        if($Defaults[$Field]['_FilterType'] == '!='){
                            $sel = 'selected="selected"';
                        }
                    }
                    $VReturn .= '<option value="!=" '.$sel.'>is not equal to</option>';
                $VReturn .= '</select> ';

                $value = '';
                if(!empty($Defaults[$Field]['_FilterBy'])){
                    $value = $Defaults[$Field]['_FilterBy'];
                }
                $VReturn .= '<input type="text" name="Data[Content][_Linkedfields]['.$Field.'][_FilterBy]" value="'.$value.'" class="textfield" size="15" />';

                $VReturn .= '</div>';
                
                $VReturn .= '<div class="list_row1" style="padding:3px;"> Sort by: ';
                $VReturn .= '<select name="Data[Content][_Linkedfields]['.$Field.'][_Sort]" id="linkedField_'.$Field.'_sort" >';
                    $VReturn .= $SortField;
                $VReturn .= '</select>  ';
                $VReturn .= '<select name="Data[Content][_Linkedfields]['.$Field.'][_SortDir]" id="linkedField_'.$Field.'_dir">';
                    $Sel = '';
                    if($Defaults[$Field]['_SortDir'] == 'ASC'){
                            $Sel = 'selected="selected"';
                    }
                    $VReturn .= '<option value="ASC" '.$Sel.'>Ascending</option>';
                    $Sel = '';
                    if($Defaults[$Field]['_SortDir'] == 'DESC'){
                            $Sel = 'selected="selected"';
                    }
                    $VReturn .= '<option value="DESC" '.$Sel.'>Descending</option>';
                    $VReturn .= '</select>  ';
                $VReturn .= '</div>';




	$Types = '<div class="list_row1" style="padding:3px;">Select Type:<select name="Data[Content][_Linkedfields]['.$Field.'][Type]" id="Ref_'.$Table.'">';
		$Sel = '';
		if($Defaults[$Field]['Type'] == 'dropdown'){
			$Sel = 'selected="selected"';	
		}
		$Types .= '<option value="dropdown" '.$Sel.'>Dropdown</option>';
		$Sel = '';
		if($Defaults[$Field]['Type'] == 'autocomplete'){
			$Sel = 'selected="selected"';	
		}
		$Types .= '<option value="autocomplete" '.$Sel.'>Autocomplete</option>';
		$Sel = '';
		if($Defaults[$Field]['Type'] == 'checkbox'){
			$Sel = 'selected="selected"';
		}
		$Types .= '<option value="checkbox" '.$Sel.'>Checkbox</option>';
		$Sel = '';
		if($Defaults[$Field]['Type'] == 'radio'){
			$Sel = 'selected="selected"';
		}
		$Types .= '<option value="radio" '.$Sel.'>Radio Group</option>';
		$Sel = '';
		//if($Defaults[$Field]['Type'] == 'multiselect'){
		//	$Sel = 'selected="selected"';
		//}
	$Types .= '</select></div>';

	

//return $IReturn.$VReturn.$URLField.$Types.$LocalURLField;
return $IReturn.$VReturn.$Types;
}
function linked_loadAdditionalValue($Table, $Field, $Default = false, $filtered = false){
	$ElID = rand(1, 999999);
	$result = mysql_query("SHOW COLUMNS FROM `".$Table."`");
	//$Return = '<input type="hidden" name="Data[Content]['.$Field.']" value="'.$Table.'" id="linkedTableRef_'.LinkedField.'" />';
	$Val = '';
	if (mysql_num_rows($result) > 0) {
		$name = '_Linkedfields';
		if(!empty($filtered)){
			$name = '_Linkedfilterfields';
		}
	$Val .= '<div class="list_row1" style="padding:3px;" id="'.$ElID.'">Additional Value: <select name="Data[Content]['.$name.']['.$Field.'][Value][]" id="Ref_'.$Table.'">';
		while ($row = mysql_fetch_assoc($result)){
			$Sel = '';
			if(!empty($Default)){
				if($Default == $row['Field']){
					$Sel = 'selected="selected"';
				}
			}
			$Val .= '<option value="'.$row['Field'].'" '.$Sel.'>'.$row['Field'].'</option>';
			$Sel = '';
		}
	$Val .= '</select> <img src="'.WP_PLUGIN_URL.'/db-toolkit/images/cancel.png" width="16" width="16" onclick="jQuery(\'#'.$ElID.'\').remove();" /></div>';
	}
	
	return $Val;
}
function linked_loadfilterfields($Table, $Field, $MainTable, $Defaults = false){
	$Ref = '';
	$Val = '';
	$ID = '';
	$LURL = '';
	$result = mysql_query("SHOW COLUMNS FROM `".$Table."`");
	//$Return = '<input type="hidden" name="Data[Content]['.$Field.']" value="'.$Table.'" id="linkedTableRef_'.LinkedField.'" />';
	if (mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_assoc($result)){
			$Sel = '';
			if(!empty($Defaults[$Field]['Ref'])){
				if($Defaults[$Field]['Ref'] == $row['Field']){
					$Sel = 'selected="selected"';
				}
			}
			$Ref .= '<option value="'.$row['Field'].'" '.$Sel.'>'.$Table.'.'.$row['Field'].'</option>';
			$Sel = '';
			if(!empty($Defaults[$Field]['Value'][0])){
				if($Defaults[$Field]['Value'][0] == $row['Field']){
					$Sel = 'selected="selected"';
				}
			}
			$Val .= '<option value="'.$row['Field'].'" '.$Sel.'>'.$Table.'.'.$row['Field'].'</option>';
			$Sel = '';
			if(!empty($Defaults[$Field]['ID'])){
				if($Defaults[$Field]['ID'] == $row['Field']){
					$Sel = 'selected="selected"';
				}
			}
			$ID .= '<option value="'.$row['Field'].'" '.$Sel.'>'.$Table.'.'.$row['Field'].'</option>';

		}
	}
	
	$RefField = '<select name="Data[Content][_Linkedfilterfields]['.$Field.'][Ref]" id="Ref_'.$Table.'">';
		$RefField .= $Ref;
	$RefField .= '</select><span class="description">Value thats saved into the database</span><br />';


	$ValField = '<select name="Data[Content][_Linkedfilterfields]['.$Field.'][Value][]" id="val_'.$Table.'">';
		$ValField .= $Val;
	$ValField .= '</select>';


	$ValField  .= '<img src="'.WP_PLUGIN_URL.'/db-toolkit/images/nadd.png" width="16" width="16" id="addbtn_'.$Field.'" onclick="linked_addReturn(\''.$Table.'\', \''.$Field.'\', 1);" /> <span class="description">The Value that the user sees.</span>';
	$ValField .= '<div id="'.$Field.'_additionalValues">';
		$TotalValues = count($Defaults[$Field]['Value'])-1;
		if($TotalValues >= 1){                    
			for($VF = 1; $VF <= $TotalValues; $VF++){                            
				$ValField .= linked_loadAdditionalValue($Table, $Field, $Defaults[$Field]['Value'][$VF], true);
			}
		}
	$ValField .= '</div>';
	
	
	$result = mysql_query("SHOW COLUMNS FROM `".$MainTable."`");
	$FilField = '<select name="Data[Content][_Linkedfilterfields]['.$Field.'][Filter]" id="Filter_'.$Table.'">';
	$FilField .= '<option value="false">None</option>';
	if (mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_assoc($result)){
			$Sel = '';
			if(!empty($Defaults[$Field]['Filter'])){
				if($Defaults[$Field]['Filter'] == $row['Field']){
					$Sel = 'selected="selected"';
				}
			}
			$FilField .= '<option value="'.$row['Field'].'" '.$Sel.'>'.$MainTable.'.'.$row['Field'].'</option>';
			$Sel = '';
			if(!empty($Defaults[$Field]['LocalURL'])){
				if($Defaults[$Field]['LocalURL'] == $row['Field']){
					$Sel = 'selected="selected"';
				}
			}
			$LURL .= '<option value="'.$row['Field'].'" '.$Sel.'>'.$row['Field'].'</option>';
		}
	}
	$FilField .= '</select><br /><span class="description">The Fields that the WHERE is applied</span><br />';


	$IDField = '<select name="Data[Content][_Linkedfilterfields]['.$Field.'][ID]" id="id_'.$Table.'">';
		$IDField .= $ID;
	$IDField .= '</select>';

return 'Reference Field: '.$RefField.' Value Field:  '.$ValField.'  WHERE :  '.$IDField.' = '.$FilField.'';// URL Field: '.$URLField.' LocalURL Field: '.$LocalURLField;
}

function linked_loadtableFields($Table, $MainTable, $Field, $Default, $EID){

return $Return;
}

function linked_makeFilterdLinkedField($IDField , $ValueField, $FilterField, $FilterValue, $Table, $Default = false){
//	return '<option>'.$ValueField.'</option>';
        global $user;
        if($FilterValue == 'auto'){
            $FilterValue = get_current_user_id();
        }
	$Query = "SELECT ".$IDField.", ".stripslashes($ValueField)." FROM `".$Table."` WHERE `".$FilterField."` = '".$FilterValue."' ORDER BY _Value_Field ASC;";
	$Res = mysql_query($Query);
//	return mysql_error();
	if(empty($Default)){
		//$Return .= '<option value="null"></option>';
	}
        $preTitle = '';
        if(mysql_num_rows($Res) < 1){
            
            $preTitle = 'No items found';
        }
	$Return .= '<option value="false">'.$preTitle.'</option>';
	while($row = mysql_fetch_assoc($Res)){
		$Sel = '';
		if(!empty($Default)){
			if($Default == $row[$IDField]){
				$Sel = 'selected="selected"';
			}
		}
		$Return .= '<option value="'.$row[$IDField].'" '.$Sel.' >'.$row['_Value_Field'].'</option>';
	}
	//$Return .= '</select>&nbsp;<a href="'.$Page.'" onclick="df_buildQuickCaptureForm('.$_GET['PageData']['ID'].', '.$RefEle.'); return false;"><img src="'.WP_PLUGIN_DIR.'/db-toolkit/data_form/add.png" width="16" height="16" alt="Add New" align="absmiddle" border="0" /></a>';
return $Return;
}

function linked_makeFilterdLinkedFilter($EID, $IDField , $ValueField, $FilterField, $FilterValue, $Table, $Field, $FieldTitle, $Default = false){

	$Query = "SELECT ".$IDField.", ".$ValueField." FROM `".$Table."` WHERE `".$FilterField."` = '".$FilterValue."' ORDER BY `".$ValueField."` ASC;";
	//return $Query;
	$Res = mysql_query($Query);
	if(empty($Default)){
		//$Return .= '<option value="null"></option>';
	}
	//$Return .= '<option value="false"></option>';
	
	$Return = '<select id="filter_'.$Field.'" name="reportFilter['.$EID.']['.$Field.'][]" multiple="multiple" size="1" class="filterBoxes">';
	$Return .= '<option></option>';
	while($row = mysql_fetch_assoc($Res)){
		$Sel = '';
		if(!empty($Default)){
			//dump($Default);
			if(in_array($row[$IDField], $Default)){
				$Sel = 'selected="selected"';
			}
		}
		$Return .= '<option value="'.$row[$IDField].'" '.$Sel.'>'.$row[$ValueField].'</option>';
	}
	$Return .= '</select>';
//$Return .= '</select>&nbsp;<a href="'.$Page.'" onclick="df_buildQuickCaptureForm('.$_GET['PageData']['ID'].', '.$RefEle.'); return false;"><img src="'.WP_PLUGIN_DIR.'/db-toolkit/data_form/add.png" width="16" height="16" alt="Add New" align="absmiddle" border="0" /></a>';
return $Return;
}


// Show Filters

function linked_showFilter($Field, $Type, $Default, $Config, $EID){	
	$FieldTitle = '';
	$Return = '';
        
	if(!empty($Config['_FieldTitle'][$Field])){
		$FieldTitle = $Config['_FieldTitle'][$Field];	
	}
	if($Type == 'linked'){
			$outList = array();
			foreach($Config['_Linkedfields'][$Field]['Value'] as $outValue){
				$outList[] = $outValue;
			}
			if(count($outList) >= 2){
				$outString = 'CONCAT('.implode(',\' \',',$outList).')';
			}else{
				$outString = '`'.$outList[0].'`';
			}
			$outString = $outString.' AS out_value';
		$Multiple = '';
		//dump($Config['_Linkedfields']);
		if(empty($Config['_Linkedfields'][$Field]['SingleSelect'])){
			$Multiple = 'multiple="multiple" size="1" class="filterBoxes"';				
		}
                $SelectID = $EID.'-'.$Field;
                $queryWhere = '';
                if(!empty($Config['_Linkedfields'][$Field]['_Filter']) && !empty($Config['_Linkedfields'][$Field]['_FilterBy'])){
                    
                    $queryWhere = " WHERE `".$Config['_Linkedfields'][$Field]['_Filter']."` = '".  mysql_real_escape_string($Config['_Linkedfields'][$Field]['_FilterBy'])."'";
                }



		$Res = mysql_query("SELECT `".$Config['_Linkedfields'][$Field]['ID']."`, ".$outString." FROM `".$Config['_Linkedfields'][$Field]['Table']."` ".$queryWhere." ORDER BY `out_value` ASC;");
                if($Res == false){
                    vardump("SELECT `".$Config['_Linkedfields'][$Field]['ID']."`, ".$outString." FROM `".$Config['_Linkedfields'][$Field]['Table']."` ".$queryWhere." ORDER BY `out_value` ASC;");
                    die;
                    return;
                }
                $Return .= '<div class="filterField"><h2>'.$FieldTitle.'</h2><select id="'.$SelectID.'" name="reportFilter['.$EID.']['.$Field.'][]" '.$Multiple.'>';
		if(empty($Config['_Linkedfields'][$Field]['SingleSelect'])){
                  //  $Return .= '<option>Select All</option>';
                }else{                    
                    $Return .= '<option></option>';
                }
		while($row = mysql_fetch_assoc($Res)){
			$Sel = '';
			if(!empty($Default[$Field])){
				if(in_array($row[$Config['_Linkedfields'][$Field]['ID']], $Default[$Field])){
					$Sel = 'selected="selected"';
				}
			}
			$Return .= '<option value="'.$row[$Config['_Linkedfields'][$Field]['ID']].'" '.$Sel.'>'.$row['out_value'].'</option>';
		}
		$Return .= '</select></div>';

                $firstItem = 'false';
                if(!empty($Config['_Linkedfields'][$Field]['SingleSelect'])){
                    $firstItem = 'false';
                }

                if(empty($Config['_Linkedfields'][$Field]['SingleSelect'])){
                    $_SESSION['dataform']['OutScripts'] .= "
                        jQuery(\"#".$SelectID."\").multiselect();
                    ";
                }
	}
	if($Type == 'linkedfiltered'){
		
		$Multiple = '';
		if(empty($Config['_Linkedfilterfields'][$Field]['SingleSelect'])){
			//$Multiple = 'multiple="multiple" size="1" class="filterBoxes"';				
		}
		$Return .= '<div style="float:left;padding:2px;" '.$Class.'><h2>'.$FieldTitle.'</h2><span id="status_'.$Field.'">';
		if(!empty($filterSet[$Config['_Linkedfilterfields'][$Field]['ID']][0])){
			$Return .= linked_makeFilterdLinkedFilter($EID, $Config['_Linkedfilterfields'][$Field]['Ref'] , $Config['_Linkedfilterfields'][$Field]['Value'], $Config['_Linkedfilterfields'][$Field]['Filter'], $filterSet[$Config['_Linkedfilterfields'][$Field]['ID']][0], $Config['_Linkedfilterfields'][$Field]['Table'], $Field, $FieldTitle, $filterSet[$Field]);
		}else{
			$Return .= '<select disabled="disabled" id="filter_'.$Field.'" name="reportFilter['.$EID.']['.$Field.'][]" '.$Multiple.'>';
			$Return .= '<option>Select '.$Config['_FieldTitle'][$Config['_Linkedfilterfields'][$Field]['Filter']].'</option>';
		//	while($row = mysql_fetch_assoc($Res)){
		//		$Sel = '';
		//		if(!empty($Default)){
		//			if(in_array($row[$Config['_Linkedfilterfields'][$Field]['ID']], $Default[$Field])){
		//				$Sel = 'selected="selected"';
		//			}
		//		}
		//		$Return .= '<option value="'.$row[$Config['_Linkedfilterfields'][$Field]['ID']].'" '.$Sel.'>'.$row[$Config['_Linkedfilterfields'][$Field]['Value']].'</option>';
		//	}	
			$Return .= '</select>';
		}
		$Return .= '</span>&nbsp;&nbsp;&nbsp;</div>';
		$_SESSION['dataform']['OutScripts'] .= "
			jQuery('#filter_".$Config['_Linkedfilterfields'][$Field]['Filter']."').unbind();
			jQuery('#filter_".$Config['_Linkedfilterfields'][$Field]['Filter']."').bind('change', function(o){
				jQuery('#status_".$Field."').html('<img src=\"".WP_PLUGIN_URL."/db-toolkit/data_report/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\"> <strong>loading...</strong>');
				ajaxCall('linked_makeFilterdLinkedFilter', '".$EID."', '".$Config['_Linkedfilterfields'][$Field]['ID']."' , '".$Config['_Linkedfilterfields'][$Field]['Value']."', '".$Config['_Linkedfilterfields'][$Field]['Filter']."', ''+this.value+'', '".$Config['_Linkedfilterfields'][$Field]['Table']."', '".$Field."', '".$FieldTitle."', function(f){
					//jQuery('#status_".$Field."').html(f);
					//jQuery('.filterBoxes').multiSelect({ oneOrMoreSelected: '*' });
                                        jQuery(\"#filter_".$Field."\").dropdownchecklist({ firstItemChecksAll: true});
				});
			});
		
		";
		
		
	}
return $Return;
}




// Autocomplete Ajax Output
function linked_autocomplete($eid, $Field, $query){

	$Element = getelement($eid);
	$Config = $Element['Content'];	
	$Setup = $Config[$Table];
	$Table = $Config['_Linkedfields'][$Field]['Table'];
	$ID = $Config['_Linkedfields'][$Field]['ID'];
	$Wheres = '';
	//dump($Config['_Linkedfields']);
	foreach($Config['_Linkedfields'][$Field]['Value'] as $outValue){
		$vals[] = $outValue;
		$Wheres .= " `".$outValue."` LIKE '%".$query."%' OR ";
	}
	$Value = implode(',', $vals);
	//$Value = $Config['_Linkedfields'][$Field]['Value'];
	$Query = "SELECT ".$ID.",".$Value." FROM `".$Table."` WHERE ".$Wheres." `".$ID."` LIKE '%".$query."%' ORDER BY `".$vals[0]."` ASC;";
	$Res = mysql_query($Query);
	//echo $Query;
	header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
	header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header ("Pragma: no-cache"); // HTTP/1.0
	header("Content-Type: application/json");

	//echo '<?xml version="1.0" encoding="utf-8" >';
	//echo '<results>';
	echo "[";
	$index = 0;
	while($Out = mysql_fetch_assoc($Res)){
		$OutString = array();
		$valindex = 0;
		foreach($vals as $visValues){
			$OutString[] .= $Out[$visValues];
		//	if($valindex == 2){
		//		break;	
		//	}
			$valindex++;
		}
		$infostring = '';
		//if(count($vals) > 3){
		//	for($i=3; $i<=count($vals); $i++){
		//		$infostring .= $Out[$vals[$i]].' ';
		//	}
		//}
		//$out['results'][$index]['id'] = $Out[$ID];
		//$out['results'][$index]['value'] = implode(' ', $OutString);
		//$out['results'][$index]['info'] = '';
		$arr[] = "{\"id\": \"".$Out[$ID]."\", \"label\": \"".implode(' ', $OutString)."\", \"value\": \"".implode(' ', $OutString)."\"}";
		//echo '<rs id="'.$Out[$ID].'" info="">'.implode(' ', $OutString).'</rs>';
		//echo $Out[$ID]." (".$Out[$Value].")|".$Out[$ID]."\n";
	}
	echo implode(", ", $arr);
	echo "]";
	//echo '</results>';
	mysql_close();
	die;
}


?>