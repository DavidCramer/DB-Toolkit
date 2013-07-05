<?php

$test = array();
$Render = true;
$TitleNotice = '';
foreach($Config['_Field'] as $Key=>$Value) {
    if($Value == 'viewitem_filter') {
        if(!empty($Config['_overRide'][$Key])) {
            if(!isset($_GET[$Config['_overRide'][$Key]])) {
                $Render = false;
            }
        }else {
            if(!isset($_GET[$Key]) && empty($Config['_selectFilterOptional'][$Key])) {
                $Render = false;
            }
        }
    }
}
if($Render != true) {
    return;
}

$FilterLocks = get_option('filter_Lock_'.$Media['ID']);
if(!is_array($FilterLocks)){
    $FilterLocks = unserialize($FilterLocks);
}

$isSearch = 0;
if($Config['_ViewMode'] == 'search'){
    $isSearch = 1;
    if(!empty($_POST['search_search'])){
        $isSearch = 0;
    }
}

if(!empty($FilterLocks) && empty($isSearch)) {

    $_SESSION['lockedFilters'][$Media['ID']] = $_SESSION['reportFilters'][$Media['ID']];
    if(empty($_SESSION['reportFilters'][$Media['ID']])) {
        $_SESSION['reportFilters'][$Media['ID']] = $FilterLocks;
    }else {
        if(is_array($FilterLocks)){
            array_merge($_SESSION['reportFilters'][$Media['ID']], $FilterLocks);
        }else{
            array_merge($_SESSION['reportFilters'][$Media['ID']], array($FilterLocks));
        }
    }
    $_SESSION['lockedFilters'][$Media['ID']] = $FilterLocks;
    //vardump($_SESSION['reportFilters'][$Media['ID']]);
}
?>