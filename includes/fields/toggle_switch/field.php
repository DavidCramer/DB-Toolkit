<div class="<?php echo $field_class; ?>">
	<?php echo $field_label; ?>
	<div class="dbtoolkit-config-field init_field_type" data-type="toggle_button">
		<div class="cu-toggle-group-buttons">
			<?php
			if(empty($field['config']['option'])){ ?>
					
					<button type="button" id="<?php echo $id; ?>_1" class="button" data-value="true"><?php echo __('Enable', 'pod-users'); ?></button>

			<?php }else{
				foreach($field['config']['option'] as $option_key=>$option){
					?><button type="button" id="<?php echo $id.'_'.$option_key; ?>" class="button" data-value="<?php echo $option['value']; ?>"><?php echo $option['label']; ?></button><?php
				}
			} ?>		
		</div>
		<div style="display:none;">
		<?php
			foreach($field['config']['option'] as $option_key=>$option){
				?>
				<input type="radio" id="<?php echo $id . '_' . $option_key; ?>" data-ref="<?php echo $id.'_'.$option_key; ?>" class="cu-toggle-group-radio" name="<?php echo $name; ?>" value="<?php echo $option['value']; ?>" {{#if <?php echo $field['slug'].'_'.$option['value']; ?>}}checked="true"{{/if}}>
				<?php
			}
		?></div>
		<?php if(!empty($field['caption'])){ ?><p class="description"><?php echo $field['label']; ?></p><?php } ?>
	</div>
</div>