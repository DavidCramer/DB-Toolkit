<?php

// use filters
$Defaults = false;
$FilterVisiable = 'none';

/*if (empty($_GET['PageData']['ID'])) {
    $_GET['PageData']['ID'] = $_SESSION['DocumentLoaded'];
}
if (!empty($_SESSION['dr_filters'][$_GET['PageData']['ID']])) {
    $Defaults = df_cleanArray($_SESSION['dr_filters'][$_GET['PageData']['ID']]);
    $FilterVisiable = 'block';
}
*/

if (empty($Config['_useListTemplate'])) {
    echo '<div id="reportPanel_' . $Media['ID'] . '">';
}
//($EID, $Page = 1, $SortField = false, $SortDir = false)

if (empty($_SESSION['report_' . $Media['ID']]['SortField'])) {
    $_SESSION['report_' . $Media['ID']]['SortField'] = $Config['_SortField'];
}
if (empty($_SESSION['report_' . $Media['ID']]['SortDir'])) {
    $_SESSION['report_' . $Media['ID']]['SortDir'] = $Config['_SortDirection'];
}
// Check sorts are valid
if (!empty($Config['_IndexType'][$_SESSION['report_' . $Media['ID']]['SortField']])) {
    $SortPart = explode('_', $Config['_IndexType'][$_SESSION['report_' . $Media['ID']]['SortField']]);
    if (!empty($SortPart[1])) {
        if ($SortPart[1] == 'hide') {
            $_SESSION['report_' . $Media['ID']]['SortField'] = $Config['_SortField'];
            $_SESSION['report_' . $Media['ID']]['SortDir'] = $Config['_SortDirection'];
        }
    } else {
        $_SESSION['report_' . $Media['ID']]['SortField'] = $Config['_SortField'];
        $_SESSION['report_' . $Media['ID']]['SortDir'] = $Config['_SortDirection'];
    }
} else {
    $_SESSION['report_' . $Media['ID']]['SortField'] = $Config['_SortField'];
    $_SESSION['report_' . $Media['ID']]['SortDir'] = $Config['_SortDirection'];
}

if (!empty($Config['_Field'][$_SESSION['report_' . $Media['ID']]['SortDir']])) {
    //echo 'not';
}

if (!empty($_SESSION['reportFilters'][$Media['ID']]) || empty($Config['_SearchMode'])) {
    $gotTo = false;
    if (!empty($_GET['_pg'])) {
        $gotTo = $_GET['_pg'];
    }

    echo dr_BuildReportGrid($Media['ID'], $gotTo, $_SESSION['report_' . $Media['ID']]['SortField'], $_SESSION['report_' . $Media['ID']]['SortDir']);


    if (!empty($Config['_autoPolling'])) {
        $Rate = $Config['_autoPolling'] * 1000;

        $_SESSION['dataform']['OutScripts'] .= "
                setInterval('dr_reloadData(\'" . $Media['ID'] . "\')', " . $Rate . ");
        ";
    }

    /*
     * Experimental Graphing


      global $wpdb;
      $Query = dr_BuildReportGrid($Media['ID'], $gotTo, $_SESSION['report_'.$Media['ID']]['SortField'], $_SESSION['report_'.$Media['ID']]['SortDir'],'sql');
      //$Query = explode('LIMIT', $Query);
      //$Query = $Query[0];
      $rowData = $wpdb->get_results($Query, ARRAY_A);
      //vardump($rowData);
      $graphData =  "var data = [ ";
      $inputData = array();
      foreach($rowData as $Entry){

      if($Entry['DateOrdered'] != '0000-00-00 00:00:00'){
      //echo $Entry['DateOrdered'].'<br />';
      $inputData[] = '['.(strtotime($Entry['DateOrdered'])*1000).','.$Entry['__4c0a9aaf39956'].']';
      }//vardump($Entry);
      //break;
      }
      $graphData .= implode(',', $inputData);
      $graphData .= "];";

      echo '<div id="placeholder" style="width:680px; height: 450px;">graph</div>';
      $_SESSION['dataform']['OutScripts'] .= $graphData;
      $_SESSION['dataform']['OutScripts'] .= "



      $.plot($(\"#placeholder\"), [{
      label: \"Phone sales\",
      data: data,
      lines: { show: true },
      points: { show: true }


      }], { xaxis: { mode: \"time\", timeformat: \"%y-%m-%d %H:%M:%S\"} });

      ";
     */
}
if (empty($Config['_useListTemplate'])) {
    echo '</div>';
}

if (is_admin ()) {
    //$SharedSecret = md5($Media['ID']).md5(serialize($Config));
    //echo '<div class="list_row1">API Key: <input type="text" value="'.$SharedSecret.'" style="width:98%;" onclick="jQuery(this).select();" onchange="jQuery(this).val(\''.$SharedSecret.'\');" /></div>';
    //echo '<div class="list_row2">Channel: '.$Media['ID'].'</div>';
}
?>