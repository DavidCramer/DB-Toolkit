<?php
// Field Types Config| 
$FieldTypeTitle = 'Item Filter';
$isVisible = true;
$FieldTypes = array();

$FieldTypes['filter'] = array('name' => 'Selected Item Filter'	, 'func' => 'viewitem_setup'	, 'visible' => false, 'baseType'  => 'INT(11)', 'hidden'=>true);

?>