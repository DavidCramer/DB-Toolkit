<?php
// Field Types Config| 
$FieldTypeTitle = 'Text Input';
$isVisible = true;
$FieldTypes = array();

$FieldTypes['singletext'] = array(
    'name' => 'Single Text Field',
    'options' => array(
        'prefix'=>array(
            'label'=>'Prefix',
            'help'=> 'Prepend a prefix to the input',
            'default'=>'',
            'type'=>'text'
        ),
        'suffix'=>array(
            'label'=>'Suffix',
            'help'=> 'Append a suffix to the input',
            'default'=>'',
            'type'=>'text'
        ),
        'type'=>array(
            'label'=>'Type|text,password',
            'help'=> 'Select the input type',
            'default'=>'text',
            'type'=>'select'
        )
    ),
    'visible' => true,
    'baseType'  => 'VARCHAR(255)'
    );
$FieldTypes['textarea'] 	= array(
    'name' => 'Text Area',
    'options' => array(
        'charlimit'=>array(
            'label'=>'Character Limit',
            'help'=> 'Limit the number of characters accepted. leave blank for unlimited',
            'default'=>'',
            'type'=>'text'
        )
    ),    
    'visible' => true,
    'baseType'  => 'TEXT'
    );
$FieldTypes['telephonenumber'] 	= array(
    'name' => 'Telephone Number',
    'visible' => true,
    'baseType'  => 'VARCHAR(45)'
    );
$FieldTypes['emailaddress'] 	= array(
    'name' => 'Email Address',
    'options' => array(
        'confirm'=>array(
            'label'=>'Email Copy',
            'help'=> 'Send a copy of this form to this email',
            'default'=>'',
            'type'=>'checkbox'
        ),
        'subject'=>array(
            'label'=>'Email Subject',
            'help'=> 'The subject line of the sent email',
            'default'=>'Confirmation of Submitted data',
            'type'=>'text'
        ),
        'sender'=>array(
            'label'=>'Sender Email Address',
            'help'=> 'The senders email address',
            'default'=>'',
            'type'=>'text'
        )
    ),
    'visible' => true,
    'baseType'  => 'VARCHAR(100)'
    );
$FieldTypes['integer']          = array(
    'name' => 'Number/Integer',
    'visible' => true,
    'baseType'  => 'INT(11)'
    );

?>