<h2 class="dbtoolkit-panel-title">Fields</h2>
{{#unless fields}}
<p class="description">No fields or datasource</p>
{{/unless}}
{{#each fields}}
	<div class="dbtoolkit-variable-group">
		{{@key}} <span class="description" style="color: rgb(188, 188, 188);"> - {{this}}</span>
		<input type="hidden" class="dbtoolkit-field-slug" value="{{@key}}">
	</div>
{{/each}}