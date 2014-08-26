<div class="<?php echo $field_class; ?>">
	<?php echo $field_label; ?>
	<div class="dbtoolkit-config-field">
		<textarea type="text" class="block-input field-config" id="<?php echo $id; ?>" name="<?php echo $name; ?>">{{<?php echo $field['slug']; ?>}}</textarea>
		<?php if(!empty($field['caption'])){ ?><p class="description"><?php echo $field['label']; ?></p><?php } ?>
	</div>
</div>