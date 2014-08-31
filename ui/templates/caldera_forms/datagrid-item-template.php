			{{#each fields}}
			<div class="dbtoolkit-datagrid-item" data-id="{{ID}}">
				<?php /*<span class="dbtoolkit-group-config dashicons dashicons-edit dbtoolkit-trigger" data-before="dbt_show_datagrid_config_panel" data-id="{{ID}}"></span>*/ ?>
				<span class="dbtoolkit-group-config dashicons dashicons-visibility {{#is visible value="1"}}dbtoolkit-datagrid-visible-field{{/is}} dbtoolkit-trigger" data-request="dbt_set_dataset_field_visible" data-id="{{ID}}"></span>

				<h4 class="{{ID}}"><span class="label_{{ID}}">{{label}}</span> <small class="dbtoolkit-panel-caption slug_{{ID}}">{{type}}</small></h4>
				<input class="datagrid-group-ID" name="fields[{{ID}}][ID]" type="hidden" value="{{ID}}">
				<input class="datagrid-group-label" name="fields[{{ID}}][label]" type="hidden" value="{{label}}">
				<input class="datagrid-group-type" name="fields[{{ID}}][type]" type="hidden" value="{{type}}">
				<input id="{{ID}}_visibility" class="datagrid-group-visible" name="fields[{{ID}}][visible]" type="hidden" value="{{#if visible}}{{visible}}{{else}}0{{/if}}">
				{{#if new_item}}
				<span class="dbtoolkit-trigger"
					data-request="dbt_add_datagrid_config"
					data-callback="dbt_trigger_new_config"
					data-template="#dbtoolkit-panel-datagrid-item-config-tmpl"
					data-target="#ce-datagrids-items-config-wrap"
					data-id="{{ID}}"
					data-target-insert="append"
					data-autoload="true"
					data-event="none"
				></span>
				{{/if}}
			</div>
			{{/each}}