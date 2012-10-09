<?php
// Run View Processes    

    if(!empty($Config['_viewProcessors'])){

        foreach($Config['_viewProcessors'] as $viewProcess){
            if(empty($_GET['format_'.$Config['_ID']])){
                //ignore on export
                if(file_exists(DBT_PATH.'processors/view/'.$viewProcess['_process'].'/functions.php')){
                    include_once(DBT_PATH.'processors/view/'.$viewProcess['_process'].'/functions.php');
                    $func = 'pre_process_'.$viewProcess['_process'];
                    $Data = $func($Data, $viewProcess, $Config, $EID);
                    if(empty($Data)){
                        return;
                    }
                }
            }
        }

    }
    
    echo "<form action=\"admin.php?page=app_builder&action=render&interface=".$Config['_ID']."\" method=\"GET\">\n";
    echo "<table cellspacing=\"0\" class=\"".$Config['_listClass']."\">\n";
    echo "  <thead>\n";
    ob_start();
    echo "      <tr>\n";
    echo "          <th style=\"\" class=\"manage-column column-cb check-column\" id=\"_cb\" scope=\"col\"><input type=\"checkbox\"></th>\n";
    // Headers
    foreach($cols as $Field){
        $Width = '';
        if(!empty($Config['_widthOverride'][$Field])){
            $Width = str_replace('px', '', $Config['_widthOverride'][$Field]);
        }
        echo "          <th style=\"width:".$Width."px; text-align:".$Config['_Justify'][$Field].";\" class=\"manage-column column-title\" id=\"title\" scope=\"col\"><span>".$Config['_FieldTitle'][$Field]."</span></th>\n";

    }
    if(!empty($Config['_showView']) || !empty($Config['_showEdit']) || !empty($Config['_showDelete'])){
        echo "          <th style=\"\" class=\"manage-column column-action dbt-column-action\" id=\"action\" scope=\"col\"><span>Action</span></th>\n";
        if(!empty($Config['_showView'])){

        }
        if(!empty($Config['_showEdit'])){

        }
        if(!empty($Config['_showDelete'])){

        }
    }
    echo "      </tr>\n";
    $heads = ob_get_clean();
    echo $heads;
    echo "  </thead>\n";
    echo "  <tfoot>\n";
    echo $heads."\n";
    echo "  </tfoot>\n";
    
    $rowClass = "alternate";
    if(!empty($Data['__noResults'])){
        $colSpan = count($cols)+1;
        if(!empty($Config['_showView']) || !empty($Config['_showEdit']) || !empty($Config['_showDelete'])){
            $colSpan++;
        }
            echo "<tr class=\"".$rowClass."\">\n";
            echo '<td colspan="'.$colSpan.'">';
            echo $Config['_noResultsText'];
            echo '</td>';
            echo '</tr>';
    }else{
        foreach($Data as $row){
            echo "      <tr class=\"".$rowClass."\">\n";
            echo "          <th class=\"check-column dbt-check-column\" scope=\"row\"><input type=\"checkbox\" value=\"".$row['__primary__']."\" name=\"_cb[]\"></th>\n";
            $actionCheck = false;
            foreach($cols as $Field){
                echo "          <td class=\"column-entry\" style=\"text-align:".$Config['_Justify'][$Field].";\">\n";
                // Run FieldType Processor
                $Type = explode('_', $Config['_Field'][$Field]);
                if (file_exists(DBT_PATH. 'fieldtypes/' . $Type[0] . '/functions.php')) {
                    include_once DBT_PATH. 'fieldtypes/' . $Type[0] . '/functions.php';
                }
                $func = $Type[0].'_viewValue';
                if(function_exists($func)){
                    echo "              ".$func($row[$Field], $Type[1], $Field, $Config, $Config['_ID'], $Data)."\n";
                }else{
                    echo "              ".$row[$Field]."\n";
                }
                echo "          </td>\n";
            }
            // render action
            if(!empty($Config['_showView']) || !empty($Config['_showEdit']) || !empty($Config['_showDelete'])){
                echo "          <td style=\"text-align:center\" class=\"column-action dbt-column-action\">";
                if(!empty($Config['_showView'])){
                    $linkURL = get_permalink($Config['_viewItemPost']);
                    $linkText = '';
                    if(empty($Config['_includeBootstrap'])){
                        $linkText = 'View';
                    }
                    echo "<a href=\"".$linkURL.urlencode($row['__primary__'])."\" class=\"icon-eye-open\">".$linkText."</a> ";
                }
                if(!empty($Config['_showEdit'])){
                    $linkText = '';
                    if(empty($Config['_includeBootstrap'])){
                        $linkText = 'Edit';
                    }
                    $linkURL = get_permalink($Config['_editItemPost']);
                    echo " <a href=\"".$linkURL.urlencode($row['__primary__'])."\" class=\"icon-edit\">".$linkText."</a> ";
                }
                if(!empty($Config['_showDelete'])){
                    $linkText = '';
                    if(empty($Config['_includeBootstrap'])){
                        $linkText = 'Delete';
                    }
                    $nounce = wp_create_nonce('dbt_nounce_delete');
                    $linkURL = get_permalink($Config['_basePost']);
                    echo " <a href=\"".$linkText."?delsel=".$nounce."&_cb%5B%5D=".urlencode($row['__primary__'])."\" class=\"icon-remove\" onclick=\"return confirm('Are you sure you want to delete this entry?');\">".$linkText."</a>";
                }
                echo "</td>\n";
            }

            echo "      </tr>\n";
            if($rowClass == 'alternate'){
                $rowClass = '';
            }else{
                $rowClass = 'alternate';
            }
        }
    }
    echo "</table>\n";


$footerscripts .= "
    jQuery('thead th.check-column input[type=\"checkbox\"], tfoot th.check-column input[type=\"checkbox\"]').click(function(){
        if(jQuery(this).prop(\"checked\")){
            jQuery('th.check-column input[type=\"checkbox\"]').prop(\"checked\", true);
        }else{
            jQuery('th.check-column input[type=\"checkbox\"]').prop(\"checked\", false);
        }
    });
    ";

?>