<?php

if(!empty($exitNotice)){
    return;//'<div id="'.$EID.'_wrapper"></div>';
}

global $wpdb;

$jsqueue = "";

$Data = $wpdb->get_results($Query, ARRAY_A);

// Run View Processes
ob_start();
    if(!empty($Config['_ViewProcessors'])){

        foreach($Config['_ViewProcessors'] as $viewProcess){
            if(empty($_GET['format_'.$EID])){
                //ignore on export
                if(file_exists(DB_TOOLKIT.'data_report/processors/'.$viewProcess['_process'].'/functions.php')){
                    include_once(DB_TOOLKIT.'data_report/processors/'.$viewProcess['_process'].'/functions.php');
                    $func = 'pre_process_'.$viewProcess['_process'];
                    $Data = $func($Data, $viewProcess, $Config, $EID);
                    if(empty($Data)){
                        return;
                    }
                }
            }
            //if(file_exists($viewProcess['_process']))
        }

    }
$ViewProcess = ob_get_clean();


ob_start();
echo $Config['_layoutTemplate']['_Header'];

//vardump($Config['_layoutTemplate']['_Content']);
$Row = 'odd';

$rowIndex = 1;
$First = 1;
$Prev = $Page - 1;
$Next = $Page + 1;
$Last = $TotalPages;
if ($Prev <= 0) {
    $Prev = 1;
}
if ($Next > $TotalPages) {
    $Next = $TotalPages;
}
if (empty($Page)) {
    $Page = 1;
}
//Page Index display
$toPos = $Page * $Offset;
if ($toPos > $Count['Total']) {
    $toPos = $Count['Total'];
}

if ($Count['Total'] == 0) {
    $nothingFound = 'Nothing Found';
    if (!empty($Config['_NoResultsText'])) {
        $nothingFound = $Config['_NoResultsText'];
    }
    $itemcount = '';
    $noentries = $nothingFound;
} else {
    $noentries = '';
    $itemcount = ($Start + 1) . ' - ' . $toPos . ' of ' . $Count['Total'] . ' Items';
}

//$prevbutton = '<div class="fbutton" onclick="dr_goToPage(\'' . $Media['ID'] . '\', ' . $Prev . ');"><div><img src="' . WP_PLUGIN_URL . '/db-toolkit/data_report/prev.gif" width="27" height="17" alt="Previous" align="absmiddle" /></div></div>';

if (!isset($pageLink))
   $pageLink = '';

$firstpagebutton = '<a href="?'.$pageLink.'npage=1" title="Go to the first page" class="first-page" onclick="dr_goToPage(\'' . $EID . '\', 1); return false;">&laquo;</a>';
$prevbutton = '<a href="?'.$pageLink.'npage='.$Prev.'" title="Go to the previous page" class="prev-page" onclick="dr_goToPage(\'' . $EID . '\', ' . $Prev . '); return false;">&lsaquo;</a>';
$pagejump = '<div class="fpanel">Page <input type="text" name="pageJump" id="pageJump_' . $Media['ID'] . '" style="width:30px; font-size:11px;" value="' . $Page . '" onkeypress="dr_pageInput(\'' . $Media['ID'] . '\', this.value);" /> of ' . $TotalPages . '</div>';
$nextbutton = '<a href="?'.$pageLink.'npage='.$Next.'" title="Go to the next page" class="next-page" onclick="dr_goToPage(\'' . $EID . '\', ' . $Next . '); return false;">&rsaquo;</a>';
$lastpagebutton = '<a href="?'.$pageLink.'npage='.$TotalPages.'" title="Go to the last page" class="last-page" onclick="dr_goToPage(\'' . $EID . '\', ' . $TotalPages . '); return false;">&raquo;</a>';

$pagecount = '<span class="paging-input"> ' . $Page . ' of <span class="total-pages">' . $TotalPages . ' </span></span>';

$pagination = '';

if(floatval($Page) > 10){
    for($s=$Page-4; $s<=$Page; $s++){
        $pagination .= '<a href="?'.$pageLink.'npage='.$s.'" title="Go to the next page" class="pagination-page '.$class.'" onclick="dr_goToPage(\'' . $EID . '\', ' . $s . '); return false;">'.$s.'</a>';
    }
    for($s=$Page+1; $s<=$Page+4; $s++){
        $pagination .= '<a href="?'.$pageLink.'npage='.$s.'" title="Go to the next page" class="pagination-page '.$class.'" onclick="dr_goToPage(\'' . $EID . '\', ' . $s . '); return false;">'.$s.'</a>';
    }

}

for($p=1; $p<=$TotalPages; $p++){
    $class= '';
    if($Page == $p){
        $class= 'highlight';
    }
    if($p <= 10){
        if($Page <=10){
            $pagination .= '<a href="?'.$pageLink.'npage='.$p.'" title="Go to the next page" class="pagination-page '.$class.'" onclick="dr_goToPage(\'' . $EID . '\', ' . $p . '); return false;">'.$p.'</a>';
        }
    }else{
        if($p == $TotalPages && $Page < $TotalPages-4){
            $pagination .= '<a href="?'.$pageLink.'npage='.$p.'" title="Go to the next page" class="pagination-page '.$class.'" onclick="dr_goToPage(\'' . $EID . '\', ' . $p . '); return false;">&hellip;</a><a href="?'.$pageLink.'npage='.$p.'" title="Go to the next page" class="pagination-page '.$class.'" onclick="dr_goToPage(\'' . $EID . '\', ' . $p . '); return false;">'.$p.'</a>';
        }
    }
}


//$lastpagebutton = '<a href="?'.$pageLink.'npage='.$TotalPages.'" title="Go to the last page" class="last-page" onclick="dr_goToPage(\'' . $EID . '\', ' . $TotalPages . '); return false;">È</a>';

foreach($Config['_layoutTemplate']['_Content']['_name'] as $key=>$rowTemplate){
    // placebefore Entry loop
    $preHeader = $Config['_layoutTemplate']['_Content']['_before'][$key];

    foreach($Config['_Field'] as $headField=>$type){

        if (!empty($Config['_FieldTitle'][$headField])) {
            $name = $Config['_FieldTitle'][$headField];
        } else {
            $name = df_parseCamelCase($headField);
        }

        $preHeader = str_replace('{{_' . $headField . '_name}}', $name, $preHeader);
        $preHeader = str_replace('{{_' . $headField . '}}', $headField, $preHeader);


        $preHeader = str_replace('{{_footer_first}}', $firstpagebutton, $preHeader);
        $preHeader = str_replace('{{_footer_prev}}', $prevbutton, $preHeader);
        $preHeader = str_replace('{{_footer_next}}', $nextbutton, $preHeader);
        $preHeader = str_replace('{{_footer_last}}', $lastpagebutton, $preHeader);
        $preHeader = str_replace('{{_footer_pagecount}}', $pagecount, $preHeader);


        $preHeader = str_replace('{{_footer_pagination}}', $pagination, $preHeader);

        $preHeader = str_replace('{{_footer_page_jump}}', $pagejump, $preHeader);
        $preHeader = str_replace('{{_footer_item_count}}', $itemcount, $preHeader);
        $preHeader = str_replace('{{_footer_no_entries}}', $noentries, $preHeader);
        $preHeader = str_replace('{{_EID}}', $Media['ID'], $preHeader);

    }
    echo $preHeader;

    $preContent = '';

    foreach($Data as $row){
        $SelectedRow = '';
        if (!empty($Config['_ReturnFields'][0])) {
            if (!empty($_GET[$Config['_ReturnFields'][0]])) {
                if ($row['_return_' . $Config['_ReturnFields'][0]] == $_GET[$Config['_ReturnFields'][0]]) {
                    if (!empty($Config['_Show_Edit'])) {
                        $SelectedRow = 'highlight';
                    }
                    $HighlightIndex = true;
                }
            }
        }
        $Row = grid_rowswitch($Row);

        $PreReturn = $Config['_layoutTemplate']['_Content']['_content'][$key];

        // Run first with processing values and wrapping them in thier template.
        foreach($row as $Field=>$Value){

            $Value = str_replace('<?php', htmlentities('<?php'), $Value);
            $Value = str_replace('?>', htmlentities('?>'), $Value);

            if(!empty($Config['_Field'][$Field])){
                $Types = $Config['_Field'][$Field];
                $func = $Types[0].'_processValue';
                if(function_exists($func)){
                    $Value = $func($Value, $Types[1], $Field, $Config, $Media['ID'], $row);
                }
            }
            // Wrap Fields in template
            if(!empty($Config['_layoutTemplate']['_Fields'][$Field]) && strlen($Value) > 0){
                $Value = $Config['_layoutTemplate']['_Fields'][$Field]['_before'].$Value.$Config['_layoutTemplate']['_Fields'][$Field]['_after'];
            }

            $row[$Field] = $Value;

            if (!empty($Config['_FieldTitle'][$Field])) {
                $name = $Config['_FieldTitle'][$Field];
            } else {
                $name = df_parseCamelCase($Field);
            }


            preg_match("/\{\{([A-Za-z0-9]+)\|([0-9]+)(,)([0-9]+)\}\}/", $PreReturn, $returnMatches);
           // vardump($returnMatches);
            if (!empty($returnMatches)) {
                $start = $returnMatches[2];
                $end = $returnMatches[4];
                $PreReturn = str_replace($returnMatches[0], substr(strip_tags($row[$returnMatches[1]]), $start, $end), $PreReturn);
            }
            preg_match("/\{\{([A-Za-z0-9]+)\|([0-9]+)\}\}/", $PreReturn, $returnMatches);
           // vardump($returnMatches);
            if (!empty($returnMatches)) {
                $start = 0;
                $end = $returnMatches[2];
                $PreReturn = str_replace($returnMatches[0], substr(strip_tags($row[$returnMatches[1]]), $start, $end), $PreReturn);
            }

            preg_match("/\{\{([A-Za-z0-9]+)\|([A-Za-z0-9_\-]+)\}\}/", $PreReturn, $returnMatches);
            if (!empty($returnMatches)) {
                //vardump($returnMatches);
                $subFunc = $returnMatches[2];
                if(function_exists($subFunc)){
                    $PreReturn = str_replace($returnMatches[0], $subFunc($row[$returnMatches[1]]), $PreReturn);
                }else{
                    $PreReturn = str_replace($returnMatches[0], $row[$returnMatches[1]], $PreReturn);
                }
            }


            $PreReturn = str_replace('{{_' . $Field . '_name}}', $name, $PreReturn);
            $PreReturn = str_replace('{{_' . $Field . '}}', $Field, $PreReturn);
            $PreReturn = str_replace('{{' . $Field . '}}', $Value, $PreReturn);
            $PreReturn = str_replace('{{_RowClass}}', $Row, $PreReturn);
            $PreReturn = str_replace('{{_SelectedClass}}', $SelectedRow, $PreReturn);
            $PreReturn = str_replace('{{_RowIndex}}', $rowIndex, $PreReturn);
            $PreReturn = str_replace('{{_UID}}', uniqid(), $PreReturn);
            //$PreReturn = str_replace('{{_PageID}}', $Media['ParentDocument'], $PreReturn);
            //$PreReturn = str_replace('{{_PageName}}', getdocument($Media['ParentDocument']), $PreReturn);
            $PreReturn = str_replace('{{_EID}}', $Media['ID'], $PreReturn);

            // View Edit links
            $ViewOnly = '';
            if (!empty($Config['_Show_View']) || !empty($Config['_Show_Edit'])) {
                $ViewLink = '';
                if (!empty($Config['_Show_View'])) {
                    $ViewOnly = "<span style=\"cursor:pointer;\" onclick=\"df_loadEntry('". $row['_return_' . $Config['_ReturnFields'][0]] . "', '" . $Media['ID'] . "', 'false'); return false;\"><img src=\"" . WP_PLUGIN_URL . "/db-toolkit/data_report/css/images/magnifier.png\" width=\"16\" height=\"16\" alt=\"View\" title=\"View\" border=\"0\" align=\"absmiddle\" /></span>";
                    $ViewLink .= $ViewOnly;//"<span style=\"cursor:pointer;\" onclick=\"df_loadEntry('". $row['_return_' . $Config['_ReturnFields'][0]] . "', '" . $Media['ID'] . "', 'false'); return false;\"><img src=\"" . WP_PLUGIN_URL . "/db-toolkit/data_report/css/images/magnifier.png\" width=\"16\" height=\"16\" alt=\"View\" title=\"View\" border=\"0\" align=\"absmiddle\" /></span>";
                    
                    if (!empty($Config['_ItemViewPage'])) {
                        $ReportVars = array();
                        foreach ($Config['_ReturnFields'] as $ReportReturnField) {
                            $ReportVars[$ReportReturnField] = urlencode($row['_return_' . $ReportReturnField]);
                        }
                        // Get permalink
                        $PageLink = get_permalink($Config['_ItemViewPage']);                        
                        $Location = parse_url($PageLink);
                        if (!empty($Location['query'])) {
                            $PageLink = str_replace('?' . $Location['query'], '', $PageLink);
                            parse_str($Location['query'], $gets);
                            $PageLink = $PageLink . '?' . htmlspecialchars_decode(http_build_query(array_merge($gets, $ReportVars)));
                        } else {
                            $PageLink = $PageLink . '?' . htmlspecialchars_decode(http_build_query($ReportVars));
                        }
                        $ViewLink = "<a href=\"" . $PageLink . "\"><img src=\"" . WP_PLUGIN_URL . "/db-toolkit/data_report/css/images/magnifier.png\" width=\"16\" height=\"16\" alt=\"View\" title=\"View\" border=\"0\" align=\"absmiddle\" /></a>";
                        $ViewOnly = $PageLink;
                    }

                }
                if (!empty($Config['_Show_Edit'])) {
                    if ($ViewLink != '') {
                        $ViewLink .= " ";
                    }
                    $isAjax = '';
                    if(!empty($Config['_ajaxForms'])){
                        $isAjax = ", true,'".build_query($_GET)."'";
                    }
                    $ViewLink .= '<span style="cursor:pointer;" onclick="dr_BuildUpDateForm(\'' . $EID . '\', \'' . $row['_return_' . $Config['_ReturnFields'][0]] . '\' '.$isAjax.');"><img src="' . WP_PLUGIN_URL . '/db-toolkit/data_report/edit.png" width="16" height="16" alt="Edit" title="Edit" border="0" align="absmiddle" /></span>';
                }


                    if (!empty($Config['_ItemViewInterface']) && !empty($Config['_targetInterface'])){

                        // Create return link
                        $ReportVars = array();
                        foreach ($Config['_ReturnFields'] as $ReportReturnField) {
                            $ReportVars[$ReportReturnField] = urlencode($row['_return_' . $ReportReturnField]);
                        }

                        $sendString = htmlspecialchars_decode(http_build_query($ReportVars));

                        $viewTarget = "dr_pushResult('".$Config['_ItemViewInterface']."', '".$sendString."');";
                        $PreReturn = str_replace('{{_ViewTarget}}', $viewTarget, $PreReturn); //'Edit | View';
                    }


                $PreReturn = str_replace('{{_ViewEdit}}', $ViewLink, $PreReturn); //'Edit | View';
                $PreReturn = str_replace('{{_ViewLink}}', $ViewOnly, $PreReturn); //'Edit | View';
            }


            // Add data to template
            //echo $Config['_layoutTemplate']['_Content']['_content'][$key];
            // data
            //$row[$Field]

        }
        $preContent = $PreReturn;
        //vardump($row);


    $PreReturn = $preContent;
    $outContent = '';
    // loop through again to change any missing ones
    foreach($row as $Field=>$Value){

            $Value = str_replace('<?php', htmlentities('<?php'), $Value);
            $Value = str_replace('?>', htmlentities('?>'), $Value);

            if (!empty($Config['_FieldTitle'][$Field])) {
                $name = $Config['_FieldTitle'][$Field];
            } else {
                $name = df_parseCamelCase($Field);
            }


            preg_match("/\{\{([A-Za-z0-9]+)\|([0-9]+)(,)([0-9]+)\}\}/", $PreReturn, $returnMatches);
           // vardump($returnMatches);
            if (!empty($returnMatches)) {
                $start = $returnMatches[2];
                $end = $returnMatches[4];
                $PreReturn = str_replace($returnMatches[0], substr(strip_tags($row[$returnMatches[1]]), $start, $end), $PreReturn);
            }
            preg_match("/\{\{([A-Za-z0-9]+)\|([0-9]+)\}\}/", $PreReturn, $returnMatches);
           // vardump($returnMatches);
            if (!empty($returnMatches)) {
                $start = 0;
                $end = $returnMatches[2];
                $PreReturn = str_replace($returnMatches[0], substr(strip_tags($row[$returnMatches[1]]), $start, $end), $PreReturn);
            }

            preg_match("/\{\{([A-Za-z0-9]+)\|([A-Za-z0-9_\-]+)\}\}/", $PreReturn, $returnMatches);
            if (!empty($returnMatches)) {
                //vardump($returnMatches);
                $subFunc = $returnMatches[2];
                if(function_exists($subFunc)){
                    $PreReturn = str_replace($returnMatches[0], $subFunc($row[$returnMatches[1]]), $PreReturn);
                }else{
                    $PreReturn = str_replace($returnMatches[0], $row[$returnMatches[1]], $PreReturn);
                }
            }


            $PreReturn = str_replace('{{_' . $Field . '_name}}', $name, $PreReturn);
            $PreReturn = str_replace('{{_' . $Field . '}}', $Field, $PreReturn);
            $PreReturn = str_replace('{{' . $Field . '}}', $Value, $PreReturn);
            $PreReturn = str_replace('{{_RowClass}}', $Row, $PreReturn);
            $PreReturn = str_replace('{{_SelectedClass}}', $SelectedRow, $PreReturn);
            $PreReturn = str_replace('{{_RowIndex}}', $rowIndex, $PreReturn);
            $PreReturn = str_replace('{{_UID}}', uniqid(), $PreReturn);
            //$PreReturn = str_replace('{{_PageID}}', $Media['ParentDocument'], $PreReturn);
            //$PreReturn = str_replace('{{_PageName}}', getdocument($Media['ParentDocument']), $PreReturn);
            $PreReturn = str_replace('{{_EID}}', $Media['ID'], $PreReturn);

            // View Edit links
            $ViewOnly = '';
            if (!empty($Config['_Show_View']) || !empty($Config['_Show_Edit'])) {
                $ViewLink = '';
                if (!empty($Config['_Show_View'])) {
                    $ViewOnly = "<span style=\"cursor:pointer;\" onclick=\"df_loadEntry(\"" . $row['_return_' . $Config['_ReturnFields'][0]] . "\", \"" . $Media['ID'] . "\", \"false\"); return false;\"><img src=\"" . WP_PLUGIN_URL . "/db-toolkit/data_report/css/images/magnifier.png\" width=\"16\" height=\"16\" alt=\"View\" title=\"View\" border=\"0\" align=\"absmiddle\" /></span>";
                    $ViewLink .= $ViewOnly;//"<span style=\"cursor:pointer;\" onclick=\"df_loadEntry(\"" . $row['_return_' . $Config['_ReturnFields'][0]] . "\", \"" . $Media['ID'] . "\", \"false\"); return false;\"><img src=\"" . WP_PLUGIN_URL . "/db-toolkit/data_report/css/images/magnifier.png\" width=\"16\" height=\"16\" alt=\"View\" title=\"View\" border=\"0\" align=\"absmiddle\" /></span>";
                    if (!empty($Config['_ItemViewPage'])) {
                        $ReportVars = array();
                        foreach ($Config['_ReturnFields'] as $ReportReturnField) {
                            $ReportVars[$ReportReturnField] = urlencode($row['_return_' . $ReportReturnField]);
                        }
                        // Get permalink
                        $PageLink = get_permalink($Config['_ItemViewPage']);
                        $Location = parse_url($PageLink);
                        if (!empty($Location['query'])) {
                            $PageLink = str_replace('?' . $Location['query'], '', $PageLink);
                            parse_str($Location['query'], $gets);
                            $PageLink = $PageLink . '?' . htmlspecialchars_decode(http_build_query(array_merge($gets, $ReportVars)));
                        } else {
                            $PageLink = $PageLink . '?' . htmlspecialchars_decode(http_build_query($ReportVars));
                        }
                        $ViewLink = "<a href=\"" . $PageLink . "\"><img src=\"" . WP_PLUGIN_URL . "/db-toolkit/data_report/css/images/magnifier.png\" width=\"16\" height=\"16\" alt=\"View\" title=\"View\" border=\"0\" align=\"absmiddle\" /></a>";
                        $ViewOnly = $PageLink;
                    }
                }
                if (!empty($Config['_Show_Edit'])) {
                    if ($ViewLink != '') {
                        $ViewLink .= " ";
                    }
                    $ViewLink .= '<span style="cursor:pointer;" onclick="dr_BuildUpDateForm(\'' . $EID . '\', \'' . $row['_return_' . $Config['_ReturnFields'][0]] . '\');"><img src="' . WP_PLUGIN_URL . '/db-toolkit/data_report/edit.png" width="16" height="16" alt="Edit" title="Edit" border="0" align="absmiddle" /></span>';
                }

                    if (!empty($Config['_ItemViewInterface']) && !empty($Config['_targetInterface'])){
                        // Create return link
                        $ReportVars = array();
                        foreach ($Config['_ReturnFields'] as $ReportReturnField) {
                            $ReportVars[$ReportReturnField] = urlencode($row['_return_' . $ReportReturnField]);
                        }
                        $sendString = htmlspecialchars_decode(http_build_query($ReportVars));

                        $viewTarget = "dr_pushResult('".$Config['_ItemViewInterface']."', '".$sendString."');";
                        $PreReturn = str_replace('{{_ViewTarget}}', $viewTarget, $PreReturn); //'Edit | View';
                    }


                $PreReturn = str_replace('{{_ViewEdit}}', $ViewLink, $PreReturn); //'Edit | View';
                $PreReturn = str_replace('{{_ViewLink}}', $ViewOnly, $PreReturn); //'Edit | View';
            }


            // Add data to template
            //echo $Config['_layoutTemplate']['_Content']['_content'][$key];
            // data
            //$row[$Field]

            $outContent = $PreReturn;

    }
    $rowIndex++;

    echo $outContent;
    }

    $preFooter = $Config['_layoutTemplate']['_Content']['_after'][$key];

    foreach($Config['_Field'] as $footField=>$type){

        if (!empty($Config['_FieldTitle'][$footField])) {
            $name = $Config['_FieldTitle'][$footField];
        } else {
            $name = df_parseCamelCase($footField);
        }

        $preFooter = str_replace('{{_' . $footField . '_name}}', $name, $preFooter);
        $preFooter = str_replace('{{_' . $footField . '}}', $footField, $preFooter);


        $preFooter = str_replace('{{_footer_first}}', $firstpagebutton, $preFooter);
        $preFooter = str_replace('{{_footer_prev}}', $prevbutton, $preFooter);
        $preFooter = str_replace('{{_footer_next}}', $nextbutton, $preFooter);
        $preFooter = str_replace('{{_footer_last}}', $lastpagebutton, $preFooter);
        $preFooter = str_replace('{{_footer_pagecount}}', $pagecount, $preFooter);


        $preFooter = str_replace('{{_footer_pagination}}', $pagination, $preFooter);

        $preFooter = str_replace('{{_footer_page_jump}}', $pagejump, $preFooter);
        $preFooter = str_replace('{{_footer_item_count}}', $itemcount, $preFooter);
        $preFooter = str_replace('{{_footer_no_entries}}', $noentries, $preFooter);
        $preFooter = str_replace('{{_EID}}', $Media['ID'], $preFooter);

$preHeader = ';';
    }
    echo $preFooter;
}
echo $Config['_layoutTemplate']['_Footer'];
$template = ob_get_clean();

// Add View Processor
if(empty($ProcessorRun)){
    $template = str_replace('{{_ViewProcessors}}', $ViewProcess, $template);
    $ProcessorRun = true;
}else{
    $template = str_replace('{{_ViewProcessors}}', '', $template);
}
$template = str_replace('<?php', '<?php ', $template);
$template = str_replace('?>', ' ?>', $template);
ob_start();
eval(' ?> '.$template.' <?php ');
$Output = do_shortcode(do_shortcode(do_shortcode(do_shortcode(ob_get_clean()))));


// Process toolbars buttons

    //    echo
    //if(!empty($Config['_useToolbarTemplate'])){
        $Template = $Output;
        // Replace Codes with Buttons


        //For Add Item - Only if showed
        if (empty($Config['_New_Item_Hide'])) {
            $ajaxSubmit = 'true';
            if (is_admin ()) {
                if (empty($Config['_ajaxForms'])) {
                    $ajaxSubmit = 'false';
                }
            } else {
                if (empty($Config['_ajaxForms'])) {
                    $ajaxSubmit = 'false';
                }
            }

            $Template = str_replace('{{_button_addItem}}', dr_toolbarButton($Config['_New_Item_Title'], 'df_buildQuickCaptureForm(\'' . $Media['ID'] . '\', ' . $ajaxSubmit . ', \''.build_query($_GET).'\');return false;','add'), $Template);
        }else{
            $Template = str_replace('{{_button_addItem}}', '', $Template);
        }

        // replace import button
        if (!empty($Config['_Show_Import'])) {
            //{{_button_import}}
            $Template = str_replace('{{_button_import}}', dr_toolbarButton('Import', 'df_buildImportForm(\'' . $Media['ID'] . '\');return false;', 'import'), $Template);
        }else{
            $Template = str_replace('{{_button_import}}', '', $Template);
        }

        //replace {{_button_toggleFilters}}
        if (!empty($Config['_Show_Filters'])) {
            $Template = str_replace('{{_button_toggleFilters}}', dr_toolbarButton('Filters', 'jQuery(\'#filterPanel_' . $Media['ID'] . '\').toggle();', 'filterbutton'), $Template);
        }else{
            $Template = str_replace('{{_button_toggleFilters}}', '', $Template);
        }

        // replace {{_button_reload}}
        if (!empty($Config['_showReload'])) {
                $Template = str_replace('{{_button_reload}}', dr_toolbarButton('Reload', 'dr_goToPage(\'' . $Media['ID'] . '\', false, false);', 'reload'), $Template);
        }else{

        }

        // replace {{_button_selectAll}}
        if (!empty($Config['_Show_Delete'])) {
            if (!empty($Config['_Show_Select'])) {
                $Template = str_replace('{{_button_selectAll}}', dr_toolbarButton('Select All', 'dr_selectAll(\'' . $Media['ID'] . '\');', 'selectall'), $Template);
                $Template = str_replace('{{_button_unselect}}', dr_toolbarButton('Unselect All', 'dr_deSelectAll(\'' . $Media['ID'] . '\');', 'unselectall'), $Template);
            }else{
                $Template = str_replace('{{_button_selectAll}}', '', $Template);
                $Template = str_replace('{{_button_unselect}}', '', $Template);
            }

            $Template = str_replace('{{_button_deleteSelected}}', dr_toolbarButton('Delete Selected', 'dr_deleteEntries(\'' . $Media['ID'] . '\');"', 'delete'), $Template);
        }else{
            $Template = str_replace('{{_button_selectAll}}', '', $Template);
            $Template = str_replace('{{_button_unselect}}', '', $Template);
            $Template = str_replace('{{_button_deleteSelected}}', '', $Template);
        }


        //replace {{_button_export_pdf}} and {{_button_export_csv}}
        if (!empty($Config['_Show_Export'])) {
            if(empty($Global))
                $Global = false;

            $Template = str_replace('{{_button_export_pdf}}', dr_toolbarButton('Export PDF', 'dr_exportReport(\'?format_' . $Media['ID'] . '=pdf\', \'' . $Media['ID'] . '\',\'' . $Global . '\');', 'export'), $Template);
            $Template = str_replace('{{_button_export_csv}}', dr_toolbarButton('Export CSV', false, 'export', '?format_' . $Media['ID'] . '=csv'), $Template);
        }else{
            $Template = str_replace('{{_button_export_pdf}}', '', $Template);
            $Template = str_replace('{{_button_export_csv}}', '', $Template);
        }




        $Output = $Template;
    //}
//vardump($Config);
echo str_replace('{{_EID}}', $Media['ID'], $Output);


$_SESSION['dataform']['OutScripts'] .= "\r\n".$jsqueue."\r\n";

// Make Scripts for deleting and select

if (!empty($Config['_Show_Edit'])) {

    //    #data_report_dt_intfc4e10d80d9c725 .report_entry


    $_SESSION['dataform']['OutScripts'] .= "
            jQuery('#reportPanel_" . $EID . " .report_entry').bind('click', function(){
                    jQuery(this).toggleClass(\"highlight\");
            });
    ";
}


?>