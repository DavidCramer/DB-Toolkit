<?php
echo '<div style="width: '.$Config['_popupTypeView'].'px;">';
    if(!empty($_SESSION['DocumentLoaded'])) {
        echo '<div class="warning">Database Interface View Mode. Requires: ';
        echo implode(', ', $Config['_ReturnFields']);
        echo '</div>';
    }
    if(!empty($_GET[$Config['_ReturnFields'][0]])) {

        $Item = di_showItem($Media['ID'], $_GET[$Config['_ReturnFields'][0]]);
        //dump($Item);
        if(empty($Config['_HideFrame'])) {
            InfoBox($Config['_ReportTitle']);
        }
        echo $Item['html'];
        if(empty($Config['_HideFrame'])) {
            EndInfoBox();
        }
    }
    echo '</div>';
    return;
    ?>