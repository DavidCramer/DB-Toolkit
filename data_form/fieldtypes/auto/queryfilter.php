<?php

//Filters query variables for the field type
global $user_ID;
get_currentuserinfo();

global $wpdb;

switch ($Type[1]) {
    case 'session':
        if ($WhereTag == '') {
            $WhereTag = " WHERE ";
        }
        $queryWhere[] = 'prim.`' . $Field . "` = " . $_SESSION[$Config['_SessionValue'][$Field]];
        break;
    case 'userbase':
        //global $user_ID;
        //get_currentuserinfo();
        //if(!empty($Config['_UserBaseFilter'][$Field])){

        $queryJoin .= " LEFT JOIN `".$wpdb->users."` AS ".$joinIndex." on (prim.`".$Field."` = ".$joinIndex.".ID) \n";
        $querySelects[$Field] = $joinIndex.'.user_login';

        if (!empty($Config['_UserBaseFilter'][$Field])) {
            if ($WhereTag == '') {
                $WhereTag = " WHERE ";
            }
            $queryWhere[] = 'prim.`'.$Field."` = ".$user_ID;
        }
        //}
        break;
}
if ($Type[0] == 'hidden') {    
    $queryWhere[] = "prim.`" . $Field . "` = '" . $_SESSION['reportFilters'][$EID][$Field] . "'";
}
?>