<?php
//Filters query variables for the field type
if(!empty($_SESSION['reportFilters'][$EID][$Field])) {
    if($WhereTag == '') {
        $WhereTag = " WHERE ";
    }
    //$filterParts = explode(';', $_SESSION['reportFilters'][$EID][$Field]);    
    $checkBoxtmp = array();
    if(is_array($_SESSION['reportFilters'][$EID][$Field])){
        foreach($_SESSION['reportFilters'][$EID][$Field] as $querySearch){

            // serialized or single.
            if($Config['_SelectType'][$Field] == 'checkbox'){
                $checkBoxtmp[] = " prim.".$Field." LIKE '%".strlen($querySearch).":\"".$querySearch."\"%' ";
            }else{
                // then a single select
                $queryWhere[] = "( prim.".$Field." = '".$querySearch."' )";
            }

        }
        if($Config['_SelectType'][$Field] == 'checkbox'){
            $queryWhere[] = '( '.implode(' OR ', $checkBoxtmp).' ) ';
        }
    }
}
?>