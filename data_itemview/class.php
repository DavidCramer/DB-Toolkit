<?php


function di_sourceSetup($id, $Default = false) {
    $Element = getelement($id);
    $Config = $Element['Content'];
    //dump($Config);
    return '<div style="padding:3px;" class="list_row1"><strong>Source Field</strong>:&nbsp; '.df_ListFields($Config['_main_table'], $Default, '_sourceField').'</div>';
}
function di_referenceSetup($id, $DefaultFilter = false,  $DefaultTitle = false) {
    $Element = getelement($id);
    $Config = $Element['Content'];
    //dump($Config);
    $Return = '<div style="padding:3px;" class="list_row1"><strong>Filter Field</strong>:&nbsp; '.df_ListFields($Config['_main_table'], $DefaultFilter, '_filterField').'</div>';
    $Return .= '<div style="padding:3px;" class="list_row2"><strong>Title Field</strong>:&nbsp; '.df_ListFields($Config['_main_table'], $DefaultTitle, '_titleField').'</div>';
    return $Return;
}


function di_showItem($EID, $Item, $Setup = false) {
    
    $Element = getelement($EID);
    $Config = $Element['Content'];
    $queryJoin = '';
    $queryWhere = array();
    $queryLimit = '';
    $querySelects = array();
    $WhereTag = '';
    $groupBy = '';
    $orderStr = '';
    $countSelect = '';

    // setup columns
    if(!empty($Config['_FormLayout'])) {
        parse_str($Config['_FormLayout'], $Columns);
        if(empty($Columns['FieldList_left'])) {
            unset($Columns);
            unset($Config['_FormLayout']);
        }
    }



//setup Field Types
    foreach($Config['_Field'] as $Field=>$Type) {
        // explodes to:
        // [0] = Field plugin dir
        // [1] = Field plugin type
        $Config['_Field'][$Field] = explode('_', $Type);
    }



    // field type filters
    $joinIndex = 'a';
    foreach($Config['_IndexType'] as $Field=>$Type) {
        $querySelects[$Field] = 'prim.`'.$Field.'`';
    }

    if(!empty($Config['_CloneField'])) {

        foreach($Config['_CloneField'] as $CloneKey=>$Clone) {
            //echo 'BEFORE';
            //vardump($querySelects);
            foreach($querySelects as $selectKey=>$selectScan) {
                $queryJoin = str_replace($CloneKey, $Clone['Master'], $queryJoin);
                $WhereTag = str_replace($CloneKey, $Clone['Master'], $WhereTag);
                if(strstr($selectScan, " AS ") === false) {
                    //echo $Clone['Master'].' - concat <br />';
                    if(strstr($selectScan, "_sourceid_") === false) {
                        $querySelects[$selectKey] = str_replace($CloneKey, $Clone['Master'].'` AS `'.$CloneKey, $selectScan);
                    }
                }
            }
            //echo 'After';
            //vardump($querySelects);
        }
    }

    // Build Query
    foreach($Config['_Field'] as $Field=>$Type) {
        // Run Filters that have been set through each field type
        if(file_exists(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$Type[0].'/queryfilter.php')) {
            include(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$Type[0].'/queryfilter.php');
        }
        //apply a generic keyword filter to each field is a key word has been sent
        if(!empty($_SESSION['reportFilters'][$EID]['_keywords'])) {
            if($WhereTag == '') {
                $WhereTag = " WHERE ";
            }
            $keyField = 'prim.'.$Field;
            if(strpos($querySelects[$Field], ' AS ') !== false) {
                $keyField = strtok($querySelects[$Field], ' AS ');
            }

            $preWhere[] = $keyField." LIKE '%".$_SESSION['reportFilters'][$EID]['_keywords']."%' ";
            //dump($_SESSION['reportFilters'][$EID]);
        }
        $joinIndex++;
    }

    //post clone fixes
    foreach($querySelects as $fieldToFix=>$select) {
        if(!empty($Config['_CloneField'][$fieldToFix])) {
            $cloneReturns[$fieldToFix] = explode(' AS ', $select);
        }
    }
    if(!empty($cloneReturns)) {
        foreach($cloneReturns as $cloneKey=>$cloneField) {
            $pureName = trim(str_replace('prim.','',$cloneField[0]), '`');
            if(!empty($cloneReturns[$pureName])) {
                $cloneReturns[$cloneKey][0] = $cloneReturns[$pureName][0];
                $querySelects[$cloneKey] = implode(' AS ', $cloneReturns[$cloneKey]);
            }
        }
    }

    // create Query Selects and Where clause string
    $querySelect = implode(',',$querySelects);
    if(!empty($Setup)) {
        $queryWhere = 'prim.'.$Setup['_filterField'].' = \''.$Item.'\'';
    }else {
        $queryWhere = 'prim.'.$Config['_ReturnFields'][0].' = \''.$Item.'\'';
    }
    if(!empty($queryWhere)) {
        $WhereTag = " WHERE ";
    }else {
        $WhereTag = "";
    }

    if(is_array($groupBy)) {
        $groupBy = 'GROUP BY ('.implode(',', $groupBy).')';
        $countLimit = '';
        $entryCount = true;
        //add totals selects to count
        if(is_array($countSelect)) {
            $countSelect = ','.implode(',',$countSelect);
        }
    }

    $Query = "SELECT ".$querySelect." FROM `".$Config['_main_table']."` AS prim \n ".$queryJoin." \n ".$WhereTag." \n ".$queryWhere."\n ".$groupBy." \n ".$orderStr." \n LIMIT 1;";
    // Wrap fields with ``
    //foreach($querySelects as $Field=>$FieldValue){
    //   $Query = str_replace($Field, '`'.$Field.'`', $Query);
    //}
    // Query Results
    //$Res = mysql_query($Query);
    //echo $Query.'<br /><br /><br />';
    //echo mysql_error();

    //vardump($Config['_ReturnFields']);

    //$Data = mysql_fetch_assoc($Res);
    
    $Data = dr_BuildReportGrid($EID, false, false, false, 'data', false, array($Config['_ReturnFields'][0]=>$Item));

    if(!empty($Config['_UseViewTemplate'])) {
        //dump($Config);
        $PreReturn = $Config['_ViewTemplateContentWrapperStart'];
        $PreReturn .= $Config['_ViewTemplatePreContent'];
        $PreReturn .= $Config['_ViewTemplateContent'];
        $PreReturn .= $Config['_ViewTemplatePostContent'];
        $PreReturn .= $Config['_ViewTemplateContentWrapperEnd'];
        //echo $Config['_ViewTemplateContent'];
        $newTitle = 'View Item';
        if(!empty($Config['_ViewFormText'])) {
            $newTitle = $Config['_ViewFormText'];
        }
        foreach($Config['_Field'] as $Field=>$Types) {
            if(!empty($Config['_ViewFormText'])) {
                //dump($Data);
                if(!empty($Data['_outvalue'][$Field])) {
                    $newTitle = str_replace('{{'.$Field.'}}', $Data['_outvalue'][$Field], $newTitle);
                }else {
                    $newTitle = str_replace('{{'.$Field.'}}', $Data[$Field], $newTitle);
                }
            }
            //	dump($Type);
            if(file_exists(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$Types[0].'/conf.php')) {
                if(!empty($Config['_FieldTitle'][$Field])) {
                    $name = $Config['_FieldTitle'][$Field];
                }else {
                    $name = df_parseCamelCase($Field);
                }
                $PreReturn = str_replace('{{_'.$Field.'_name}}', $name, $PreReturn);
                if(file_exists(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$Types[0].'/output.php')) {
                    $Out = false;
                    include(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$Types[0].'/output.php');
                    $PreReturn = str_replace('{{'.$Field.'}}', $Out, $PreReturn);
                }
                $PreReturn = str_replace('{{_PageID}}', $Element['ParentDocument'], $PreReturn);
                $PreReturn = str_replace('{{_PageName}}', getdocument($Element['ParentDocument']), $PreReturn);
                $PreReturn = str_replace('{{_EID}}', $EID, $PreReturn);
            }
        }
        $Output['title'] = $newTitle;
        $Output['width'] = $Config['_popupWidthview'];
        $Output['html'] = $PreReturn;
        return $Output;
    }


    //dump($Data);
    $Row = 'list_row2';
    $LeftColumn = '';
    $RightColumn = '';
    $FarRightColumn = '';
    //vardump($Config);
    //dump($Config['_Field']);
    if(!empty($Config['_gridViewLayout'])) {
        //$Config['_gridViewLayout'] = str_replace('=viewrow', '=row', $Config['_gridLayoutView']);
        parse_str($Config['_gridViewLayout'], $Layout);
        //vardump($Config['_gridView']);
        //vardump($Layout);
        $Form = '';
        $CurrRow = '0';
        $CurrCol = '0';
        $Index = 0;

        $newTitle = 'View Item';
        if(!empty($Config['_ViewFormText'])) {
            $newTitle = $Config['_ViewFormText'];
        }
        //dump($Setup);
        foreach($Config['_gridView'] as $row=>$cols){
            $Form .= "<div style=\"clear: both;\" class=\"view-gen-row\" id=\"pg-view-".$row."\">\n";
                foreach($cols as $col=>$width){
                    $Form .= "<div class=\"view-".$row."-".$col."\" style=\"float: left; overflow: hidden; width: ".$width.";\">\n";
                        $Form .= "<div id=\"pg-view-".$row."-".$col."\" class=\"view-gen-row view-gen-col view-col-".$col."\">\n";

                            // check for section breaks
                            $contentKeys = array_keys($Layout, $row.'_'.$col);
                            foreach($contentKeys as $Field){
                                $Field = str_replace('View_Field_', '', $Field);
                                $FieldSet = $Config['_Field'][$Field];
                                if(file_exists(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$FieldSet[0].'/conf.php') && count($FieldSet) == 2) {
                                    include(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$FieldSet[0].'/conf.php');
                                    if(!empty($FieldTypes[$FieldSet[1]]['visible']) && (empty($Config['_CloneField'][$Field]) || !empty($FieldTypes[$FieldSet[1]]['cloneview']))){
                                        // Check if is visible or not
                                        $Out = false;
                                        $Type = $FieldSet[1];
                                        $Types = $FieldSet;
                                        $Form .= "<label class=\"view-gen-lable singletext\" for=\"entry_".$Element['ID']."_".$Field."\" id=\"lable_".$Element['ID']."_".$Field."\">".$Config['_FieldTitle'][$Field]."</label>\n";
                                        $Form .= "<div class=\"view-gen-field-data-wrapper\" id=\"view-data-".$Field."\">\n";
                                        //$Val = $Defaults[$Field];]
                                        include(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$FieldSet[0].'/output.php');
                                        //$Form = str_replace('{{'.$Field.'}}', $Out, $Form);
                                        $Form .= $Out;
                                        $Form .= "&nbsp;</div>\n";
                                        $Form .= "<span class=\"description\" id=\"caption_".$Element['ID']."_".$Field."\">\n";
                                        $Form .= $Config['_FieldCaption'][$Field].'&nbsp';
                                        $Form .= "</span>\n";
                                    }else{
                                        if(empty($FieldTypes[$FieldSet[1]]['visible'])){
                                            ob_start();
                                            $Val = $Defaults[$Field];
                                            include(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$FieldSet[0].'/input.php');
                                            $Hidden .= ob_get_clean();
                                        }
                                    }

                                }else{
                                    if(!empty($Config['_SectionBreak'][$Field])){
                                        $Form .= "<div class=\"sectionbreak\">\n";
                                        $Form .= "<h2>".$Config['_SectionBreak'][$Field]['Title']."</h2>\n";
                                        if(!empty($Config['_SectionBreak'][$Field]['Caption'])){
                                            $Form .= "<span class=\"description\">".$Config['_SectionBreak'][$Field]['Caption']."</span>\n";
                                        }
                                        $Form .= "</div>\n";
                                    }
                                    $Form .= '&nbsp;';
                                }
                            }
                            if(empty($contentKeys)){
                                $Form .= '&nbsp;';
                            }
                        $Form .= "</div>\n";
                    $Form .= "</div>\n";
                }
            $Form .= "</div>\n";
        }
        $Form .= '<div style="clear:left;"></div>';
        $Shown = '';
        // add title
        if(!empty($Config['_ViewFormText'])) {
            //dump($Data);
            if(!empty($Data['_outvalue'][$Field])) {
                $newTitle = str_replace('{{'.$Field.'}}', $Data['_outvalue'][$Field], $newTitle);
            }else {
                $newTitle = str_replace('{{'.$Field.'}}', $Data[$Field], $newTitle);
            }
            $Output['title'] = $newTitle;
        }
        $Output['width'] = '420';
        if(!empty($Config['_popupWidthview'])){
            $Output['width'] = $Config['_popupWidthview'];
        }
        $Output['html'] = '<div class="formular">'.$Form.'</div>';
        if(!empty($Config['_Show_Edit'])) {
            $OutPut['edit'] = true;
        }
        return $Output;
    }

    if(!empty($Config['_FormLayout'])) {
        parse_str($Config['_FormLayout'], $Columns);
        if(empty($Columns['FieldList_left'])) {
            unset($Columns);
            unset($Config['_FormLayout']);
        }

    }



    if(!empty($Columns)) {
        foreach($Columns as $Key=>$Side) {
            if($Key == 'FieldList_Main') {
                $ColumnSet = 'LeftColumn';
            }
            if($Key == 'FieldList_left') {
                $ColumnSet = 'RightColumn';
            }
            if($Key == 'FieldList_right') {
                $ColumnSet = 'FarRightColumn';
            }
            foreach($Side as $Entry) {
                if(substr($Entry,0,12) != 'SectionBreak') {
                    $Row = dais_rowSwitch($Row);
                    $Field = $Entry;
                    $Types = $Config['_Field'][$Field];
                    $$ColumnSet .= $FieldSet[1];
                    //ob_start();
                    //dump($Config['_Field']);
                    //$$ColumnSet = ob_get_clean();
                    //$$ColumnSet .= $FieldSet[0].'<br />';
                    if(!empty($Config['_FieldTitle'][$Field])) {
                        $FieldTitle = $Config['_FieldTitle'][$Field];
                    }else {
                        $FieldTitle = df_parsecamelcase($Field);
                    }


                    if(!empty($Types[1])) {
                        include(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$Types[0].'/conf.php');
                        if($FieldTypes[$Types[1]]['visible'] == true) {
                            $Out = false;
                            $Out = '<div id="lable_'.$Element['ID'].'_'.$Field.'" for="entry_'.$Element['ID'].'_'.$Field.'" class="view-gen-lable"><strong>'.$FieldTitle.'</strong></div>';
                            include(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$Types[0].'/output.php');
                            $$ColumnSet .= $Out;
                        }
                    }
                }else {
                    $$ColumnSet .= '<h3>'.$Config['_SectionBreak']['_'.$Entry].'</h3>';
                }

            }
        }
    }else {
        foreach($Config['_Field'] as $Field=>$Types) {
            $Row = dais_rowswitch($Row);
            if(!empty($Types[1])) {
                if(!empty($Config['_FieldTitle'][$Field])) {
                    $FieldTitle = $Config['_FieldTitle'][$Field];
                }else {
                    $FieldTitle = df_parsecamelcase($Field);
                }
                include(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$Types[0].'/conf.php');
                if($FieldTypes[$Types[1]]['visible'] == true) {
                    $Out = false;
                    $Out = '<div id="lable_'.$Element['ID'].'_'.$Field.'" for="entry_'.$Element['ID'].'_'.$Field.'" class="view-gen-lable"><strong>'.$FieldTitle.'</strong></div>';
                    include(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$Types[0].'/output.php');
                    //echo $Out;
                    if(!empty($Columns)) {
                        if(in_array($Field, $Columns['FieldList_Main'])) {
                            $LeftColumn .= $Out;
                        }elseif(in_array($Field, $Columns['FieldList_left'])) {
                            $RightColumn .= $Out;
                        }
                    }else {
                        $RightColumn .= $Out;
                    }
                }
            }
        }
    }


    if(!empty($Config['_titleField'])) {
        //infobox($Setup['_Prefix'].$Data[$Setup['_titleField']].$Setup['Suffix']);
        $OutPut['title'] = $Config['_Prefix'].$Data[$Config['_titleField']].$Config['Suffix'];
    }else {
        //echo '<h2>View Entry</h2>';
        $OutPut['title'] = 'View Entry';//$Setup['_Prefix'].$Data[$Setup['_titleField']].$Setup['Suffix'];
    }
    $Return = '<table width="100%" border="0" cellspacing="0" cellpadding="2">
		<tr>';
    $OutPut['width'] = 300;
    if(!empty($LeftColumn)) {
        $ColWidth = '33';
        if(empty($FarRightColumn)) {
            $ColWidth = '50';
        }
        $Return .= '<td width="'.$ColWidth.'%" valign="top">'.$LeftColumn.'</td>';
        $OutPut['width'] = $OutPut['width']+100;
    }
    if(!empty($RightColumn)) {
        $Return .= '<td valign="top">'.$RightColumn.'</td>';
        $OutPut['width'] = $OutPut['width']+100;
    }
    if(!empty($FarRightColumn)) {
        $Return .= '<td width="33%" valign="top">'.$FarRightColumn.'</td>';
        $OutPut['width'] = $OutPut['width']+100;
    }
    $Return .= '</tr>';
    $Return .= '</table>';
    if(!empty($Config['_Show_Edit'])) {
        $OutPut['edit'] = true;
        //$Return .= '<input type="button" value="Edit" class="close" onclick="dr_BuildUpDateForm('.$EID.', '.$Item.');" />';
    }
    if(!empty($Config['_EnableAudit'])) {
        $revres = mysql_query("SELECT count(_ID) as Rev FROM `_audit_".$Config['_main_table']."` WHERE `".$Config['_ReturnFields'][0]."` = '".$Data[$Config['_ReturnFields'][0]]."';");
        if($revres) {
            if(mysql_num_rows($revres) == 1) {
                $R = mysql_fetch_assoc($revres);
                $Return .= '<div class="captions">Revision '.$R['Rev'].'</div>';
            }
        }
    }
    $OutPut['html'] = $Return;
    return $OutPut;
}




?>