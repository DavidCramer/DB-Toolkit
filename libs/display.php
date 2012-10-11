<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
    echo dbt_configOption('Items_Per_Page', 'Items_Per_Page', 'textfield', 'Items per Page', $Config);
    echo dbt_configOption('autoPolling', 'autoPolling', 'textfield', 'Auto polling/refresh', $Config);

?>
<h2>Toolbar Buttons</h2>
<div><span class="description">Configure the toolbar</span></div>
<?php
    echo dbt_configOption('enableToolbar', 'enableToolbar', 'checkbox', 'Enable Toolbar', $Config);
    echo dbt_configOption('addItem', 'addItem', 'checkbox', 'Allow Adding Items', $Config);
?>
<div class="dbt_configOption">
    <div class="dbt_configTitle">Add Item Template</div>
    <div class="dbt_configField">
        <select name="data[_addTemplate]" id="addTemplate">
        <?php
        $sel = '';
        if(empty($Config['_addTemplate'])){
            $Config['_addTemplate'] = '__interface__';
            $sel = 'selected="selected"';
        }
        echo '<option value="__interface__" '.$sel.'>Interface Default</option>';
        $sel = '';
        if($Config['_addTemplate'] == '__app__'){
            $sel = 'selected="selected"';
        }
        echo '<option value="__app__" '.$sel.'>App Default</option>';
        $sel = '';
        if(!empty($Config['_addTemplate'])){
            if($Config['_addTemplate'] == 'default'){
                $sel = 'selected="selected"';
            }
        }
        echo '<option value="default" '.$sel.'>Theme Default</option>';

        page_template_dropdown($Config['_addTemplate']);

        ?>
        </select>
    </div>
    <div class="clear"></div>
</div>
<?php
    echo dbt_configOption('showReloader', 'showReloader', 'checkbox', 'Show Reloader', $Config);
    echo dbt_configOption('showImporter', 'showImporter', 'checkbox', 'Show Importer', $Config);
    echo dbt_configOption('showSelect', 'showSelect', 'checkbox', 'Show Select All', $Config);
    echo dbt_configOption('showDeleteAll', 'showDeleteAll', 'checkbox', 'Show Delete Selected', $Config);
    //echo dbt_configOption('showReset', 'showReset', 'checkbox', 'Show Reset Button', $Config);
    //echo dbt_configOption('buttonAlignment', 'buttonAlignment', 'radio', 'Form Button Alignment|left,right', $Config);
?>
<h2>Filters</h2>
<div><span class="description">Setup how filters are displayed.</span></div>
<?php
    echo dbt_configOption('enableFilters', 'enableFilters', 'checkbox', 'Enable Filters', $Config);
    echo dbt_configOption('ajaxFilters', 'ajaxFilters', 'checkbox', 'Ajax Filters', $Config);
    echo dbt_configOption('showFilterLock', 'showFilterLock', 'checkbox', 'Show Filter Lock', $Config);
    echo dbt_configOption('autoHideFilters', 'autoHideFilters', 'checkbox', 'Toggle Filter Panel', $Config);
    echo dbt_configOption('showKeywordSearch', 'showKeywordSearch', 'checkbox', 'Show Keyword Search', $Config);
    echo dbt_configOption('keywordSearchLabel', 'keywordSearchLabel', 'textfield', 'Keyword Search Label', $Config);
?>
<h2>List Settings</h2>
<div><span class="description">Setup how the default list is displayed.</span></div>
<?php
    echo dbt_configOption('showEdit', 'showEdit', 'checkbox', 'Show Edit Entry', $Config);
?>
<div class="dbt_configOption">
    <div class="dbt_configTitle">Edit Template</div>
    <div class="dbt_configField">
        <select name="data[_editTemplate]" id="editTemplate">
        <?php
        $sel = '';
        if(empty($Config['_editTemplate'])){
            $Config['_editTemplate'] = '__interface__';
            $sel = 'selected="selected"';
        }
        echo '<option value="__interface__" '.$sel.'>Interface Default</option>';
        $sel = '';
        if($Config['_editTemplate'] == '__app__'){
            $sel = 'selected="selected"';
        }
        echo '<option value="__app__" '.$sel.'>App Default</option>';
        $sel = '';
        if(!empty($Config['_editTemplate'])){
            if($Config['_editTemplate'] == 'default'){
                $sel = 'selected="selected"';
            }
        }
        echo '<option value="default" '.$sel.'>Theme Default</option>';

        page_template_dropdown($Config['_editTemplate']);

        ?>
        </select>
    </div>
    <div class="clear"></div>
</div>
<?php
    echo dbt_configOption('showView', 'showView', 'checkbox', 'Show View Entry', $Config);
?>
<div class="dbt_configOption">
    <div class="dbt_configTitle">View Template</div>
    <div class="dbt_configField">
        <select name="data[_viewTemplate]" id="baseTemplate">
        <?php
        $sel = '';
        if(empty($Config['_viewTemplate'])){
            $Config['_viewTemplate'] = '__interface__';
            $sel = 'selected="selected"';
        }
        echo '<option value="__interface__" '.$sel.'>Interface Default</option>';
        $sel = '';
        if($Config['_viewTemplate'] == '__app__'){
            $sel = 'selected="selected"';
        }
        echo '<option value="__app__" '.$sel.'>App Default</option>';
        $sel = '';
        if(!empty($Config['_viewTemplate'])){
            if($Config['_viewTemplate'] == 'default'){
                $sel = 'selected="selected"';
            }
        }
        echo '<option value="default" '.$sel.'>Theme Default</option>';

        page_template_dropdown($Config['_viewTemplate']);

        ?>
        </select>
    </div>
    <div class="clear"></div>
</div>
<?php
    echo dbt_configOption('showDelete', 'showDelete', 'checkbox', 'Show Delete Entry', $Config);
    echo dbt_configOption('showInline', 'showInline', 'checkbox', 'Show Inline Actions', $Config);
    echo dbt_configOption('showFooter', 'showFooter', 'checkbox', 'Show Footer Bar', $Config);
?>
<h2>Styling</h2>
<div><span class="description">Setup the basic CSS Class styling</span></div>
<?php
    echo dbt_configOption('includeBootstrap', 'includeBootstrap', 'checkbox', 'Include front end StyleSheets', $Config, 'Include Styles form Breadcrumbs and Pagination on frontend');
    echo dbt_configOption('submitClass', 'submitClass', 'textfield', 'Submit Button Class', $Config);
    echo dbt_configOption('updateClass', 'updateClass', 'textfield', 'Update Button Class', $Config);
    echo dbt_configOption('cancelClass', 'cancelClass', 'textfield', 'Cancel Button Class', $Config);
    echo dbt_configOption('listClass', 'listClass', 'textfield', 'Default List Table Class', $Config);
    echo dbt_configOption('formClass', 'formClass', 'textfield', 'Form Class', $Config);
    echo dbt_configOption('formActionClass', 'formActionClass', 'textfield', 'Form Action Class', $Config);
    echo dbt_configOption('toolBarClass', 'toolBarClass', 'textfield', 'Toolbar Class', $Config);
    echo dbt_configOption('filterBarClass', 'filterBarClass', 'textfield', 'Filters Bar Class', $Config);

?>