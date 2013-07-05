<h2>Data Source (Experimental)</h2>
<div class="description">Supplying a data source allows for auto populating of data from an XML source. (JSON Coming Soon)</div><br />

    <?php
    if(empty($Element['Content']['_DataSourceURL']))
        $Element['Content']['_DataSourceURL'] = '';
    
        echo dais_customfield('text', 'Source URL', '_DataSourceURL', '_DataSourceURL', 'list_row1' , $Element['Content']['_DataSourceURL'], '','<input type="button" value="Invoke" onclick="dr_loadDataSource(jQuery(\'#_DataSourceURL\').val());" />');
    ?>
<h2>Field Mapping</h2>
<div id="_dataSourceView"><?php
    if(!empty($Element['Content']['_DataSourceURL'])){
        echo dr_dataSourceMapping($Element['Content']['_DataSourceURL'], $Element['Content']);
    }else{
?>
<div class="description">Source needs to be invoked before you can map fields.</div>
<?php
    }
?></div>
<h2>Data Source Call</h2>
<input type="text" value="<?php echo get_bloginfo('url').'/'.$Element['ID'].'/import'; ?>" style="width: 98%;" />
<div class="description">This URL will run the import. Add it to a cron task at a scheduled interval to periodically import new data.</div><br />