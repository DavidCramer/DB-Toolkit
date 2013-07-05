<?php

// Field Types Config| 
$FieldTypeTitle = 'Date Input';
$isVisible = true;
$FieldTypes = array();

$FieldTypes['date'] 			= array('name' => 'Date Picker'	, 'func' => 'date_config'	 , 'visible' => true, 'baseType' => 'DATE');
$FieldTypes['datetime'] 		= array('name' => 'Date and Time'	, 'func' => 'date_config', 'visible' => true, 'baseType' => 'DATETIME');
$FieldTypes['timepicker'] 		= array('name' => 'Time Picker'	, 'func' => 'date_config'	 , 'visible' => true);
$FieldTypes['timestamp'] 		= array('name' => 'Auto Timestamp'	, 'func' => 'date_config', 'visible' => false, 'baseType' => 'DATETIME');
//$FieldTypes['scheduleMin'] 		= array('name' => 'Minute Scheduler'	, 'func' => 'null'	 , 'visible' => true, 'baseType' => 'VARCHAR( 100 )');
//$FieldTypes['scheduleHour'] 		= array('name' => 'Hour Scheduler'	, 'func' => 'null'	 , 'visible' => true, 'baseType' => 'VARCHAR( 100 )');
//$FieldTypes['scheduleDay'] 		= array('name' => 'Day Scheduler'	, 'func' => 'null'	 , 'visible' => true, 'baseType' => 'VARCHAR( 100 )');
//$FieldTypes['time'] 		= array('name' => 'Time Picker'	, 'func' => 'null'	, 'visible' => true);


?>