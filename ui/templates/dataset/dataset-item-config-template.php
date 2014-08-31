{{#each fields}}
	<div class="dbtoolkit-dataset-config-group" id="{{Field}}">
		<h3><span class="dashicons dashicons-admin-settings"></span> <span class="label_{{Field}}">{{Field}}</span> <small class="dbtoolkit-panel-caption slug_{{Field}}">{{Type}}</small></h3>
		<?php include DBTOOLKIT_PATH . "ui/templates/dataset/dataset-item-config-settings.php"; ?>
	</div>
{{/each}}