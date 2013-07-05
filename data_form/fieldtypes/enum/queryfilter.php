<?php
//Filters query variables for the field type
if(!empty($Config['_CloneField'][$Field])){
    $Field = $Config['_CloneField'][$Field]['Master'];
}
if(!empty($_SESSION['reportFilters'][$EID][$Field])){
	if($WhereTag == ''){
		$WhereTag = " WHERE ";
	}
        if(is_array($_SESSION['reportFilters'][$EID][$Field])){
            $queryWhere[] = 'prim.'.$Field." IN ('".implode('\',\'', $_SESSION['reportFilters'][$EID][$Field])."')";
        }else{
            $queryWhere[] = 'prim.'.$Field." = '".$_SESSION['reportFilters'][$EID][$Field]."'";
        }
        if(!empty($Format)){
            if($Format == 'pdf'){
                $apiOutput['filters'][$Field] = implode('\',\'', $_SESSION['reportFilters'][$EID][$Field]);
            }
        }
}

?>