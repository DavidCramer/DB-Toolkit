<?php
global $wpdb;

//dump($element,0);

$connections = get_posts( array('post_type' => 'cf_databind', 'post_status' => 'publish') );
$connection_options = '<option value=""></option>';
$connection_options .= '<option value="internal" {{#is connection value="internal"}}selected="selected"{{/is}}>Internal (This DB)</option>';
if(!empty($connections)){
	
	foreach($connections as $connection){
		//sort($table);
		
		$connection_options .= '<option value="'.$connection->ID.'" {{#is connection value="'.$connection->ID.'"}}selected="selected"{{/is}}>'.$connection->post_title.'</option>';
	}
}else{
	//$connection_options = '<option value="">You have no connections setup.</option>';
}



//dump($connections,0);

?>
<div class="caldera-config-group">
	<label>Connection</label>
	<div class="caldera-config-field">
		<select class="block-input field-config ajax-trigger" data-id="{{_id}}" data-name="{{_name}}" data-callback="rebuild_field_binding" data-action="dbt_load_table_list" data-target="#dbt-table-config-{{_id}}" data-event="change" name="{{_name}}[connection]">
		<?php echo $connection_options; ?>
		</select>
	</div>
</div>
<div id="dbt-table-config-{{_id}}">
{{#if tables}}
	
	<div class="caldera-config-group">
		<label for="{{_id}}_table">Table</label>
		<div class="caldera-config-field">
			<select class="block-input field-config ajax-trigger" data-id="{{_id}}" id="{{_id}}" data-connection="{{connection}}" data-name="{{_name}}" data-callback="rebuild_field_binding" data-action="dbt_load_field_list" data-target="#dbt-field-config-{{_id}}" data-event="change" name="{{_name}}[table]">
			{{#each tables}}
			<option value="{{this}}" {{#is ../table value=this}}selected="selected"{{/is}}>{{this}}</option>
			{{/each}}
			</select>
		</div>
		{{#each tables}}
		<input name="{{../_name}}[tables][]" value="{{this}}" type="hidden" class="field-config">
		{{/each}}
	</div>

	{{#if fields}}
	<div id="dbt-field-config-{{_id}}">
		<h4>Fields</h4>
		{{#each fields}}


		<div class="caldera-config-group">
				<label for="{{../_id}}_fields_{{@key}}">{{@key}}</label>		
				<div class="caldera-config-field">
					<select class="block-input caldera-field-bind" id="{{../_id}}_fields_{{@key}}" name="{{../_name}}[fields][{{@key}}]" data-default="{{this}}">
						<option class="bound-field" value="{{this}}"></option>
					</select>
				</div>
		</div>
		{{/each}}

		{{#if field_list}}
		<h4>Index</h4>
		<div class="caldera-config-group">
			<label for="{{_id}}_primary_index_field">Table Field</label>
			<div class="caldera-config-field">
				<select class="block-input field-config" id="{{_id}}_primary_index_field"name="{{_name}}[index]">
				{{#each field_list}}
				<option value="{{this}}" {{#is ../index value=this}}selected="selected"{{/is}}>{{this}}</option>
				{{/each}}
				</select>
			</div>
			{{#each field_list}}
			<input name="{{../_name}}[field_list][]" value="{{this}}" type="hidden" class="field-config">
			{{/each}}
		</div>
		{{/if}}
	</div>
	{{/if}}

{{/if}}
</div>




















