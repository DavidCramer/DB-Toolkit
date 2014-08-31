<?php


add_action( "wp_ajax_dbt_load_local_tables", "dbtoolkit_load_local_tables" );
function dbtoolkit_load_local_tables(){

	$autoload = '';
	if(!empty($_POST['table'])){
		//$autoload = ' data-autoload="true"';
	}

	echo '<select style="width:210px;" name="table" class="dbtoolkit-trigger"'.$autoload.' data-event="change" data-before="dbt_clear_configs" data-action="dbt_load_local_table" data-template="#dbtoolkit-panel-local-field-item-tmpl" data-target="#ce-data-source">';
	echo '<option></option>';

	$tables = dbtoolkit_get_table_list();
	foreach($tables as $table){		
		$sel = '';
		if(!empty($_POST['table']) && $_POST['table'] == $table){
			$sel = 'selected="selected"';
		}
		echo '<option value="'.$table.'" '.$sel.'>'.$table.'</option>';
	}
	echo '</select>';

	echo '<button class="button right">Add Field</button>';

	exit;

}

function dbtoolkit_get_table_list(){
	global $wpdb;

	$list = array();
	$tables = $wpdb->get_results("SHOW TABLES", ARRAY_A);
	foreach($tables as $table){
		sort($table);
		$list[] = $table[0];
	}

	return $list;
}


add_action( "wp_ajax_dbt_load_local_table", "dbtoolkit_load_local_table" );
function dbtoolkit_load_local_table(){
	global $wpdb;

	$fields = $wpdb->get_results( "SHOW COLUMNS FROM `".$_POST['table']."`;", ARRAY_A);
		
	$struct = array();
	foreach ($fields as $field_index => $field) {
		$field['new_item'] = true; // SET TO INDICATE NEWLY LOADED
		$struct[] = $field;
	}

	wp_send_json( array('fields' => $struct ) );
}

add_action( "wp_ajax_dbt_load_local_table_fields", "dbtoolkit_load_local_table_fields" );
function dbtoolkit_load_local_table_fields(){
	global $wpdb;

	$fields = $wpdb->get_results( "SHOW COLUMNS FROM `".$_POST['_value']."`;", ARRAY_A);
		
	$struct = array();
	echo '<option></option>';
	foreach ($fields as $field_index => $field) {
		echo '<option value="'.$field['Field'].'">'.$field['Field'].'</option>';
	}
	
	exit;	
}

add_action("dbtoolkit_editor_templates", "dbtoolkit_render_local_table_templates");
function dbtoolkit_render_local_table_templates(){
	echo "<script type=\"text/html\" id=\"dbtoolkit-panel-local-field-item-tmpl\">\r\n";
		include DBTOOLKIT_PATH . "ui/templates/dataset/dataset-item-template.php";
	echo "</script>\r\n";
	echo "<script type=\"text/html\" id=\"dbtoolkit-panel-local-field-config-tmpl\">\r\n";
		include DBTOOLKIT_PATH . "ui/templates/dataset/dataset-item-config-template.php";
	echo "</script>\r\n";
	$field_types = apply_filters("dbtoolkit_local_table_field_types", array() );

	foreach($field_types as $field_slug=>$field_config){
		if(isset($field_config['template']) && file_exists($field_config['template'])){
			echo "<script type=\"text/html\" id=\"dbtoolkit-handler-template-".$field_slug."-tmpl\">\r\n";
				include $field_config['template'];
			echo "</script>\r\n";
		}else{
			echo "<script type=\"text/html\" id=\"dbtoolkit-handler-template-".$field_slug."-tmpl\">\r\n";
			echo "</script>\r\n";
		}
	}
	
}

add_filter("dbtoolkit_local_table_field_types", "dbtoolkit_get_field_type_handlers");
function dbtoolkit_get_field_type_handlers($types){
	$type = array(
		'text'	=>	array(
			'label'		=>	'Text'
		),
		'primary'	=>	array(
			'label'		=>	'Primary',
			'template'	=>	DBTOOLKIT_PATH . "ui/templates/dataset/types/type-primary-template.php"
		),
		'relation'	=>	array(
			'label'		=>	'Relation',
			'template'	=>	DBTOOLKIT_PATH . "ui/templates/dataset/types/type-relation-template.php"
		),
		'wpuserid'	=>	array(
			'label'		=>	'WordPress User ID',
			'template'	=>	DBTOOLKIT_PATH . "ui/templates/dataset/types/type-wpuserid-template.php"
		)
	);
	return array_merge($types, $type);
}

















