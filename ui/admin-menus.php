<?php
$dbt_elements_types = apply_filters( "dbtoolkit_get_element_types", array() );
?>
<li class="dbtoolkit-element-tool dbtoolkit-projects" style="opacity:0;margin-left: -10px;"><a class="wp-filter-link dbtoolkit-trigger" href="#projects" data-action="dbt_load_projects" data-active-class="current" data-group="filter-nav" data-callback="dbt_reset_screen_state" data-template="#elements-list-tmpl" data-autoload="true" data-target="#dbtoolkit-canvas">Projects</a> </li>
<li class="dbtoolkit-element-tool dbtoolkit-elements" style="opacity:0;margin-left: -10px;"><a class="wp-filter-link dbtoolkit-trigger" href="#elements" data-action="dbt_load_projects" data-active-class="current" data-group="filter-nav" data-callback="dbt_reset_screen_state" data-template="#elements-list-tmpl" data-target="#dbtoolkit-canvas">Interfaces</a> </li>
<li class="dbtoolkit-element-tool dbtoolkit-element-tool-separator"><a class="wp-filter-link">&nbsp;</a></li>
<li class="dbtoolkit-element-tool dbtoolkit-new" style="opacity:0;margin-left: -10px;"><span class="wp-filter-link" >New Data Source</span>
<ul><?php			
	foreach( $dbt_elements_types as $type_slug=>$element_type){
		if($element_type['type'] != 'data'){
			continue;
		}
		$create_button = __('Create '.$element_type['name']).'|{"data-element" : "'.$type_slug.'", "data-action" : "dbt_create_element", "data-active-class": "disabled", "data-load-class": "disabled", "data-callback": "dbt_reveal_tools", "data-before" : "dbt_build_new_element", "data-target" : "#dbtoolkit-toolbar", "data-template" : "#dbtoolkit-editor-tools-tmpl", "data-modal-autoclose" : "new_element" }';
	?>
	<li><a class="wp-filter-link dbtoolkit-trigger" href="#new-elements" data-request="dbt_create_new_element" data-modal="new_element" data-modal-title="New <?php echo $element_type['name']; ?>" data-modal-buttons='<?php echo $create_button; ?>' data-modal-height="288px" data-modal-width="530px" data-active-class="current" data-group="filter-nav" data-elementtype="<?php echo $type_slug; ?>" data-template="#create-new-element-tmpl"><?php echo $element_type['name']; ?></a></li>
<?php } ?>
</ul>
</li>
<li class="dbtoolkit-element-tool dbtoolkit-new" style="opacity:0;margin-left: -10px;"><span class="wp-filter-link" >New Data View</span>
<ul><?php			
	foreach( $dbt_elements_types as $type_slug=>$element_type){
		if($element_type['type'] != 'display'){
			continue;
		}
		$create_button = __('Create '.$element_type['name']).'|{"data-element" : "'.$type_slug.'", "data-action" : "dbt_create_element", "data-active-class": "disabled", "data-load-class": "disabled", "data-callback": "dbt_reveal_tools", "data-before" : "dbt_build_new_element", "data-target" : "#dbtoolkit-toolbar", "data-template" : "#dbtoolkit-editor-tools-tmpl", "data-modal-autoclose" : "new_element" }';
	?>
	<li><a class="wp-filter-link dbtoolkit-trigger" href="#new-elements" data-request="dbt_create_new_element" data-modal="new_element" data-modal-title="New <?php echo $element_type['name']; ?>" data-modal-buttons='<?php echo $create_button; ?>' data-modal-height="288px" data-modal-width="530px" data-active-class="current" data-group="filter-nav" data-elementtype="<?php echo $type_slug; ?>" data-template="#create-new-element-tmpl"><?php echo $element_type['name']; ?></a></li>
<?php } ?>
</ul>
</li>
<li class="dbtoolkit-element-tool dbtoolkit-element-tool-separator"><a class="wp-filter-link">&nbsp;</a></li>
<li class="dbtoolkit-element-tool dbtoolkit-elements" style="opacity:0;margin-left: -10px;"><a class="wp-filter-link dbtoolkit-trigger" href="#elements" data-action="dbt_load_projects" data-active-class="current" data-group="filter-nav" data-template="#elements-list-tmpl" data-target="#dbtoolkit-canvas">Import</a> </li>
<li class="dbtoolkit-element-tool dbtoolkit-elements" style="opacity:0;margin-left: -10px;"><a class="wp-filter-link dbtoolkit-trigger" href="#elements" data-action="dbt_load_projects" data-active-class="current" data-group="filter-nav" data-template="#elements-list-tmpl" data-target="#dbtoolkit-canvas">Export</a> </li>