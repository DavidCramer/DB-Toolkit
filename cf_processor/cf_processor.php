<?php
/**
 * DBT Caldera Forms processor Addon
 */





add_filter('caldera_forms_get_form_processors', 'dbt_register_processor');
add_action("wp_ajax_dbt_load_table_list", 'dbt_load_table_list' );
add_action("wp_ajax_dbt_load_field_list", 'dbt_load_field_list' );
add_filter("caldera_forms_render_get_entry", 'dbt_get_entry', 10, 3);
add_filter("caldera_forms_get_entry_meta_table_capture", 'dbt_get_entry_meta', 10, 3);


add_filter('caldera_forms_get_addons', 'cf_dbtoolkit_addon' );
function cf_dbtoolkit_addon($addons){
  $addons['dbtoolkit'] = __FILE__;
  return $addons;
}




//add_filter('caldera_forms_render_setup_field', 'dbt_populate_defaults', 10, 2);

function dbt_register_processor($processors){

	$processors['table_capture'] = array(
		"name"				=>	__('DB-Toolkit Capture', 'caldera-forms'),
		"description"		=>	__("Capture to Database", 'caldera-forms'),
		"icon"				=>	plugin_dir_url(__FILE__) . "icon.png",
		"processor"			=>	'do_form_capture',
		"template"			=>	plugin_dir_path(__FILE__) . "config.php",
		"setup"				=>	array(
			"styles"			=> array(
				plugin_dir_url( __FILE__ ) . "dbt.css"
			)
		),
		"magic_tags" => array(
			"entryid",
			"connection"
		)
	);
	$processors['dbt_search'] = array(
		"name"				=>	__('DB-Toolkit Search', 'caldera-forms'),
		"description"		=>	__("Search Database", 'caldera-forms'),
		"icon"				=>	plugin_dir_url(__FILE__) . "icon.png",
		"processor"			=>	'dbt_do_table_search',
		"template"			=>	plugin_dir_path(__FILE__) . "search_config.php",
	);
	return $processors;

}

function dbt_do_table_search($config, $form){
	dump($config);
}

function dbt_get_entry($data, $form, $entry_id){

	$detail = Caldera_forms::get_entry_detail($entry_id, $form);

	
	if(!empty($detail['meta']['connection'])){
		//dump($detail);
		$processors = Caldera_forms::get_processor_by_type('table_capture', $form);
		if(!empty($processors)){
			foreach($processors as $processor){
				if($processor['config']['connection'] == $detail['meta']['connection']){
					// yup - get data
					$connection = dbt_open_connection($processor['config']['connection']);
					foreach($processor['config']['fields'] as $table_field=>$field_id){
						// exclude if field was removed.
						if(isset($form['fields'][$field_id])){
							$selects[] = '`'.$table_field.'` AS `'.$form['fields'][$field_id]['slug'].'`'; 
						}
					}
					$query = "SELECT " . implode(', ', $selects) ." FROM `".$processor['config']['table']."` WHERE `".$processor['config']['index']."` = %s";
					$row = $connection->get_row( $connection->prepare($query, $detail['meta']['entryid']), ARRAY_A);
					if(!empty($row)){
						$data = $row;
					}
					break;
				}
			}
		}

	}

	return $data;
}

function dbt_get_entry_meta($meta, $config, $form){
	return $meta;
}

function do_form_capture($config, $form){
	global $transdata;

	
	$connection = dbt_open_connection($config['connection']);

	if(!empty($transdata['edit'])){
		// EDIT PROCESS
		$metadata = Caldera_Forms::get_entry_meta($transdata['edit'], $form, 'table_capture');
		
		if(isset($metadata[$config['processor_id']]['entry']['entryid']['meta_value'])){
			$meta_entry_id = $metadata[$config['processor_id']]['entry']['entryid']['meta_value'];
		}
	}

	$entry = array();
	if(empty($config['fields']) || empty($config['table'])){
		return;
	}

	foreach($config['fields'] as $field_key=>$field){
		//if(isset($form['fields'][$field])){
		$entry[$field_key] = Caldera_Forms::get_field_data($field, $form);
		//}
	}

	// insert	
	if(!isset($meta_entry_id)){
		if($connection->insert($config['table'], $entry)){

			$entryid = $connection->insert_id;
			// save connection id
			$out['connection'] = $config['connection'];
			if(!empty($entryid)){
				// save entry id if has
				$out['entryid'] = $entryid;
			}
			return $out;
		}
	}else{
	//update
		$connection->update($config['table'], $entry, array($config['index'] => $meta_entry_id));
		//dump()
	}


	return;
}

function dbt_open_connection($id){
	global $wpdb;
	if($id == 'internal'){
		return $wpdb;
	}
	$connection_details = get_post_meta( (int) $id, 'cf_databind', true );
	if(empty($connection_details)){
		echo 'Error, Invalid connection or connection not setup.';
		exit;
	}
	
	$connection = new wpdb( $connection_details['username'], $connection_details['password'], $connection_details['database_name'], $connection_details['host'] );
	
	return $connection;	
}

function dbt_get_tables($id, $default=null){
	global $wpdb;

	$connection = dbt_open_connection($id);

	$tables = $connection->get_results("SHOW TABLES", ARRAY_A);

	$tableoptions = '<option value=""></option>';
	$tableslist = array();
	foreach($tables as $table){
		sort($table);
		$tableoptions .= '<option value="'.$table[0].'" '.($default == $table[0] ? 'selected="selected"' : '' ).'>'.$table[0].'</option>';
		$tableslist[] = $table[0];
	}

	$out['options'] = $tableoptions;
	$out['list'] = $tableslist;
	return $out;

}


function dbt_load_connection(){	
	$tables = dbt_get_tables($id);
}

/*function dbt_populate_defaults($field, $form){
	global $dbt_fieldcolumns;

	$processors = Caldera_forms::get_processor_by_type('table_capture', $form);
	if(!empty($processors)){
		foreach($processors as $processor){
			if(empty($dbt_fieldcolumns[$form['ID']])){
				$connection = dbt_open_connection($processor['config']['connection']);
				$fields = $connection->get_results( "SHOW COLUMNS IN " . $processor['config']['table'] );
				$dbt_fieldcolumns[$form['ID']] = $fields;
			}
			foreach($dbt_fieldcolumns[$form['ID']] as $columnfield){
				dump($columnfield,0);

				if(in_array($columnfield, $processor['config']['enums'])){
				//	$processor['config']['enums']
				}
			}
		}
	}

}*/

function dbt_load_field_list(){


	$locktypes=array(
		'DATE'	=>	array(
			'date_picker',
			'hidden'
		),
		'DATETIME'	=> array(
			'date_picker',
			'text',
			'hidden'
		),
		'TIMESTAMP'	=> array(
			'date_picker',
			'text',
			'hidden'
		),
		'ENUM'	=>	array(
			'toggle_switch',
			'dropdown',
			'radio'
		)
	);

	if(empty($_POST['_value'])){
		exit;
	}

	$connection = dbt_open_connection($_POST['connection']);


	$fields = $connection->get_results( "SHOW COLUMNS IN " . $_POST['_value'] );
	if(empty($fields)){
		echo '<p>Table contains no fields.</p>';
		die;
	}
	echo "<h4>Fields</h4>";

	foreach( $fields as $field ){

		$locktype = null;
		$capt = null;
		if( isset( $locktypes[strtoupper( $field->Type )] ) ){
			$locktype = ' data-type="'.implode( ',', $locktypes[strtoupper($field->Type)] ).'"';
		}

		//enum check 
		if(false !== strpos($field->Type, 'enum')){
			$locktype = ' data-type="'.implode( ',', $locktypes['ENUM'] ).'"';
			$capt = '<p class="description">' . $field->Type . '</p>';
			//echo '<input name="'.$_POST['name'].'[enums][]" value="'.$field->Field.'" type="text" class="field-config">';
		}

		if($field->Extra !== 'auto_increment' && $field->Extra !== 'on update CURRENT_TIMESTAMP'){
	?>
	<div class="caldera-config-group">
			<label for="<?php echo $_POST['id']; ?>_fields_<?php echo $field->Field; ?>"><?php echo $field->Field; ?></label>
			<div class="caldera-config-field">
				<select class="block-input caldera-field-bind required"<?php echo $locktype; ?> id="<?php echo $_POST['id']; ?>_fields_<?php echo $field->Field; ?>" name="<?php echo $_POST['name']; ?>[fields][<?php echo $field->Field; ?>]"></select>
				<?php echo $capt; ?>
			</div>
	</div>
	<?php
		}
	}
	// build primaries for indexing.
	echo "<h4>Index</h4>";
	?>
	<div class="caldera-config-group">
			<label for="<?php echo $_POST['id']; ?>_primary_index_field">Table Field</label>
			<div class="caldera-config-field">
				<select class="block-input" id="<?php echo $_POST['id']; ?>_<?php echo $field->Field; ?>_index" name="<?php echo $_POST['name']; ?>[index]">
				<?php foreach( $fields as $field ){ ?>
					<option value="<?php echo $field->Field; ?>" <?php if($field->Key === 'PRI'){ echo 'selected=="selected"'; } ?>><?php echo $field->Field; ?></option>
				<?php } ?>
				</select>
			</div>
	</div>
	<?php foreach($fields as $field){ ?>
	<input name="<?php echo $_POST['name']; ?>[field_list][]" value="<?php echo $field->Field; ?>" type="hidden" class="field-config">
	<?php }

	exit;
}

function dbt_load_table_list(){	

	if(empty($_POST['_value'])){
		exit;
	}

	$tables = dbt_get_tables($_POST['_value']);
	?>
	<div class="caldera-config-group">
		<label for="<?php echo $_POST['id']; ?>_table">Table</label>
		<div class="caldera-config-field">
			<select class="block-input field-config ajax-trigger" data-id="<?php echo $_POST['id']; ?>" id="<?php echo $_POST['id']; ?>" data-connection="<?php echo $_POST['_value']; ?>" data-name="<?php echo $_POST['name']; ?>" data-callback="rebuild_field_binding" data-action="dbt_load_field_list" data-target="#dbt-field-config-<?php echo $_POST['id']; ?>" data-event="change" name="<?php echo $_POST['name']; ?>[table]">
			<?php echo $tables['options']; ?>
			</select>
		</div>
	</div>
	<?php foreach($tables['list'] as $table){ ?>
	<input name="<?php echo $_POST['name']; ?>[tables][]" value="<?php echo $table; ?>" type="hidden" class="field-config">
	<?php } ?>
	<div id="dbt-field-config-<?php echo $_POST['id']; ?>"></div>
	<?php
	exit;
}
