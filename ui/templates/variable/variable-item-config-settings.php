<div class="dbtoolkit-field-group">
	<label>Label</label>
	<input name="variables[{{id}}][label]" class="{{id}}" data-sync="label_{{id}}" type="text" value="{{label}}">
</div>

<div class="dbtoolkit-field-group">
	<label>Slug</label>
	<input name="variables[{{id}}][slug]" class="{{id}} dbtoolkit-field-slug" data-format="slug" data-sync="slug_{{id}}" type="text" value="{{slug}}">
</div>

<div class="dbtoolkit-field-group">
	<label>Description</label>
	<input name="variables[{{id}}][description]" class="{{id}}" type="text" value="{{description}}">
</div>

<div class="dbtoolkit-field-group">
	<label>Type</label>
	<select name="variables[{{id}}][type]" class="{{id}} dbtoolkit-trigger" data-event="change" data-field="{{id}}" data-request="dbt_setup_fieldtype" data-callback="dbt_init_fieldtype_switch" data-target="#{{id}}_field_config">
		<?php
		global $field_types;
		foreach($field_types as $field_slug=>$field_config){
			echo "<option value=\"" . $field_slug ."\" {{#is type value=\"" . $field_slug . "\"}}selected=\"selected\"{{/is}}>" . $field_config['field'] . "</option>\r\n";
		}
		?>
	</select>
	
</div>

<div id="{{id}}_field_config">
{{include type}}
</div>

<div class="dbtoolkit-panel-footer">
	<button class="button dbtoolkit-trigger" data-id="{{id}}" data-request="dbt_delete_variable_field" type="button">Delete Variable</button>
</div>