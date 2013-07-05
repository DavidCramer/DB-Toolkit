<?php
//Filters query variables for the field type
if(!empty($_SESSION['reportFilters'][$EID][$Field])) {
    if($WhereTag == '') {
        $WhereTag = " WHERE ";
    }
    $filterParts = explode(';', $_SESSION['reportFilters'][$EID][$Field]);
    if(is_array($filterParts)){
        foreach($filterParts as $querySearch){
            $queryWhere[] = "( prim.".$Field." LIKE '%".$querySearch."%' )";
        }
    }
}
?>