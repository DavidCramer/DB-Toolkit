<div class="<?php echo $field_class; ?>">
	<?php echo $field_label; ?>
	<div class="dbtoolkit-config-field">
		<select id="<?php echo $id; ?>" class="field-config" name="<?php echo $name; ?>">
		<?php
		if(!empty($field['config']['option'])){
			foreach($field['config']['option'] as $option_key=>$option){
				?>
				<option value="<?php echo $option['value']; ?>" {{#if <?php echo $field['slug'].'_'.$option['value']; ?>}}selected="true"{{/if}}><?php echo $option['label']; ?></option>
				<?php
			}
		} ?>
		</select>
		<?php if(!empty($field['caption'])){ ?><p class="description"><?php echo $field['label']; ?></p><?php } ?>
	</div>
</div>