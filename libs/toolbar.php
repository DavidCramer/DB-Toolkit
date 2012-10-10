<h2><?php echo $Config['_ReportDescription']; ?></h2>

<?php
//<ul class="subsubsub">
//	<li class="all"><a class="current" href="edit.php?post_type=post">All <span class="count">(23)</span></a> |</li>
//	<li class="publish"><a href="edit.php?post_status=publish&amp;post_type=post">Published <span class="count">(22)</span></a> |</li>
//	<li class="draft"><a href="edit.php?post_status=draft&amp;post_type=post">Draft <span class="count">(1)</span></a></li>
//</ul>
    if(!empty($Config['_showKeywordSearch'])){
        //echo "<form>\n";
        echo "<p class=\"search-box\">\n";
        echo "<input type=\"hidden\" name=\"page\" value=\"".$_GET['page']."\" />\n";
        echo "<input type=\"hidden\" name=\"action\" value=\"".$_GET['action']."\" />\n";
        echo "<input type=\"hidden\" name=\"interface\" value=\"".$_GET['interface']."\" />\n";
        $sval = '';
        if(!empty($_GET['s'])){
            $sval = $_GET['s'];
        }
        echo "  <label for=\"post-search-input\" class=\"screen-reader-text\">".$Config['_keywordSearchLabel'].":</label>\n";
        echo "  <input type=\"search\" value=\"".$sval."\" name=\"s\" id=\"post-search-input\">\n";
        echo "  <input type=\"submit\" value=\"".$Config['_keywordSearchLabel']."\" class=\"button\" id=\"search-submit\" name=\"\">\n";        
        echo "</p>\n";
        //echo "</form>";
    }
    
    if(empty($Config['_enableToolbar'])){
        return;
    }
    
    echo "<div class=\"dbt-toolbar well well-small\" id=\"".$Config['_ID']."\">\n";
        // Buttons
        if(!empty($Config['_addItem']) && !empty($Config['_addItemName'])){
            $addLink = false;
            $addScript = "alert('dialog here');";
            if(empty($Config['_modalForm'])){
                $addLink = 'admin.php?page=app_builder&action=render&interface='.$Config['_ID'].'&mode=form';
                $addScript = "";
            }            
            echo dbt_toolbarButton($Config['_addItemName'], $addScript, 'icon-plus', 'a', $addLink, '_parent');
        }
        if(!empty($Config['_showImporter'])){
            echo dbt_toolbarButton('Import', "alert('Not implemented yet.')", 'icon-upload', 'a', false, '_parent');
        }
        if(!empty($Config['_showDeleteAll'])){
            $nounce = wp_create_nonce('dbt_nounce_delete');
            echo dbt_toolbarButton('Delete Selected', "return confirm('Are you sure you want to delete the selected item?');", 'icon-remove-sign', 'button type="submit" name="delsel" value="'.$nounce.'"');
        }
    echo '<br class="clear" />';
    echo '</div>';

?>