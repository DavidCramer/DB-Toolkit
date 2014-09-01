<div><button class="button" type="button">Add Filter</button></div>
{{#each filter}}
	<?php include DBTOOLKIT_PATH . "ui/templates/dataset/dataset-filter-item-template.php"; ?>
{{/each}}