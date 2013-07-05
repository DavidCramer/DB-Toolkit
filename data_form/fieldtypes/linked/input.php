<?php
/// This creates the actual input fields for capturing. this will handle the occurance of the setting
//$Data['_main_table'], $ElementID, $Field, $Data[$Data[$Field]]['Type'], false, $Req

if($FieldSet[1] == 'linked'){
	switch($Config['_Linkedfields'][$Field]['Type']){
		case "checkbox":
                    $Ordering = " ORDER BY `".$Config['_Linkedfields'][$Field]['Value'][0]."` ASC";
                    if(!empty($Config['_Linkedfields'][$Field]['_Sort'])){
                        $Ordering = " ORDER BY `".$Config['_Linkedfields'][$Field]['_Sort']."` ".$Config['_Linkedfields'][$Field]['_SortDir']."";

                    }
                    
                    global $wpdb;
                    $LinkingTable = $wpdb->prefix.'__'.str_replace($wpdb->prefix.'dbt_', '', $Config['_main_table']).str_replace($wpdb->prefix.'dbt_', '', $Config['_Linkedfields'][$Field]['Table']);
                    
                    //dump();
                    $checked = $wpdb->get_results("SELECT * FROM `".$LinkingTable."` WHERE `from` = '".$Defaults[$Config['_ReturnFields'][0]]."'", ARRAY_A);
                    $checkDefault = array();
                    foreach($checked as $check){
                        $checkDefault[$check['to']] = true;
                    }
                    
                    
                    
				$concatvalues = array();
				foreach($Config['_Linkedfields'][$Field]['Value'] as $outValue){
					$concatvalues[] = $outValue;
				}
				$outString = 'CONCAT('.implode(',\' \',',$concatvalues).') as outValue';
				$Query = "SELECT ".$Config['_Linkedfields'][$Field]['ID'].", ".$outString." FROM `".$Config['_Linkedfields'][$Field]['Table']."` ".$Ordering.";";
			$Res = mysql_query($Query);
			//$Return = '<select name="dataForm['.$Element['ID'].']['.$Field.']" id="entry_'.$Element['ID'].'_'.$Field.'" class="'.$Req.'">';
			//if(empty($Defaults[$Field])){
				//$Return .= '<option value=""></option>';
			//}
                        //$DefaultChecks = explode(',' $Defaults[$Field]);
			$checkindex = 0;
			$Return = '';
                        $Return .= '<div style="max-height: 300px; overflow: auto;" >';
			while($lrow = mysql_fetch_assoc($Res)){
				$Sel = '';
                                if(!empty($checkDefault[$lrow[$Config['_Linkedfields'][$Field]['ID']]])){
                                    $Sel = 'checked="checked"';
                                }
				$Return .= '<label class="entrylabel checkbox" style="background-color:inherit;" for="entry_'.$Element['ID'].'_'.$Field.'_'.$checkindex.'"><input type="checkbox" value="'.$lrow[$Config['_Linkedfields'][$Field]['ID']].'" name="dataForm['.$Element['ID'].']['.$Field.'][]" id="entry_'.$Element['ID'].'_'.$Field.'_'.$checkindex.'" class="'.$Req.' " '.$Sel.' />'.$lrow['outValue'].'</label>';
				$checkindex++;
			}
			$Return .= '</div>';
		
		break;
		case "radio":
                    $Ordering = " ORDER BY `".$Config['_Linkedfields'][$Field]['Value'][0]."` ASC";
                    if(!empty($Config['_Linkedfields'][$Field]['_Sort'])){
                        $Ordering = " ORDER BY `".$Config['_Linkedfields'][$Field]['_Sort']."` ".$Config['_Linkedfields'][$Field]['_SortDir']."";

                    }

				$concatvalues = array();
				foreach($Config['_Linkedfields'][$Field]['Value'] as $outValue){
					$concatvalues[] = $outValue;
				}
				$outString = 'CONCAT('.implode(',\' \',',$concatvalues).') as outValue';
				$Query = "SELECT ".$Config['_Linkedfields'][$Field]['ID'].", ".$outString." FROM `".$Config['_Linkedfields'][$Field]['Table']."` ".$Ordering.";";
			$Res = mysql_query($Query);
			//$Return = '<select name="dataForm['.$Element['ID'].']['.$Field.']" id="entry_'.$Element['ID'].'_'.$Field.'" class="'.$Req.'">';
			//if(empty($Defaults[$Field])){
				//$Return .= '<option value=""></option>';
			//}
                        //$DefaultChecks = explode(',' $Defaults[$Field]);
			$checkindex = 0;
			$Return = '';
			while($lrow = mysql_fetch_assoc($Res)){
				$Sel = '';
				if(!empty($Defaults[$Field])){
					$DefaultArray = core_cleanArray(explode(',',$Defaults[$Field]));
					if(in_array($lrow[$Config['_Linkedfields'][$Field]['ID']], $DefaultArray)){
						$Sel = 'checked="checked"';
					}
				}
				$Return .= '<label class="entrylabel radio" style="background-color:inherit;" for="entry_'.$Element['ID'].'_'.$Field.'_'.$checkindex.'"><input type="radio" value="'.$lrow[$Config['_Linkedfields'][$Field]['ID']].'" name="dataForm['.$Element['ID'].']['.$Field.']" id="entry_'.$Element['ID'].'_'.$Field.'_'.$checkindex.'" class="'.$Req.' " '.$Sel.' />'.$lrow['outValue'].'</label>';
				$checkindex++;
			}
			//$Return .= '</select>';

		break;
		case "multiselect":
				$concatvalues = array();
				foreach($Config['_Linkedfields'][$Field]['Value'] as $outValue){
					$concatvalues[] = $outValue;
				}
				$outString = 'CONCAT('.implode(',\' \',',$concatvalues).') as outValue';
				$Query = "SELECT ".$Config['_Linkedfields'][$Field]['ID'].", ".$outString." FROM `".$Config['_Linkedfields'][$Field]['Table']."` ORDER BY `".$Config['_Linkedfields'][$Field]['Value'][0]."` ASC;";
			$Res = mysql_query($Query);
			//$Return = '<select name="dataForm['.$Element['ID'].']['.$Field.']" id="entry_'.$Element['ID'].'_'.$Field.'" class="'.$Req.'">';
			//if(empty($Defaults[$Field])){
				//$Return .= '<option value=""></option>';
			//}
			$checkindex = 0;
			//$Return = 'Listed: <select name="multiselect_'.$Field.'" id="selector_'.$Element['ID'].'_'.$Field.'" class="'.$Req.'">';
			$Return ='';
			while($lrow = mysql_fetch_assoc($Res)){
				$Sel = '';
				if(!empty($Defaults[$Field])){
					$DefaultArray = core_cleanArray(explode('|',$Defaults[$Field]));
					if(in_array($lrow[$Config['_Linkedfields'][$Field]['ID']], $DefaultArray)){
						$Sel = 'checked="checked"';
					}
				}
				//$Return .= '<option value="'.$lrow[$Config['_Linkedfields'][$Field]['ID']].'" '.$Sel.' >'.$lrow['outValue'].'</option>';
				$Return .= '<label class="checkbox" for="entry_'.$Element['ID'].'_'.$Field.'_'.$checkindex.'"><input type="checkbox" value="'.$lrow[$Config['_Linkedfields'][$Field]['ID']].'" name="dataForm['.$Element['ID'].']['.$Field.'][]" id="entry_'.$Element['ID'].'_'.$Field.'_'.$checkindex.'" class="'.$Req.'" '.$Sel.' />'.$lrow['outValue'].'</label>';
				$checkindex++;
				
			}
			//$Return .= '</select> <input type="button" name="button" id="button" value="Add" onclick="linked_addOption(\''.$Element['ID'].'_'.$Field.'\')" />';
			//$Return .= 'Selected: <select name="dataForm['.$Element['ID'].']['.$Field.'][]" id="entry_'.$Element['ID'].'_'.$Field.'" class="'.$Req.'">';
			//$Return .= '</select>';
			
			
		break;
		case "dropdown":



                    $Ordering = " ORDER BY `".$Config['_Linkedfields'][$Field]['Value'][0]."` ASC";
                    if(!empty($Config['_Linkedfields'][$Field]['_Sort'])){
                        $Ordering = " ORDER BY `".$Config['_Linkedfields'][$Field]['_Sort']."` ".$Config['_Linkedfields'][$Field]['_SortDir']."";

                    }

				$values = array();
				foreach($Config['_Linkedfields'][$Field]['Value'] as $outValue){
					$values[] = $outValue;
				}
				$outString = 'CONCAT('.implode(',\' \',',$values).') as outValue';

                                $Where = '';
                                if(!empty($Config['_Linkedfields'][$Field]['_Filter'])){// && !empty($Config['_Linkedfields'][$Field]['_FilterBy'])){
                                    $Where .= " WHERE ";

                                    $filterType = '=';
                                    if(!empty($Config['_Linkedfields'][$Field]['_FilterType'])){
                                        $filterType = $Config['_Linkedfields'][$Field]['_FilterType'];
                                    }
                                    $Where .= "`".$Config['_Linkedfields'][$Field]['_Filter']."` ".$filterType." '".  mysql_real_escape_string($Config['_Linkedfields'][$Field]['_FilterBy'])."'";
                                }




			$QuerySQL = "SELECT ".$Config['_Linkedfields'][$Field]['ID'].", ".$outString." FROM `".$Config['_Linkedfields'][$Field]['Table']."` ".$Where." ".$Ordering.";";
			$Res = mysql_query($QuerySQL);
			//cho $QuerySQL;
			$Return = '<select name="dataForm['.$Element['ID'].']['.$Field.']" id="entry_'.$Element['ID'].'_'.$Field.'" ref="'.$row['_return_'.$Config['_ReturnFields'][0]].'" class="'.$Req.' '.$Config['_FormFieldWidth'][$Field].'">';
			if(empty($Defaults[$Field])){
				$Return .= '<option value=""></option>';
			}
			while($lrow = mysql_fetch_assoc($Res)){
				$Sel = '';
				if(!empty($Defaults[$Field])){
					if($Defaults[$Field] == $lrow[$Config['_Linkedfields'][$Field]['ID']]){
						$Sel = 'selected="selected"';
						$Defaults['_outvalue'][$Field] = $lrow['outValue'];
					}
				}
				$Return .= '<option value="'.$lrow[$Config['_Linkedfields'][$Field]['ID']].'" '.$Sel.' >'.$lrow['outValue'].'</option>';
			}
			$Return .= '</select>';
                        // add insert Auto button!

                        // get the linked interfaces add entry button title
                        if(!empty($Config['_Linkedfields'][$Field]['_addInterface'])){
                        $linkInterface = getelement($Config['_Linkedfields'][$Field]['_addInterface']);                        
                            $Return .= ' <button class="btn" onclick="df_buildQuickCaptureForm(\''.$Config['_Linkedfields'][$Field]['_addInterface'].'\', true, \''.$Element['ID'].'|'.$Field.'\', linked_reloadField);return false;">'.$linkInterface['Content']['_New_Item_Title'].'</button>';
                        }
                        $_SESSION['dataform']['OutScripts'] .="

                            

                        ";






		break;
		case "autocomplete":
			$Det[$Config['_Linkedfields'][$Field]['ID']] = '';
			//$Det[$Config['_Linkedfields'][$Field]['Value']] = '';
			$VisDef = '';
			$Return = '';
			if(!empty($Defaults[$Field])){
				$values = array();
				foreach($Config['_Linkedfields'][$Field]['Value'] as $outValue){
					$values[] = $outValue;
				}
				$OutValues = implode(', ',$values);
				$defQuery = "SELECT ".$Config['_Linkedfields'][$Field]['ID'].",".$OutValues." FROM `".$Config['_Linkedfields'][$Field]['Table']."` WHERE `".$Config['_Linkedfields'][$Field]['ID']."` =  '".$Defaults[$Field]."' ;";
				$Res = mysql_query($defQuery);
				$Det = mysql_fetch_assoc($Res);
				$OutString = '';
				foreach($values as $visValues){
					$OutString .= $Det[$visValues].' ';
					$Defaults['_outvalue'][$Field] = $OutString;

				}
				//ob_start();
				//echo "SELECT ".$IDField.",".$ValueField." FROM `".$Table."` WHERE `".$ValueField."` LIKE '%".$Default."%' OR `".$IDField."` LIKE '%".$Default."%' ORDER BY `".$ValueField."` ASC;";
				//dump($Det);
				//$Return .= ob_get_clean();
				$VisDef = $OutString;
			}
			//$FieldID = uniqid('check_'.$Field);
			//$Return .= '<input type="text" id="autocomplete_'.$FieldID.'" class="textfield" value="'.$Det[$IDField].' ['.$Det[$ValueField].']" /><input type="hidden" name="dataForm['.$ElementID.']['.$Field.']" id="autocomplete_'.$FieldID.'_value" value="'.$Det[$IDField].'" class="'.$Req.'" />';
			$Return .= '<input type="text" id="entry_'.$Element['ID'].'_'.$Field.'_view" class="'.$Req.' '.$Config['_FormFieldWidth'][$Field].'" value="'.$VisDef.'" autocomplete="off" /><input type="hidden" name="dataForm['.$Element['ID'].']['.$Field.']" id="entry_'.$Element['ID'].'_'.$Field.'" value="'.$Det[$Config['_Linkedfields'][$Field]['ID']].'" />';



                       $_SESSION['dataform']['OutScripts'] .="

                        jQuery('#entry_".$Element['ID']."_".$Field."_view').autocomplete({
                                source: function( request, response ) {
                                    //alert(request.term);
                                    ajaxCall('linked_autocomplete', '".$Element['ID']."', '".$Field."', request.term, function(output){
                                        response(output);
                                    });
                                },
                                minLength: 2,
                                select: function( event, ui ) {
                                        jQuery('#entry_".$Element['ID']."_".$Field."').val(ui.item.id);
                                },
                                open: function() {
                                        jQuery( this ).removeClass( \"ui-corner-all\" ).addClass( \"ui-corner-top\" );
                                },
                                close: function() {
                                        jQuery( this ).removeClass( \"ui-corner-top\" ).addClass( \"ui-corner-all\" );
                                }
                        });
                        ";

                        /*
                        $_SESSION['dataform']['OutScripts'] .="
			
			var options = {
				script:'".getdocument($_GET['page'])."?q_eid=".$Element['ID']."&f_i=".urlencode(base64_encode($Field))."&',
				varname:'input',
				json: true,
				timeout: 5000,
				callback: function (obj) {
					document.getElementById('entry_".$Element['ID']."_".$Field."').value = obj.id;
				}
			};
			var as_json = new bsn.AutoSuggest('entry_".$Element['ID']."_".$Field."_view', options);
			*/
			
			
				//jQuery('#autocomplete_".$FieldID."').autocomplete(\"".getdocument($_GET['page'])."?q_eid=".$Element['ID']."&f_i=".encodestring($Field)."\",{width: 250, selectFirst: false});
				//jQuery('#autocomplete_".$FieldID."').result(function(event, data, formatted) {
				//	jQuery('#autocomplete_".$FieldID."_value').val(data[1]);
				//});";
		break;
	}


echo $Return;
}

if($FieldSet[1] == 'linkedfiltered'){

		$Ent = '<option>Select '.$Config['_FieldTitle'][$Config['_Linkedfilterfields'][$Field]['Filter']].' First</option>';
		$Dis = 'disabled="disabled"';
		if(!empty($Defaults[$Config['_Linkedfilterfields'][$Field]['Filter']]) || $Config['_Field'][$Config['_Linkedfilterfields'][$Field]['Filter']] == 'auto_userbase'){
                    $sendval = '';
                    if($Config['_Field'][$Config['_Linkedfilterfields'][$Field]['Filter']] == 'auto_userbase'){
                        
                        $sendval = get_current_user_id();
                        if(!empty($Defaults[$Config['_Linkedfilterfields'][$Field]['Filter']])){
                            $sendval = $Defaults[$Config['_Linkedfilterfields'][$Field]['Filter']];
                        }
                    }

                    $Ent = linked_makeFilterdLinkedField($Config['_Linkedfilterfields'][$Field]['Ref'], "CONCAT(".implode(",' ',",$Config['_Linkedfilterfields'][$Field]['Value']).") AS _Value_Field",$Config['_Linkedfilterfields'][$Field]['ID'], $sendval, $Config['_Linkedfilterfields'][$Field]['Table'], $Defaults[$Field]);
                    $Dis = '';
		}
	$Return = '<select id="entry_'.$Element['ID'].'_'.$Field.'" name="dataForm['.$Element['ID'].']['.$Field.']" id="" class="'.$Req.' '.$Config['_FormFieldWidth'][$Field].'" '.$Dis.'>';
		$Return .= $Ent;
	$Return .= '</select><span id="entry_status_'.$Element['ID'].'_'.$Field.'"></span>';
	$_SESSION['dataform']['OutScripts'] .= "
	jQuery(\"#entry_".$Element['ID']."_".$Config['_Linkedfilterfields'][$Field]['Filter']."\").change(function(){
			jQuery('#entry_".$Element['ID']."_".$Field."').html('<option>Loading...</option>');
			caption = jQuery('#caption_".$Element['ID']."_".$Field."').html();
			jQuery('#caption_".$Element['ID']."_".$Field."').html('<img src=\"".WP_PLUGIN_URL."/db-toolkit/data_form/fieldtypes/linked/images/miniload.gif\" width=\"9\" height=\"9\" alt=\"Loading\" align=\"absmiddle\" /> loading data...');
			jQuery('#entry_".$Element['ID']."_".$Field."').attr('disabled', 'disabled');
		ajaxCall('linked_makeFilterdLinkedField', '".$Config['_Linkedfilterfields'][$Field]['Ref']."', 'CONCAT(".implode(",\' \',",$Config['_Linkedfilterfields'][$Field]['Value']).") AS _Value_Field','".$Config['_Linkedfilterfields'][$Field]['ID']."', this.value, '".$Config['_Linkedfilterfields'][$Field]['Table']."', function(x){
			jQuery('#caption_".$Element['ID']."_".$Field."').html(caption);
			jQuery('#entry_status_".$Element['ID']."_".$Field."').html('');
			jQuery('#entry_".$Element['ID']."_".$Field."').html(x);
			jQuery('#entry_".$Element['ID']."_".$Field."').removeAttr('disabled');
		});
	});\n";

echo $Return;
}

?>