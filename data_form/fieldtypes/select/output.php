<?php
	switch($Config['_SelectType'][$Field]){
		case 'checkbox':

                        $data = unserialize($Data[$Field]);
                        $Out .= implode(',', $data);
			break;
		default:
			$Out .= $Data[$Field];
			break;
	};
        

?>