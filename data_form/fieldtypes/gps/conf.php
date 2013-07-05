<?php
// Field Types Config| 
$FieldTypeTitle = 'GPS Coordinates';
$isVisible = true;
$FieldTypes = array();

$FieldTypes['coordinates'] 		= array('name' => 'GPS Coordinates'	, 'func' => 'gps_setup'	, 'visible' => true, 'captionsOff' => true, 'baseType'  => 'VARCHAR(255)');

?>