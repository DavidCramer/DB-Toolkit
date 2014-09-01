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
		'db_table'			=>	array(
			'name'			=>	"Local Table",
			'type'			=>	'data',
			'processor'		=>	'dbtoolkit_build_local_query',
			'structure'		=>	'dbtoolkit_dataset_struct',
			'panels'		=>	array(
				'dataset_builder',
				'dataset_filters'
			)
		)

	);

	return $elements_types;
}