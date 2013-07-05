<?php
//Filters query variables for the field type
		//if($Type[1] == 'count'){
			$primField = $Config['_TotalsFields'][$Field]['PrimField'];//str_replace('Totals', '', $Field);
			if($Config['_TotalsFields'][$Field]['Function'] == 'compare'){
				$querySelects[$Field] = 'avg(prim.'.$primField.') AS _average_'.$Config['_TotalsFields'][$Field]['Title'];
			}
			//echo $primField;
			$querySelects[$Field] = $Config['_TotalsFields'][$Field]['Type'].'(prim.'.$primField.') AS '.$Config['_TotalsFields'][$Field]['Title'];
			$countSelect[$Field] = $Config['_TotalsFields'][$Field]['Type'].'(prim.'.$primField.') AS '.$Config['_TotalsFields'][$Field]['Title'];
			$groupBy[$Config['_TotalsFields'][$Field]['Grouping']] = 'prim.'.$Config['_TotalsFields'][$Field]['Grouping'];
			//dump($querySelects);
		//}


?>