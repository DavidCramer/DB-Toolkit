<?php
/**
*	Editor Tools Template
*
*/
?>
<script type="text/html" id="dbtoolkit-editor-tools-tmpl">
	{{#if error}}
		<li class="dbtoolkit-element-tool dbtoolkit-element-tool-error" style="opacity:0;margin-left: -10px;"><span class="wp-filter-link">{{error}}</span> </li>
	{{/if}}
	{{#if confirm}}
		<li class="dbtoolkit-element-tool dbtoolkit-element-tool-error" style="opacity:0;margin-left: -10px;"><span class="wp-filter-link">{{confirm}}</span> </li>
	{{/if}}
	
	{{#each tools}}
		<li class="dbtoolkit-element-tool" style="opacity:0;margin-left: -10px;"><a class="wp-filter-link dbtoolkit-editor-tab dbtoolkit-trigger" data-panel="{{slug}}" data-active-class="current" data-group="editor-tabs" {{#if default}}data-autoload="true"{{/if}} data-request="dbt_toggle_editor_tab" data-callback="dbt_switch_editor_tab" href="#{{slug}}">{{label}}</a> </li>
	{{/each}}
	{{#if panels}}
		<li class="dbtoolkit-element-tool dbtoolkit-element-tool-separator"><a class="wp-filter-link">&nbsp;</a></li>
		{{#each panels}}
			<li class="dbtoolkit-element-tool" style="opacity:0;margin-left: -10px;"><a class="wp-filter-link dbtoolkit-editor-tab dbtoolkit-trigger" data-panel="{{slug}}" data-active-class="current" data-group="editor-tabs" data-request="dbt_toggle_editor_tab" data-callback="dbt_switch_editor_tab" href="#{{slug}}">{{label}}</a> </li>
		{{/each}}		
	{{/if}}
	{{#if editors}}
	<li class="dbtoolkit-element-tool dbtoolkit-element-tool-separator"><a class="wp-filter-link">&nbsp;</a></li>
	{{/if}}
	{{#each editors}}
		<li class="dbtoolkit-element-tool" style="opacity:0;margin-left: -10px;"><a class="wp-filter-link dbtoolkit-editor-tab dbtoolkit-trigger" data-panel="{{slug}}" data-active-class="current" data-group="editor-tabs" data-request="dbt_toggle_editor_tab" data-callback="dbt_switch_editor_tab" href="#{{slug}}">{{label}}</a> </li>
	{{/each}}	
	{{#if meta_tools}}
		<li class="dbtoolkit-element-tool dbtoolkit-element-tool-separator"><a class="wp-filter-link">&nbsp;</a></li>
		{{#each meta_tools}}
			<li class="dbtoolkit-element-tool" style="opacity:0;margin-left: -10px;"><a class="wp-filter-link dbtoolkit-editor-tab dbtoolkit-trigger" data-panel="{{slug}}" {{#each attributes}}{{@key}}="{{this}}" {{/each}}href="#{{slug}}">{{label}}</a> </li>
		{{/each}}
	{{/if}}
	<li class="dbtoolkit-element-tool dbtoolkit-element-tool-separator"><a class="wp-filter-link">&nbsp;</a></li>
	{{#if confirm}}
	<li class="dbtoolkit-element-tool dbtoolkit-close-element-tool" style="opacity:0;margin-left: -10px;"><a class="wp-filter-link dbtoolkit-trigger"
		id="dbtoolkit-close-editor"
		href="#close-element"
		data-request="dbt_close_editor"
		data-target="#dbtoolkit-toolbar"
		data-template="#dbtoolkit-admin-menus-tmpl"
		data-before="dbt_clear_canvas"
		data-callback="dbt_reveal_tools"
		{{#if error}}
			data-autoload="true"
			data-delay="2000"
		{{/if}}
	>Close, without saving</a></li>
	{{/if}}	
	{{#unless confirm}}
	<li class="dbtoolkit-element-tool dbtoolkit-close-element-tool" style="opacity:0;margin-left: -10px;"><a class="wp-filter-link dbtoolkit-trigger"
		id="dbtoolkit-close-editor"
		href="#close-element"
		data-action="dbt_close_editor"
		data-target="#dbtoolkit-toolbar"
		data-template="#dbtoolkit-editor-tools-tmpl"
		data-before="dbt_check_state"
		data-callback="dbt_reveal_tools"
		data-element="{{id}}"
		{{#if error}}
			data-autoload="true"
			data-delay="2000"
		{{/if}}
	>Close</a></li>
	<li style="display:none;">
	<?php 
	// LOAD GENERAL SETTINGS
	?>
	<span class="dbtoolkit-trigger" data-event="none" data-request="dbt_get_screen_canvas_data" data-target="#dbtoolkit-canvas" data-target-insert="append" data-template="#dbtoolkit-general-settings-tmpl" {{#unless menuonly}}data-autoload="true"{{/unless}}></span>

	<?php 
	// INIT EDITORS
	?>
	<span class="dbtoolkit-trigger" data-event="none" data-request="dbt_get_screen_canvas_data" data-callback="dbt_init_editors" data-target="#dbtoolkit-canvas" data-target-insert="append" data-template="#dbtoolkit-code-editors-tmpl" {{#unless menuonly}}data-autoload="true"{{/unless}}></span>

	{{#if panels}}
	<?php
	// INIT PANELS	
	?>
	{{#each panels}}
	<span class="dbtoolkit-trigger" data-event="none" data-request="dbt_get_screen_canvas_data" data-target="#dbtoolkit-canvas" data-target-insert="append" data-template="#dbtoolkit-panel-{{slug}}-tmpl" {{#unless ../menuonly}}data-autoload="true"{{/unless}}></span>
	{{/each}}
	{{/if}}


	</li>
	{{/unless}}
</script>
<?php
/**
*	Code Editors Template
*
*/
?>
<script type="text/html" id="dbtoolkit-code-editors-tmpl">

{{#each editors}}
<div class="dbtoolkit-code-editor dbtoolkit-editor-panel" data-slug="{{slug}}" id="{{slug}}" data-callback="dbt_reset_editor" style="display:none;">
	<h2 class="dbtoolkit-panel-title">{{label}} <small class="dbtoolkit-panel-caption">{{type}}</small></h2>
	<textarea name="code[{{slug}}]" id="editor_textarea{{slug}}">{{code}}</textarea>
</div>
{{/each}}

</script>
<?php
/**
*	General Settings Template
*
*/
?>
<script type="text/html" id="dbtoolkit-general-settings-tmpl">
<div class="dbtoolkit-editor-panel" id="general_settings">
	<h2 class="dbtoolkit-panel-title">General Settings <small class="dbtoolkit-panel-caption">general element details</small></h2>
	<input name="id" type="hidden" value="{{id}}" id="element_id">
	<input name="type" type="hidden" value="{{type}}" id="element_id">
	<div id="setup_name" class="dbtoolkit-field-group">
		<label>Interface Name</label>
		<input name="name" type="text" value="{{name}}" id="element_name">
	</div>
	<div id="setup_description" class="dbtoolkit-field-group">
		<label>Interface Description</label>
		<input name="description" type="text" value="{{description}}" id="element_description">
	</div>
	<div id="setup_project" class="dbtoolkit-field-group">
		<label>Project</label>
		<input name="project" type="text" autocomplete="off" value="{{project}}" id="element_project">
	</div>
	<div id="setup_slug" class="dbtoolkit-field-group">
		<label>Slug</label>
		<input name="slug" type="text" value="{{slug}}" data-format="slug" id="element_slug">
	</div>
</div>
</script>
<?php
/**
 * Pull in Panel & fieldtype templates
 */
do_action( "dbtoolkit_editor_templates" );
