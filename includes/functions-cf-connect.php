<?php


add_action( "wp_ajax_dbt_load_caldera_forms", "dbtoolkit_load_caldera_forms" );
function dbtoolkit_load_caldera_forms(){

	$forms = get_option( '_caldera_forms' );

	$autoload = '';
	if(!empty($_POST['source'])){
		$autoload = ' data-autoload="true"';
	}

	echo '<select name="form" class="dbtoolkit-trigger"'.$autoload.' data-event="change" data-action="dbt_load_caldera_form" data-template="#dbtoolkit-panel-data-field-tmpl" data-target="#ce-data-source">';
	echo '<option></option>';

	foreach($forms as $form_id=>$form){
		$sel = '';

		echo '<option value="'.$form_id.'" '.$sel.'>'.$form['name'].'</option>';
	}
	echo '</select>';
	exit;

}

add_action( "wp_ajax_dbt_load_caldera_form", "dbtoolkit_load_caldera_form" );
function dbtoolkit_load_caldera_form(){
	$form = get_option( $_POST['form'] );

	$struct = array();
	foreach($form['fields'] as $field_id=>$field){
		$struc[$field['slug']] = $field['type'];
	}

	wp_send_json( array('fields' => $struc) );

}


function dbtoolkit_cf_form_structure($structue, $config){
	$form = get_option($config['form']);
	
	$struct = array();
	foreach($form['fields'] as $field_id=>$field){
		$struc[$field['slug']] = $field['type'];
	}

	return $struc;
}

function dbtoolkit_cf_form_get_entries($data,$config){
	global $wpdb, $passback_args;

	$data = array(
		'page'				=>	1,
		'pages'				=>	1,
		'total_entries'		=>	1,
		'entries_per_page'	=>	1,
	);
	// get entries
	$entry_count = $wpdb->get_results( $wpdb->prepare( "SELECT `id` FROM `".$wpdb->prefix."cf_form_entries` WHERE `form_id` = %s", $config['form'] ), ARRAY_A );

	$data['total_entries'] = count( $entry_count );
	$entry_ids = array();
	foreach($entry_count as $entry_item){
		$entry_ids[] = $entry_item['id'];
	}
	// entries
	$rawdata = $wpdb->get_results( "SELECT * FROM `".$wpdb->prefix."cf_form_entry_values` WHERE `entry_id` IN (".implode(',', $entry_ids).")", ARRAY_A );
	foreach($rawdata as $entry){
		$data['entries'][$entry['entry_id']][$entry['slug']] = $entry['value'];
	}

	return $data;
}















