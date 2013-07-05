<?php
//Filters query variables for the field type

                //apply substr to date formate length.
if(empty($_SESSION['reportFilters'][$EID][$Field][0]))
    unset($_SESSION['reportFilters'][$EID][$Field]);
if(empty($_SESSION['reportFilters'][$EID][$Field][1]))
    unset($_SESSION['reportFilters'][$EID][$Field][1]);

                if(!empty ($groupBy[$Field]) && !empty($Config['_dateFormat'][$Field])){
                    $groupBy[$Field] = 'SUBSTRING('.$Field.',1,'.strlen(date($Config['_dateFormat'][$Field])).')';
                }

		if(!empty($_SESSION['reportFilters'][$EID][$Field][0]) && !empty($_SESSION['reportFilters'][$EID][$Field][1])){

			if($WhereTag == ''){
				$WhereTag = " WHERE ";
			}
			if($_SESSION['reportFilters'][$EID][$Field][0] == $_SESSION['reportFilters'][$EID][$Field][1]){
                            $queryWhere[] = "( prim.".$Field." BETWEEN '".$_SESSION['reportFilters'][$EID][$Field][0]." 00:00:00' AND '".$_SESSION['reportFilters'][$EID][$Field][0]." 23:59:59')";
			}else{
                            $format = 'Y-m-d 00:00:00';
                            if($_SESSION['reportFilters'][$EID][$Field][0] == 'NOW'){
                                $format = 'Y-m-d H:i:s';
                            }
                            
                            $StartDate = date($format, strtotime($_SESSION['reportFilters'][$EID][$Field][0]));
                            $format = 'Y-m-d 00:00:00';
                            if($_SESSION['reportFilters'][$EID][$Field][1] == 'NOW'){
                                $format = 'Y-m-d H:i:s';
                            }
                            $EndDate = date($format, strtotime($_SESSION['reportFilters'][$EID][$Field][1]));


                            $queryWhere[] = "( prim.".$Field." BETWEEN '".$StartDate."' AND '".$EndDate."' )";
                        }

                }
	//dump($_SESSION['reportFilters'][$EID]);

        if(!empty($Format)){
            if($Format == 'pdf'){
                if(!empty($_SESSION['reportFilters'][$EID][$Field][0]) && !empty($_SESSION['reportFilters'][$EID][$Field][1])){
                    $apiOutput['filters'][$Field] = date($Config['_dateFormat'][$Field], strtotime($_SESSION['reportFilters'][$EID][$Field][0])).' to '.date($Config['_dateFormat'][$Field], strtotime($_SESSION['reportFilters'][$EID][$Field][1]));
                }else{
                    unset($apiOutput['filters'][$Field]);
                }
            }
        }

  //      $apiOutput['filters'][$Field] = ;
?>