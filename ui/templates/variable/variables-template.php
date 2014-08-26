<ul class="dbtoolkit-panel-controls">
	<li style="margin-bottom:0;"><span 
	class="dbtoolkit-panel-control-icon dbtoolkit-trigger dashicons dashicons-admin-page"
	data-request="dbt_add_variable_group"
	data-target="#ce-variables-wrap"
	data-target-insert="append"
	data-template="#dbtoolkit-panel-variable-group-tmpl"
	data-callback="dbt_reset_variable_sortables"
	></span></li>	
</ul>
<div class="dbtoolkit-variable-wrapper" id="ce-variables-wrap">
<?php include DBTOOLKIT_PATH . "ui/templates/variable/variable-group-template.php"; ?>
</div>
<div class="dbtoolkit-variable-items-config-wrapper" id="ce-variables-items-config-wrap">
<?php include DBTOOLKIT_PATH . "ui/templates/variable/variable-item-config-template.php"; ?>
</div>