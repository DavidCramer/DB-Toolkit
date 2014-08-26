<?php


add_action( "wp_ajax_dbt_close_editor", "dbtoolkit_close_editor" );
function dbtoolkit_close_editor(){

	$confirm = array(
		'confirm' 	=> 'Save changes before closing?',
		'name' 		=> 'Save Changes',
		'slug' 		=> 'before closing'
	);
	$confirm['meta_tools'][] = array(
		"label"			=>	'Save Changes',
		"slug"			=>	'save_element',
		"attributes"	=> 	array(
			'data-request'	=>	'dbt_save_element',
			'data-callback'	=>	'dbt_close_editor'
		)
	);
	$confirm['meta_tools'][] = array(
		"label"			=>	'Cancel',
		"slug"			=>	'save_element',
		"attributes"	=> 	array(
			'data-request'	=>	'dbt_get_screen_canvas_data',
			'data-template'	=>	'#dbtoolkit-editor-tools-tmpl',
			'data-target'	=>	'#dbtoolkit-toolbar',
			'data-callback'	=>	'dbt_reveal_tools',
			'data-menuonly'	=>	'true'
		)
	);

	wp_send_json( $confirm );
}
add_action( "wp_ajax_dbt_load_element", "dbtoolkit_load_element" );
function dbtoolkit_load_element($id = null){
	
	if(!empty($id)){
		$element = get_option( $id );
	}else{
		$element = get_option( $_POST['element'] );	
	}
	
	$elements_types = apply_filters( "dbtoolkit_get_element_types", array() );

	if(empty($element) || !isset($element['id']) || !isset($elements_types[$element['type']])){
		wp_send_json( array('error' => 'Invalid element.', 'name' => 'Error', 'slug' => 'invalid element') );
	}


	$editor_tools[] = array(
		"label"		=>	'Settings',
		"caption"	=>	'general element details',
		"slug"		=>	'general_settings',
		"default"	=> true
	);

	$editor_meta_tools[] = array(
		"label"			=>	'Save',
		"slug"			=>	'save_element',
		"attributes"	=> 	array(
			'data-request'	=>	'dbt_save_element',
			'data-callback'	=>	'dbt_update_save_state'
		)
	);
	$editor_meta_tools[] = array(
		"label"			=>	'Preview',
		"slug"			=>	'preview_element',
		"attributes"	=> 	array(
			'data-request'	=>	'dbt_save_element',
			'data-callback'	=>	'dbt_update_save_state'
		)
	);


	$panel_templates = apply_filters( "dbtoolkit_get_element_panels", array() );

	$panel_tools = array();

	if(!empty($elements_types[$element['type']]['panels'])){
		// seup panels
		foreach($elements_types[$element['type']]['panels'] as $key=>$panel){
			if(isset($panel_templates[$panel])){
				if( file_exists($panel_templates[$panel]['template'])){
					$panel_tools[] = array(
						"label"		=>	$panel_templates[$panel]['label'],
						"caption"	=>	$panel_templates[$panel]['caption'],
						"slug"		=>	$panel
					);
				}
			}
		}
	
	}

	if(!empty($elements_types[$element['type']]['editors'])){
		// seup editors
		foreach($elements_types[$element['type']]['editors'] as $editor_slug=>$editor){
			
			$editor_panel = array(
				"label"	=>	$editor['label'],
				"mode"	=>	$editor['mode'],
				"type"	=>	$editor['type'],
				"slug"	=>	$editor_slug,
				"code"	=>	( !empty($element['code'][$editor_slug]) ? $element['code'][$editor_slug] : '')
			);
			if(!empty($editor['options'])){
				$editor_panel['options'] = $editor['options'];
			}
			$editor_panels[] = $editor_panel;
		}
	}

	$element['tools'] 		= $editor_tools;
	if(!empty($panel_tools)){
		$element['panels']		= $panel_tools;
	}
	if(!empty($editor_panels)){
		$element['editors'] 	= $editor_panels;
	}
	if(!empty($editor_meta_tools)){
		$element['meta_tools']	= $editor_meta_tools;
	}
	$element['type_label']		= $elements_types[$element['type']]['name'];


	wp_send_json( $element );
}

add_action("dbtoolkit_editor_templates", "dbtoolkit_render_fieldtype_templates");
function dbtoolkit_render_fieldtype_templates(){
	global $field_types;


	// no config template
	echo "<script type=\"text/html\" class=\"dbtoolkit-field-template\" data-type=\"_no_config_\">\r\n";
		echo "<div class=\"dbtoolkit-field-group\">\r\n";
			echo "<label>Default</label>\r\n";
			echo "<input type=\"text\" name=\"datasets[{{id}}][default]\" value=\"{{default}}\">\r\n";
		echo "</div>\r\n";
	echo "</script>";
	if(!empty($field_types)){
		foreach($field_types as $field_slug=>$field_config){
			if(!empty($field_config['setup']['template']) && file_exists($field_config['setup']['template'])){
				echo "<script type=\"text/html\" class=\"dbtoolkit-field-template\" data-type=\"" . $field_slug . "\">\r\n";
					include $field_config['setup']['template'];
				echo "</script>\r\n";
			}
		}
	}
	//dump($field_types);

}

add_action("dbtoolkit_editor_templates", "dbtoolkit_render_panel_templates");
function dbtoolkit_render_panel_templates(){
	$panel_templates = apply_filters( "dbtoolkit_get_element_panels", array() );
	if(!empty($panel_templates)){
		foreach($panel_templates as $panel_slug=>$panel){
			if(file_exists($panel['template'])){
			?>
			<script type="text/html" id="dbtoolkit-panel-<?php echo $panel_slug; ?>-tmpl">
				<div class="dbtoolkit-editor-panel" id="<?php echo $panel_slug; ?>" style="display:none;" <?php if( !empty($panel['callback'])){ echo 'data-callback="'.$panel['callback'].'"'; } ?>>
					<h2 class="dbtoolkit-panel-title"><?php echo $panel['title']; ?> <small class="dbtoolkit-panel-caption"><?php echo $panel['caption']; ?></small></h2>
					<?php include $panel['template']; ?>
				</div>
			</script>
			<?php
			}
		}
	}

	// options template
	echo "<script type=\"text/html\" id=\"dbtoolkit-options-field-option-tmpl\">\r\n";
	echo "{{#each option}}<div class=\"toggle_option_row\">\r\n";
		echo "<i class=\"dashicons dashicons-sort\" style=\"padding: 4px 9px;\"></i>\r\n";
		echo "<input type=\"checkbox\" class=\"toggle_set_default field-config\" name=\"datasets[{{../id}}][option][{{@key}}][default]\" value=\"1\">\r\n";
		echo "<input type=\"text\" class=\"toggle_value_field field-config\" name=\"datasets[{{../id}}][option][{{@key}}][value]\" value=\"{{value}}\" placeholder=\"value\">\r\n";
		echo "<input type=\"text\" class=\"toggle_label_field field-config\" name=\"datasets[{{../id}}][option][{{@key}}][label]\" value=\"{{label}}\" placeholder=\"label\">\r\n";
		echo "<button class=\"button toggle-remove-option\" type=\"button\"><i class=\"dashicons dashicons-no\" style=\"padding: 3px 0px;\"></i></button>		\r\n";
	echo "</div>{{/each}}\r\n";
	echo "</script>\r\n";
}

add_action("dbtoolkit_editor_templates", "dbtoolkit_render_datasets_templates");
function dbtoolkit_render_datasets_templates(){
	echo "<script type=\"text/html\" id=\"dbtoolkit-panel-dataset-group-tmpl\">\r\n";
		include DBTOOLKIT_PATH . "ui/templates/dataset/dataset-group-template.php";
	echo "</script>\r\n";
	echo "<script type=\"text/html\" id=\"dbtoolkit-panel-dataset-item-tmpl\">\r\n";
		include DBTOOLKIT_PATH . "ui/templates/dataset/dataset-group-item-template.php";
	echo "</script>\r\n";
	echo "<script type=\"text/html\" id=\"dbtoolkit-panel-dataset-item-config-tmpl\">\r\n";
		include DBTOOLKIT_PATH . "ui/templates/dataset/dataset-item-config-template.php";
	echo "</script>\r\n";
	echo "<script type=\"text/html\" id=\"dbtoolkit-panel-data-field-tmpl\">\r\n";
		include DBTOOLKIT_PATH . "ui/templates/data_connect/data-connect-fields-template.php";
	echo "</script>\r\n";

}












