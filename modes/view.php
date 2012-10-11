<?php
    $entry = false;
    if(!empty($Data[0])){
        $entry = $Data[0];
    }
    echo dbt_buildFormView($Config, 'view', $entry);    
?>