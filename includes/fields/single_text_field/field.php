<div class="<?php echo $field_class; ?>">
	<?php echo $field_label; ?>
	<div class="dbtoolkit-config-field">
		<input type="text" class="block-input field-config" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="{{<?php echo $field['slug']; ?>}}">
		<?php if(!empty($field['caption'])){ ?><p class="description"><?php echo $field['label']; ?></p><?php } ?>
	</div>
</div>