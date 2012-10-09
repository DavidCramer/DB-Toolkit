<?php
//Filters query variables for the field type

if(!empty($_GET[$Field])) {
    $queryWhere['AND'][] = $querySelects[$Field]." = '".trim(mysql_real_escape_string($_GET[$Field]))."'";
}else{
    $haltRender = true;
}
?>