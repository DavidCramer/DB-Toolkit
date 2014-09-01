<?php




add_filter( "dbtoolkit_get_element_types", "dbtoolkit_load_element_types", 1);
function dbtoolkit_load_element_types(){
	// need to make this a filter.
	$elements_types = array(
		/*'sql_query'				=>	array(
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
		),*/
		'db_table'			=>	array(
			'name'			=>	"Local Table",
			'type'			=>	'data',
			'processor'		=>	'dbtoolkit_build_local_query',
			'structure'		=>	'dbtoolkit_get_local_structure',
			'panels'		=>	array(
				'dataset_builder',
				'dataset_filters'
			)
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

	);

	return $elements_types;
}