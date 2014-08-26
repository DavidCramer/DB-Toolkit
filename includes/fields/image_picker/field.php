<?php
// default icon
$default_icon = DBTOOLKIT_URL . "includes/fields/image_picker/img/".$field['config']['picker'].".png";
if(!empty($field['config']['default']['thumbnail'])){
	$default_icon = $field['config']['default']['thumbnail'];
}
?><div class="<?php echo $field_class; ?>">
	<?php echo $field_label; ?>
	<div class="dbtoolkit-config-field" id="<?php echo $id; ?>_wrapper">
		<div class="image-picker-content <?php echo $field['config']['picker']; ?>">
			<div class="image-picker-side-bar">
				<img class="image-picker-thumbnail" 
				data-placehold="<?php echo $default_icon; ?>" src="{{#if <?php echo $field['slug']; ?>/thumbnail}}{{<?php echo $field['slug']; ?>/thumbnail}}{{else}}<?php echo $default_icon; ?>{{/if}}">
			</div>
			<div class="image-picker-main-content">
				<?php if( count($field['config']['size']) > 1){ ?>
				<div>
					<select id="<?php echo $id; ?>_size" name="<?php echo $name; ?>[size]" class="image-picker-sizer" {{#unless <?php echo $field['slug']; ?>/id}}disabled="true"{{/unless}}>
						<?php
						$size_checks = array();
						foreach(get_intermediate_image_sizes() as $size){
							if(!isset($field['config']['size'][$size])){
								continue;
							}
							echo "<option value=\"".$size."\" {{#is ".$field['slug']."/size value=\"".$size."\"}}selected=\"selected\"{{/is}}
							{{#if size}}
								{{#unless size/".$size."}}
								disabled=\"disabled\" style=\"display:none;\"
								{{/unless}}
							{{/if}}
							>".$size."</option>\r\n";
							$size_checks[] = "<label><input class=\"image-picker-allowed-size\" type=\"checkbox\" name=\"variables[{{id}}][size][".$size."]\" value=\"".$size."\" {{#if size}}{{#if size/".$size."}}checked=\"true\"{{/if}}{{else}}checked=\"checked\"{{/if}}> ".$size."</label>";
						}
						?>
					</select>
				</div>
				<?php }else{ ?>
					<input id="<?php echo $id; ?>_size" class="image-picker-image-id" name="<?php echo $name; ?>[size]" type="hidden" value="{{<?php echo $field['slug']; ?>/size}}">
				<?php } ?>
				<?php if(is_admin()){ ?>
				<button class="button image-picker-button cu-image-picker<?php if( count($field['config']['size']) === 1){ echo ' image-picker-button-solo'; }; ?>" data-title="<?php echo __('Select Image', 'pod-users'); ?>" data-button="<?php echo __('Use Image', 'pod-users'); ?>" type="button"><?php echo __('Select Image', 'pod-users'); ?></button>
				<?php }else{ ?>
				<button class="button image-picker-button cu-image-picker<?php if( count($field['config']['size']) === 1){ echo ' image-picker-button-solo'; }; ?>" type="button" data-text="<?php echo __('Select Image', 'pod-users'); ?>" data-loading="<?php echo __('Uploading..', 'pod-users'); ?>"><?php echo __('Select Image', 'pod-users'); ?></button>
				<input type="file" name="file_upload" disabled="disabled" class="cu-image-picker-file-select ajax-trigger" data-id="<?php echo $id; ?>_wrapper" data-callback="cu_use_uploaded_image" data-event="change" data-before="cu_use_uploaded_image" data-action="handle_upload" data-size="<?php echo $field['config']['picker']; ?>">
				<?php } ?>
				<button class="button button-primary image-picker-button cu-image-remover<?php if( count($field['config']['size']) === 1){ echo ' image-picker-button-solo'; }; ?>" data-title="<?php echo __('Select Image', 'pod-users'); ?>" data-button="<?php echo __('Use Image', 'pod-users'); ?>" type="button" {{#unless <?php echo $field['slug']; ?>/id}}disabled="true"{{/unless}}><?php echo __('Remove', 'pod-users'); ?></button>
			</div>
		</div>
		<input id="<?php echo $id; ?>_id" class="image-picker-image-id" name="<?php echo $name; ?>[id]" type="hidden" value="{{<?php echo $field['slug']; ?>/id}}">
		<input id="<?php echo $id; ?>_thumb" class="image-picker-image-thumb" name="<?php echo $name; ?>[thumbnail]" type="hidden" value="{{<?php echo $field['slug']; ?>/thumbnail}}">
		<?php if(!empty($field['caption'])){ ?><p class="description"><?php echo $field['label']; ?></p><?php } ?>
	</div>
</div>