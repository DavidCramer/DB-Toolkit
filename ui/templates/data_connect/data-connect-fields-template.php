{{#if fields}}
{{#each fields}}
<div class="dbtoolkit-dataset-item" data-id="{{Field}}">
	{{@key}} <span class="description" style="color: rgb(188, 188, 188);"> - {{this}}</span>
	<input type="hidden" class="dbtoolkit-field-slug" value="{{@key}}">
</div>
{{/each}}
{{else}}
<p class="description">No fields or datasource</p>
{{/if}}