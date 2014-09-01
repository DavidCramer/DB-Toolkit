{{#if fields}}
{{#each fields}}
<div class="dbtoolkit-dataset-item" data-id="{{Field}}">
	<span class="dbtoolkit-group-config dashicons dashicons-edit dbtoolkit-trigger" data-before="dbt_show_dataset_config_panel" data-id="{{Field}}"></span>
	{{#is Key value="PRI"}}<span class="dbtoolkit-group-config dashicons dashicons-admin-network" title="Primary Key" style="cursor: default; color: rgb(255, 102, 0);"></span>{{/is}}
	<h4 class="{{Field}}"><span class="label_{{Field}}">{{Field}}</span> <small class="dbtoolkit-panel-caption slug_{{Field}}">{{Type}}</small></h4>
	<input class="dataset-group-id" name="fields[{{Field}}][Field]" type="hidden" value="{{Field}}">
	<input class="dataset-group-id" name="fields[{{Field}}][Type]" type="hidden" value="{{Type}}">
	<input class="dataset-group-id" name="fields[{{Field}}][Key]" type="hidden" value="{{Key}}">
	{{#if new_item}}
	<span class="dbtoolkit-trigger" 
	data-request="dbt_add_dataset_config" 
	data-target=".dbtoolkit-dataset-items-config-wrapper"
	data-target-insert="append"
	data-template="#dbtoolkit-panel-local-field-config-tmpl"
	data-field="{{Field}}"
	data-fieldtype="{{Type}}"
	data-autoload="true"
	></span>
	{{/if}}
</div>
{{/each}}
{{else}}
<p class="description">Please select a table</p>
{{/if}}