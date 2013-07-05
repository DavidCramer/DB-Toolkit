<?php

function gravatar_processValue($Value, $Type, $Field, $Config, $EID, $Data){

    return '<img src="http://www.gravatar.com/avatar/'.md5(strtolower(trim($Value))).'?s='.$Config['_GravatarSize'][$Field].'" width="'.$Config['_GravatarSize'][$Field].'" height="'.$Config['_GravatarSize'][$Field].'" class="gravatar" >';

}

function gravatar_setup($Field, $Table, $Config = false){
	
	$PreSize = '100';
	if(!empty($Config['Content']['_GravatarSize'][$Field])){
		$PreSize = $Config['Content']['_GravatarSize'][$Field];
	}
	$PostSize = '100';
	if(!empty($Config['Content']['_GravatarSizeV'][$Field])){
		$PostSize = $Config['Content']['_GravatarSizeV'][$Field];
	}

	$Return = 'Icon Size: <input type="text" name="Data[Content][_GravatarSize]['.$Field.']" value="'.$PreSize.'" class="textfield" size="5" /> (px) <span class="description">Max size is 512</span>&nbsp;<br />';
	$Return .= 'View Size: <input type="text" name="Data[Content][_GravatarSizeV]['.$Field.']" value="'.$PostSize.'" class="textfield" size="5" /> (px) <span class="description">Max size is 512</span>&nbsp;';
       
        
return $Return;
}


?>