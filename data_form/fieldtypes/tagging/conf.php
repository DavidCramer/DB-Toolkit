<?php
$FieldTypeTitle = 'Tagging';
$FieldDescription = 'Allows for the capturing of Tags.';
$FieldVersion = '1.0';
$FieldAuthor = 'David Cramer';
$FieldURL = 'http://dbtoolkit.digilab.co.za';
$isVisible = true;
$FieldTypes = array();

// FieldType ID				  Type name					Setup function for insert/setup	    is field a visible or hidden field				
$FieldTypes['tagit'] = array('name' => 'Tags Selector','func' => 'tagging_setup', 'visible' => true, 'baseType'  => 'TEXT');


?>