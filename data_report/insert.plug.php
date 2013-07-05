<style type="text/css">
	.column { width: 170px; float: left; padding-bottom: 100px; }
	.formportlet, .viewportlet { margin: 3px; }
	.formportlet-header, .viewportlet-header { margin: 0.3em; padding: 5px; padding-left: 0.2em; }
	.formportlet-header .ui-icon, .viewportlet-header .ui-icon { float: right; }
	.formportlet-content, .viewportlet-content { padding: 0.4em; }
	.ui-sortable-placeholder { border: 1px dotted black; visibility: visible !important; height: 50px !important; }
	.ui-sortable-placeholder * { visibility: hidden; }
	</style>
<img src="'.WP_PLUGIN_DIR.'/db-toolkit/images/indicator.gif" width="16" height="16" alt="loading" align="absmiddle" style="display:none" />
<img src="'.WP_PLUGIN_DIR.'/db-toolkit/data_report/arrow_out.png" width="16" height="16" alt="loading" align="absmiddle" style="display:none" />
<img src="'.WP_PLUGIN_DIR.'/db-toolkit/data_report/tag.png" width="16" height="16" alt="loading" align="absmiddle" style="display:none" />
<input type="hidden" name="Data[Content][_FormLayout]" cols="50" rows="10" id="_FormLayout" />
<div id="tabs">
<ul class="content-box-tabs">
		<li><a href="#tabs-1">Field Setup</a></li>
		<li><a href="#tabs-2">Form Layout</a></li>
		<li><a href="#tabs-2b">View Layout</a></li>
		<li><a href="#tabs-3">Settings</a></li>
		<li><a href="#tabs-4">List Template</a></li>
	</ul>
	<div id="tabs-1">
		<?php
        $Doc = GetDocument($_SESSION['DocumentLoaded'], 2);
        echo dais_customfield('text', 'Interface Title', '_ReportTitle', '_ReportTitle', 'list_row1' , $_POST['dt_newInterface'] , '');
        echo dais_customfield('text', 'Interface Description', '_ReportDescription', '_ReportDescription', 'list_row2' , '' , '');
        echo dais_customfield('checkbox', 'Set as Menu Item', '_SetMenuItem', '_SetMenuItem', 'list_row1' , 1 , '');
        InfoBox('Table Selection');
        ?>
        <?php
        echo df_listTables('_main_table', 'dr_fetchPrimSetup');
        EndInfoBox();
        ?>
        <div id="col-container">
        <?php
        InfoBox('Report Setup');
        /*
        <div id="col-right">

                <?php InfoBox('Advanced Field Types'); ?>
                <div class="list_row3">
                    Re-edit this interface to enable advanced field options.
                </div>
                <?php EndInfoBox(); ?>

        </div>
         *
         */
        ?>
        <div id="col-left">
        <div id="referenceSetup"></div>
        <table width="500px" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td valign="top" class="columnSorter" id="FieldList_Main">Select a table to setup</td>
          </tr>
        </table>
        </div>
        <?php
        EndInfoBox();
        ?>
        </div>
        <?php
        InfoBox('General Settings');
        echo '<div style="padding3px;"><input type="button" name="button" id="button" value="Add Totals Field" onclick="dr_addTotalsField();"/></div>';
        echo '<div id="totalsListStatus">Select table to setup</div>';
        echo '<div id="totalsList"></div>';
        EndInfoBox();
        InfoBox('Passback Value');
        ?>
        <div style="padding3px;"><input type="button" name="button" id="button" value="Add Passback Field" onclick="dr_addPassbackField();"/> (First one is primary)</div>
        <div id="PassBack_FieldSelect"></div>
        <?php
        EndInfobox();
        InfoBox('Sort Field');
        ?>
        <div id="sortFieldSelect">Select a table to setup</div>
        <?php
        EndInfobox();
        ?>
  </div>
<?php

include(WP_PLUGIN_DIR.'/db-toolkit/data_report/formlayout.php');
include(WP_PLUGIN_DIR.'/db-toolkit/data_report/viewlayout.php');

?>
	<div id="tabs-3">
    <table width="100%" border="0" cellspacing="2" cellpadding="2">
      <tr>
        <td width="50%" valign="top">
			<?php
            InfoBox('General Settings');
                  echo dais_customfield('checkbox', 'View Mode', '_ViewMode', '_ViewMode', 'list_row2' , 1 , '');
                  echo dais_customfield('checkbox', 'Form Mode', '_FormMode', '_FormMode', 'list_row1' , 1 , '');
                  echo dais_customfield('checkbox', 'Search Mode', '_SearchMode', '_SearchMode', 'list_row2' , 1 , '');
                  echo dais_customfield('checkbox', 'Hide Frame', '_HideFrame', '_HideFrame', 'list_row1' , 1 , '');
                  echo dais_customfield('text', 'New Item Title', '_New_Item_Title', '_New_Item_Title', 'list_row2' , 'Create new Entry', '');
                  echo dais_customfield('checkbox', 'Hide new item button', '_New_Item_Hide', '_New_Item_Hide', 'list_row1' , 1, $Sel);
                  echo dais_customfield('text', 'Items Per Page', '_Items_Per_Page', '_Items_Per_Page', 'list_row2' , 20 , '');
			EndInfoBox();
            InfoBox('Tool Bar Settings');
			
                  echo dais_customfield('checkbox', 'Hide Tool Bar', '_Hide_Toolbar', '_Hide_Toolbar', 'list_row1' , 1 , '');
                  echo dais_customfield('checkbox', 'Show Filters', '_Show_Filters', '_Show_Filters', 'list_row1' , 1 , 'checked="checked"');
                  echo dais_customfield('checkbox', 'Autohide Filters', '_toggle_Filters', '_toggle_Filters', 'list_row2' , 1 , '');
                  echo dais_customfield('checkbox', 'Show Keyword Filter', '_Show_KeywordFilters', '_Show_Filters', 'list_row1' , 1 , 'checked="checked"');
                  echo dais_customfield('text', 'Keyword Search Title', '_Keyword_Title', '_Keyword_Title', 'list_row2' , 'Keywords' , '');
		          echo dais_customfield('checkbox', 'Show Reload Button', '_showReload', '_showReload', 'list_row1' , 1 , 'checked="checked"');
                  echo dais_customfield('checkbox', 'Show Export Button', '_Show_Export', '_Show_Export', 'list_row1' , 1 , 'checked="checked"');
                  echo dais_customfield('checkbox', 'Show Plugins', '_Show_Plugins', '_Show_Plugins', 'list_row2' , 1 , 'checked="checked"');
                  echo '<div style="padding:3px;" class="list_row1"><strong>Export Orientation: </strong>';
                    echo '<select name="Data[Content][_orientation]" >';
                        echo '<option value="P">Portrait</option>';
                        echo '<option value="L">Landscape</option>';
                    echo '</select>';
                  echo '</div>';
                  echo dais_customfield('checkbox', 'Show Select Options', '_Show_Select', '_Show_Select', 'list_row2' , 1 , 'checked="checked"');
                  echo dais_customfield('checkbox', 'Show Delete Options', '_Show_Delete', '_Show_Delete', 'list_row1' , 1, $Sel);
			EndInfoBox();
            InfoBox('List Settings');
                  echo dais_customfield('checkbox', 'Show and Enable Edit Action', '_Show_Edit', '_Show_Edit', 'list_row2' , 1 , 'checked="checked"');
                  echo dais_customfield('checkbox', 'Show View Action', '_Show_View', '_Show_View', 'list_row2' , 1 , 'checked="checked"');
                  echo dais_customfield('checkbox', 'Show Delete Action', '_Show_Delete_action', '_Show_Delete_action', 'list_row1' , 1, "");
                  echo dais_customfield('checkbox', 'Popup Links', '_Show_popup', '_Show_popup', 'list_row1' , 1 , '');
                  echo dais_customfield('checkbox', 'Show Footer', '_Show_Footer', '_Show_Footer', 'list_row2' , 1 , 'checked="checked"');
			EndInfoBox();
            InfoBox('Notification & Buttons');
                  echo dais_customfield('text', 'Insert Success Text', '_InsertSuccess', '_InsertSuccess', 'list_row1' , 'Entry inserted successfully', '');
                  echo dais_customfield('text', 'Update Success Text', '_UpdateSuccess', '_UpdateSuccess', 'list_row2' , 'Entry updated successfully', '');
                  echo dais_customfield('text', 'Insert Fail Text', '_InsertFail', '_InsertFail', 'list_row1' , 'Could not insert entry', '');
                  echo dais_customfield('text', 'Update Fail Text', '_UpdateFail', '_UpdateFail', 'list_row2' , 'Could not update entry', '');
                  echo dais_customfield('text', 'Submit Button Text', '_SubmitButtonText', '_SubmitButtonText', 'list_row1' , 'Submit', '');
                  echo dais_customfield('text', 'Update Button Text', '_UpdateButtonText', '_UpdateButtonText', 'list_row2' , 'Submit', '');
                  echo dais_customfield('text', 'Edit Form Title', '_EditFormText', '_EditFormText', 'list_row1' , 'Edit Entry', '');
				  echo dais_customfield('text', 'View Form Title', '_ViewFormText', '_ViewFormText', 'list_row1' , 'View Entry', '');
				  echo dais_customfield('text', 'No results text', '_NoResultsText', '_NoResultsText', 'list_row1' , 'Nothing Found', '');
                  echo dais_customfield('checkbox', 'Disable Notifications', '_NotificationsOff', '_NotificationsOff', 'list_row2' , 1, '');
                  echo dais_customfield('checkbox', 'Show Reset Button', '_ShowReset', '_ShowReset', 'list_row1' , 1, 'checked="checked"');
                  echo '<div style="padding:3px;" class="list_row1"><strong>Button Alignment: </strong>';
                    echo '<select name="Data[Content][_SubmitAlignment]" >';
                        echo '<option value="left">Left</option>';
                        echo '<option value="center">Center</option>';
                        echo '<option value="right">Right</option>';
                    echo '</select>';
                  echo '</div>';
			EndInfoBox();
            InfoBox('API and Audit');		  
                  echo dais_customfield('checkbox', 'Enable Auditing', '_EnableAudit', '_EnableAudit', 'list_row2' , 1, 'checked="checked"');
                  echo dais_customfield('text', 'API Key Seed', '_APISeed', '_APISeed', 'list_row1' , md5(rand(0,9999)), '');
            EndInfoBox();
            ?>
          <td width="50%" valign="top">
              <?php
              EndInfoBox();
                  InfoBox('Item View Page');
                  echo dais_customfield('radio', 'None', '_ItemViewPage', '_ItemViewPage', 'list_row2' , false, 'checked="checked"');
                  echo dais_page_selector('s', false, false, '_ItemViewPage');
              EndInfoBox();


              ?>
          </td>            
                </td>
        <td width="50%" valign="top">
        </td>
      </tr>
    </table>
  </div>
  <div id="tabs-4">
  <?php
		$Sel = '';
		if(!empty($Element['Content']['_UseListViewTemplate'])){
			$Sel = 'checked="checked"';	
		}
        echo dais_customfield('checkbox', 'Use Template', '_UseListViewTemplate', '_UseListViewTemplate', 'list_row1' , 1, '');
		
        echo dais_customfield('textarea', 'Pre Header', '_ListViewTemplatePreHeader', '_ListViewTemplatePreHeader', 'list_row2' , '', '');
        echo dais_customfield('textarea', 'Header', '_ListViewTemplateHeader', '_ListViewTemplateHeader', 'list_row2' , '', '');
        echo dais_customfield('textarea', 'Post Header', '_ListViewTemplatePostHeader', '_ListViewTemplatePostHeader', 'list_row2' , '', '');
		
        echo dais_customfield('textarea', 'Content Wrapper Start', '_ListViewTemplateContentWrapperStart', '_ListViewTemplateContentWrapperStart', 'list_row2' , '', '');
        echo dais_customfield('textarea', 'PreContent', '_ListViewTemplatePreContent', '_ListViewTemplatePreContent', 'list_row2' , '', '');
        echo dais_customfield('textarea', 'Content', '_ListViewTemplateContent', '_ListViewTemplateContent', 'list_row2' , '', '');
		InfoBox('Useable Keys');
		?>
<pre>
{{_ViewEdit}}	: View and Edit Icons
{{_ViewLink}}	: View Item Link
{{_RowClass}}	: Row Class
{{_RowIndex}}	: Row Index
{{_UID}}	: Unique Row ID
{{_PageID}}	: Page ID
{{_PageName}}	: Page Name
{{_EID}}	: Element ID
{{<i>Fieldname</i>}}	: Field Data
{{<i>Fieldname</i>|<i>substr value</i>}}	: Field Data | substring value
{{_<i>Fieldname</i>_name}}	: Field Name
{{_return_<i><b>Fieldname</b></i>}}	: Return Field
</pre>
to enable selection and deleting:
id="row_{{_EID}}_{{_RowIndex}}"  ref="{{_return_<em><strong>Fieldname</strong></em>}} highlight" class="itemRow_{{_EID}}  report_entry"

		<?php
		EndInfoBox();
        echo dais_customfield('textarea', 'PostContent', '_ListViewTemplatePostContent', '_ListViewTemplatePostContent', 'list_row2' , '', '');
        echo dais_customfield('textarea', 'Content Wrapper End', '_ListViewTemplateContentWrapperEnd', '_ListViewTemplateContentWrapperEnd', 'list_row2' , '', '');
		
        echo dais_customfield('textarea', 'Pre Footer', '_ListViewTemplatePreFooter', '_ListViewTemplatePreFooter', 'list_row2' , '', '');
        echo dais_customfield('textarea', 'Footer', '_ListViewTemplateFooter', '_ListViewTemplateFooter', 'list_row2' , '', '');
  InfoBox('Footer Codes');

  ?>
<pre>
{{_footer_prev}}        : Previous Page/Entries
{{_footer_next}}        : Next Page/Entries
{{_footer_page_jump}}   : Page Index Input Box (page __ of 20)
{{_footer_item_count}}  : Number of items found and displayed (1 - 10 of 200 items)
{{_footer_no_entries}}  : No results

</pre>
<?php
EndInfoBox();
  echo dais_customfield('textarea', 'Post Footer', '_ListViewTemplatePostFooter', '_ListViewTemplatePostFooter', 'list_row2' , '', '');

  
  
  ?>
  </div>
</div>
<?php
	echo dais_standardElementConfig(false, false);
	echo dais_standardinsertbuttons($Plugin);


ob_start();
?>

jQuery(document).ready(function($) {
		jQuery("#tabs").tabs();
		jQuery('select').live('change', function(){
			if(this.value == 'index_hide' || this.value == 'noindex_hide'){
				jQuery(this).parent().parent().fadeTo(500, 0.6);
			}
			if(this.value == 'index_show' || this.value == 'noindex_show'){
				jQuery(this).parent().parent().fadeTo(500, 1);				
			}
		});
				
	});

<?php
$_SESSION['dataform']['OutScripts'] .= ob_get_clean();
?>