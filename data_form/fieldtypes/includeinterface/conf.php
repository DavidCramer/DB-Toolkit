<?php
// Field Types Config 
$FieldTypeTitle = 'Interface Include';
// is set visible
$isVisible = true;

//ready the fieldtype group array
$FieldTypes = array();

// FieldType ID				  Type name					Setup function for insert/setup	    is field a visible or hidden field				
$FieldTypes['includer'] = array('name' => 'Interface Include','func' => 'includeinterface_setup', 'visible' => true, 'cloneview' => true, 'baseType'  => 'INT(1)');