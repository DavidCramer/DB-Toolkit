<div class="alignleft actions">
    Powered by <a href="http://dbtoolkit.co.za" target="_blank">DB-Toolkit</a> <span class="description"><?php echo dbt_getVersion(); ?></span>
</div>

<?php
        if(empty($pages)){
            $pages = 0;
        }

        echo "<div class=\"tablenav-pages\"><span class=\"displaying-num\">".$Count." ".$tense."</span>\n";
        if($pages > 1){
            echo "  <span class=\"pagination-links\">\n";
            echo "      <a href=\"admin.php?page=app_builder&action=render&interface=".$Config['_ID']."\" title=\"Go to the first page\" class=\"first-page ".$prevClass."\">&laquo;</a>\n";
            echo "      <a href=\"admin.php?page=app_builder&action=render&interface=".$Config['_ID']."&_npage=".$prevPage."\" title=\"Go to the previous page\" class=\"prev-page ".$prevClass."\">&lsaquo;</a>\n";
            echo "      <span class=\"paging-input\">\n";
            echo "          <input type=\"hidden\" value=\"".$_GET['page']."\" name=\"page\" >\n";
            echo "          <input type=\"hidden\" value=\"".$_GET['action']."\" name=\"action\" >\n";
            echo "          <input type=\"hidden\" value=\"".$Config['_ID']."\" name=\"interface\" >\n";
            echo "          <input type=\"text\" size=\"1\" value=\"".$currentPage."\" name=\"_npage\" title=\"Current page\" class=\"current-page\"> of <span class=\"total-pages\">".$pages."</span>\n";
            echo "      </span>\n";
            echo "      <a href=\"admin.php?page=app_builder&action=render&interface=".$Config['_ID']."&_npage=".$nextPage."\" title=\"Go to the next page\" class=\"next-page ".$nextClass."\">&rsaquo;</a>\n";
            echo "      <a href=\"admin.php?page=app_builder&action=render&interface=".$Config['_ID']."&_npage=".$lastPage."\" title=\"Go to the last page\" class=\"last-page ".$nextClass."\">&raquo;</a>\n";
            echo "  </span>\n";
        }
        echo "</div>\n";

?>