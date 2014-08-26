<ul class="dbtoolkit-panel-controls">
	<li style="margin-bottom:0;"><span 
	class="dbtoolkit-panel-control-icon dbtoolkit-trigger dashicons dashicons-admin-page"
	data-request="dbt_add_dataset_group"
	data-target="#ce-datasets-wrap"
	data-target-insert="append"
	data-template="#dbtoolkit-panel-dataset-group-tmpl"
	data-callback="dbt_reset_dataset_sortables"
	></span></li>	
</ul>
<div class="dbtoolkit-dataset-wrapper" id="ce-datasets-wrap">
<?php include DBTOOLKIT_PATH . "ui/templates/dataset/dataset-group-template.php"; ?>
</div>
<div class="dbtoolkit-dataset-items-config-wrapper" id="ce-datasets-items-config-wrap">
<?php include DBTOOLKIT_PATH . "ui/templates/dataset/dataset-item-config-template.php"; ?>
</div>