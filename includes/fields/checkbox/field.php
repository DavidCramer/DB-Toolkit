<div class="<?php echo $field_class; ?>">
	<?php echo $field_label; ?>
	<div class="dbtoolkit-config-field">
		<?php
		if(empty($field['config']['option'])){ ?>
			
			<input type="checkbox" id="<?php echo $id; ?>" class="field-config" name="<?php echo $name; ?>" value="1" {{#if <?php echo $field['slug']; ?>}}checked="true"{{/if}}>

		<?php }else{
			foreach($field['config']['option'] as $option_key=>$option){
				?>
				<div><label><input type="checkbox" id="<?php echo $id . '_' . $option_key; ?>" class="field-config" name="<?php echo $name; ?>" value="<?php echo $option['value']; ?>" {{#if <?php echo $field['slug'].'_'.$option['value']; ?>}}checked="true"{{/if}}> <?php echo $option['label']; ?></label></div>
				<?php
			}
		} ?>
		<?php if(!empty($field['caption'])){ ?><p class="description"><?php echo $field['label']; ?></p><?php } ?>
	</div>
</div>