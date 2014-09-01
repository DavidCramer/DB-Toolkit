<?php




// filter to register panels
add_filter( "dbtoolkit_get_element_panels", "dbtoolkit_load_element_panels", 1);
function dbtoolkit_load_element_panels($panels){

	$panels = array(

		'dataset_builder'	=>	array(
			'label'		=>	'Dataset',
			'title'		=>	'Dataset Builder',
			'caption'	=>	'builds a complex dataset',
			'functions'	=>	DBTOOLKIT_PATH . 'includes/functions-local-table.php',
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
		'dataset_filters'	=>	array(
			'label'		=>	'Filters',
			'title'		=>	'Dataset Filters',
			'caption'	=>	'setup dataset filtering rules',
			'template'	=>	DBTOOLKIT_PATH . 'ui/templates/dataset/dataset-filters-template.php',
		)
	);

	return $panels;
}
