<?php
echo '<div style="width: '.$Config['_popupWidth'].'px;">';
    //_useToolbarTemplate _layoutTemplate
    if(!empty($_SESSION['DF_Notification'])){

        ob_start();
        foreach($_SESSION['DF_Notification'] as $Key=>$Notice){
        $uid = uniqid();
        ?>
            <div class="alert alert-<?php echo $_SESSION['DF_NotificationTypes'][$Key]; ?>" id="<?php echo $uid; ?>">
            <a class="close" onClick="jQuery('#<?php echo $uid; ?>').fadeOut('slow');">?</a>
            <?php echo $Notice; ?>
            </div>
        <?php
        }
        unset($_SESSION['DF_Notification']);
        echo ob_get_clean();
    }

    foreach($Config['_Field'] as $Field=>$Value) {
        $typeSet = explode('_', $Value);
        if(function_exists($typeSet[0].'_preForm')) {
            $Func = $typeSet[0].'_preForm';
            $Func($Field, $typeSet[1], $Media, $Config);
        }
    }
    if(!empty($_GET[$Config['_ReturnFields'][0]]) && !empty($Config['_Show_Edit'])) {
        $Form = dr_BuildUpDateForm($Media['ID'], $_GET[$Config['_ReturnFields'][0]]);
    }else {
        $Form = df_buildQuickCaptureForm($Media['ID']);
    }
    foreach($Config['_Field'] as $Field=>$Value) {
        $typeSet = explode('_', $Value);
        if(function_exists($typeSet[0].'_postForm')) {
            $Func = $typeSet[0].'_postForm';
            $Func($Field, $typeSet[1], $Media, $Config);
        }
    }
    if(empty($Config['_HideFrame'])) {
        InfoBox($Form['title']);
    }
    echo $Form['html'];
    if(empty($Config['_HideFrame'])) {
        EndInfoBox();
    }
    echo '</div>';
    return;
?>