<div class="dbtoolkit-field-group">
	<label>Type</label>
	<select name="fields[{{Field}}][Handler]" class="{{Field}} dbtoolkit-trigger" data-event="change" data-field="{{Field}}" data-request="dbt_setup_field_handler" data-callback="dbt_init_fieldhandler_switch" data-target="#{{Field}}_field_config">
		<?php
		$field_types = apply_filters("dbtoolkit_local_table_field_types", array() );

		foreach($field_types as $field_slug=>$field_config){
			echo "<option value=\"" . $field_slug ."\" {{#is Handler value=\"" . $field_slug . "\"}}selected=\"selected\"{{/is}}>" . $field_config['label'] . "</option>\r\n";
		}
		?>
	</select>
	
</div>

<div id="{{Field}}_field_config">
{{include Handler}}
</div>