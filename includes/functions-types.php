<?php




add_filter( "dbtoolkit_get_element_types", "dbtoolkit_load_element_types", 1);
function dbtoolkit_load_element_types(){
	// need to make this a filter.
	$elements_types = array(
		'sql_query'				=>	array(
			'name'				=>	"SQL Query",
			'type'				=>	'data',
			'processor'			=>	'dbtoolkit_run_sql',
			'structure'			=>	'dbtoolkit_sql_structure',
			'editors'			=>	array(
				'sql' 			=>	array(
					'label'		=>	'SQL',
					'mode'		=>	'text/x-mysql',
					'type'		=>	'sql'
				)
			),
		),
		'cf_form_data'	=>	array(
			'name'			=>	"Caldera Form Data",
			'type'			=>	'data',
			'processor'		=>	'dbtoolkit_cf_form_get_entries',
			'structure'		=>	'dbtoolkit_cf_form_structure',
			'panels'		=>	array(
				'cf_form_data'
			)
		),
		'data_grid'	=>	array(
			'name'			=>	"Data Grid",
			'type'			=>	'display',
			'renderer'		=> 	'render_cf_data_grid',
			'panels'		=>	array(
				'cf_data_grid'
			),
			'scripts'	=> array(
				DBTOOLKIT_URL . 'assets/js/jquery.bootgrid.min.js'
			),
			'styles'	=> array(
				DBTOOLKIT_URL . 'assets/css/jquery.bootgrid.css'
			),
		),
		'query_template'	=>	array(
			'name'			=>	"Template",
			'type'			=>	'display',
			'panels'		=>	array(
				'data_connect',
			),
			'editors'			=>	array(
				'html'			=>	array(
					'label'		=>	'Template',
					'mode'		=>	'text/html',
					'type'		=>	'html'
				),
				'script'		=>	array(
					'label'		=>	'Scripts',
					'mode'		=>	'text/javascript',
					'type'		=>	'javascript'
				)
			),
		),
		'dataset'			=>	array(
			'name'			=>	"Dataset",
			'type'			=>	'data',
			'processor'		=>	'dbtoolkit_rundata_set',
			'structure'		=>	'dbtoolkit_dataset_struct',
			'panels'		=>	array(
				'dataset_builder'
			)
		)

	);

	return $elements_types;
}