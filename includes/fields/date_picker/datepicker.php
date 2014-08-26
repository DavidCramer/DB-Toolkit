<div class="<?php echo $field_class; ?>">
	<?php echo $field_label; ?>
	<div class="dbtoolkit-config-field">
		<input type="text" class="field-config is-datepicker" id="<?php echo $id; ?>" data-date-format="<?php echo $field['config']['format']; ?>" name="<?php echo $name; ?>" value="{{<?php echo $field['slug']; ?>}}">
		<?php if(!empty($field['caption'])){ ?><p class="description"><?php echo $field['label']; ?></p><?php } ?>
	</div>
</div>