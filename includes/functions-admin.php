<?php

// include panel definitions

// include types




add_action( "wp_ajax_dbt_load_projects", "dbtoolkit_load_projects" );
function dbtoolkit_load_projects(){
	

	$elements_types = apply_filters( "dbtoolkit_get_element_types", array() );

	$elements = get_option( 'DBT_ELEMENTS' );


	if( empty( $elements ) ){
		wp_send_json( array('message' => 'No Interfaces Available' ) );
	}

	$projects = array();
	$out = array(
		'project' => array(),
		'count' => 0,
		"list_type"		=>	str_replace('#', '', $_POST['href'])
	);
	if(empty($_POST['filter'])){
		$out['current'] = true;
	}
	foreach( $elements as $element_id=>$element ){
		if(!isset($elements_types[$element['type']])){
			continue;
		}
		
		if(!empty($element['project'])){
			$project = $element['project'];
		}else{
			$project = 'Ungrouped';
		}

		if($_POST['href'] == '#elements'){
			$filter = $element['type'];
			$filter_name = $elements_types[$filter]['name'];
		}
		if($_POST['href'] == '#projects'){
			$filter = $project;
			$filter_name = ucfirst( str_replace('_', ' ', $project ) );
		}

		if(!isset($out['filter'][$filter])){
			$out['filter'][$filter] = array(
				'type'	=>	$filter_name,
				'count' =>	0
			);
			if(!empty($_POST['filter']) && $_POST['filter'] === $filter){
				$out['filter'][$filter]['current'] = true;
			}
			if(!empty($_POST['project']) && $_POST['project'] === $project){
				$out['project'][$filter]['current'] = true;
			}
		}

		$out['filter'][$filter]['count'] += 1;
		$out['count']	+= 1;
		if(!empty($_POST['filter']) && $_POST['filter'] !== $filter){
			continue;
		}
		if(!empty($_POST['project']) && $_POST['project'] !== $project){
			continue;
		}
		$element_item = array(
			"id"			=>	$element_id,
			"name" 			=> 	$element['name'],
			"description" 	=> 	$element['description'],
			"type"			=>	$elements_types[$element['type']]['name'],
			"element"		=>	$element
		);
		if(!empty($element['state'])){
			$element_item['state'] = true;
		}
		$projects[] = $element_item;


	};
	$out['project'] = $projects;
	$out[$out['list_type']] = true;

	wp_send_json( $out );
}


function dbtoolkit_get_legacy_type($type){
	
	$elements_types = apply_filters( "dbtoolkit_get_element_types", array() );

	if(!isset($elements_types[$type])){
		// find legacy
		foreach($elements_types as $type_slug=>$element_type){
			if(isset($element_type['legacy'])){
				if($element_type['legacy'] == $type){
					return $type_slug;
				}
			}
		}

	}
	if(!isset($elements_types[$type])){
		return false;
	}
}

// create new element
add_action( "wp_ajax_dbt_create_element", "dbtoolkit_create_element" );
function dbtoolkit_create_element(){

	// build new element
	$new_element = array(
		'id'			=>	strtoupper(uniqid('DBT')),
		'name'			=>	$_POST['element_name'],
		'description'	=>	$_POST['element_description'],
		'slug'			=>	$_POST['element_slug'],
		'project'		=>	$_POST['element_project'],
		'type'			=>	$_POST['element']
	);

	// register new element
	$elements = get_option('DBT_ELEMENTS');
	if(empty($elements)){
		$elements = array();
	}
	$elements[$new_element['id']] = $new_element;
	update_option( 'DBT_ELEMENTS', $elements );

	// save new element
	update_option( $new_element['id'], $new_element);

	// load new element and send to editor
	dbtoolkit_load_element( $new_element['id'] );

}

// activate an element
add_action( "wp_ajax_dbt_activate_element", "dbtoolkit_activate_element" );
function dbtoolkit_activate_element(){
	$elements = get_option( 'DBT_ELEMENTS' );	
	if(empty($elements[$_POST['element']])){
		wp_send_json( array('message'=>'error, invalid element') );
	}
	if(isset($elements[$_POST['element']]['state'])){
		unset($elements[$_POST['element']]['state']);
		$message = 'Activate';
	}else{
		$elements[$_POST['element']]['state'] = 'active';
		$message = 'Deactivate';
	}
	update_option( 'DBT_ELEMENTS', $elements );

	wp_send_json( array('message' => $message) );
}

// create new element
add_action( "wp_ajax_dbt_element_handler", "dbtoolkit_element_handler" );
function dbtoolkit_element_handler(){

	$elements = get_option( 'DBT_ELEMENTS' );
	if(empty($elements[$_POST['id']])){
		wp_send_json( array('message'=>'error, invalid element') );
	}
	$data = stripslashes_deep( $_POST );
	foreach( $elements[$data['id']] as $field=>&$value){
		if(isset($data[$field])){
			$value = $data[$field];
		}
	}

	// update regestry
	update_option( 'DBT_ELEMENTS', $elements );

	// send variables to groups
	if(!empty($data['variable_groups'])){
		if(empty($data['variables'])){
			unset($data['variable_groups']);
		}else{
			foreach($data['variable_groups'] as $group_id=>&$group){
				$group['id'] = $group_id;
				// move variables to thier groups
				foreach($data['variables'] as $variable_id=>$variable){
					if($variable['group'] == $group_id){
						$variable['id'] = $variable_id;
						$group['variables'][$variable_id] = $variable;
						unset($data['variables'][$variable_id]);
					}
				}
				// clear empty groups
				if(empty($group['variables'])){
					unset($data['variable_groups'][$group_id]);
				}
			}
		}
		unset($data['variables']);
	}
	
	update_option( $data['id'], $data );

	wp_send_json( array('message' => 'Interface updated' ) );
}










