<?php
//add to the query that is generated for the FINAL QUERY IN VIEW/REPORT/LIST
/*
variables avaiable
 
 $Type - array(0=>folder,1=>fieldtype);
 $Field - field name
 $Config - config for the element
 $EID - element ID
 $querySelects - fields to be returned - array(fieldnames)
 $queryWhere[] - array with string of where clause e.g 'prim.'.$Field." in ('".implode('\',\'', $filterSet[$Field])."')";
 $joinIndex - uniqu index value created for joins
 $queryJoin - string .= with join string
 
 other important things -
 $joinIndex is used only for joined tbles - the primary table that is being returned starts with "prim.{{fieldname}}"

*/


?>