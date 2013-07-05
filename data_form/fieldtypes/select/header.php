<?php
/*<link rel="stylesheet" media="screen" type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/db-toolkit/data_form/fieldtypes/text/css/colorpicker.css" />
<script type="text/javascript" src="<?php echo WP_PLUGIN_URL; ?>/db-toolkit/data_form/fieldtypes/text/js/colorpicker.js"></script>
<script type="text/javascript" src="<?php echo WP_PLUGIN_URL; ?>/db-toolkit/data_form/fieldtypes/text/ckeditor/ckeditor.js"></script>
<?php
 *
 */

        wp_register_script('ckEditor', WP_PLUGIN_URL. '/db-toolkit/data_form/fieldtypes/text/ckeditor/ckeditor.js');
        wp_enqueue_script("ckEditor");
        wp_register_style('ckEditorCSS', WP_PLUGIN_URL.'/db-toolkit/data_form/fieldtypes/text/ckeditor/skins/kama/editor.css');
        wp_enqueue_style('ckEditorCSS');


        wp_register_script('miniColors', WP_PLUGIN_URL. '/db-toolkit/data_form/fieldtypes/text/js/jquery.miniColors.min.js');
        wp_enqueue_script("miniColors");
        wp_register_style('miniColorsCSS', WP_PLUGIN_URL.'/db-toolkit/data_form/fieldtypes/text/js/jquery.miniColors.css');
        wp_enqueue_style('miniColorsCSS');
        
/*
if(!empty($_GET['interface'])){
        if(is_admin ()){
            echo '<link rel="stylesheet" media="screen" type="text/css" href="'.WP_PLUGIN_URL.'/db-toolkit/data_form/fieldtypes/text/ckeditor/skins/kama/editor.css?t=B0VI4XQ" />';
        }
    }
 /*
 */
?>