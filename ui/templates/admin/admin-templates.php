<script type="text/html" id="elements-list-tmpl">
{{#if filter}}
	<div class="wp-filter wp-filter-small wp-filter-{{list_type}}">
		<ul class="wp-filter-links">
		<li><a class="wp-filter-link dbtoolkit-trigger {{#if current}}current{{/if}}" href="#{{list_type}}" data-action="dbt_load_projects" data-active-class="current" data-group="filter-sub-nav" data-template="#elements-list-tmpl" data-target="#dbtoolkit-canvas">All <span class="count">{{count}}</span></a></li>
		{{#if elements}}
			<li class="dbtoolkit-element-tool dbtoolkit-element-tool-separator"><a class="wp-filter-link">&nbsp;</a></li>
		{{/if}}
	{{#each filter}}
		<li><a class="wp-filter-link dbtoolkit-trigger {{#if current}}current{{/if}}" href="#{{../list_type}}" data-filter="{{@key}}" data-action="dbt_load_projects" data-active-class="current" data-group="filter-sub-nav" data-template="#elements-list-tmpl" data-target="#dbtoolkit-canvas">{{{type}}} <span class="count">{{count}}</span></a></li>
	{{/each}}
		</ul>
	</div>
{{/if}}
<div class="db-toolkit-list-{{list_type}}">
	{{#if message}}
	<p class="description" style="margin-left: 30px;">{{message}}</p>
	{{/if}}
	{{#each project}}
	<div id="element-{{id}}" style="margin: 0 10px 10px 0; width: 330px; float: left; height: 78px; overflow: hidden; border: 1px solid #e5e5e5; box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04); background:#fff; color:#333;position: relative;">
		<h2 style="height: 28px; margin: 0px; font-size: 16px; padding: 6px 12px; text-shadow:0 0 2px #fff;">{{name}} <small style="color: rgb(159, 159, 159); font-style: italic;"> {{type}}</small></h2>
		<div style="margin: 0px; padding: 6px 12px; overflow: auto; height: 22px; display:none;">
			{{{description}}}
		</div>
		<div style="position: absolute; bottom: 0px; padding: 6px; background: none repeat scroll 0 0 rgba(0, 0, 0, 0.03); left: 0px; right: 0px; border-top: 1px solid #e5e5e5;">
			<a id="activate-toggle-{{id}}" class="button button-small dbtoolkit-trigger {{#if state}}button-primary{{/if}}" href="#activate_element"
				data-action="dbt_activate_element"
				data-active-class="disabled"
				data-group="{{id}}"
				data-element="{{id}}"
				data-target="#element_{{id}}"
				data-target-insert="replace"
				data-callback="dbt_toggle_activation"
			>{{#if state}}Deactivate{{else}}Activate{{/if}}</a>
			<a class="button button-small dbtoolkit-trigger"
				href="#edit-{{id}}"
				data-action="dbt_load_element"
				data-element="{{id}}"
				data-target="#dbtoolkit-toolbar"
				data-template="#dbtoolkit-editor-tools-tmpl"
				data-before="dbt_clear_canvas"
				data-callback="dbt_reveal_tools"
			>Edit</a>
			<a class="button button-small" href="{{footer/link}}" target="_blank">Clone</a>
			<a class="button button-small right" href="{{footer/link}}" target="_blank">Delete</a>
		</div>
	</div>
	{{/each}}
</div>
</script>
<script type="text/html" id="dbtoolkit-admin-menus-tmpl">
<?php include DBTOOLKIT_PATH . 'ui/admin-menus.php'; ?>
</script>
<script type="text/html" id="create-new-element-tmpl">
<div id="setup_name" class="dbtoolkit-field-group">
	<label>Interface Name</label>
	<input type="text" value="" id="new_element_name">
</div>
<div id="setup_description" class="dbtoolkit-field-group">
	<label>Interface Description</label>
	<input type="text" value="" id="new_element_description">
</div>
<div id="setup_project" class="dbtoolkit-field-group">
	<label>Project</label>
	<input type="text" autocomplete="off" value="" id="new_element_project">
</div>
<div id="setup_slug" class="dbtoolkit-field-group">
	<label>Slug</label>
	<input type="text" value="" id="new_element_slug" data-format="slug">
</div>
</script>