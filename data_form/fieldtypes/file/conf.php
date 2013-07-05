<?php
// Field Types Config| 
$FieldTypeTitle = 'File';
$isVisible = true;
$FieldTypes = array();

$FieldTypes['image'] 		= array('name' => 'Image Upload'	, 'func' => 'file_imageConfig'	, 'visible' => true, 'baseType'  => 'VARCHAR(255)');
$FieldTypes['file'] 		= array('name' => 'File Upload'		, 'func' => 'file_filesetup', 'visible' => true, 'baseType'  => 'VARCHAR(255)');
//$FieldTypes['multi'] 		= array('name' => 'Multi File Upload'	, 'func' => 'null'				, 'visible' => true, 'baseType'  => 'VARCHAR(255)');
$FieldTypes['mp3'] 		= array('name' => 'MP3 File Upload'	, 'func' => 'file_playerSetup'				, 'visible' => true, 'baseType'  => 'VARCHAR(255)');


?>