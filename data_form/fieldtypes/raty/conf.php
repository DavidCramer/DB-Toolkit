<?php
// Field Types Config|
$FieldTypeTitle = 'Star Rating';
$FieldDescription = 'Allows for the capturing of star ratings';
$FieldVersion = '1.0';
$FieldAuthor = 'David Cramer';
$FieldURL = 'http://dbtoolkit.digilab.co.za';
$isVisible = true;
$FieldTypes = array();

//$FieldTypes['raty']  = array('name' => 'Star Rating'	, 'func' => 'raty_setup', 'visible' => true, 'captionsOff' => true);
$FieldTypes['raty']  = array('name' => 'Star Rating'	, 'func' => 'raty_setup', 'visible' => true, 'INT(11)');

?>