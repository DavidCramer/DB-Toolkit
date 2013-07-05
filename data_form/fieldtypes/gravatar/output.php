<?php
        $Out = '<img src="http://www.gravatar.com/avatar/'.md5(strtolower(trim($Data[$Field]))).'?s='.$Config['_GravatarSizeV'][$Field].'" width="'.$Config['_GravatarSizeV'][$Field].'" height="'.$Config['_GravatarSizeV'][$Field].'" >';
?>