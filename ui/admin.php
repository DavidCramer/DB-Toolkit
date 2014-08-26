<?php

global $dbt_elements_types;

?><div class="wrap">
	<h2 class="dbtoolkit-h2"><span id="dbtoolkit-page-title">DB-Toolkit</span> <small id="dbtoolkit-page-caption" data-version="<?php echo DBTOOLKIT_VER; ?>" style="font-size: 11px; line-height: 10px; color: rgb(159, 159, 159);"><?php echo DBTOOLKIT_VER; ?></small> <span id="loading-indicator"><span class="spinner"></span></span></h2>
	<div class="wp-filter">
		<ul class="wp-filter-links" id="dbtoolkit-toolbar">
		<?php include DBTOOLKIT_PATH . 'ui/admin-menus.php'; ?>
		</ul>
	</div>
	<form id="dbtoolkit-canvas" data-action="dbt_element_handler"></form>
</div>

<?php
// pull in templates

// admin specific
include_once DBTOOLKIT_PATH . 'ui/templates/admin/admin-templates.php';

// editor specific
include_once DBTOOLKIT_PATH . 'ui/templates/editor/editor-templates.php';

?>
<script type="text/javascript">
	
	/*Init*/
	dbt_reveal_tools();

</script>