<h2>Templates</h2>
<div id="layoutTemplateArea">
            <?php
            ?>

        <div id="templateTabs" class="dbtools_tabs">
            <ul class="content-box-tabs">
                <li><a href="#listTemplate">Row/Entry Template</a></li>
                <li><a href="#fieldTemplate">Field Template</a></li>
                <li><a href="#toolbarTemplate">Toolbar Template</a></li>
            </ul>
            <div id="listTemplate" class="setupTab">





                <div id="layoutContentTemplate">

                    <div id="layoutHeaderTemplate">
<?php
            $Sel = '';
            if (!empty($Element['Content']['_useListTemplate'])) {
                $Sel = 'checked="checked"';
            }
            echo dais_customfield('checkbox', 'Enable', '_useListTemplate', '_useListTemplate', 'list_row1', 1, $Sel, 'Set this interface to use custom list templates.');

            $wapperEl = 'div';
            if(!empty($Element['Content']['_TemplateWrapper'])){
                $wapperEl = $Element['Content']['_TemplateWrapper'];
            }
            $wapperClass = '';
            if(!empty($Element['Content']['_TemplateClass'])){
                $wapperClass = $Element['Content']['_TemplateClass'];
            }
            echo dais_customfield('text', 'Wrapper Element', '_TemplateWrapper', '_TemplateWrapper', 'list_row1', $wapperEl, '', 'Element tag that wrappes the interface.');
            echo dais_customfield('text', 'Wrapper Classes', '_TemplateClass', '_TemplateClass', 'list_row1', $wapperClass, '', 'Additional classes to add to the interface wrapper.');

            $HeaderTemplate = '';
            if (!empty($Element['Content']['_layoutTemplate']['_Header'])) {
                $HeaderTemplate = $Element['Content']['_layoutTemplate']['_Header'];
            }
?>
                        <h2>Header Template <span class="description">Placed before the interface is rendered.</span></h2>
                        <textarea class="headerFooterTemplate" name="Data[Content][_layoutTemplate][_Header]"><?php echo $HeaderTemplate; ?></textarea>
                        <!-- Header Template Area -->
                    </div>


                    <img align="absmiddle" style="float: right; padding: 5px; cursor: pointer;" onclick="jQuery('.row_helpPanel').toggle();" src="<?php echo WP_PLUGIN_URL; ?>/db-toolkit/images/help.png">
                    <ul class="tools_widgets">
                        <li class="root_item tabBox"><a class="parent" onclick="addRowTemplate();"><strong>Add Row Template</strong></a></li>
                    </ul>
                    <div style="clear:both;"></div>

                    <!-- Content Template Panel -->
                    <div style="display: none; background-color: #fff; border: 1px solid #666666;" class="admin_config_panel row_helpPanel">
                        <div><strong>I will reformat this into a proper help dialog when I finialise the codes.</strong></div>
                        <strong>Dynamic Template Codes</strong> <span class="description">Can be used in all template boxes.</span>
                        <pre>
{{_ViewProcessors}}     : View Processor
- can be multiple instanced. so careful when placing in the loop.
{{_ViewEdit}}           : View and Edit Icons
{{_ViewLink}}           : View Item Link
{{_ViewTarget}}         : javascript target interface call
{{_RowClass}}           : Row Class
{{_RowIndex}}           : Row Index
{{_UID}}                : Unique Row ID
{{_EID}}                : Element ID
{{<i><b>Fieldname</b></i>}}           : Field Data
{{_<i>Fieldname</i>_name}}     : Field Name
{{_return_<i><b>Fieldname</b></i>}}   : Return Field

{{<i>Fieldname</i>|<i>substr value</i> [, substring char count]}}
Formats substr(Value, 0, num)
if "," and second num is added:substr(Value, first num, second num)

{{<i>Fieldname</i>|<i>php formatting function</i>}}
Field Data | php formatting function eg: add_slashes, urlencode, htmlentities etc...

Field Keys:
                            <?php
                            if (!empty($Element['Content']['_FieldTitle'])) {
                                foreach ($Element['Content']['_FieldTitle'] as $FieldKey => $Val) {
                                    echo $Val . ' = {{' . $FieldKey . '}}<br />';
                                }
                            } else {
                                echo 'Save and edit to see available fields';
                            }
                            ?>
                        </pre>
                        <strong>Dynamic Footer Codes</strong> <span class="description">Can be used in BEFORE and AFTER code boxes.</span>
                        <pre>
{{_footer_first}}       : Jump to first page
{{_footer_prev}}        : Previous Page/Entries
{{_footer_next}}        : Next Page/Entries
{{_footer_last}}        : Jump to last page

{{_footer_pagination}}  : Build pagination index

{{_footer_pagecount}}   : Show page of count (3 of 5)

{{_footer_page_jump}}   :
Page Index Input Box (page __ of 20)

{{_footer_item_count}}  :
Number of items found and displayed (1 - 10 of 200 items)

                        </pre>
                        To enable selection and deleting:<br/>
                        id="row_{{_EID}}_{{_RowIndex}}"  ref="{{_return_<em><strong>Fieldname</strong></em>}} highlight" class="itemRow_{{_EID}}  report_entry"

                    </div>

                    <div class="rowTemplateHolder" id="rowTemplateHolder">
<?php
                            if (empty($Element['Content']['_layoutTemplate']['_Content'])) {
                                echo dr_addListRowTemplate();
                            } else {
                                $TemplateTotal = count($Element['Content']['_layoutTemplate']['_Content']['_name']) - 1;
                                for ($T = 0; $T <= $TemplateTotal; $T++) {
                                    $Defaults = array(
                                        '_name' => $Element['Content']['_layoutTemplate']['_Content']['_name'][$T],
                                        '_before' => $Element['Content']['_layoutTemplate']['_Content']['_before'][$T],
                                        '_content' => $Element['Content']['_layoutTemplate']['_Content']['_content'][$T],
                                        '_after' => $Element['Content']['_layoutTemplate']['_Content']['_after'][$T],
                                    );

                                    echo dr_addListRowTemplate($Defaults);
                                }
                            }
                            $rowTemplateID = uniqid('Template');
?>
                        </div>


                        <div id="layoutFooterTemplate">
                            <h2>Footer Template <span class="description">Placed before the interface is rendered.</span></h2>

<?php
                            $FooterTemplate = '';
                            if (!empty($Element['Content']['_layoutTemplate']['_Footer'])) {
                                $FooterTemplate = $Element['Content']['_layoutTemplate']['_Footer'];
                            }
?>
                            <textarea class="headerFooterTemplate" name="Data[Content][_layoutTemplate][_Footer]"><?php echo $FooterTemplate; ?></textarea>
                            <!-- Footer Template Area -->
                        </div>



                    </div>




                </div>
                <div id="fieldTemplate" class="setupTab">
                    <h2>Field Templates</h2>
                <span class="description">The Field template wraps each field value is your custom code. These are always on. so this applies to both list templates and default list view.</span>
                <br />
                <br />
                    <div id="layoutFieldTemplate">
                        <ul class="tools_widgets">
                            <li class="root_item tabBox"><a class="parent hasSubs"><strong>Fields</strong></a>
                                <ul id="" style="visibility: hidden; display: block;">
<?php
                            //echo df_listProcessors();
                            if (!empty($Element['Content']['_FieldTitle'])) {
                                foreach ($Element['Content']['_FieldTitle'] as $FieldKey => $Val) {
                                    echo '<li><a onclick="dr_addListFieldTemplate(\'' . $FieldKey . '\');"><img align="absmiddle" src="' . WP_PLUGIN_URL . '/db-toolkit/data_report/arrow_switch.png"> ' . $Val . '</a></li>';
                                }
                            }
?>
                            </ul>
                        </li>
                    </ul>
                    <div style="clear:both;"></div>
                    <!-- Fields Template Panel -->

                    <div id="fieldTemplateHolder">
                        <?php
                            // Echo out Field Templates
                            if (!empty($Element['Content']['_layoutTemplate']['_Fields'])){

                                foreach($Element['Content']['_layoutTemplate']['_Fields'] as $Field=>$Defaults){
                                    echo dr_addListFieldTemplate($Field, $Defaults);
                                }

                            }


                        ?>
                    </div>


                </div>

            </div>
                <div id="toolbarTemplate" class="setupTab">

                <h2>Toolbar Template</h2>
                <span class="description">Allows you to customise the toolbar.</span>
                <br />
                <div class="row_helpPanel" style="display:none;">
                    <strong>Dynamic Toolbar Codes</strong>
                    <span class="description">All the <strong>button</strong> codes, can be used in the row/entry templates as well.</span>
                    <pre>
{{_button_addItem}}             :Add Item Button
{{_button_import}}              :Import from CSV button
{{_button_toggleFilters}}       :Show Filter Panel Toggle button
{{_button_reload}}              :Content reload button
{{_button_selectAll}}           :Select all button
{{_button_unselect}}            :Unselect button
{{_button_deleteSelected}}      :Delete Selected items button
{{_button_export_pdf}}          :PDF Export button
{{_button_export_csv}}          :CSV Export button

                    </pre>

                </div>
                <span class="description">All buttons have a float:right style associated unless using a custom stylesheet in your theme. in which case you control it.</span>
                <span class="description">This was bad on my side, but will correct it soon. So be sure to clear:left within your styling, for now.</span>
                <br /><br />
                <span class="description">If a button is disabled in general settings, it wont be rendered.</span>
                <br /><br />
                <img align="absmiddle" style="float: right; padding: 5px; cursor: pointer;" onclick="jQuery('.row_helpPanel').toggle();" src="<?php echo WP_PLUGIN_URL; ?>/db-toolkit/images/help.png">
                <?php

                $Sel = '';

                if (!empty($Element['Content']['_useToolbarTemplate'])) {
                    $Sel = 'checked="checked"';
                }
                echo dais_customfield('checkbox', 'Enable', '_useToolbarTemplate', '_useToolbarTemplate', 'list_row1', 1, $Sel, 'Set this interface to use the custom toolbar templates.');


                $ToolbarTemplate = '';
                    if (!empty($Element['Content']['_layoutTemplate']['_Toolbar'])) {
                        $ToolbarTemplate = $Element['Content']['_layoutTemplate']['_Toolbar'];
                    }
                ?>
                <textarea class="headerFooterTemplate" name="Data[Content][_layoutTemplate][_Toolbar]"><?php echo $ToolbarTemplate; ?></textarea>

            </div>
        </div>


    </div>


<?php
                            /*



                              <pre>
                              {{_ViewEdit}}	: View and Edit Icons
                              {{_ViewLink}}	: View Item Link
                              {{_RowClass}}	: Row Class
                              {{_RowIndex}}	: Row Index
                              {{_UID}}	: Unique Row ID
                              {{_PageID}}	: Page ID
                              {{_PageName}}	: Page Name
                              {{_EID}}	: Element ID
                              {{<i><b>Fieldname</b></i>}}	: Field Data
                              {{_<i>Fieldname</i>_name}}	: Field Name
                              {{_return_<i><b>Fieldname</b></i>}}	: Return Field
                              {{<i>Fieldname</i>|<i>substr value</i>}}	: Field Data | substring value

                              Field Keys:
                              <?php
                              if(!empty($Element['Content']['_FieldTitle'])){
                              foreach($Element['Content']['_FieldTitle'] as $FieldKey=>$Val){
                              echo $Val.' = {{'.$FieldKey.'}}<br />';
                              }
                              }else{
                              echo 'Save and edit to see available fields';
                              }
                              ?>

                              </pre>
                              to enable selection and deleting:
                              id="row_{{_EID}}_{{_RowIndex}}"  ref="{{_return_<em><strong>Fieldname</strong></em>}} highlight" class="itemRow_{{_EID}}  report_entry"

                              {{_footer_prev}}        : Previous Page/Entries
                              {{_footer_next}}        : Next Page/Entries
                              {{_footer_page_jump}}   : Page Index Input Box (page __ of 20)
                              {{_footer_item_count}}  : Number of items found and displayed (1 - 10 of 200 items)
                              {{_footer_no_entries}}  : No results




                             */
?>
<script>
    jQuery(function() {
        jQuery("#templateTabs").tabs();
        jQuery(".rowTemplateHolder").sortable({

            placeholder: 'sortable-placeholder',
            forcePlaceholderSize: true,
            connectWith: '.fieldsTemplateHolder',
            stop: function(p){
                //alert(columns);
            }

        });
    });


</script>