<div class="dbtoolkit-field-group">
	<label>Slug</label>
	<input type="text" name="fields[{{Field}}][slug]" value="{{#if slug}}{{slug}}{{else}}{{Field}}{{/if}}" data-format="slug">
</div>
<div class="dbtoolkit-field-group">
	<label>Type</label>
	<select name="fields[{{Field}}][Handler]" class="{{Field}} dbtoolkit-trigger" data-event="change" data-field="{{Field}}" data-request="dbt_setup_field_handler" data-target="#{{Field}}_field_config_settings_panel">
		<option></option>
		<?php
		$field_types = apply_filters("dbtoolkit_local_table_field_types", array() );

		foreach($field_types as $field_slug=>$field_config){
			echo "<option value=\"" . $field_slug ."\" {{#is Handler value=\"" . $field_slug . "\"}}selected=\"selected\"{{/is}}>" . $field_config['label'] . "</option>\r\n";
		}
		?>
	</select>
	
</div>

<div id="{{Field}}_field_config_settings_panel">
<?php
	$field_types = apply_filters("dbtoolkit_local_table_field_types", array() );

	foreach($field_types as $field_slug=>$field_config){
		if(isset($field_config['template']) && file_exists($field_config['template'])){
			echo "{{#is Handler value=\"".$field_slug."\"}}\r\n";
			include $field_config['template'];
			echo "\r\n{{/is}}\r\n";
		}
	}

	//{{include Handler}}
?>

</div>