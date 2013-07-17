<?php
/*
Plugin Name: DB-Toolkit V1
Plugin URI: http://dbtoolkit.co.za
Description: WARNING: This is a development version. Do not use it for anything other than testing.
Author: David Cramer
Version: 1.0 alpha 1
Author URI: http://dbtoolkit.co.za
*/

//initilize plugin

define('DBT_PATH', plugin_dir_path(__FILE__));
define('DBT_URL', plugin_dir_url(__FILE__));

$ajaxAllowedFunctions = array();

require_once DBT_PATH.'libs/actions.php';
require_once DBT_PATH.'libs/functions.php';


register_activation_hook( __FILE__, 'dbt_interface_VersionCheck');

?>