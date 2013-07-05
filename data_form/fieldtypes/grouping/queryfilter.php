<?php
//add to the query that is generated for the FINAL QUERY IN VIEW/REPORT/LIST
/*
variables avaiable

 $Type - array(0=>folder,1=>fieldtype);
 $Field - field name
 $Config - config for the element
 $EID - element ID
 $querySelects - fields to be returned - array(fieldnames)
 $queryWhere[] - array with string of where clause e.g 'prim.'.$Field." in ('".implode('\',\'', $_SESSION['reportFilters'][$EID][$Field])."')";
 $joinIndex - uniqu index value created for joins
 $queryJoin - string .= with join string

 other important things -
 $joinIndex is used only for joined tbles - the primary table that is being returned starts with "prim.{{fieldname}}"

*/
if(!empty($Config['_CloneField'][$Field]['Master'])){
    $groupBy[$Config['_CloneField'][$Field]['Master']] = '`'.$Config['_CloneField'][$Field]['Master'].'`';
}else{
    $groupBy['group_'.$Field] = 'prim.`'.$Field.'`';
}

if($Config['_GroupingFields'][$Field]['Action'] == 'concat'){
    //$querySelects[$Field] = $Config['_GroupingFields'][$Field]['Action'].'(`'.$Config['_GroupingFields'][$Field]['Field'].'`)';
    if(array_key_exists($Config['_GroupingFields'][$Field]['Field'],$Config['_Field']) && substr($Config['_GroupingFields'][$Field]['Field'], 0, 2) != '__'){
        $querySelects[$Field] = 'GROUP_CONCAT(prim.`'.$Config['_GroupingFields'][$Field]['Field'].'`)';
    }else{
        $querySelects[$Field] = 'GROUP_CONCAT('.$Config['_GroupingFields'][$Field]['Field'].')';
    }

}else{
    $querySelects[$Field] = $Config['_GroupingFields'][$Field]['Action'].'(prim.`'.$Config['_GroupingFields'][$Field]['Field'].'`)';
}
//dump($querySelects);
?>