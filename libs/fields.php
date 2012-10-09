<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
echo dbt_configOption('data_source', 'data_source', 'dropdown', 'Data Source|Select a data source;null,Internal;internal,Interface;interface,Database Table;table,API (OAuth 2 Based);api,Remote Database;remote', $Config);
?>
<span class="description">Please note: Data source is locked to Table for testing.</span>
<div id="dataSourceInternal" class="hidden">
    <input type="text" value="" id="intenalDB" name="data[_internalDB]" />
</div>
<div id="dataSourceTable">
    <?php
    echo dbt_configOption('main_table', 'main_table', 'custom', 'Database Table', $Config, false, 'dbt_tableSelector');
    ?>
</div>
<div id="dataSourceNewTable" class="hidden">
    <div class="dbt_configOption">
    <div class="dbt_configTitle">New Table Name</div>
        <div class="dbt_configField"><input type="textfield" id="newTableName" /> <input type="button" value="Create Table" id="createNewTableCommit"></div>
    </div><br />
</div>
<div class="itemField">
    <div class="dbt-elementItem" id="helperBar">
        <span class="fbutton" id="addNewFieldPrompt"><button type="button" class="button">Add Field</button></span> <div id="newFieldNameBox" class="hidden" style="padding: 5px;"><input type="textfield" id="newFieldName" /> <input type="button" value="Add Field" id="addNewField"> <input type="button" value="Close" id="cancelNewField"></div>
    </div>
    <div class="dbt-elementItem small" id="helperBar">
        <div class="dbt-elementInfoPanel">
            <div class="title">Table Field</div>
        </div>
        <div class="dbt-elementInfoPanel mid">
            <div class="title">Handler</div>
        </div>
        <div class="dbt-elementInfoPanel last">
            <div class="title">Visibility & Interaction</div>
        </div>
        <div class="dbt-elementInfoPanel primary">
            <div class="title">Primary</div>
        </div>
    </div>
</div>
<div id="fieldsTray">
    <?php    
    echo dbt_loadInterfaceFields($Config);
    ?>   
</div>
<div class="clear"></div>
<h2>Sorting</h2>
<span class="description">Sets the default sorting for the interface results</span>
<div class="clear"></div>
<div class="fbutton">
    <a id="addSortingField" class="button">
        <span class="icon-plus"></span> Add Sorting Field
    </a>
</div>
<div class="sortingTray clear" id="sortingTray">
    <?php
    if (!empty($Config['_sorting']['Field'])) {
        foreach ($Config['_sorting']['Field'] as $Key => $Field) {
            echo dbt_addSortingField($Config['_main_table'], $Config, $Key);
        }
    }
    ?>
</div>
<?php
//vardump($Config);
?>
<script>

    function reloadTableSelection(){
        if(jQuery('#_main_table').val() != '__unconfigured__'){
            jQuery('#dataSourceNewTable').fadeOut().find('#newTableName').val('');
            jQuery('#formFields').html('');
            jQuery('.formFieldElement').remove();
            jQuery('#fieldsTray').html('Loading Fields...');
            jQuery('#sortingTray').html('');
            dbt_ajaxCall('dbt_setupTable', this.value, function(o){
                jQuery('#fieldsTray').html(o.html);
                dbt_ajaxCall('dbt_addSortingField', jQuery('#_main_table').val(), function(o){
                    jQuery('#sortingTray').append(o);
                })
                eval(o.script);
            });
        }
    }

    jQuery(document).ready(function(){
    
        jQuery('#dataSourceTable').on('change', '#_main_table', reloadTableSelection);
        jQuery('#createNewTable').click(function(){jQuery('#dataSourceNewTable').fadeIn().find('#newTableName').focus()})
        jQuery('#cancelNewField').click(function(){jQuery('#newFieldNameBox').fadeOut().find('#newFieldName').val('')})
        jQuery('#addNewFieldPrompt').click(function(){
            if(jQuery('#_main_table').val() == '__unconfigured__'){
                alert('You need to select a table or crate a new one first.');
            }else{            
                jQuery('#newFieldNameBox').fadeIn().find('#newFieldName').focus()
            }
        })
        jQuery('#addNewField').click(function(){            
            if(jQuery('#newFieldName').val().length > 1){
                dbt_ajaxCall('dbt_buildNewFieldSetup', jQuery('#newFieldName').val(), function(o){
                    jQuery('#fieldsTray').append(o.html);
                    jQuery('#newFieldName').val('').focus();
                    eval(o.script);
                })
            }
        })
        jQuery( "#fieldsTray" ).sortable({placeholder: "dbt-elementHelper"});
        jQuery('#addSortingField').click(function(){
            if(jQuery('#_main_table').val() == '__unconfigured__'){
                alert('Please select a table first.');
            }else{
                dbt_ajaxCall('dbt_addSortingField', jQuery('#_main_table').val(), function(o){jQuery('#sortingTray').append(o);})
            }
        });
        
        jQuery('#createNewTableCommit').click(function(){
            
            var newName = jQuery('#newTableName').val();
            dbt_ajaxCall('dbt_createNewTable', newName, function(o){
                jQuery('#dataSourceTable .dbt_configField').html(o);
                jQuery('#_main_table').show(reloadTableSelection);
            })
            
        });
        jQuery('#wpbody-content').on('submit', '#interfaceEditForm', function(e){
            if(jQuery(this).find('[id^="displayTypeV"]:checked').length <= 0){
                e.preventDefault();
                alert('You have no fields visible. Please set at least 1 field visible before saving.');
            }
            
        })
    });

    function dbt_loadfile(filename, filetype){
        if (filetype=="js"){ //if filename is a external JavaScript file
            var fileref=document.createElement('script')
            fileref.setAttribute("type","text/javascript")
            fileref.setAttribute("src", filename)
        }
        else if (filetype=="css"){ //if filename is an external CSS file
            var fileref=document.createElement("link")
            fileref.setAttribute("rel", "stylesheet")
            fileref.setAttribute("type", "text/css")
            fileref.setAttribute("href", filename)
        }
        if (typeof fileref!="undefined")
            document.getElementsByTagName("head")[0].appendChild(fileref)
    }

</script>