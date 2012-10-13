<?php

    if(empty($Config['_enableToolbar'])){
        return;
    }
    
    echo "<div class=\"dbt-toolbar ".$Config['_toolBarClass']."\" id=\"".$Config['_ID']."\">\n";
        // Buttons
        if(!empty($Config['_addItem']) && !empty($Config['_addItemName'])){

            $addLink = get_permalink($Config['_addItemPost']);
            $addScript = "";
            if(!empty($Config['_modalForm'])){
                $addLink = '#md_'.$Config['_ID'];
                $addScript = "";//alert('dialog here');";
                echo "<div class=\"modal hide fade\" id=\"md_".$Config['_ID']."\">\n";
                    echo "<div class=\"modal-header\">\n";
                    echo "<button type=\"button\" class=\"close\" data-dismiss=\"modal\">×</button>\n";
                    echo "<h3>Loading Form</h3>\n";
                    echo "</div>\n";
                    echo "<div class=\"modal-body\">\n";
                    echo "<p>Loading...</p>\n";
                    echo "</div>\n";
                    //echo "<div class=\"modal-footer\">\n";
                    //echo "<a href=\"#\" class=\"btn\" data-dismiss=\"modal\">Close</a>\n";
                    //echo "<a href=\"#\" class=\"btn btn-primary\">Save changes</a>\n";
                    //echo "</div>\n";
                echo "</div>\n";
                $footerscripts .= "
                    jQuery('#md_".$Config['_ID']."').on('show', function () {
                        dbt_ajaxCall('dbt_ajaxloadForm', '".$Config['_ID']."', function(data){
                            jQuery('#md_".$Config['_ID']."').find('.modal-body p').html(data);
                        });
                    })
                ";
            }
            echo dbt_toolbarButton($Config['_addItemName'], $addScript, 'icon-plus', 'a data-toggle="modal" ', $addLink, '_parent');
        }
        if(!empty($Config['_autoHideFilters'])){
            echo dbt_toolbarButton('Filters', "alert('ping')", 'btn');
        }
        if(!empty($Config['_showReloader'])){
            echo dbt_toolbarButton('Reload', "alert('ping')", 'btn');
        }
        if(!empty($Config['_showImporter'])){
            echo dbt_toolbarButton('Import', "alert('Not implemented yet.')", 'icon-upload', 'a', false, '_parent');
        }
        if(!empty($Config['_showDeleteAll'])){
            $nounce = wp_create_nonce('dbt_nounce_delete');
            echo dbt_toolbarButton('Delete Selected', "return confirm('Are you sure you want to delete the selected item?');", 'icon-remove-sign', 'button type="submit" name="delsel" value="'.$nounce.'"');
        }
    //echo '<br class="clear" />';
    echo '</div>';


?>