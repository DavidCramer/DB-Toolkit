<?php

// Field Type Template
// Field Types Config


$FieldTypeTitle = 'Grouping';
// is set visible
$isVisible = true;

//ready the fieldtype group array
$FieldTypes = array();

$FieldTypes['grouping'] = array(
    
    'name'      => 'Field Group',         // Name of the Field Type
    'func'      => 'grouping_setup',     // Setup field type function call
    'visible'   => true,                    // true: visible field type | false: hidden field
    'cloneview' => false,                    // is visible in a clone field
    'baseType'  => 'INT(1)'
    );


?>