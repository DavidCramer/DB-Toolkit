<?php

/* Caldera Engine - API engine
 * (C) 2011 - Caldera
 *
 */
//  Key / Method / Format / ? GET Variables


    $interfaceID = trim($matches[0], '/');    
    $vars = explode($interfaceID, $_SERVER['REQUEST_URI']);
    $vars = explode('/', ltrim($vars[1], '/'));
    

    
    if(!empty($pattern['interfaces'][$interfaceID])){
        $interfaceID = $pattern['interfaces'][$interfaceID];
    }    
    // validate API Key
    $Intrface = get_option($interfaceID);
    $Media = $Intrface;
    $APIkey = $vars[0];
    $Method = $vars[1];
    $Format = $vars[2];

    $Page = 0;
    if(!empty($_GET['offset'])){
        $Page = $_GET['offset'];
    }
    $Limit = false;
    if(!empty($_GET['limit'])){
        $Limit = $_GET['limit'];
    }
    $Config = unserialize(base64_decode($Intrface['Content']));
    if($vars[0] == 'import'){

        dt_runDataSourceImport($Config);
        exit;

    }

    if(!empty($vars[1])){
        if($vars[0] == 'auth'){
            if(!empty($_POST['user']) && !empty($_POST['pass'])){
                $creds = array();
                $creds['user_login'] = $_POST['user'];
                $creds['user_password'] = $_POST['pass'];
                $creds['remember'] = true;
                $user = wp_signon($creds, false);
                header("content-type: text/" . strtolower($vars[1]));
                if($Config['_menuAccess'] != 'null'){
                    if(!isset($user->allcaps[$Config['_menuAccess']])){
                       if(strtolower($vars[1]) == 'json'){
                           $output['result'] = 'fail';
                           $output['error'] = $user;
                           echo json_encode($output);
                           exit;
                       }else{
                           echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
                           echo "   <result>fail</error>\r\n";
                           echo "   <error>Access Denied</error>\r\n";
                           echo "</xml>";
                           exit;
                       }
                        exit;
                    }
                }
                
                if(is_wp_error($user)){
                   if(strtolower($vars[1]) == 'json'){
                       $output['result'] = 'fail';
                       $output['error'] = $user->get_error_message();
                       echo json_encode($output);
                       exit;
                   }else{
                       echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
                       echo "   <result>fail</error>\r\n";
                       echo "   <error><![CDATA[".$user->get_error_message()."]]></error>\r\n";
                       echo "</xml>";
                       exit;
                   }

                }else{
                    set_current_user($user->data->ID);
                    $token = API_getCurrentUsersKey();
                    if(strtolower($vars[1]) == 'json'){
                        $output['result'] = 'success';
                        $output['token'] = $token;
                        echo json_encode($output);
                    }else{
                       echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
                       echo "   <result>success</error>\r\n";
                       echo "   <error>".$token."</error>\r\n";
                       echo "</xml>";
                    }
                    exit;
                }
            }
        }
    }

    if($Config['_APIAuthentication'] == 'key'){
        //echo API_getCurrentUsersKey();
        if($userData = API_decodeUsersAPIKey($APIkey)){            
            if($user = get_user_by('id', $userData['id'])){
                if($user->user_pass != $userData['pass_word']){
                    api_Deny();
                    exit;
                }else{
                    set_current_user($userData['id']);
                }
            }else{
                api_Deny();
                exit;
            }
        }else{
            api_Deny();
            exit;
        }

    }else{
        $VerifyKey = md5($interfaceID.$Config['_APISeed']);
        if ($VerifyKey !== $APIkey) {
            api_Deny();
            exit;
        }
    }
    if (!empty($Method)) {
        
        $formatType = strtolower($Format);
        switch ($formatType) {
            case 'xml':
                header('Content-type: text/xml; charset=utf-8');
                break;
            case 'json':
                header('Content-Type: text/javascript; charset=utf-8');
                break;
            case 'html':
                header('Content-type: text/html; charset=utf-8');
                break;
        }
        switch ($Method) {
            
            case 'search':
                if(empty($Config['_APIMethodSearch'])){
                    api_Deny();
                    exit;
                }
                if (!empty($Format)) {
                    if (strtolower($Format) != 'xml' && strtolower($Format) != 'json' && strtolower($Format) != 'html') {
                        api_Deny();
                    }
                    //header("content-type: text/" . strtolower($Format));
                        //($EID, $Page = false, $SortField = false, $SortDir = false, $Format = false, $limitOveride = false)
                    $Return = false;
                    if($Format == 'html'){
                        $Format = false;
                    }
                    include PLUGINDIR.'/db-toolkit/data_report/element.def.php';
                    if(!empty($_GET)){
                    // Convert the search strings
                        foreach($_GET as $gkey=>$val){
                            $_SESSION['reportFilters'][$interfaceID][$gkey] = $val;
                        }
                    }
                    
                    echo dr_BuildReportGrid($interfaceID, $Page, false, false, strtolower($Format), $Limit, $Return);
                        foreach($_GET as $gkey=>$val){
                            unset($_SESSION['reportFilters'][$interfaceID][$gkey]);
                        }
                    exit;
                }
                break;
            case 'list':
                if(empty($Config['_APIMethodList'])){
                    api_Deny();
                    exit;
                }
                if (!empty($Format)) {
                    if (strtolower($Format) != 'xml' && strtolower($Format) != 'json' && strtolower($Format) != 'html') {
                        api_Deny();
                    }

                    //header("content-type: text/" . strtolower($Format));
                        //($EID, $Page = false, $SortField = false, $SortDir = false, $Format = false, $limitOveride = false)
                    $Return = false;
                    if($Format == 'html'){
                        $Format = false;
                    }
                    include PLUGINDIR.'/db-toolkit/data_report/element.def.php';
                    echo dr_BuildReportGrid($interfaceID, $Page, false, false, strtolower($Format), $Limit, $Return);
                    exit;
                }
                break;
            case 'fetch':
                if(empty($Config['_APIMethodFetch'])){
                    api_Deny();
                    exit;
                }
                if (!empty($Format)) {
                    if (strtolower($Format) != 'xml' && strtolower($Format) != 'json' && strtolower($Format) != 'html') {
                        api_Deny();
                    }
                    //header("content-type: text/" . strtolower($Format));
                        //($EID, $Page = false, $SortField = false, $SortDir = false, $Format = false, $limitOveride = false)
                    $Return = false;
                    if(!empty($_GET['itemID'])){
                        $Return = array($Config['_ReturnFields'][0]=>$_GET['itemID']);
                    }
                    if(!empty($_POST['itemID'])){
                        $Return = array($Config['_ReturnFields'][0]=>$_POST['itemID']);
                    }
                    if($Format == 'html'){
                        $Format = false;
                    }
                    echo dr_BuildReportGrid($interfaceID, $Page, false, false, strtolower($Format), $Limit, $Return);
                    exit;
                }
                break;
            case 'insert':
                if(empty($Config['_APIMethodInsert'])){
                    api_Deny();
                    exit;
                }
                if(!empty($_POST)){
                    $result = df_processInsert($interfaceID, $_POST);
                    echo json_encode($result);
                }else{
                    $Return['Message'] = 'No Data submitted';
                    echo json_encode($Return);
                }
                exit;
                break;
            case 'update':
                
                if(empty($Config['_APIMethodUpdate'])){
                    api_Deny();
                    exit;
                }
                
                if(!empty($_POST)){
                
                    $Data[$Config['_ReturnFields'][0]] = $_POST['itemID'];
                    unset($_POST['itemID']);
                    $Data[$interfaceID] = $_POST;

                $result = df_processupdate($Data, $interfaceID);
                echo json_encode($result);
                }else{
                    $Return['Message'] = 'No Data submitted';
                    echo json_encode($Return);
                }
                exit;
                break;
            case 'delete':
                if(empty($Config['_APIMethodDelete'])){
                    api_Deny();
                    exit;
                }

                if(!empty($_POST['itemID'])){
                $result = df_deleteEntries($interfaceID, $_POST['itemID']);
                echo json_encode($result);
                }else{
                    $Return['Message'] = 'No Data submitted';
                    echo json_encode($Return);
                }
                exit;
                break;
        }
    } else {
        api_Deny();
    }
    api_Deny();
    //header ("content-type: text/xml");
    //echo dr_BuildReportGrid($_GET['subvars'][3], false, false, false, 'xml');


function api_encode_string($str) {
    //$str = gzdeflate($str);
    $str = base64_encode($str);
    return urlencode(str_replace('=', '', $str));
}

function api_dencode_string($str) {
    $str = urldecode($str);
    $str = base64_decode($str);
    //$str = gzinflate($str);
    return str_replace('=', '', $str);
}

function api_Deny() {
    mysql_close();
    //header("content-type: text/html");
    echo 'Access Denied';
    exit;
}

?>
