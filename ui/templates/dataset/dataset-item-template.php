{{#if fields}}
{{#each fields}}
<div class="dbtoolkit-dataset-item" data-id="{{Field}}">
	<span class="dbtoolkit-group-config dashicons dashicons-edit dbtoolkit-trigger" data-before="dbt_show_dataset_config_panel" data-id="{{Field}}"></span>
	<h4 class="{{Field}}"><span class="label_{{Field}}">{{Field}}</span> <small class="dbtoolkit-panel-caption slug_{{Field}}">{{Type}}</small></h4>
	<input class="dataset-group-id" name="fields[{{Field}}][Field]" type="hidden" value="{{Field}}">
	<input class="dataset-group-id" name="fields[{{Field}}][Type]" type="hidden" value="{{Type}}">
</div>
{{/each}}
{{else}}
<p class="description">Please select a table</p>
{{/if}}