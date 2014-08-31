<div class="dbtoolkit-dataset-wrapper" id="ce-datasets-wrap">
<select class="dbtoolkit-table-selector">
	<option>Table 1</option>
	<option>Table 2</option>
	<option>Table 3</option>
	<option>Table 4</option>
</select>
<hr>
<div class="dbtoolkit-dataset-group">
	<div class="dbtoolkit-dataset-item" data-id="{{id}}">
		<span class="dbtoolkit-group-config dashicons dashicons-edit dbtoolkit-trigger" data-before="dbt_show_dataset_config_panel" data-id="{{id}}"></span>
		<h4 class="{{id}}"><span class="label_{{id}}">Field Name One</span></h4>
		<input class="dataset-group-id" name="datasets[{{id}}][group]" class="{{id}}" type="hidden" value="{{group}}">
		{{#if new_item}}
		<span class="dbtoolkit-trigger"
			data-request="dbt_add_dataset_config"
			data-callback="dbt_trigger_new_config"
			data-template="#dbtoolkit-panel-dataset-item-config-tmpl"
			data-target="#ce-datasets-items-config-wrap"
			data-id="{{id}}"
			data-target-insert="append"
			data-autoload="true"
			data-event="none"
		></span>
		{{/if}}
	</div>

	<div class="dbtoolkit-dataset-item dbtoolkit-dataset-item-clone" data-id="{{id}}">
		<span class="dbtoolkit-group-config dashicons dashicons-edit dbtoolkit-trigger" data-before="dbt_show_dataset_config_panel" data-id="{{id}}"></span>
		<h4 class="{{id}}"><span class="label_{{id}}">Field Name Two</span></h4>
		<input class="dataset-group-id" name="datasets[{{id}}][group]" class="{{id}}" type="hidden" value="{{group}}">
		{{#if new_item}}
		<span class="dbtoolkit-trigger"
			data-request="dbt_add_dataset_config"
			data-callback="dbt_trigger_new_config"
			data-template="#dbtoolkit-panel-dataset-item-config-tmpl"
			data-target="#ce-datasets-items-config-wrap"
			data-id="{{id}}"
			data-target-insert="append"
			data-autoload="true"
			data-event="none"
		></span>
		{{/if}}
	</div>

	<div class="dbtoolkit-dataset-item" data-id="{{id}}">
		<span class="dbtoolkit-group-config dashicons dashicons-edit dbtoolkit-trigger" data-before="dbt_show_dataset_config_panel" data-id="{{id}}"></span>
		<h4 class="{{id}}"><span class="label_{{id}}">Field Name Three</span></h4>
		<input class="dataset-group-id" name="datasets[{{id}}][group]" class="{{id}}" type="hidden" value="{{group}}">
		{{#if new_item}}
		<span class="dbtoolkit-trigger"
			data-request="dbt_add_dataset_config"
			data-callback="dbt_trigger_new_config"
			data-template="#dbtoolkit-panel-dataset-item-config-tmpl"
			data-target="#ce-datasets-items-config-wrap"
			data-id="{{id}}"
			data-target-insert="append"
			data-autoload="true"
			data-event="none"
		></span>
		{{/if}}
	</div>
</div>
</div>
<div class="dbtoolkit-dataset-items-config-wrapper" id="ce-datasets-items-config-wrap">
	<div class="dbtoolkit-dataset-config-group" id="{{id}}">
		<h3><span class="dashicons dashicons-admin-settings"></span> <span class="label_{{id}}">Field Name One</span></h3>
		
	</div>
</div>






