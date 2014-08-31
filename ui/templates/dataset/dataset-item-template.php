			{{#each datasets}}
			<div class="dbtoolkit-dataset-item" data-id="{{id}}">
				<span class="dbtoolkit-group-config dashicons dashicons-edit dbtoolkit-trigger" data-before="dbt_show_dataset_config_panel" data-id="{{id}}"></span>
				<h4 class="{{id}}"><span class="label_{{id}}">{{label}}</span> <small class="dbtoolkit-panel-caption slug_{{id}}">{{slug}}</small></h4>
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
			{{/each}}