<?php
//Filters query variables for the field type
if(!empty($_GET[$Field]) && !empty($Config['_IndexType'][$Field]['Filter'])) {
if($Type[1] == 'integer'){
        if($_SESSION['reportFilters'][$EID][$Field][0] != '' && $_SESSION['reportFilters'][$EID][$Field][0] != ''){
            if($WhereTag == '') {
                $WhereTag = " WHERE ";
            }

            $queryWhere[] = "( prim.".$Field." >= '".floatval($_SESSION['reportFilters'][$EID][$Field][0])."' AND prim.".$Field." <= '".floatval($_SESSION['reportFilters'][$EID][$Field][1])."')";
        }
    }else{
        if($WhereTag == '') {
            $WhereTag = " WHERE ";
        }

        $filterParts = explode('|', $_GET[$Field]);
        if(is_array($filterParts)){
            foreach($filterParts as $querySearch){
                switch ($Config['_filterMode'][$Field]){
                    case 'mid':
                        $qline = "%".$querySearch."%";
                        break;
                    case 'before':
                        $qline = "%".$querySearch;
                        break;
                    case 'after':
                        $qline = $querySearch."%";
                        break;
                    default:
                        $qline = "%".$querySearch."%";
                        break;
                }
                $queryWhere[] = "( prim.".$Field." LIKE '".$qline."' )";
            }
        }
    }
}

if(!empty($_GET['s'])){
    foreach(explode(',', $_GET['s']) as $keyWord){
        $queryWhere['OR'][] = $querySelects[$Field]." LIKE '%".trim($keyWord)."%'";
    }
}
?>