<?php
//Filters query variables for the field type
if(!empty($_SESSION['reportFilters'][$EID][$Field])) {

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

        $filterParts = explode(';', $_SESSION['reportFilters'][$EID][$Field]);
        if(is_array($filterParts)){
            foreach($filterParts as $querySearch){
			    if (array_key_exists('_filterMode',$Config)) {
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
				} else {
                    $qline = "%".$querySearch."%";
				}
                $queryWhere[] = "( prim.".$Field." LIKE '".$qline."' )";
            }
        }
    }
}
?>