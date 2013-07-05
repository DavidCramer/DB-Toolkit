<?php
//Filters query variables for the field type
if(!empty($_SESSION['reportFilters'][$EID][$Field])) {
    if($WhereTag == '') {
        $WhereTag = " WHERE ";
    }
    $filterParts = explode(',', $_SESSION['reportFilters'][$EID][$Field]);
    if(is_array($filterParts)){
        $segments = array();
        foreach($filterParts as $querySearch){
            $segments[] = "( prim.".$Field." LIKE '%".$querySearch.",%' )";
        }
        $queryWhere[] = '('.implode(' OR ', $segments).')';
    }
}
?>