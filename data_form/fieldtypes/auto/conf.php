<?php
// Field Types Config| 
$FieldTypeTitle = 'Auto Values';
$isVisible = true;
$FieldTypes = array();

$FieldTypes['UUID']             = array('name' => 'UUID' , 'func' => 'null', 'visible' => false, 'baseType'  => 'BINARY(16)');
$FieldTypes['AutoInc']          = array('name' => 'Auto-Increment' , 'func' => 'null', 'visible' => false, 'baseType'  => 'INT(11)');
$FieldTypes['userbase'] 	= array('name' => 'User ID' , 'func' => 'session_config', 'visible' => false, 'baseType'  => 'INT(11)');
$FieldTypes['ipaddress'] 	= array('name' => 'IP Address' , 'func' => 'null', 'visible' => false, 'baseType'  => 'VARCHAR(42)');
$FieldTypes['autovalue'] 	= array('name' => 'Preset Autovalue' , 'func' => 'auto_preset', 'visible' => false, 'baseType'  => 'VARCHAR(255)');
$FieldTypes['GETValue'] 	= array('name' => 'GET Auto Value' , 'func' => 'auto_get', 'visible' => false, 'baseType'  => 'VARCHAR(255)');


?>