	{{#each variable_groups}}
	<div id="{{id}}" class="dbtoolkit-variable-group">
		<span class="dbtoolkit-group-config dbtoolkit-trigger dashicons dashicons-admin-generic"
		data-request="dbt_toggle_group_config"
		data-config="#dbtoolkit-group-config-{{id}}"
		></span>
		<span class="dbtoolkit-group-config dbtoolkit-trigger dashicons dashicons-plus-alt"
		data-request="dbt_add_variable_item"
		data-target="#items-{{slug}}"
		data-target-insert="append"
		data-template="#dbtoolkit-panel-variable-item-tmpl"
		data-callback="dbt_reset_variable_sortables"
		data-group="{{id}}"
		></span>
		<h3><span class="dbtoolkit-group-handle dashicons dashicons-menu"></span> <span class="label_{{id}}">{{label}}</span></h3>
		<div class="dbtoolkit-variable-group-config" id="dbtoolkit-group-config-{{id}}">
			<label>Label</label>
			<input name="variable_groups[{{id}}][label]" class="{{id}} dbtoolkit-group-label dbtoolkit-trigger-text-change" data-sync="label_{{id}}" type="text" value="{{label}}">
			<label>Description</label>
			<input name="variable_groups[{{id}}][description]" class="{{id}}" type="text" value="{{description}}">
			<label>Slug</label>
			<input name="variable_groups[{{id}}][slug]" class="{{id}}" data-format="slug" type="text" value="{{slug}}">
			<button type="button" data-id="{{id}}" data-request="dbt_delete_variable_group" class="dbtoolkit-trigger button button-small right" style="margin: 6px 2px 2px;">Delete Group</button>
			<div class="clear"></div>
		</div>
		<div class="dbtoolkit-variable-holder" id="items-{{slug}}" data-group="{{id}}">
		<?php include DBTOOLKIT_PATH . "ui/templates/variable/variable-group-item-template.php"; ?>
		</div>		
	</div>
	{{/each}}
