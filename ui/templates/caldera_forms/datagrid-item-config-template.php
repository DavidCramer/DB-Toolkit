{{#each fields}}
	<div class="dbtoolkit-datagrid-config-group" id="{{ID}}">
		<h3><span class="dashicons dashicons-admin-settings"></span> <span class="label_{{ID}}">{{label}}</span> <small class="dbtoolkit-panel-caption slug_{{ID}}">{{slug}}</small></h3>
		<?php include DBTOOLKIT_PATH . "ui/templates/caldera_forms/datagrid-item-config-settings.php"; ?>
	</div>
{{/each}}