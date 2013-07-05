<?php

        wp_register_script('audioPlayer', WP_PLUGIN_URL. '/db-toolkit/data_form/fieldtypes/file/js/audio-player.js', 'jquery', false, true);
        wp_enqueue_script("audioPlayer");
        wp_register_script('uploadifyJS', WP_PLUGIN_URL. '/db-toolkit/data_form/fieldtypes/file/js/jquery.uploadify.min.js', 'jquery', false, true);
        wp_enqueue_script("uploadifyJS");
        wp_register_style('uploadifyCSS', WP_PLUGIN_URL.'/db-toolkit/data_form/fieldtypes/file/css/uploadify.css');
        wp_enqueue_style('uploadifyCSS');

?>