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

function tagging_processValue($Value, $Type, $Field, $Config, $EID, $Data){
		
	return $Value;
}

function tagging_handleInput($Field, $Input, $FieldType, $Config, $Data){
    global $wpdb;

    if(empty($Input)){
        return $Input;
    }

    $tagset = sanitize_title_with_dashes($Config['Content']['_tagBase'][$Field]);
    $tagTable = $wpdb->prefix.'dbt_tagbase_'.$tagset;
    $tags = explode(',', $Input);
    if(!empty($Config['_tagBase'][$Field])){
        foreach($tags as $tag){
            $preTag = $wpdb->get_var($wpdb->prepare("SELECT `id` FROM `".$tagTable."` WHERE `tag` = '".$tag."';"));
            if(empty($preTag)){
                if(!$wpdb->insert($tagTable, array('tag'=>$tag))){
                    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                    if(dbDelta ("CREATE TABLE `".$tagTable."` (
                                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                                  `tag` text,
                                  PRIMARY KEY (`id`)
                                ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;")){
                        $wpdb->insert($tagTable, array('tag'=>$tag));
                    }
                }
            }
        }
    }
    return $Input.',';
}


// setup the fieldtype
function tagging_setup($Field, $Table, $Config = false){
        global $wpdb;
        
        $tagbase = str_replace($wpdb->prefix.'dbt_', '', $Table);
	if(!empty($Config['Content']['_tagBase'][$Field])){
		$tagbase = $Config['Content']['_tagBase'][$Field];
	}
	$Return = '<div style="padding:3px;" class="list_row3">Tag Base: <input type="text" name="Data[Content][_tagBase]['.$Field.']" value="'.$tagbase.'" /></div><div><span class="description"><p>Tags are stored in a tag database. The tag base is a collection name of tag topics.</p><p>This allows the autocomplete feature of tagging to work.</p><p> creating a base allows you to share tags accross multiple interfaces by using the same base.<p/><p>To disable the autocomplete, leave this field blank.</p></span></div>';
	
        $tagText ='add a tag';
	if(!empty($Config['Content']['_tagText'][$Field])){
		$tagText = $Config['Content']['_tagText'][$Field];
	}
	$Return .= '<div style="padding:3px;" class="list_row3">Tag Helper Text: <input type="text" name="Data[Content][_tagText]['.$Field.']" value="'.$tagText.'" /></div><div><span class="description">the Add a tag helper text that follows the tags input.</span></div>';

	return $Return;
	
}


// Show Filters
/* adds a filter panel to the filters box. returned value is whats displayed.*/


function tagging_showFilter($Field, $Type, $Default, $Config, $EID){
	$FieldTitle = df_parseCamelCase($Field);
	if(!empty($Config['_FieldTitle'][$Field])){
		$FieldTitle = $Config['_FieldTitle'][$Field];
	}

	$Return .= '<div class="filterField '.$Class.'"><h2>'.$FieldTitle.'</h2>';

	$Return .= '<input type="text" id="filter_'.$Field.'" name="reportFilter['.$EID.']['.$Field.']" value="'.$Default[$Field].'" '.$Sel.' />&nbsp;&nbsp;&nbsp;</div>';

        $autoComplete = '';
        if(!empty($Config['_tagBase'][$Field])){
                $autoComplete = "autocomplete_url:'?taggingAction=".$EID."&field=".$Field."',";
        }

        $_SESSION['dataform']['OutScripts'] .="
            jQuery('#filter_".$Field."').tagsInput({
                    height: 'auto',
                    width: 'auto',
                    ".$autoComplete."
                    'defaultText':'".$Config['_tagText'][$Field]."'                    
        });";

	
return $Return;
}


/// run taging process auto complete process
if(!empty($_GET['taggingAction']) && !empty($_GET['term'])){
    global $wpdb;

    $element = getelement($_GET['taggingAction']);
    $Config = $element['Content'];


    $tagset = sanitize_title_with_dashes($Config['_tagBase'][$_GET['field']]);
    $tagTable = $wpdb->prefix.'dbt_tagbase_'.$tagset;

    $data = $wpdb->get_results("SELECT `id`,`tag` FROM `".$tagTable."` WHERE `tag` LIKE '".mysql_real_escape_string($_GET['term'])."%' LIMIT 10;", ARRAY_N);
    if(!empty($data)){
        foreach($data as $key=>$value){
            $output[$key]['id'] = $value[0];
            $output[$key]['label'] = $value[1];
            $output[$key]['value'] = $value[1];
        }
    echo json_encode($output);
    }
    exit;
    

}

?>