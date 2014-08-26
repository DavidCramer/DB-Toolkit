<?php


// Add Admin menu page
add_action( 'admin_menu', 'dbtoolkit_register_admin_page' );

// Add admin scritps and styles
add_action( 'admin_enqueue_scripts', 'dbtoolkit_enqueue_admin_stylescripts' );

add_action( 'media_buttons', 'dbtoolkit_shortcode_insert_buttons', 11 );
function dbtoolkit_shortcode_insert_buttons(){
	global $post, $dbtoolkit_panel_templates;
	if(!empty($post)){

		$elements = dbtoolkit_get_active_elements('query_template');
		foreach($elements as $element){
			echo "<a id=\"dbtoolkit-insert-shortcode-".$element['id']."\" title=\"".__('Add Form to Page','db-toolkit')."\" class=\"button dbtoolkit-trigger\" data-modal-buttons=\"Close|dismiss\" data-modal=\"shortcode_insert\" data-modal-width=\"640\" data-modal-content=\"#dbtoolkit-shortcode-panel-" . $element['slug'] . "-tmpl\" data-modal-height=\"500\" data-modal-title=\"".$element['name']."\" href=\"#inst\">\n";
			echo $element['name'].' - ' .$element['slug'];
			echo "</a>\n";
			$dbtoolkit_panel_templates[] = $element['id'];
		}
	}
}

add_action( 'admin_footer', 'dbtoolkit_shortcode_panel_template');
function dbtoolkit_shortcode_panel_template(){
	global $dbtoolkit_panel_templates;
	$screen = get_current_screen();

	if( !empty($dbtoolkit_panel_templates) && in_array( $screen->base, array('post') ) ){
		foreach($dbtoolkit_panel_templates as $element_id){			
			$element = get_option( $element_id );
			if(!empty($element['variable_groups'])){
				echo "<script id=\"dbtoolkit-shortcode-panel-" . $element['slug'] . "-tmpl\">\r\n";

				foreach($element['variable_groups'] as $group_id=>$group_setup){
					dump($group_setup,0);
				}

				echo "</script>\r\n";
			}
		}
	}
}

function dbtoolkit_register_admin_page(){
	global $dbtoolkit_pages;

	$dbtoolkit_pages[] = add_menu_page( 'DB-Toolkit', 'DB-Toolkit', 'manage_options', 'dbtoolkit', 'dbtoolkit_build_admin_page', 'dashicons-menu' );
	add_submenu_page( 'dbtoolkit', 'DB-Toolkit', 'DB-Toolkit', 'manage_options', 'dbtoolkit', 'dbtoolkit_build_admin_page' );
	
	//foreach($pagesets as $setid=>$pageset){
	//	$this->screen_prefix[] 	 = add_submenu_page( 'dbtoolkit', 'DB-Toolkit - ' . $form['name'], '- '.$form['name'], 'manage_options', 'dbtoolkit-pin-' . $form_id, 'dbtoolkit_build_admin_page' );
	//}

}

function dbtoolkit_enqueue_admin_stylescripts(){
	global $dbtoolkit_pages, $field_types;

	$screen = get_current_screen();

	if( in_array( $screen->base, array('post') ) ){
		$elements = get_option( 'DBT_ELEMENTS' );
		foreach($elements as $element){
			if(empty($element['state'])){
				continue;
			}
			// modals
			wp_enqueue_style(  'dbtoolkit-modal-styles'			, DBTOOLKIT_URL . 'assets/css/modals.css'				, array()							, DBTOOLKIT_VER );
			// scripts
			wp_enqueue_script( 'dbtoolkit-handlebars'			, DBTOOLKIT_URL . 'assets/js/handlebars.js'			, array()							, DBTOOLKIT_VER );
			wp_enqueue_script( 'dbtoolkit-baldrick-handlebars'	, DBTOOLKIT_URL . 'assets/js/handlebars.baldrick.js'	, array('dbtoolkit-baldrick')	, DBTOOLKIT_VER );
			wp_enqueue_script( 'dbtoolkit-baldrick-modals'		, DBTOOLKIT_URL . 'assets/js/modals.baldrick.js'		, array('dbtoolkit-baldrick')	, DBTOOLKIT_VER );
			wp_enqueue_script( 'dbtoolkit-baldrick'				, DBTOOLKIT_URL . 'assets/js/jquery.baldrick.js'		, array('jquery')					, DBTOOLKIT_VER );
			wp_enqueue_script( 'dbtoolkit-app'					, DBTOOLKIT_URL . 'assets/js/admin-app.js'			, array('jquery')					, DBTOOLKIT_VER );

			break;
		}
		return;
	}

	if( !in_array( $screen->base, $dbtoolkit_pages ) ){
		return;
	}
	// include media scripts
	wp_enqueue_media();

	// load styles
	wp_enqueue_style( 'dbtoolkit-admin-styles'			, DBTOOLKIT_URL . 'assets/css/admin.css'				, array()							, DBTOOLKIT_VER );
	wp_enqueue_style( 'dbtoolkit-editor-styles'			, DBTOOLKIT_URL . 'assets/css/editor.css'			, array()							, DBTOOLKIT_VER );
	wp_enqueue_style( 'dbtoolkit-modal-styles'			, DBTOOLKIT_URL . 'assets/css/modals.css'			, array()							, DBTOOLKIT_VER );
	wp_enqueue_style( 'dbtoolkit-cm-styles'				, DBTOOLKIT_URL . 'assets/css/codemirror.css'		, array()							, DBTOOLKIT_VER );

	// load scripts
	wp_enqueue_script( 'dbtoolkit-cm-app'				, DBTOOLKIT_URL . 'assets/js/codemirror-compressed.js', array('jquery')					, DBTOOLKIT_VER );
	wp_enqueue_script( 'dbtoolkit-handlebars'			, DBTOOLKIT_URL . 'assets/js/handlebars.js'			, array()							, DBTOOLKIT_VER );
	wp_enqueue_script( 'dbtoolkit-baldrick-handlebars'	, DBTOOLKIT_URL . 'assets/js/handlebars.baldrick.js'	, array('dbtoolkit-baldrick')	, DBTOOLKIT_VER );
	wp_enqueue_script( 'dbtoolkit-baldrick-modals'		, DBTOOLKIT_URL . 'assets/js/modals.baldrick.js'		, array('dbtoolkit-baldrick')	, DBTOOLKIT_VER );
	wp_enqueue_script( 'dbtoolkit-baldrick'				, DBTOOLKIT_URL . 'assets/js/jquery.baldrick.js'		, array('jquery')					, DBTOOLKIT_VER );
	wp_enqueue_script( 'dbtoolkit-app'					, DBTOOLKIT_URL . 'assets/js/admin-app.js'			, array('jquery')					, DBTOOLKIT_VER );
	wp_enqueue_script( 'dbtoolkit-editor-app'			, DBTOOLKIT_URL . 'assets/js/editor-app.js'			, array('jquery')					, DBTOOLKIT_VER );	
	
	// panel support scripts ( jquery-ui etc.. )
	$panels = apply_filters( 'dbtoolkit_get_element_panels', array(), 1);
	if( !empty( $panels ) ){
		foreach($panels as $panel_id=>$panel){
			if(isset($panel['styles'])){
				// panel has styles
				foreach($panel['styles'] as $style){
					wp_enqueue_style( 'dbtoolkit-panel-'. sanitize_key( basename( $style ) ) .'-styles'				, $style		, array()							, DBTOOLKIT_VER );
				}
			}
			if(isset($panel['scripts'])){
				// panel has scripts
				foreach($panel['scripts'] as $script){
					if( false !== strpos($script, '/')){
						// url
						wp_enqueue_script( 'dbtoolkit-panel-'. sanitize_key( basename( $script ) )				, $script, array('jquery')					, DBTOOLKIT_VER );
					}else{
						// slug
						wp_enqueue_script( $script );
					}
				}
			}
		}
	}
	
	$field_types = apply_filters( 'dbtoolkit_get_field_types', array() );
	if( !empty( $field_types ) ){
		foreach($field_types as $field_type_id=>$field_type){
			if(isset($field_type['setup']['styles'])){
				// field_type has styles
				foreach($field_type['setup']['styles'] as $style_key=>$style){
					wp_enqueue_style( 'dbtoolkit-fieldtype-'. $field_type_id . sanitize_key( basename( $style ) ) .'-styles'				, $style		, array()							, DBTOOLKIT_VER );
				}
			}
			if(isset($field_type['setup']['scripts'])){
				// field_type has scripts
				foreach($field_type['setup']['scripts'] as $script){
					if( false !== strpos($script, '/')){
						// url						
						wp_enqueue_script( 'dbtoolkit-fieldtype-'. $field_type_id . sanitize_key( basename( $script ) )				, $script, array('jquery')					, DBTOOLKIT_VER );
					}else{
						// slug
						wp_enqueue_script( $script );
					}
				}
			}
		}
	}
}

function dbtoolkit_build_admin_page(){
	include DBTOOLKIT_PATH . 'ui/admin.php';
}


// helper function to get active elements of a specific type
function dbtoolkit_get_active_elements($type = null){

	$elements = get_option( 'DBT_ELEMENTS' );
	$returns = array();
	foreach($elements as $element_id=>$element_def){

		if( !empty($element_def['state']) ){
			if(!empty($type)){
				if($element_def['type'] !== $type){
					continue;
				}
			}
			$returns[$element_id] = get_option( $element_id );
		}
	}

	return $returns;
}











