{{#each variable_groups}}
	{{#each variables}}
		<div class="dbtoolkit-variable-config-group" id="{{id}}">
			<div class="dbtoolkit-panel-toggle-buttons">
				<button class="button button-small active">Settings</button>
				<button class="button button-small">Conditionals</button>
			</div>
			<h3><span class="dashicons dashicons-admin-settings"></span> <span class="label_{{id}}">{{label}}</span> <small class="dbtoolkit-panel-caption slug_{{id}}">{{slug}}</small></h3>
			<?php include DBTOOLKIT_PATH . "ui/variable/templates/variable-item-config-settings.php"; ?>
		</div>
	{{/each}}
{{/each}}