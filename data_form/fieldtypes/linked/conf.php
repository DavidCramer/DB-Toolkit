<?php
// Field Types Config| 
$FieldTypeTitle = 'Join';
$isVisible = true;
$FieldTypes = array();

$FieldTypes['linked'] = array('name' => 'Join Table','func' => 'linked_tableSetup', 'visible' => true, 'baseType'  => 'INT(11)');
//$FieldTypes['linkedMultiple'] = array('name' => 'Join Table Multiple','func' => 'linked_tableSetup', 'visible' => true, 'baseType'  => 'INT');
$FieldTypes['linkedfiltered'] = array('name' => 'Filtered Join Table','func' => 'linked_tablefilteredSetup', 'visible' => true, 'baseType'  => 'INT(11)');


?>