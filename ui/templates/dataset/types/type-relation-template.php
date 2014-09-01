<?php

$tables = dbtoolkit_get_table_list();

?><div class="dbtoolkit-field-group">
	<label>Table</label>
	<select style="width: 90px;" name="fields[{{Field}}][join_type]">
		<option>join</option>
		<option>left join</option>
		<option>right join</option>
	</select>
	<select style="width: 224px;" name="fields[{{Field}}][join_table]" {{#if join_table}}data-autoload="true"{{/if}} class="dbtoolkit-trigger" data-callback="dbt_update_table_field_selection" data-action="dbt_load_local_table_fields" data-selector=".join_table_fields_{{Field}}" data-event="change">
		<option></option>
		<?php foreach($tables as $table){ ?>
		<option value="<?php echo $table; ?>" {{#is join_table value="<?php echo $table; ?>"}}selected="selected"{{/is}}><?php echo $table; ?></option>
		<?php } ?>
	</select>
</div>
<div class="dbtoolkit-field-group">
	<label>Relation Field</label>
	<select name="fields[{{Field}}][join_field]" class="join_table_fields_{{Field}}" {{#if join_field}}data-default="{{join_field}}"{{/if}}></select>
</div>
<div class="dbtoolkit-field-group">
	<label>Returned Field</label>
	<select name="fields[{{Field}}][join_select]" class="join_table_fields_{{Field}}" {{#if join_select}}data-default="{{join_select}}"{{/if}}></select>
	<?php /*<div data-name="fields[{{Field}}][join_select]" class="join_table_fields_{{Field}}" {{#if join_select}}data-default="{{join_select}}" {{/if}}style="display: inline-block;"></div>*/ ?>
</div>

<div class="dbtoolkit-field-group">
	<label>Join Where</label>
	<select style="width: 110px;" name="fields[{{Field}}][join_where]" class="join_table_fields_{{Field}}" {{#if join_where}}data-default="{{join_where}}"{{/if}}></select>
	<select style="width: 88px;" name="fields[{{Field}}][join_condition]">
		<option value="=" {{#is join_condition value="="}}selected="selected"{{/is}}>=</option>
		<option value="!=" {{#is join_condition value="!="}}selected="selected"{{/is}}>!=</option>
		<option value="LIKE" {{#is join_condition value="LIKE"}}selected="selected"{{/is}}>LIKE</option>
		<option value="LIKE%%" {{#is join_condition value="LIKE%%"}}selected="selected"{{/is}}>LIKE %%</option>
	</select>
	<select id="onfield-{{Field}}" style="width: 111px;" name="fields[{{Field}}][join_on_field]" {{#if join_on_field}}data-default="{{join_on_field}}"{{/if}}>
		<optgroup label="Field">
		{{#each ../../fields}}
		<option value="{{Field}}">{{Field}}</option>
		{{/each}}
		</optgroup>
		<optgroup label="Custom">
		<option value="NULL">NULL</option>
		<option value="__custom_value__">Custom Value</option>
		</optgroup>
	</select>
</div>
<div class="dbtoolkit-field-group" id="onfield-custom-{{Field}}">
	<label></label>
	<input type="text" value="{{join_on_custom}}" name="fields[{{Field}}][join_value]">
</div>


<div class="dbtoolkit-field-group">
	<label>Order By</label>
	<select name="fields[{{Field}}][join_order_by]" class="join_table_fields_{{Field}}">
	</select>
</div>

{{#script}}
jQuery(function($){
	jQuery(document).on('change','#onfield-{{Field}}', function(){
		if(this.value === '__custom_value__'){
			jQuery('#onfield-custom-{{Field}}').show().find('input').focus();
		}else{
			jQuery('#onfield-custom-{{Field}}').hide();
		}
	});
	jQuery('#onfield-{{Field}}').val(jQuery('#onfield-{{Field}}').data('default')).trigger('change');
});
{{/script}}