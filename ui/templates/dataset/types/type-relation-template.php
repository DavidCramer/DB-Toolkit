<?php

$tables = dbtoolkit_get_table_list();

?><div class="dbtoolkit-field-group">
	<label>Table</label>
	<select style="width: 90px;" name="fields[{{Field}}][join_type]">
		<option>join</option>
		<option>left join</option>
		<option>right join</option>
	</select>
	<select style="width: 224px;" name="fields[{{Field}}][join_table]" class="dbtoolkit-trigger" data-action="dbt_load_local_table_fields" data-target=".join_table_fields_{{Field}}" data-event="change">
		<option></option>
		<?php foreach($tables as $table){ ?>
		<option value="<?php echo $table; ?>" {{#is join_table value="<?php echo $table; ?>"}}selected="selected"{{/is}}><?php echo $table; ?></option>
		<?php } ?>
	</select>
</div>
<div class="dbtoolkit-field-group">
	<label>Relation Field</label>
	<select name="fields[{{Field}}][join_field]" class="join_table_fields_{{Field}}" ></select>
</div>
<div class="dbtoolkit-field-group">
	<label>Returned Fields</label>
	<select name="fields[{{Field}}][join_select]" class="join_table_fields_{{Field}}"></select>
</div>
<?php
/*
<div class="dbtoolkit-field-group">
	<label>Join Where</label>
	<select style="width: 110px;" name="fields[{{Field}}][join_where]" class="join_table_fields_{{Field}}"></select>
	<select style="width: 88px;" name="fields[{{Field}}][join_condition]">
		<option>==</option>
		<option>!=</option>
		<option>LIKE</option>
		<option>LIKE %%</option>
	</select>
	<select style="width: 111px;" name="fields[{{Field}}][join_field]">
		{{#each ../../../fields}}
		<option>{{Field}}</option>
		{{/each}}
	</select>
</div>
*/
?>
<div class="dbtoolkit-field-group">
	<label>Order By</label>
	<select name="fields[{{Field}}][join_condition]" class="join_table_fields_{{Field}}">
	</select>
</div>