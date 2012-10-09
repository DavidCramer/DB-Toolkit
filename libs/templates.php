<div id="layoutTemplateArea">

    <div id="templateTabs" class="dbtools_tabs">

        <div id="listTemplate" class="setupTab">





            <div id="layoutContentTemplate">

                <div id="layoutHeaderTemplate">
                    <?php
                    $Sel = '';
                    if (!empty($Config['_useListTemplate'])) {
                        $Sel = 'checked="checked"';
                    }
                    echo dbt_configOption('useListTemplate', 'useListTemplate', 'checkbox', 'Use List Template', $Config);


                    $wapperEl = '';
                    if (!empty($Config['_TemplateWrapper'])) {
                        $wapperEl = $Config['_TemplateWrapper'];
                    }
                    $wapperClass = '';
                    if (!empty($Config['_TemplateClass'])) {
                        $wapperClass = $Config['_TemplateClass'];
                    }
                    echo dbt_configOption('TemplateWrapper', 'TemplateWrapper', 'textfield', 'Wrapper Element', $Config);
                    echo dbt_configOption('TemplateClass', 'TemplateClass', 'textfield', 'Wrapper Class', $Config);

                    $HeaderTemplate = '';

                    if (!empty($Config['_layoutTemplate']['_Header'])) {
                        $HeaderTemplate = $Config['_layoutTemplate']['_Header'];
                    }
                    ?>
                    <h2>Header Template <span class="description">Placed before the interface is rendered.</span></h2>
                    <textarea class="headerFooterTemplate" name="data[_layoutTemplate][_Header]"><?php echo $HeaderTemplate; ?></textarea>
                    <!-- Header Template Area -->
                </div>

                <div class="itemField">
                    <div class="dbt-elementItem">
                        <span class="fbutton">
                            <a href="" class="button" onclick="addRowTemplate(); return false;"><i class="icon-plus"></i> Add Row Template</a>
                        </span>
                    </div>
                </div>
                <div style="clear:both;"><br /></div>



                <div class="rowTemplateHolder" id="rowTemplateHolder">
                    <?php
                    if (empty($Config['_layoutTemplate']['_Content'])) {
                        echo dbt_addListRowTemplate();
                    } else {
                        $TemplateTotal = count($Config['_layoutTemplate']['_Content']['_name']) - 1;
                        for ($T = 0; $T <= $TemplateTotal; $T++) {
                            $Defaults = array(
                                '_name' => $Config['_layoutTemplate']['_Content']['_name'][$T],
                                '_before' => $Config['_layoutTemplate']['_Content']['_before'][$T],
                                '_content' => $Config['_layoutTemplate']['_Content']['_content'][$T],
                                '_after' => $Config['_layoutTemplate']['_Content']['_after'][$T],
                            );

                            echo dbt_addListRowTemplate($Defaults);
                        }
                    }
                    $rowTemplateID = uniqid('Template');
                    ?>
                </div>


                <div id="layoutFooterTemplate">
                    <h2>Footer Template <span class="description">Placed before the interface is rendered.</span></h2>

                    <?php
                    $FooterTemplate = '';
                    if (!empty($Config['_layoutTemplate']['_Footer'])) {
                        $FooterTemplate = $Config['_layoutTemplate']['_Footer'];
                    }
                    ?>
                    <textarea class="headerFooterTemplate" name="data[_layoutTemplate][_Footer]"><?php echo $FooterTemplate; ?></textarea>
                    <!-- Footer Template Area -->
                </div>

                <div id="noResultsTemplate">
                    <h2>No Results Template <span class="description">This is displayed if there are no entries to display.</span></h2>

                    <?php
                    $noResultsTemplate = '';
                    if (!empty($Config['_layoutTemplate']['_noResults'])) {
                        $noResultsTemplate = $Config['_layoutTemplate']['_noResults'];
                    }
                    ?>
                    <textarea class="headerFooterTemplate" name="data[_layoutTemplate][_noResults]"><?php echo $noResultsTemplate; ?></textarea>
                    <!-- Footer Template Area -->
                </div>



            </div>




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
                      if(!empty($Config['_FieldTitle'])){
                      foreach($Config['_FieldTitle'] as $FieldKey=>$Val){
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

        jQuery(".rowTemplateHolder").sortable({

            placeholder: 'sortable-placeholder',
            forcePlaceholderSize: true,
            connectWith: '.fieldsTemplateHolder',
            stop: function(p){
                //alert(columns);
            }

        });

    });
    
    function addRowTemplate(){
        dbt_ajaxCall('dbt_addListRowTemplate', function(o){
            jQuery('#rowTemplateHolder').append(o);
            jQuery(".rowTemplateHolder").sortable({

                placeholder: 'sortable-placeholder',
                forcePlaceholderSize: true,
                connectWith: '.fieldsTemplateHolder',
                stop: function(p){
                    //alert(columns);
                }

            });

        });
    }


</script>