<?php
// Field Types Config| 
$FieldTypeTitle = 'Text Input';
$isVisible = true;
$FieldTypes = array();

$FieldTypes['singletext'] 	= array('name' => 'Single Text Field'	, 'func' => 'text_presuff'	, 'visible' => true, 'baseType'  => 'VARCHAR(255)');
$FieldTypes['sanitized'] 	= array('name' => 'Sanitize Field'	, 'func' => 'text_sanitize'	, 'visible' => false, 'baseType'  => 'VARCHAR(255)');
$FieldTypes['presettext'] 	= array('name' => 'Preset Value'	, 'func' => 'text_preset'	, 'visible' => false, 'baseType' => 'VARCHAR(255)');
$FieldTypes['password'] 	= array('name' => 'Password (md5)'	, 'func' => 'null'      	, 'visible' => true, 'baseType'  => 'VARCHAR(100)');
$FieldTypes['textarea'] 	= array('name' => 'Text Area'		, 'func' => 'text_chartotal'	, 'visible' => true, 'baseType'  => 'TEXT');
$FieldTypes['textarealarge'] 	= array('name' => 'Text Area Large'	, 'func' => 'null'		, 'visible' => true, 'baseType'  => 'TEXT');
$FieldTypes['telephonenumber'] 	= array('name' => 'Telephone Number'	, 'func' => 'null'      	, 'visible' => true, 'baseType'  => 'VARCHAR(45)');
$FieldTypes['emailaddress'] 	= array('name' => 'Email Address'	, 'func' => 'text_emailSetup'	, 'visible' => true, 'baseType'  => 'VARCHAR(100)');
$FieldTypes['phpcodeblock'] 	= array('name' => 'PHP Code Block'	, 'func' => 'null'		, 'visible' => true, 'baseType'  => 'TEXT');
$FieldTypes['wysiwyg'] 		= array('name' => 'Wysiwyg Editor'	, 'func' => 'text_wysiwygsetup'	, 'visible' => true, 'baseType'  => 'TEXT');
$FieldTypes['url'] 		= array('name' => 'URL'			, 'func' => 'null'		, 'visible' => true, 'baseType'  => 'TEXT');
$FieldTypes['colourpicker'] 	= array('name' => 'Colour Picker'	, 'func' => 'null'		, 'visible' => true, 'baseType'  => 'VARCHAR(7)');
$FieldTypes['integer']              = array('name' => 'Number/Integer'	, 'func' => 'null'		, 'visible' => true, 'baseType'  => 'INT(11)');


?>