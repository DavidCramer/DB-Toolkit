<?php
// Field Types Config| 
$FieldTypeTitle = 'Text Input';
$isVisible = true;
$FieldTypes = array();

$FieldTypes['singletext'] 	= array('name' => 'Single Text Field'	, 'func' => 'text_presuff'	, 'visible' => true, 'baseType'  => 'VARCHAR(255)');
$FieldTypes['textarea'] 	= array('name' => 'Text Area'		, 'func' => 'text_chartotal'	, 'visible' => true, 'baseType'  => 'TEXT');
$FieldTypes['telephonenumber'] 	= array('name' => 'Telephone Number'	, 'func' => 'null'      	, 'visible' => true, 'baseType'  => 'VARCHAR(45)');
$FieldTypes['emailaddress'] 	= array('name' => 'Email Address'	, 'func' => 'text_emailSetup'	, 'visible' => true, 'baseType'  => 'VARCHAR(100)');
$FieldTypes['integer']          = array('name' => 'Number/Integer'	, 'func' => 'null'		, 'visible' => true, 'baseType'  => 'INT(11)');

?>