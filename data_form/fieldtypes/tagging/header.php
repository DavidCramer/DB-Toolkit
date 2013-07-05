<?php
// add anyscripts needed in the page header

        wp_register_script('jQueryTaginputJS', WP_PLUGIN_URL. '/db-toolkit/data_form/fieldtypes/tagging/libs/jquery.tagsinput.min.js', 'jquery', false, true);
        wp_enqueue_script("jQueryTaginputJS");
        wp_register_style('jQueryTaginputCSS', WP_PLUGIN_URL.'/db-toolkit/data_form/fieldtypes/tagging/libs/jquery.tagsinput.css');
        wp_enqueue_style('jQueryTaginputCSS');

?>