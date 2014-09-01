<?php


add_action( "wp_ajax_dbt_load_local_tables", "dbtoolkit_load_local_tables" );
function dbtoolkit_load_local_tables(){

	$autoload = '';
	if(!empty($_POST['table'])){
		//$autoload = ' data-autoload="true"';
	}

	echo '<select style="width:210px;" name="table" class="dbtoolkit-trigger"'.$autoload.' data-event="change" data-before="dbt_clear_configs" data-action="dbt_load_local_table" data-template="#dbtoolkit-panel-local-field-item-tmpl" data-target="#ce-data-source" data-callback="dbt_dataset_currenct_object">';
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
	//echo '<option></option>';
	foreach ($fields as $field_index => $field) {
		$struct[] = $field['Field'];
		//echo '<option value="'.$field['Field'].'">'.$field['Field'].'</option>';
	}
	wp_send_json( $struct );
}

add_action("dbtoolkit_editor_templates", "dbtoolkit_render_local_table_templates");
function dbtoolkit_render_local_table_templates(){
	echo "<script type=\"text/html\" id=\"dbtoolkit-panel-local-field-item-tmpl\">\r\n";
		include DBTOOLKIT_PATH . "ui/templates/dataset/dataset-item-template.php";
	echo "</script>\r\n";
	echo "<script type=\"text/html\" id=\"dbtoolkit-panel-local-field-config-tmpl\">\r\n";
		include DBTOOLKIT_PATH . "ui/templates/dataset/dataset-item-config-template.php";
	echo "</script>\r\n";
	echo "<script type=\"text/html\" id=\"dbtoolkit-panel-local-field-filter-item-tmpl\">\r\n";
		include DBTOOLKIT_PATH . "ui/templates/dataset/dataset-filter-item-template.php";
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

function dbtoolkit_build_local_joins($query, $field, $config){
	global $wpdb;

	$join = array();
	
	$join[] = strtoupper($field['join_type']);
	$join[] = '`'.$field['join_table'].'` ON';
	$join[] = '(';
	//$join[] = '`'.$field['join_table'].'`.`'.$field['join_field'].'`';
	$join[] = '`'.$config['table'].'`.`'.$field['Field'].'` '.$field['join_condition'].' `'.$field['join_table'].'`.`'.$field['join_field'].'`';
	if(!empty($field['join_where'])){
		$join[] = 'AND';
		$join[] = '`'.$field['join_table'].'`.`'.$field['join_where'].'`';
		if($field['join_on_field'] == 'NULL'){
			switch($field['join_condition']){
				default:
				case '=':
					$join[] = 'IS NULL';
					break;
				case '!=':
					$join[] = 'IS NOT NULL';
					break;
			}
		}elseif($field['join_on_field'] == '__custom_value__'){
			switch($field['join_condition']){
				default:
					if(is_numeric($field['join_value'])){
						$join[] = $wpdb->prepare( $field['join_condition']." %d", $field['join_value'] );
					}else{
						$join[] = $wpdb->prepare( $field['join_condition']." %s", $field['join_value'] );
					}
					break;
				case 'LIKE%%':
					$join[] = $wpdb->prepare( $field['join_condition']." %%%s%", $field['join_value'] );
					break;
			}
		}else{
			switch($field['join_condition']){
				default:
					$join[] = $field['join_condition'].' `'.$config['table'].'`.`'.$field['join_on_field'].'`';
					break;
				case 'LIKE%%':
					$join[] = 'LIKE `'.$config['table'].'`.`'.$field['join_on_field'].'`';
					break;
			}
		}
	}
	$join[] = ')';
	// build join
	$join_line = implode(' ', $join);
	$query['select']['_'.$field['slug']] = $config['table'].'`.`'.$field['slug'];
	$query['select'][$field['slug']] = $field['join_table'].'`.`'.$field['join_select'];
	$query['join'][] = $join_line;

	return $query;
}

add_filter("dbtoolkit_local_table_field_types", "dbtoolkit_get_field_type_handlers");
function dbtoolkit_get_field_type_handlers($types){
	$type = array(
		'text'	=>	array(
			'label'		=>	'Text'
		),
		'primary'	=>	array(
			'label'		=>	'Primary',
			'template'	=>	DBTOOLKIT_PATH . "ui/templates/dataset/types/type-primary-template.php",
		),
		'relation'	=>	array(
			'label'		=>	'Relation',
			'template'	=>	DBTOOLKIT_PATH . "ui/templates/dataset/types/type-relation-template.php",
			'processor' =>	'dbtoolkit_build_local_joins'
		),
		'wpuserid'	=>	array(
			'label'		=>	'WordPress User ID',
			'template'	=>	DBTOOLKIT_PATH . "ui/templates/dataset/types/type-wpuserid-template.php"
		)
	);
	return array_merge($types, $type);
}


function dbtoolkit_build_local_query($data, $config){

	global $wpdb;

	$handlers = apply_filters('dbtoolkit_local_table_field_types', array());
	if(empty($config['fields'])){
		return $data;
	}
	// setup query object
	$query = array(
		'select' 	=> array(),
		'from'		=> '`'.$config['table'].'`',
		'join' 		=> array(),
		'where' 	=> array(),
		'order'		=> array(),
		'group'		=> array(),
		'limit'		=> array(),
	);

	// setup initual selects
	if(isset($config['select'])){
		// if current request se which to select, use it
		$query['select'] = (array) $config['select'];
	}else{
		// else use all fields
		foreach($config['fields'] as $field_id=>$field){
			$query['select'][$field_id] = $field['slug'];
		}
	}

	foreach($config['fields'] as $field_id=>$field){
		
		if(isset($handlers[$field['Handler']]['processor'])){
			// setup handler
			add_filter('dbtoolkit_local_table_handle-'.$field['Handler'], $handlers[$field['Handler']]['processor'], 1, 3);
			// init handler
			$query = apply_filters('dbtoolkit_local_table_handle-'.$field['Handler'], $query, $field, $config);
			$groupby[] = $field_id;
		}

		if($field['Key'] == 'PRI'){
			$primary = 'SELECT COUNT(`'.$config['table'].'`.`'.$field_id.'`) AS `total`';
		}
	}
	if(empty($primary)){
		// no primary just pick one for now
		// TODO: make it list the keys and then choose.
		$field_keys = array_keys($config['fields']);
		$primary = 'SELECT COUNT(`'.$config['table'].'`.`'.$field_keys[0].'`) AS `total`';
	}

	// build filters
	// TODO
	// build pagination
	// TODO
	// build query
	$query_build = array();
	foreach($query as $q_key=>$q_part){
		$query_build_line = null;
		if(!empty($q_part)){
			if($q_key == 'select'){
				$select = array();
				foreach($q_part as $as => $field){
					if($as != $field && !is_numeric($as)){
						$select[] = '`'.trim($field,'`').'` AS `'.$as.'`';
					}else{
						$select[] = '`'.$config['table'].'`.`'.trim($field,'`').'` AS `'.$field.'`';
					}
				}
				$query_build_line = strtoupper($q_key) . ' ' . implode( ', ', (array) $select );
			}elseif($q_key == 'join'){
				$query_build_line = implode( ' ', (array) $q_part );
			}else{
				$query_build_line = strtoupper($q_key) . ' ' . implode( ' ', (array) $q_part );
			}
		}
		$query_build[$q_key] = $query_build_line;
	}
	
	// full query string
	$query_full = implode("\r\n", $query_build);


	// count query string
	$query_build['select'] = $primary;
	if(!empty($groupby)){
		//setup grouping counter for relations
		foreach($groupby as &$field){
			if(isset($query['select'][$field])){
				$field = '`'.trim($query['select'][$field], '`').'`';
			}else{
				$field = '`'.$field.'`';
			}
		}

		$query_build['group'] = 'GROUP BY '. implode(' ', $groupby);

		$query_count = "SELECT COUNT(`a`.`total`) as `total` FROM ( ".implode("\r\n", $query_build)." ) as `a` ";
	}else{
		// straight count
		$query_count = implode("\r\n", $query_build);
	}

	$data['total']		=	$wpdb->get_var($query_count);
	$data['pages']		=	ceil( $data['total'] / $data['per_page'] );
	$data['entries']	=	$wpdb->get_results($query_full, ARRAY_A);

	dump($data);
	//$raw_results = 
	dump($query_full,0);
	dump($query_count);
	die;
}

























