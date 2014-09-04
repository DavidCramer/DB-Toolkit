<?php


add_action( "wp_ajax_dbt_load_local_tables", "dbtoolkit_load_local_tables" );
function dbtoolkit_load_local_tables(){

	$autoload = '';
	if(!empty($_POST['table'])){
		//$autoload = ' data-autoload="true"';
	}

	echo '<select style="width:100%;" name="table" class="dbtoolkit-trigger"'.$autoload.' data-event="change" data-before="dbt_clear_configs" data-action="dbt_load_local_table" data-template="#dbtoolkit-panel-local-field-item-tmpl" data-target="#ce-data-source" data-callback="dbt_dataset_currenct_object">';
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

	//echo '<button class="button right">Add Field</button>';

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

function dbtoolkit_get_local_structure($structure, $config){
	
	foreach($config['fields'] as $field){
		$structure[$field['slug']] = $field['slug'];
	}

	return $structure;
}
function dbtoolkit_build_local_joins($query, $field, $config){
	global $wpdb;

	$join = array();
	// build a joinkey with join fields
    $join_key = md5($field['join_type'].$field['join_table'].$field['join_field'].$field['join_where'].$field['join_condition'].$field['join_on_field'].$field['join_value'].$field['join_order_by']);	
    if(isset($query['join'][$join_key])){
    	$keys = array_keys($query['join']);
    	$alias_key = array_search($join_key, $keys);
    	$alias = 't'.$alias_key;
    }else{
		$alias = 't'.count($query['join']);

		$join[] = strtoupper($field['join_type']);
		$join[] = '`'.$field['join_table'].'` AS `'.$alias.'` ON';
		$join[] = '(';
		//$join[] = '`'.$field['join_table'].'`.`'.$field['join_field'].'`';

		// check if join field is a clone
		if(!empty($field['Clone'])){
			//$field['join_field']
			$master_id = $field['Clone'];
			$master = $config['fields'][$master_id]['slug'];
			$loopback = 0;
			while(!isset($query['select'][$master])){
				$master_id = $config['fields'][$master]['Clone'];
				$master = $config['fields'][$master_id]['slug'];
				if($loopback >= count($query['select'])){
					// not going to find it.
					$master = null;
					break;
				}
			}
			
			$join_field = $query['select'][$master];
			if(empty($join_field)){
				dump($query['select']);
			}
		}else{
			$join_field = $config['table'].'`.`'.$field['Field'];
		}

		$join[] = '`'.$join_field.'` '.$field['join_condition'].' `'.$alias.'`.`'.$field['join_field'].'`';
		if(!empty($field['join_where'])){
			$join[] = 'AND';
			$join[] = '`'.$alias.'`.`'.$field['join_where'].'`';
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
		$query['join'][$join_key] = $join_line;
	}
	//$query['select']['_'.$field['slug']] = $config['table'].'`.`'.$field['slug'];
	$query['select'][$field['slug']] = $alias.'`.`'.$field['join_select'];

	return $query;
}

add_filter("dbtoolkit_local_table_field_types", "dbtoolkit_get_field_type_handlers");
function dbtoolkit_get_field_type_handlers($types){
	$type = array(
		'relation'	=>	array(
			'label'		=>	'Relation',
			'template'	=>	DBTOOLKIT_PATH . "ui/templates/dataset/handlers/handler-relation-template.php",
			'query' 	=>	'dbtoolkit_build_local_joins'
		),
		'email'			=>	array(
			'label'		=>	'email',
			'format'	=>	'dbtoolkit_format_email'
		)
	);
	return array_merge($types, $type);
}

function dbtoolkit_format_email($value, $field, $config){
	return '<a href="mailto:'.$value.'">'.$value.'</a>';
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
			$query['select'][$field['slug']] = $field_id;
		}
	}

	// init handler filters
	foreach($handlers as $handler_id=>$handler){
		// format handler
		if(isset($handler['format'])){
			add_filter('dbtoolkit_local_table_format-'.$handler_id, $handler['format'], 1, 3);
		}
		// query handler
		if(isset($handler['query'])){
			add_filter('dbtoolkit_local_table_query-'.$handler_id, $handler['query'], 1, 3);
		}
	}

	foreach($config['fields'] as $field_id=>$field){
		//filter query
		$query = apply_filters('dbtoolkit_local_table_query-'.$field['Handler'], $query, $field, $config);
		
		// set field select
		if( false === strpos( $query['select'][$field['slug']], '.' )){
			// track down clones
			if(!empty($field['Clone'])){
				$master_id = $field['Clone'];
				$master = $config['fields'][$master_id]['slug'];
				$loopback = 0;
				while(!isset($query['select'][$master])){
					$master_id = $config['fields'][$master]['Clone'];
					$master = $config['fields'][$master_id]['slug'];
					if($loopback >= count($query['select'])){
						// not going to find it.
						$master = null;
						break;
					}
				}
				$query['select'][$field['slug']] = $query['select'][$master];
			}else{
				// reference root table
				$query['select'][$field['slug']] = $config['table'].'`.`'.$query['select'][$field['slug']];
			}
		}
		
		// set primary field
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
	if(!empty($config['filter'])){
		foreach($config['filter'] as $filter_key=>$filter_value){
			if(isset($config['fields'][$filter_key])){
				$query['where'][] = $wpdb->prepare('`'.$config['table'].'`.`'.$filter_key.'` = %s', $filter_value);
			}
		}
	}	
	// build pagination
	// TODO
	// build query
	$query_build = array();
	foreach($query as $q_key=>&$q_part){
		$query_build_line = null;
		if(!empty($q_part)){
			if($q_key == 'select'){
				$select = array();
				foreach($q_part as $as => &$field){
					$select[] = '`'.$field.'` AS `'.$as.'`';
				}
				$query_build_line = strtoupper($q_key) . "\r\n\t" . implode( ",\r\n\t", (array) $select );
			}elseif($q_key == 'join'){
				$query_build_line = implode( "\r\n", (array) $q_part );
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
				$field = $query['select'][$field];
			}
		}
		//dump($groupby);
		//$query_build['group'] = 'GROUP BY '. implode(', ', $groupby);

		$query_count = implode("\r\n", $query_build);
	}else{
		// straight count
		$query_count = implode("\r\n", $query_build);
	}

	//dump($query_full);

	$data['total']		=	$wpdb->get_var($query_count);
	$data['pages']		=	ceil( $data['total'] / $data['per_page'] );
	$data['entry']		=	$wpdb->get_results($query_full, ARRAY_A);
	if(!empty($data['entry'])){
		// apply formatting
		foreach($data['entry'] as $entry_slug=>&$entry){

			foreach($config['fields'] as $field_id=>$field){
				if( has_filter( 'dbtoolkit_local_table_format-'.$field['Handler'] ) ){
					$entry[$field['slug']] = apply_filters('dbtoolkit_local_table_format-'.$field['Handler'], $entry[$field['slug']], $field, $config);
				}
			}
			
		}
	}
	
	return $data;
	
}

























