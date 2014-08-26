{{#each dataset_groups}}
	{{#each datasets}}
		<div class="dbtoolkit-dataset-config-group" id="{{id}}">
			<div class="dbtoolkit-panel-toggle-buttons">
				<button class="button button-small active">Settings</button>
				<button class="button button-small">Conditionals</button>
			</div>
			<h3><span class="dashicons dashicons-admin-settings"></span> <span class="label_{{id}}">{{label}}</span> <small class="dbtoolkit-panel-caption slug_{{id}}">{{slug}}</small></h3>
			<?php include DBTOOLKIT_PATH . "ui/dataset/templates/dataset-item-config-settings.php"; ?>
		</div>
	{{/each}}
{{/each}}