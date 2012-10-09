<?php

//Filters query variables for the field type
global $user_ID;
get_currentuserinfo();

global $wpdb;
if(!empty($Type[1])){
    switch ($Type[1]) {
        case 'session':
            if ($WhereTag == '') {
                $WhereTag = " WHERE ";
            }
            $queryWhere['AND'][] = 'prim.`' . $Field . "` = " . $_SESSION[$Config['_SessionValue'][$Field]];
            break;
        case 'userbase':
            //global $user_ID;
            //get_currentuserinfo();
            //if(!empty($Config['_UserBaseFilter'][$Field])){

            $queryJoin[$wpdb->users][$prime]['ID'][$joinIndex] = " LEFT JOIN `".$wpdb->users."` AS ".$joinIndex." on (`prim`.`".$Field."` = `".$joinIndex."`.`ID`) \n";
            //vardump($queryJoin);
            //die;


            $querySelects[$Field] = '`'.$joinIndex.'`.`user_login`';

            if (!empty($Config['_UserBaseFilter'][$Field])) {
                $queryWhere[' AND '] = 'prim.`'.$Field."` = ".$user_ID;
            }
            //}
            break;
    }
}
if ($Type[0] == 'hidden') {    
    $queryWhere['AND'][] = "prim.`" . $Field . "` = '" . $_SESSION['reportFilters'][$EID][$Field] . "'";
}
?>