<h2>Page Bindings</h2>
<div class="description" id="redirectHelp">
    <p>By binding an interface to a page, you allow WordPress to load the interface rather than the page content.</p>
    <p>This makes it easy to create a distributed application that has front end pages that requires certain pages.</p>
    <p>If you wish to have more than one interface on a page, you'll need to use the shortcode on the page.</p>
</div>
<div id="redirectTabs" class="dbtools_tabs">
    <div class="setupTab" id="publicRedirect">
        <?php
        InfoBox('Page');
        $Sel = '';
        if(empty($Element['Content']['_ItemBoundPage'])) {
            $Sel = 'checked="checked"';
        }
        echo dais_customfield('radio', 'Not Bound', '_ItemBoundPage', '_ItemBoundPage', 'list_row1' , 0, $Sel);
        if(empty($Element['Content']['_ItemBoundPage']))
            $Element['Content']['_ItemBoundPage'] = '';
        $PageList = array();
        $PageList[] = $Element['Content']['_ItemBoundPage'];
        echo dais_page_selector('s', $PageList, false, '_ItemBoundPage');
        EndInfoBox();
        ?>
    </div>
    
</div>