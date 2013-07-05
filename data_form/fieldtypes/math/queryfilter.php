<?php

//dump($querySelects[$Field]);
//echo $Field;
// Clear out cloned field


//dump($querySelects);
if($Type[1] == 'mysqlfunc'){
$type = $Config['_mathMysqlFunc'][$Field];
if($type == '_custom'){
    if(!empty($Config['_mathMysqlCustomFunc'][$Field])){
        $type = str_replace('()', '', $Config['_mathMysqlCustomFunc'][$Field]);
    }
}
//dump($_SERVER['fieldMath'][$Field]);
if($Config['_mathMysqlFunc'][$Field] == 'sumtotal'){
    $type = 'count';
    $_SERVER['fieldMath'][$Field] = 0;
}
$fieldSett = $Field;
if(!empty($Config['_CloneField'][$Field])){
    if(!empty($Config['_CloneField'][$Config['_CloneField'][$Field]['Master']])){
        $fieldSett = dr_findCloneParent($Field, $Config['_CloneField'], $querySelects);
    }else{
        $fieldSett = $Config['_CloneField'][$Field]['Master'];
    }
    if(strpos($fieldSett, '.') === false){
       $fieldSett = 'prim.`'.$fieldSett.'`';
    }
}
if($type != '_custom'){
    $querySelects[$Field] = $type.'(`'.$fieldSett.'`)';
}else{
    $querySelects[$Field] = $fieldSett;
}


}

?>