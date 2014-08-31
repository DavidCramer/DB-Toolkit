<?php


add_action( "wp_ajax_dbt_load_caldera_forms", "dbtoolkit_load_caldera_forms" );
function dbtoolkit_load_caldera_forms(){

	$forms = get_option( '_caldera_forms' );

	$autoload = '';
	if(!empty($_POST['source'])){
		$autoload = ' data-autoload="true"';
	}

	echo '<select style="width:100%;" name="form" class="dbtoolkit-trigger"'.$autoload.' data-event="change" data-action="dbt_load_caldera_form" data-template="#dbtoolkit-panel-datagrid-item-tmpl" data-target="#ce-data-source">';
	echo '<option></option>';

	foreach($forms as $form_id=>$form){
		$sel = '';
		if(!empty($_POST['form']) && $_POST['form'] == $form_id){
			$sel = 'selected="selected"';
		}
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
		if( in_array($field['type'], array('html','button'))){
			continue;
		}

		$struc[$field['slug']] = $field;
	}

	wp_send_json( array('fields' => $struc) );

}


function dbtoolkit_cf_form_structure($structue, $config){
	$form = get_option($config['form']);
	
	$struct = array();
	foreach($form['fields'] as $field_id=>$field){
		if( in_array($field['type'], array('html','button'))){
			continue;
		}

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


function render_cf_data_grid($config){
	
	if(empty($config['form'])){
		return;
	}

	$form = get_option($config['form']);
	if(empty($form['fields'])){
		return;
	}
	$gridID = uniqid('CF-dg');

	echo '<div class="caldera-grid">';
	echo '<table class="table" id="'.$gridID.'">';	
	echo '<thead>';
	echo '<tr>';
	// TODO: Make UI for which fields are displayed and filterable and sortable
	foreach($config['fields'] as $field_id=>$field){
		if(empty($field['visible'])){
			continue;
		}
		echo '<th data-column-id="'.$field_id.'">'.$form['fields'][$field_id]['label'].'</th>';
	}
	echo "<th data-column-id=\"commands\" data-formatter=\"commands\" data-sortable=\"false\">Commands</th>\r\n";
	echo '</tr>';
	echo '</thead>';

	// do filters for data
	// TODO: make an add filters UI to builder
	global $wpdb;

	$total_entries = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(`id`) AS `total` FROM `" . $wpdb->prefix . "cf_form_entries` WHERE `form_id` = %s AND `status` = 'active';", $form['ID']));
	$raw_data = $wpdb->get_results($wpdb->prepare("
				SELECT
					`id`,
					`form_id`
				FROM `" . $wpdb->prefix ."cf_form_entries`

				WHERE `form_id` = %s AND `status` = 'active' ORDER BY `datestamp` DESC;", $form['ID'] ));	

	echo '<tbody>';
	foreach($raw_data as $entry){
		$entry_set = Caldera_Forms::get_entry($entry->id, $form);

		echo '<tr>';
		foreach($config['fields'] as $field_id=>$field){
			if(empty($field['visible'])){
				continue;
			}			
			echo '<td data-column-id="'.$field_id.'">'. $entry_set['data'][$field_id]['view'] .'</td>';
		}

		echo '</tr>';
		//dump($data);
	}
	echo '</tbody>';
	echo '</table>';
	echo '</div>';
	echo "<script type=\"text/javascript\">\r\n";
	echo "jQuery(function($){\r\n";	
	?>


var grid = $("#<?php echo $gridID; ?>").bootgrid({
	ajax: false,
	post: function ()
	{
		return {
			id: "b0df282a-0d67-40e5-8558-c9e93b7befed"
		};
	},
	url: "/api/data/basic",
	css: {
		search: "search",
		pagination: "pagination pagination-sm",
	},
	formatters: {
		"commands": function(column, row)
		{
			return "<button type=\"button\" class=\"btn btn-xs btn-default command-edit\" data-row-id=\"" + row.id + "\"><span class=\"fa fa-pencil\"></span></button> " +
			"<button type=\"button\" class=\"btn btn-xs btn-default command-delete\" data-row-id=\"" + row.id + "\"><span class=\"fa fa-trash-o\"></span></button>";
		}
	}
}).on("loaded.rs.jquery.bootgrid", function()
{
	/* Executes after data is loaded and rendered */
	grid.find(".command-edit").on("click", function(e)
	{
		alert("You pressed edit on row: " + $(this).data("row-id"));
	}).end().find(".command-delete").on("click", function(e)
	{
		alert("You pressed delete on row: " + $(this).data("row-id"));
	});
});


	<?php
	echo "});\r\n";
	echo "</script>";
}


add_action("dbtoolkit_editor_templates", "dbtoolkit_render_datagrids_templates");
function dbtoolkit_render_datagrids_templates(){
	echo "<script type=\"text/html\" id=\"dbtoolkit-panel-datagrid-item-tmpl\">\r\n";
		include DBTOOLKIT_PATH . "ui/templates/caldera_forms/datagrid-item-template.php";
	echo "</script>\r\n";
	echo "<script type=\"text/html\" id=\"dbtoolkit-panel-datagrid-item-config-tmpl\">\r\n";
		include DBTOOLKIT_PATH . "ui/templates/caldera_forms/datagrid-item--config-template.php";
	echo "</script>\r\n";
}










