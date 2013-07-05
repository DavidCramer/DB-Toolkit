<?php
//Filters query variables for the field type
//echo $Field.'<br />';
//vardump($Config);
if(empty($_GET[$Field]) && empty($_GET[$Config['_overRide'][$Field]])){
    if(empty($Config['_selectFilterOptional'][$Field])){
        $exitNotice = true;
    }
}


if(!empty($_GET[$Field]) || !empty($_GET[$Config['_overRide'][$Field]])){

	if(empty($Config['_overRide'][$Field])){
            if(!empty($_GET[$Field])){
                $Filter = urldecode($_GET[$Field]);
                $_SESSION['viewitemFilter'][$EID][$Field] = $Filter;
            }
        }else{
            if(!empty($_GET[$Config['_overRide'][$Field]])){
                $Filter = urldecode($_GET[$Config['_overRide'][$Field]]);
                $_SESSION['viewitemFilter'][$EID][$Field] = $Filter;
            }
        }
	if($WhereTag == ''){
		$WhereTag = " WHERE ";
	}


	$queryWhere[$Field] = "prim.`".$Field."` = '".$Filter."' ";
}else{
    if(empty($Config['_selectFilterOptional'][$Field])){
        if(!empty($_SESSION['viewitemFilter'][$EID][$Field])){
            if($WhereTag == ''){
                    $WhereTag = " WHERE ";
            }
            $queryWhere[$Field] = "prim.`".$Field."` = '".$_SESSION['viewitemFilter'][$EID][$Field]."' ";
        }
    }
}



?>