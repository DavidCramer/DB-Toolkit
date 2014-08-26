<div class="<?php echo $field_class; ?>">
	<?php echo $field_label; ?>
	<div class="dbtoolkit-config-field">
		<input id="<?php echo $id; ?>" type="text" class="minicolor-picker field-config init_field_type" data-type="color_picker" name="<?php echo $name; ?>" value="{{<?php echo $field['slug']; ?>}}">
		<?php if(!empty($field['caption'])){ ?><p class="description"><?php echo $field['label']; ?></p><?php } ?>
	</div>
</div>
