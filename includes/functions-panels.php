<?php




// filter to register panels
add_filter( "dbtoolkit_get_element_panels", "dbtoolkit_load_element_panels", 1);
function dbtoolkit_load_element_panels($panels){

	$panels = array(
		
		'variables'	=>	array(
			'label'		=>	'Fields',
			'title'		=>	'Fields & Options',
			'caption'	=>	'add fields to create setting controls',
			'template'	=>	DBTOOLKIT_PATH . 'ui/templates/variable/variables-template.php',
			'callback'	=>	'dbt_reset_variable_sortables',
			'styles'	=>	array(
				DBTOOLKIT_URL . 'assets/css/variables-panel.css'
			),
			'scripts'	=>	array(
				DBTOOLKIT_URL . 'assets/js/variables-panel.js',
				'jquery-ui-core',
				'jquery-ui-sortable'
			)
		),
		'dataset_builder'	=>	array(
			'label'		=>	'Dataset',
			'title'		=>	'Dataset Builder',
			'caption'	=>	'builds a complex dataset',
			'template'	=>	DBTOOLKIT_PATH . 'ui/templates/dataset/dataset-template.php',
			'callback'	=>	'dbt_reset_dataset_sortables',
			'styles'	=>	array(
				DBTOOLKIT_URL . 'assets/css/dataset-panel.css'
			),
			'scripts'	=>	array(
				DBTOOLKIT_URL . 'assets/js/dataset-panel.js',
				'jquery-ui-core',
				'jquery-ui-sortable'
			)
		),
		'data_connect' =>	array(
			'label'		=>	'Data',
			'title'		=>	'Data Source',
			'caption'	=>	'connect to data source interface',
			'functions'	=>	DBTOOLKIT_PATH . 'includes/functions-data-connect.php',
			'template'	=>	DBTOOLKIT_PATH . 'ui/templates/data_connect/data-connect-template.php',
			'scripts'	=>	array(
				DBTOOLKIT_URL . 'assets/js/data-connect-panel.js',
			)
		),
		'cf_form_data' =>	array(
			'label'		=>	'Caldera Form',
			'title'		=>	'Caldera Form Data',
			'caption'	=>	'connect to entries from a Caldera Form',
			'functions'	=>	DBTOOLKIT_PATH . 'includes/functions-cf-connect.php',
			'template'	=>	DBTOOLKIT_PATH . 'ui/templates/caldera_forms/cf-connect-template.php',
		),
		'cf_data_grid' =>	array(
			'label'		=>	'Form',
			'title'		=>	'Caldera Form Fields',
			'caption'	=>	'set field visibility, sorting and order',
			'functions'	=>	DBTOOLKIT_PATH . 'includes/functions-cf-connect.php',
			'template'	=>	DBTOOLKIT_PATH . 'ui/templates/caldera_forms/datagrid-template.php',
			'callback'	=>	'dbt_reset_datagrid_sortables',
			'styles'	=>	array(
				DBTOOLKIT_URL . 'assets/css/datagrid-panel.css'
			),
			'scripts'	=>	array(
				DBTOOLKIT_URL . 'assets/js/datagrid-panel.js',
				'jquery-ui-core',
				'jquery-ui-sortable'
			)
		),

	);

	return $panels;
}
