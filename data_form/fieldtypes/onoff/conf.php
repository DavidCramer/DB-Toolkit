<?php
// Field Types Config 
$FieldTypeTitle = 'Interactive';
// is set visible
$isVisible = true;

//ready the fieldtype group array
$FieldTypes = array();

// FieldType ID				  Type name					Setup function for insert/setup	    is field a visible or hidden field				
$FieldTypes['onoff'] = array('name' => 'On-Off Toggle','func' => 'onoff_timestamp', 'visible' => true, 'baseType'  => 'INT(1)');


?>