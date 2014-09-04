{{#each fields}}
	<div class="dbtoolkit-dataset-config-group" id="field-config-{{Field}}">
		<div class="dbtoolkit-panel-toggle-buttons">
			<button class="button button-small active" data-panel="{{Field}}-handler-panel">Handler</button>
			<button class="button button-small" data-panel="{{Field}}-filter-panel">Filters</button>
		</div>
		<h3><span class="dashicons dashicons-admin-settings"></span> <span class="label_{{Field}}">{{Field}}</span> <small class="dbtoolkit-panel-caption slug_{{Field}}">{{Type}}</small></h3>
		<div id="{{Field}}-handler-panel" class="dbtoolkit-panel">
		<?php include DBTOOLKIT_PATH . "ui/templates/dataset/dataset-item-config-settings.php"; ?>
		</div>
		<div id="{{Field}}-filter-panel" class="dbtoolkit-panel" style="display:none;">
		<?php include DBTOOLKIT_PATH . "ui/templates/dataset/dataset-item-config-filters.php"; ?>
		</div>
	</div>
{{/each}}