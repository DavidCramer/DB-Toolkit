<?php

$FieldTypeFunctions = loadFolderContents(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes');

foreach($FieldTypeFunctions[0] as $Type) {
    if(file_exists(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$Type[1].'/functions.php')) {
        include_once(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$Type[1].'/functions.php');
    }
}


if(is_admin()) {
    // edit mode check
    function df_controlFunc($Field, $Table, $Func) {
        if(function_exists($Func)) {
            $Return = '<div class="widefat" id="'.$Field.'_configPanel" style="text-align:left;">';
            $Return .= '<h3>Fieldtype Config</h3><div class="admin_config_panel">';
            $Return .= $Func($Field, $Table);
            $Return .= '</div></div>';
            $Return .= '<input type="button" class="button" style="margin-top:5px;" value="Setup" onclick="toggle(\''.$Field.'_configPanel\');" />';
            return $Return;
        }
        return 'No Config Options Available';
    }
    function df_listTables($TableReference, $JFunc = 'alert', $Default = false, $Req = false) {
    /*
    global $wpdb;

        if(empty($Value))
            $Value = '';
        //$Return .= 'Select Table: <select name="Data[Content]['.$TableReference.']" id="'.$TableReference.'" onchange="'.$JFunc.'(\''.$TableReference.'\');">';
        //$Return .= '<a tabindex="0" href="#database-tables" class="button" id="hierarchybreadcrumb">Select Database & Table</a>';

        $refTitle = 'Select Database & Table';
        if(!empty($Default)){
            $refTitle = 'Table: '.str_replace('`', '', $Default).': Change';
        }

        $Return .= '<ul class="tools_widgets">';
        $Return .= '<li class="root_item"><a class="parent hasSubs" id="tableReferance">'.$refTitle.'</a>';
        $Return .= '<ul>';
        $databases = $wpdb->get_results("show databases", ARRAY_N);


        foreach($databases as $database) {
            if($database[0] != 'information_schema' AND $database[0] != 'mysql'){

                $class="";
                if($database[0] == DB_NAME){
                    $class="highlight";
                }

                $Return .= '<li><a class="'.$class.'" ><img src="'.WP_PLUGIN_URL.'/db-toolkit/data_report/db.png" align="absmiddle" /> '.$database[0].'</a>';
                $Return .= '<ul>';
                $Data = $wpdb->get_results( "SHOW TABLES FROM `".$database[0]."`", ARRAY_N);
                $Return .= '<li class="title"><h2>'.$database[0].'</h2><li>';
                foreach($Data as $Tables) {
                    //vardump($Tables);
                    //if(strpos($Tables[0], $wpdb->prefix.'dbt_') === false){
                        $Value = $Tables[0];
                        $Sel = '';
                        if($Default == $Value) {
                            $Sel = 'selected="selected"';
                        }
                        //if(substr($Value, 0, 5) != 'dais_'){
                        $List[] = $Value;
                        //$Return .= '<option value="'.$database[0].'`.`'.$Value.'" '.$Sel.'>&nbsp;&nbsp;'.$Value.'</option>';
                        $Return .= '<li><a href="#" onclick="jQuery(\'#'.$TableReference.'\').val(\''.$database[0].'`.`'.$Value.'\'); jQuery(\'#tableReferance\').html(\'Table: '.$database[0].'.'.$Value.' : Change\'); '.$JFunc.'(\''.$TableReference.'\'); jQuery(\'.root_item ul\').hide();"><img src="'.WP_PLUGIN_URL.'/db-toolkit/data_report/table.png" align="absmiddle" /> '.$Value.'</a></li>';
                        //}
                    //}

                }
                $Return .= '</ul>';
                $Return .= '</li>';
            }
        }

        $Return .= '</ul>';
        $Return .= '</li>';

        $Return .= '<li class="root_item">&nbsp;</li>';
        $Return .= '<li class="root_item">&nbsp;</li>';
        $Return .= '<li class="root_item"><a class="parent button" href="#" onclick="dt_addNewTable(\''.DB_NAME.'\'); return false;">Create New Table</a></li>';

        $Return .= '</ul>';

        $Return .= '<div style="clear:both;"><input type="hidden" value="'.$Default.'" name="Data[Content]['.$TableReference.']" id="'.$TableReference.'" onchange="'.$JFunc.'(\''.$TableReference.'\');" /></div>';
        return $Return;

        */

        /// old select method

        global $wpdb;

        if(empty($Value))
            $Value = '';
        $Return = 'Select Table: <select name="Data[Content]['.$TableReference.']" id="'.$TableReference.'" onchange="'.$JFunc.'(\''.$TableReference.'\');">';
        $Return .= '<option value="">'.$Value.'</option>';

        //$databases = $wpdb->get_results("show databases", ARRAY_N);


        //foreach($databases as $database) {
            //if($database[0] != 'information_schema' AND $database[0] != 'mysql'){
                //$Return .= '<optgroup label="'.$database[0].'">';
                $Data = $wpdb->get_results( "SHOW TABLES FROM `".DB_NAME."`", ARRAY_N);
                foreach($Data as $Tables) {
                    //vardump($Tables);
                    //if(strpos($Tables[0], $wpdb->prefix.'dbt_') === false){
                        $Value = $Tables[0];
                        $Sel = '';
                        if($Default == $Value) {
                            $Sel = 'selected="selected"';
                        }
                        //if(substr($Value, 0, 5) != 'dais_'){
                        $List[] = $Value;
                        $Return .= '<option value="'.$Value.'" '.$Sel.'>&nbsp;&nbsp;'.$Value.'</option>';
                        //}
                    //}

                }
                //$Return .= '</optgroup>';
            //}
        //}

        $Return .= '</select> <a href="#" onclick="dt_addNewTable(\''.DB_NAME.'\'); return false;">Add New</a>';
        return $Return;
    }

    function df_tableFormSetup($Table, $Config = false) {
        $Defaults = false;
        if(!empty($Config)) {
            $Defaults = $Config['Content']['_Field'];
        }
        //include('configs/upc.cfg.php');
        ob_start();
        echo '<span class="captions">Required</span>';
        $result = mysql_query("SHOW COLUMNS FROM `".$Table."`");
        if (mysql_num_rows($result) > 0) {
            $Row = 'list_row2';
            while ($row = mysql_fetch_assoc($result)) {
                $FieldSet = explode('_', $Defaults[$row['Field']]);
                $Row = dais_rowSwitch($Row);
                $Field = $row['Field'];
                $name = df_parseCamelCase($Field);
                //REquired Check
                $Sel = '';
                if(!empty($Config['Content']['_Required'][$Field])) {
                    $Sel = 'checked="checked"';
                }
                echo '<div id="Field_'.$Field.'" class="list_row1 table_sorter" style="padding:3px;"><img src="'.WP_PLUGIN_DIR.'/db-toolkit/data_report/arrow_out.png" align="absmiddle" class="OrderSorter" /><input type="checkbox" name="Data[Content][_Required]['.$Field.']" id="required_'.$Field.'" '.$Sel.' /> '.ucwords($name).' : '.df_fieldTypes($Field, $Table, $row, $Defaults).'<span class="list_row3" id="ExtraSetting_'.$Field.'">';
                if(!empty($FieldSet[1])) {
                    if(file_exists(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$FieldSet[0].'/conf.php')) {
                        include(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$FieldSet[0].'/conf.php');
                        if($FieldTypes[$FieldSet[1]]['func'] != 'null') {
                            echo $FieldTypes[$FieldSet[1]]['func']($Field, $Table, $Config);
                        }
                    }
                }
                echo '</span></div>';
            }
        }
        return ob_get_clean();
    }


    function df_loadReturnFields($Table, $Default = false) {
        $Return = df_ListFields($Table, $Default, '_ReturnField');
        return $Return;
    }
    function df_loadSortFields($Table, $Default = false, $Dir = 'DESC') {
        $Return = df_ListFields($Table, $Default, '_SortField');
        $Return .= '<select name="Data[Content][_SortDirection]">';
        $Sel = '';
        if($Dir == 'ASC') {
            $Sel = 'selected="selected"';
        }
        $Return .= '<option value="ASC" '.$Sel.'>Ascending</option>';
        $Sel = '';
        if($Dir == 'DESC') {
            $Sel = 'selected="selected"';
        }
        $Return .= '<option value="DESC" '.$Sel.'>Descending</option>';
        $Return .= '</select>';
        return $Return;
    }

    function df_ListFields($Table, $Default, $Name) {
        if(empty($Table)){
            return;
        }
        $result = mysql_query("SHOW COLUMNS FROM `".$Table."`");
        $Return = '<select name="Data[Content]['.$Name.']" id="Return_'.$Table.'">';
        //$Return .= '<option value="false">None</option>';
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {
                $Sel = '';
                if($Default == $row['Field']) {
                    $Sel = 'selected="selected"';
                }
                $Return .= '<option value="'.$row['Field'].'" '.$Sel.'>'.$row['Field'].'</option>';
            }
        }
        if(!empty($_GET['interface'])) {
            $Element = getElement($_GET['interface']);
            $Config = &$Element['Content'];
            if(!empty ($Config['_CloneField'])) {
                $Return .= '<optgroup label="Cloned Fields">';
                foreach ($Config['_CloneField'] as $Field=>$Array) {
                    $Sel = '';
                    if($Default == $Field) {
                        $Sel = 'selected="selected"';
                    }
                    $Return .= '<option value="'.$Field.'" '.$Sel.'>'.$Config['_FieldTitle'][$Field].'</option>';
                }
            }

        }
        $Return .= '</select>';

        return $Return;
    }

    function df_fieldTypes($Field, $Table, $c, $Defaults) {
        // $c = data types.

        if (!key_exists($Field, $Defaults))
            $Defaults[$Field] = '';
        $Return = '';
        $Type = explode('_', $Defaults[$Field]);
        //$Return = $Type[0];
        global $wpdb;
        $existingFields = $wpdb->get_results("SHOW FIELDS FROM `".$Table."`", ARRAY_A);

        foreach($existingFields as $curField){
            if($curField['Field'] == $Field){
                $FieldType = $curField['Type'];
            }
        }
        $Title = 'Ignore Field';
        $Icon = WP_PLUGIN_URL.'/db-toolkit/data_form/wand.png';
        if($Defaults[$Field] == 'hidden' || empty($Defaults[$Field])){
            /*
            // Auto Select the BEST fieldtype for the JOB!
            $Types = loadFolderContents(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes');
            $Icon = WP_PLUGIN_URL.'/db-toolkit/data_form/wand.png';
            foreach($Types[0] as $Type) {
                if(file_exists(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$Type[1].'/conf.php')) {
                    include(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$Type[1].'/conf.php');
                    foreach($FieldTypes as $possField=>$conf){
                        if(strtolower($conf['baseType']) == $FieldType){

                        if(file_exists(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$Type[1].'/icon.png')) {
                            $Icon = WP_PLUGIN_URL.'/db-toolkit/data_form/fieldtypes/'.$Type[1].'/icon.png';
                        }
                        echo $Icon.'<br>';
                        $Defaults[$Field] = $Type[1].'_'.$possField;
                        $func = $conf['func'];
                        $Title = $conf['name'];
                        break;
                        }
                    }
                }
            }
             *
             */


            $Return .= '<span class="button" id="fieldTypeButton_'.$Field.'" onclick="bf_loadFieldTypePanel(\''.$Field.'_FieldTypePanel\', \''.$Table.'\');"><span style="background: url('.$Icon.') left center no-repeat; padding:5px 18px;"> '.$Title.'</span></span> <span style="display:none;" id="'.$Field.'_FieldTypePanel_status"><img src="'.WP_PLUGIN_URL.'/db-toolkit/images/indicator.gif" align="absmiddle" /></span>';
            $Return .= '<input type="hidden" name="Data[Content][_Field]['.$Field.']" id="Fieldtype_'.$Field.'" value="'.$Defaults[$Field].'" />';
            return $Return;
        }
        if(file_exists(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$Type[0].'/conf.php')) {
            $Icon = WP_PLUGIN_URL.'/db-toolkit/data_form/fieldtype.png';
            if(file_exists(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$Type[0].'/icon.png')) {
                $Icon = WP_PLUGIN_URL.'/db-toolkit/data_form/fieldtypes/'.$Type[0].'/icon.png';
            }

            include(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$Type[0].'/conf.php');
            //vardump($FieldTypes[$Type[0]]);
            $Return .= '<span class="button" id="fieldTypeButton_'.$Field.'" onclick="bf_loadFieldTypePanel(\''.$Field.'_FieldTypePanel\', \''.$Table.'\');"><span style="background: url('.$Icon.') left center no-repeat; padding:5px 18px;"> '.$FieldTypes[$Type[1]]['name'].'</span></span> <span style="display:none;" id="'.$Field.'_FieldTypePanel_status"><img src="'.WP_PLUGIN_URL.'/db-toolkit/images/indicator.gif" align="absmiddle" /></span>';
            $Return .= '<input type="hidden" name="Data[Content][_Field]['.$Field.']" id="Fieldtype_'.$Field.'" value="'.$Defaults[$Field].'" />';

        }
        return $Return;
    }


    function df_buildFieldTypesMenu($Field, $Table){

        global $wpdb;
        $existingFields = $wpdb->get_results("SHOW FIELDS FROM `".$Table."`", ARRAY_A);
        $fields = array();
        foreach($existingFields as $curField){
            $fields[$curField['Field']] = $curField['Type'];
        }



        $Types = loadFolderContents(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes');
        //$Return = '<select name="Data[Content][_Field]['.$Field.']" id="Fieldtype_'.$Field.'" >';
        //$Return = '<select name="Data[Content][_Field]['.$Field.']" id="Fieldtype_'.$Field.'" >'
        //$Return .= '<optgroup label="Auto Increment">';
        //$Return .= '<option value="hidden" onclick="df_noOptions(\''.$Field.'\');">Auto</option>';
        //$Return .= '</optgroup>';
        $Return = '';
        if(!empty($fields[$Field])){
            //$Return .= '<div class="warning" style="font-weight:normal;">Changing FieldTypes on existing data may result in data loss. <div style="padding:3px; cursor:pointer;"><a onclick="jQuery(\'.inComp\').toggle(); return false;">Show All FieldTypes</a></div></div>';
        }

        $Return .= '<div style="width:33.33333%; float:left;">';
        //$Return .= '<div style="">';
        $Return .= '<div style="padding:3px; cursor:default" class="admin_config_toolbar">Default / Auto Increment</div>';
        $Return .= '<div style="" id="_default_'.$Field.'">';
            $Icon = WP_PLUGIN_URL.'/db-toolkit/data_form/wand.png';
            $Return .= '<div style="padding:3px;">';
            $Return .= '<a href="#" id="'.$Field.'_hidden" onclick="return df_setOptions(\''.$Field.'\', \'null\', \'hidden\');"><span style="background: url('.$Icon.') left center no-repeat; padding:5px 18px;"> Ignore Field</span></a>';
            $Return .= '</div>';
        $Return .= '</div>';
        $Return .= '</div>';
        //sort($Types[0]);
        foreach($Types[0] as $Type) {

            if(file_exists(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$Type[1].'/conf.php')) {
                include(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$Type[1].'/conf.php');
                //$Return .= '<optgroup label="'.$FieldTypeTitle.'">';
                $CIcon = WP_PLUGIN_URL.'/db-toolkit/data_form/fieldtype.png';
                if(file_exists(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$Type[1].'/icon.png')) {
                    $CIcon = WP_PLUGIN_URL.'/db-toolkit/data_form/fieldtypes/'.$Type[1].'/icon.png';
                }
                $Return .= '<div style="width:33.33333%; float:left;">';
                //$Return .= '<div style="">';
                $Return .= '<div style="padding:3px;  cursor:default;" class="admin_config_toolbar"><span style="background: url('.$CIcon.') left center no-repeat; padding:5px 20px;"> '.$FieldTypeTitle.'</span></div>';
                $Return .= '<div id="folder_'.$Type[1].'_'.$Field.'" style="padding-left:5px;">';
                ksort($FieldTypes);
                $used = 0;
                foreach($FieldTypes as $Key=>$FieldSet) {
                    $fieldDisplay = "block";
                    $fieldClass = '';

                    /*
                    $fieldDisplay = "none";
                    $fieldClass = 'inComp';
                    if(!empty($fields[$Field])){
                        if(strtolower($FieldSet['baseType']) == strtolower($fields[$Field])){
                            $fieldDisplay = "block";
                            $fieldClass = '';
                            $used++;
                        }
                    }else{
                       $fieldDisplay = "block";
                       $fieldClass = '';
                       $used++;
                    }
                    */

                        $Icon = WP_PLUGIN_URL.'/db-toolkit/data_form/insert.png';
                        if(file_exists(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$Type[1].'/'.$Type[1].'_'.$Key.'.png')) {
                            $Icon = WP_PLUGIN_URL.'/db-toolkit/data_form/fieldtypes/'.$Type[1].'/'.$Type[1].'_'.$Key.'.png';
                        }
                        $Return .= '<div style="padding:4px; display:'.$fieldDisplay.';" class="'.$fieldClass.'">';
                        $Return .= '<a href="#" id="'.$Field.'_'.$Type[1].'_'.$Key.'" onclick="return df_setOptions(\''.$Field.'\', \''.$FieldSet['func'].'\', \''.$Type[1].'_'.$Key.'\');"><span style="background: url('.$Icon.') left center no-repeat; padding:5px 20px;"> '.$FieldSet['name'].'</span></a>';
                        $Return .= '</div>';


                }
                //if(empty($used)){
                //    $Return .= '<div style="padding:4px;" class="inComp">';
                //    $Return .= '<span class="description">No compatible fieldtypes</span>';
                //    $Return .= '</div>';
               //}
                $Return .= '</div>';
                $Return .= '</div>';
            }
        }
        $Return .= '<div style="clear:both;"></div>';
        //$Return .= '</select>';

        return $Return;
    }

    // End Edit mode check
}

function df_processAjaxForm($Input, $addQuery = false){

    if(!empty($addQuery)){
        parse_str($addQuery, $_GET);
    }
    ob_start();
    parse_str($Input, $Data);
    $Data = stripslashes_deep($Data);

    if(!empty($Data['processKey'])) {
        $Data = stripslashes_deep($Data);
        if($Data['processKey'] == $_SESSION['processKey']) {
            if(!empty($Data['dr_update'])) {
                $EID = $Data['dataForm']['EID'];
                $Setup = getelement($EID);
                unset($Data['dataForm']['dr_update']);
                unset($Data['dataForm']['EID']);
                $Return = df_processUpdate($Data['dataForm'], $EID);
                dr_trackActivity('Update', $EID, $Return['Value']);
                if(empty($Setup['Content']['_NotificationsOff'])) {
                    $_SESSION['DF_Post'][] = $Return['Message'];
                }
                $_SESSION['DF_Post_returnID'] = $Return['Value'];
                $_SESSION['DF_Post_EID'] = $EID;
            }else {
                foreach($Data['dataForm'] as $EID=>$Data) {
                    $Return = df_processInsert($EID, $Data);
                    // Track Activity
                    dr_trackActivity('Insert', $EID, $Return['Value']);
                    $Setup = getelement($EID);
                    if(empty($Setup['Content']['_NotificationsOff'])) {
                        $_SESSION['dataform']['OutScripts'] .="
                            df_dialog('".$Return['Message']."');
                        ";
                    }
                }
            }
        }
    }
    if(!empty($addQuery)){
        $Return['addQuery'] = $addQuery;
    }
    //vardump($Return);
    return $Return;

    //return ob_get_clean();
}


function is_uppercase($Char) {
    $Upper = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
    //$Lower = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
    if(in_array($Char, $Upper)) {
        return true;
    }
    return false;
}

function df_buildQuickCaptureForm($EID, $addQuery = false) {

    parse_str($addQuery, $_GET);

    $Data = getelement($EID);
    //$Return = '<h2>'.$Data['Content']['_New_Item_Title'].'</h2>';
    if(!empty($Data['Content']['_New_Item_Hide'])){
        $Return['title'] = 'Access Denied';
        $Return['html'] = '<div class="notice">Entry Insertion is disabled on this interface</div>';
        return $Return;
    }
    $Data['_ActiveProcess'] = 'insert';
    $Out = df_BuildCaptureForm($Data);
    $Out['title'] = $Data['Content']['_New_Item_Title'];
    if(!empty($addQuery)){
        $Out['addQuery'] = $addQuery;
    }

    if($Data['Content']['_ViewMode'] != 'form'){
        $Out['script'] = $_SESSION['dataform']['OutScripts'];
        unset($_SESSION['dataform']['OutScripts']);
    }
    if(!isset($Out['width'])){
        //$Out['width'] = 1550;
    }

    //$Return = df_BuildCaptureForm($Data);
    return $Out;//$Return;
}
function df_buildtabsindex($Setup, $Config){
    // serach for main tabs
    //vardump($Config);
    $Return = '';
    foreach($Setup as $Row=>$Set){
        //vardump($Set);
        if(empty($Set['col2'])){
            if(!empty($Set['col1']['Fields'])) {
                foreach( $Set['col1']['Fields'] as $Column) {
                    foreach($Column as $Item) {
                        if(substr($Item,0,4) == '_tab') {
                            $maintabs[$Item] = $Config['_Tab'][$Item]['Title'];
                        }
                    }
                }
            }
        }
    }
    if(!empty($maintabs)){
        $tab_id = uniqid('tab_');
        $Return .= '<div id="'.$tab_id.'">';
        $Return .= '<ul class="content-box-tabs">';
        foreach($maintabs as $id=>$lable){
            $Return .= '<li><a href="#'.$id.'">'.$lable.'</a></li>';
        }
        $Return .= '</ul>';
        $Out['html'] = $Return;
        $Out['tabs'] = $maintabs;

        $_SESSION['dataform']['OutScripts'] .="
            jQuery('#".$tab_id."').tabs();
        ";
        return $Out;
    }

return false;
}
function df_checkTabState($ColSet, $Tabs){

    foreach($ColSet['Fields'] as $Field){
        if(!empty($Tabs['tabs'][$Field['Name']])){

            return $Field['Name'];
        }
    }
return false;

}

function set_iso($string) {
    if(function_exists('mb_detect_encoding')){
        if(mb_detect_encoding($string, "UTF-8, ISO-8859-1") == "UTF-8"){
            return utf8_decode($string);
        }else{
            return $string;
        }
    }else{
        return $string;
    }
}

function df_reloadFormField($EID, $Field, $Default = false){
    global $wpdb;

    $source = explode('|', $Field);
    $Element = getelement($source[0]);
    $Config = $Element['Content'];
    $Field = $source[1];

    if(!empty($Default)){
        parse_str($Default, $Defaults);
        foreach($Defaults as $default){
            $Defaults[$Field] = $default;
            break;
        }
    }


    $FieldSet = explode('_', $Config['_Field'][$Field]);


        $name = df_parseCamelCase($Field);
        if(!empty ($Config['_FieldTitle'][$Field])){
            $name = $Config['_FieldTitle'][$Field];
        }
        $Req = false;
        if(!empty($Config['_Required'][$Field])) {
            $name = '<span style="color:#ff0000;">*</span>'.$name;
            $Req = 'validate[required]';
        }
        if(!empty($Config['_Unique'][$Field])) {
            if(!empty($Req)) {
                $Req = 'validate[required, ajax[ajaxUnique]]';
            }else {
                $Req = 'validate[optional, ajax[ajaxUnique]]';
            }
        }






        ob_start();
        if(!empty($Defaults[$Field])){
            $Val = esc_attr($Defaults[$Field]);
        }else{
            $Val = '';
        }
        if(!empty($Config['_readOnly'][$Field])){
            $func = $FieldSet[0].'_processValue';
            if(function_exists($func)){
                echo $func($Val, $FieldSet[1], $Field, $Config, $Element['ID'], $Defaults);
            }
        }else{
            include(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$FieldSet[0].'/input.php');
        }
        $Pre = ob_get_clean();




    $Output['html'] = $Pre;
    $Output['element'] = 'form-field-'.$Field;
    return $Output;
}

// TODO: rebuild this to be better!!!
function df_BuildCaptureForm($Element, $Defaults = false, $ViewOnly = false) {

    global $wpdb;

    include_once DB_TOOLKIT.'/data_form/phpscaffold.php';
    //vardump($_GET);
    $Script = '';
    $Config = $Element['Content'];
    //vardump($Config);
    if(!empty($Config['_FormLayout'])) {
        parse_str($Config['_FormLayout'], $Columns);
    }
    if(!empty($Config['_Edit_Element_Reference'])) {
        $Element = getelement($Config['_Edit_Element_Reference']);
        $Config = $Element['Content'];
    }

    if(!empty($Defaults) && !empty($Config['_Show_Edit'])) {

        $EditID = $Defaults;
        // userbase lockdown
        $hardFilter = '';
        if(!empty($Config['_UserBaseFilter'])){
            global $user_ID;
            if(!empty($user_ID)){
                $harfilter = '';
                foreach($Config['_UserBaseFilter'] as $userField=>$yes){
                    $hardFilter .= ' AND `'.$userField.'` = \''.$user_ID.'\' ';
                }
            }else{
                // return hahaha no change matee!
                $Output['width'] = 220;
                $Output['html'] = '<div style="warning">That does not belong to you!</div>';
                return $Output;
            }
        }

        $defRes = mysql_query("SELECT * FROM `".$Config['_main_table']."` WHERE `".$Config['_ReturnFields'][0]."` = '".$EditID."' ".$hardFilter.";");
        $Query = "SELECT * FROM `".$Config['_main_table']."` WHERE `".$Config['_ReturnFields'][0]."` = '".$EditID."' ".$hardFilter.";";
        if(mysql_num_rows($defRes) == 0) {
            $Output['width'] = 220;
            $Output['html'] = '<div style="warning">That does not belong to you!</div>';
            return $Output;

        }
        //$Defaults = mysql_fetch_assoc($defRes);
        $Defaults = $wpdb->get_results($Query, ARRAY_A);
        $Defaults = $Defaults[0];

    }else {
        unset($Defaults);
    }

    // check for a failed insert
    if(!empty($_SESSION['failedProcess'][$Element['ID']]) && empty($Defaults)){
        $Defaults = $_SESSION['failedProcess'][$Element['ID']]['Data'];
        $psudoDefault = true;
    }

    $Row = 'list_row2';
    $formID = rand(0,999);
    $SubmitURL = getdocument($Element['ParentDocument']);
    if(!empty($Config['_FormMode']) && !empty($Config['_ItemViewPage'])) {
        $SubmitURL = getdocument($Config['_ItemViewPage']);
    }

    $customClass= '';
    if(!empty($Config['_FormClass'])){
        $customClass= $Config['_FormClass'];
    }

    /// attempt to place in here
    ### DONT FORGET VALIDATION!!!!
    if(!empty($Config['_Required'])) {
        $_SESSION['dataform']['OutScripts'] .= "
			jQuery('#data_form_".$Element['ID']."').validationEngine({
			  success :  false,
			  failure : function() {}
			 });

		";
    }
    $Form = '';

    // Build Form layout
    $formStack = 'form-vertical';


    if(empty($Config['_grid']) || !empty($Config['_disableLayoutEngineform'])) {
        $formStack = 'form-horizontal';
        $Config['_grid']['row1']['col1'] = '100%';
        $Config['_gridLayout'] = '';
        $Layout = array();
        foreach($Config['_Field'] as $Key=>$val){
            $Layout[$Key] = 'row1_col1';
        }
        $Config['_gridLayout'] = true;
    }

    if(!empty($Config['_gridLayout']) && !empty($Config['_grid'])) {
        if(empty($Layout)){
            parse_str($Config['_gridLayout'], $Layout);
        }

        $Hidden = '<div class="formular"><form enctype="multipart/form-data" method="post" action="'.$_SERVER['REQUEST_URI'].'" class="'.$formStack.' '.$customClass.'" id="data_form_'.$Element['ID'].'" >';
        if(empty($_SESSION['processKey'])) {
            $_SESSION['processKey'] = uniqid(rand(100, 999).'_processKey_');
        }
        $Hidden .= '<input type="hidden" name="processKey" id="processKey" value="'.$_SESSION['processKey'].'" />';
        $Hidden .= '<input type="hidden" name="action" id="action" value="true" />';
        $Hidden .= '<input type="hidden" name="dataForm['.$Element['ID'].'][_control_'.uniqid().']" id="processKey_inner" value="'.$_SESSION['processKey'].'" />';


        $FormLayout = new Layout('fluid');
        //$FormLayout->debug();
        $tmpLayout = array();
        $fildLocations = array();
        $columnCounter = 1;
        $rowIndex = 1;
        $layoutData = array();
        //apply validation script
        if(!empty($Config['_Required'])) {
            $_SESSION['dataform']['OutScripts'] .= "
                jQuery('#data_form_".$Element['ID']."').validationEngine({
                  success :  false,
                  failure : function() {}
                 });
            ";
        }
        foreach($Config['_grid'] as $Columns){

            $tmpStr = array();
            $columnIndex = 1;
            foreach($Columns as $Column){
                $tabs = '';
                $sections = '';
                $columnSpan = round($Column*12/100);
                $tmpStr[] = $columnSpan;
                $Fields = array_keys($Layout, 'row'.$rowIndex.'_col'.$columnIndex);
                // insert input fields
                // check the config for visibility
                if(!empty($Fields)){
                    $fieldIndex = 1;
                    foreach($Fields as $Field){
                        $Return = '';

                        if(empty($Config['_FormFieldWidth'][$Field])){
                            $Config['_FormFieldWidth'][$Field] = 'input-medium';
                        }

                        $Field = str_replace('Field_', '', $Field);
                        $Req = false;
                        if(!empty($Config['_Required'][$Field])) {
                            $Req = 'validate[required]';
                        }
                        if(!empty($Config['_Unique'][$Field])) {
                            if(!empty($Req)) {
                                $Req = 'validate[required, ajax[ajaxUnique]]';
                            }else {
                                $Req = 'validate[optional, ajax[ajaxUnique]]';
                            }
                        }

                        $FieldSet = explode('_', $Config['_Field'][$Field]);
                        $name = $Config['_FieldTitle'][$Field];
                        if(file_exists(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$FieldSet[0].'/conf.php') && count($FieldSet) == 2) {

                            include(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$FieldSet[0].'/conf.php');
                            if(!empty($Defaults[$Field])){
                                $Val = esc_attr($Defaults[$Field]);
                            }else{
                                $Val = '';
                            }
                            if(!empty($FieldTypes[$FieldSet[1]]['visible']) && (empty($Config['_CloneField'][$Field]) || !empty($FieldTypes[$FieldSet[1]]['cloneview']))){
                                    $isValid = '';
                                    if(!empty($_SESSION['failedProcess'][$Element['ID']]['Fields'][$Field])){
                                        $isValid = 'fail';
                                    }

                                    ob_start();
                                    include(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$FieldSet[0].'/input.php');
                                    $inputField = '<div class="control-group '.$isValid.'">';
                                        $inputField .= '<label class="control-label">'.$name.'</label>';
                                        if(empty($Config['_placeHolderTitle'][$Field])){
                                            $placeholder = '';
                                        }else{
                                            $placeholder = $name;
                                        }

                                    $inputField .= '<div class="controls" id="form-field-'.$Field.'">';
                                    $inputField .= ob_get_clean();
                                    if(!empty($Config['_placeHolderTitle'][$Field])){
                                        if(strpos($inputField, 'type="text') !== false){
                                            $inputField = str_replace('<input', '<input placeholder="'.$name.'"', $inputField);
                                            $inputField = str_replace('<label class="control-label">'.$name.'</label>', '', $inputField);
                                        }
                                    }


                                    if(!empty($Config['_FieldCaption'][$Field])){
                                        $inputField .= '<p class="help-block">'.$Config['_FieldCaption'][$Field].'</p>';
                                    }
                                    $inputField .= '</div>';
                                    $inputField .= '</div>';

                                $FormLayout->append($inputField, $columnCounter);
                            }
                            if(!empty($FieldTypes[$FieldSet[1]]['hidden'])){
                                    ob_start();
                                    include(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$FieldSet[0].'/input.php');
                                    $Hidden .= ob_get_clean();
                            }
                        }else{
                            // Check for tabs and section breaks
                            // remember to close them before form renders.
                            if(substr($Field,0,4) == '_tab'){
                                //its a tab!
                                if(empty($tabStarted)){
                                    $tabs = '<div id="tabs_row_'.$rowIndex.'_col_'.$columnCounter.'"><ul>';
                                    $tabStarted = true;
                                }
                                $tabs .= '<li><a href="#'.$Field.'">'.$Config['_Tab'][$Field]['Title'].'</a></li>';

                                // start tab content
                                if(!empty($tabContentStarted)){
                                    $FormLayout->append('</div>', $columnCounter);
                                }
                                $FormLayout->append('<div id="'.$Field.'">', $columnCounter);
                                $tabContentStarted = true;
                            }elseif(substr($Field,0,5) == '_html'){
                                //$FormLayout->append(, $columnCounter);
                                foreach($Config['_Field'] as $fieldKey=>$type){
                                    if(!empty($Defaults)){
                                        $Config['_html'][$Field]['Title'] = str_replace('{{'.$fieldKey.'}}', $Defaults[$fieldKey], $Config['_html'][$Field]['Title']);
                                    }else{
                                        $Config['_html'][$Field]['Title'] = str_replace('{{'.$fieldKey.'}}', '', $Config['_html'][$Field]['Title']);
                                    }
                                }

                                $FormLayout->append($Config['_html'][$Field]['Title'], $columnCounter);
                            }

                        }

                    $fieldIndex++;
                    }
                }
                if(!empty($tabStarted)){
                    // end off tabs
                    $tabs .= '</ul>';
                    $tabStarted = false;
                    // send tab headers to top of column
                    $FormLayout->prepend($tabs, $columnCounter);

                    //end last tab content
                    $FormLayout->append('</div>', $columnCounter);
                    $tabContentStarted = false;
                    // send ending of tabs to end of column
                    $FormLayout->append('</div>', $columnCounter);

                    // Send Out Script to activate tabs
                    $_SESSION['dataform']['OutScripts'] .= "
                        jQuery(\"#tabs_row_".$rowIndex."_col_".$columnCounter."\" ).tabs();
                    ";
                }


                $columnCounter++;
                $columnIndex++;

            }

            $tmpLayout[] = implode(':', $tmpStr);

         $rowIndex++;
        }

        $FormLayout->setLayout(implode('|', $tmpLayout));


    // check for a failed insert
        if(!empty($_SESSION['failedProcess'][$Element['ID']])){
            unset($_SESSION['failedProcess'][$Element['ID']]);
        }
        if(!empty($psudoDefault)){
            unset($Defaults);
        }


        $buttonBar = '';
        if(!empty($Config['_FormMode']) || ($Config['_ViewMode'] == 'form' || empty($Config['_grid']))) {
            $ButtonText = 'Submit';
            if(!empty($Config['_SubmitButtonText'])) {
                $ButtonText = $Config['_SubmitButtonText'];
            }

            if(!empty($_GET[$Config['_ReturnFields'][0]])) {
                if(!empty($Config['_UpdateButtonText'])) {
                    $ButtonText = $Config['_UpdateButtonText'];
                }
            }
            $ButtonClass = '';
            if(!empty($Config['_SubmitButtonClass'])) {
                $ButtonClass = $Config['_SubmitButtonClass'];
            }

            if(!empty($_GET[$Config['_ReturnFields'][0]])) {
                if(!empty($Config['_UpdateButtonClass'])) {
                    $ButtonClass = $Config['_UpdateButtonClass'];
                }
            }
            $ButtonAlign = 'center';
            if(!empty($Config['_SubmitAlignment'])) {
                $ButtonAlign = $Config['_SubmitAlignment'];
            }


            $buttonBar .= '<div style="text-align: '.$ButtonAlign.';" class="form-actions">';
            $buttonBar .= '<button type="submit" name="captureEntry" id="submit_'.$Element['ID'].'" class="btn '.$ButtonClass.'">'.$ButtonText.'</button>';
            if(!empty($Config['_ShowReset'])) {
                $buttonBar .= ' <button type="reset" name="Reset" id="reset_'.$Element['ID'].'" class="btn '.$ButtonClass.'">Reset</button>';
            }
            $buttonBar .= '</div>';
        }
        if(!empty($Defaults)) {
            $Hidden .= '<input type="hidden" name="processKey" id="processKey" value="'.$_SESSION['processKey'].'" />';
            $Hidden .= '<input type="hidden" name="dr_update" value="1" />';
            $Hidden .= '<input type="hidden" name="dataForm[EID]" value="'.$Element['ID'].'" />';
            $Hidden .= '<input type="hidden" name="dataForm['.$Config['_ReturnFields'][0].']" value="'.$EditID.'" />';
        }

        //vardump($layoutString);
        //vardump($layoutData);
        //vardump($Config['_grid']);

        $Form = $FormLayout->renderLayout();


        $Output['width'] = $Config['_popupWidth'];
        $Output['html'] = $Hidden.$Form.$buttonBar.'</form></div>';
        $Output['script'] = $Script;
        return $Output;
    }

}

function df_parseCamelCase($Field) {
    $name = '';
    for($i=0; $i<strlen($Field); $i++) {
        $Char = substr($Field, $i, 1);
        if(is_uppercase($Char) && $i > 0 && strlen($Field) >1 && (!is_uppercase(substr($Field, $i+1, 1)) && (substr($Field, $i+1, 1) != '' && substr($Field, $i+1, 1) != ' ') && (substr($Field, $i-1, 1) != '-' && substr($Field, $i-1, 1) != ' '))) {
            $name .= ' ';
        }
        $name .= str_replace('_', ' ', str_replace('_ ', ' ', $Char));
    }
    return trim($name);
}

function df_processInsert($EID, $Data) {
    global $wpdb;
    $Setup = getelement($EID);
    $Config = $Setup['Content'];
    if(!empty($Config['_New_Item_Hide'])){
        $Return['Message'] = $Config['_InsertFail'];
        return $Return;
    }

    foreach($Config['_Field'] as $Field=>$Type){
        if(empty($Data[$Field]) && !empty($Config['_Required'][$Field])){
                $return['_error_'][] = $Config['_FieldTitle'][$Field].' is required.';
                $return['_fail_'][$Field] = true;
        }
        $typeSet = explode('_', $Type);
        if(!empty($typeSet[1])) {
            if(function_exists($typeSet[0].'_handleInput')) {
                $Setup['_ActiveProcess'] = 'insert';
                $Func = $typeSet[0].'_handleInput';
                $Value = false;
                if(isset($Data[$Field])){
                    $Value = $Data[$Field];
                }
                $Data[$Field] = $Func($Field, $Value, $typeSet[1], $Setup, $Data);
                if(is_array($Data[$Field])){
                    if(!empty($Data[$Field]['_fail_'])){
                        $return['_error_'][] = $Data[$Field]['_error_'];
                        $return['_fail_'][$Field] = true;
                    }
                }
            }
        }
    }
    if(!empty($return['_fail_'])){
       return $return;
    }

    if(!empty($Config['_FormProcessors'])){
        foreach($Config['_FormProcessors'] as $processID=>$Setup){
            if(empty($Data)){
                if(empty($Config['_InsertFail'])) {
                    $Return['Message'] = 'Entry Insert Failed';
                }else {
                    $Return['Message'] = $Config['_InsertFail'];
                }
                return $Return;
            }
            if(!empty($Setup['_onInsert'])){
                if(file_exists(WP_PLUGIN_DIR.'/db-toolkit/data_form/processors/'.$Setup['_process'].'/functions.php')){
                    include_once WP_PLUGIN_DIR.'/db-toolkit/data_form/processors/'.$Setup['_process'].'/functions.php';
                    $func = 'pre_process_'.$Setup['_process'];
                    if(function_exists($func)){
                        $Data = $func($Data, $Setup, $Config);
                        if(!empty($Data['__fail__'])){
                            if(!empty($Data['__error__'])) {
                                $Return['Message'] = $Data['__error__'];
                                return $Return;
                            }
                            if(empty($Config['_InsertFail'])) {
                                $Return['Message'] = 'Entry Insert Failed';
                            }else{
                                $Return['Message'] = $Config['_InsertFail'];
                            }
                            return $Return;
                        }
                    }
                }
            }
        }
    }

    foreach($Config['_Field'] as $Field=>$Type) {
        if(isset($Data[$Field])){
            if(substr($Field,0,2) != '__'){
                $Fields[] = '`'.$Field.'`';
                if(is_array($Data[$Field])) {
                    $EntryData = serialize($Data[$Field]);
                }else {
                    if(isset($Files[$Field])) {
                        $EntryData = $Files[$Field];
                    }else {
                        $EntryData = $Data[$Field];
                    }
                }
                $Entries[$Field] = "'".mysql_real_escape_string($EntryData)."'";
                //$Entries[$Field] = $EntryData;
            }
        }
    }

    if(empty($Entries)){
        $Return['Message'] = 'umm, there was nothing to insert.';
        return $Return;
    }
    $Query = "INSERT INTO `".$Config['_main_table']."` (". implode(',',$Fields).") VALUES (".implode(',', $Entries).");";

    if($wpdb->query($Query)){
        $inserted = true;
        $ID = $wpdb->insert_id;
    }else{
        $inserted = false;
    }


    if(!empty($inserted)){
        //vardump($Config['_ReturnFields']);

        if(!empty($Config['_ReturnFields'][0])) {
            $ReturnVals = implode(', ', $Config['_ReturnFields']);

            if(!empty($ID)){
                $Data[$Config['_ReturnFields'][0]] = $ID;
                $Query = "SELECT ".$ReturnVals." FROM `".$Config['_main_table']."` WHERE `".$Config['_ReturnFields'][0]."` = '".$ID."';";
            }else{
                $Wheres = array();
               // vardump($Entries);
                foreach($Entries as $Field=>$Value) {
                    $Wheres[] = "`".$Field."` = ".$Value;
                }
                $Query = "SELECT ".$ReturnVals." FROM `".$Config['_main_table']."` WHERE ".implode('&&', $Wheres)." LIMIT 1";
            }

            $dta = $wpdb->get_row($Query, ARRAY_A);
            $outstr = array();
            if(!empty($dta)){
                foreach($dta as $key=>$val) {
                    $outstr[] = $key.'='.$val;
                }
            }
            $Return['Value'] = implode('&', $outstr);
            $Data[$Config['_ReturnFields'][0]] = $dta[$Config['_ReturnFields'][0]];
        }else {
            $Return['Value'] = $ID;
        }

        // post insert
        foreach($Config['_Field'] as $Field=>$Type) {
            $typeSet = explode('_', $Type);
            if(!empty($typeSet[1])) {
                if(function_exists($typeSet[0].'_postProcess')) {
                    $Setup['_ActiveProcess'] = 'insert';
                    $Func = $typeSet[0].'_postProcess';
                    $Func($Field, $Data[$Field], $typeSet[1], $Setup, $Data, $ID);
                }
            }
        }

        // Auding
        /* Disabled Auditing
        if(!empty($Config['_EnableAudit'])) {
            $memberID = 0;
            if(!empty($_SESSION['UserBase']['Member']['ID'])) {
                $memberID = $_SESSION['UserBase']['Member']['ID'];
            }
            $lres = mysql_query("SHOW COLUMNS FROM ".$Config['_main_table']);
            $prerows = array();
            while ($row = mysql_fetch_assoc($lres)) {
                $prerows[] = $row['Field'];
            }
            $rows = implode(',', $prerows);
			if(mysql_query("CREATE TABLE `_audit_".$Config['_main_table']."` SELECT * FROM `".$Config['_main_table']."` WHERE `".$Config['_ReturnFields'][0]."` = '".$Data[$Config['_ReturnFields'][0]]."' LIMIT 1")){

				mysql_query("ALTER TABLE `_audit_".$Config['_main_table']."` ADD `_ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST ,
							ADD `_DateInserted` DATETIME NOT NULL AFTER `_ID` ,
							ADD `_DateModified` DATETIME NOT NULL AFTER `_ID` ,
							ADD `_User` INT NOT NULL AFTER `_DateModified`  ,
                                                        ADD `_RawData` TEXT NOT NULL AFTER `_DateInserted`");
                                mysql_query("INSERT INTO `_audit_".$Config['_main_table']."` SET `_DateInserted` = '".date('Y-m-d H:i:s')."', `_User` = '".$memberID."', `_RawData` = '".mysql_real_escape_string(serialize($Data))."', `".$Config['_ReturnFields'][0]."` = '".$ID."'  ;");
			}else{
				mysql_query("INSERT INTO `_audit_".$Config['_main_table']."` SET `_DateInserted` = '".date('Y-m-d H:i:s')."', `_User` = '".$memberID."', `_RawData` = '".mysql_real_escape_string(serialize($Data))."', `".$Config['_ReturnFields'][0]."` = '".$ID."'  ;");
			}
        }
         *
         */
        //post processors
        if(!empty($Config['_FormProcessors'])){
            foreach($Config['_FormProcessors'] as $processID=>$Setup){
                if(!empty($Setup['_onInsert'])){
                    if(file_exists(WP_PLUGIN_DIR.'/db-toolkit/data_form/processors/'.$Setup['_process'].'/functions.php')){
                        include_once WP_PLUGIN_DIR.'/db-toolkit/data_form/processors/'.$Setup['_process'].'/functions.php';
                        $func = 'post_process_'.$Setup['_process'];
                        if(function_exists($func)){
                            $Data = $func($Data, $Setup, $Config);
                            if(!is_array($Data)){
                                $Config['_InsertSuccess'] = $Data;
                            }
                        }
                    }
                }
            }
        }
        if(empty($Config['_InsertSuccess'])) {
            $Return['Message'] = 'Entry inserted successfully';
        }else {
            $Return['Message'] = $Config['_InsertSuccess'];
        }
        return $Return;
    }
    return;
}

function df_loadOutScripts() {

    $Return = '';
    if(!empty($_SESSION['dataform']['OutScripts'])) {
        $Return .= $_SESSION['dataform']['OutScripts'];
        $_SESSION['dataform']['OutScripts'] = '';
    }

    return $Return;
}

// Activity Tracking Functions

function df_checkActivity($Act, $create = 0) {
    global $wpdb;
    return;
    $Res = mysql_query("SELECT ID FROM `_adittrack_activities` WHERE `Activity` = '".$Act."' LIMIT 1");
    if(mysql_num_rows($Res) == 1) {
        $Activity = mysql_fetch_assoc($Res);
        return $Activity['ID'];
    }
    $InQu = "INSERT INTO `_adittrack_activities` (
						`ID` ,
						`Activity`
						)
						VALUES (
						NULL , '".$Act."'
						);";
    if(mysql_query($InQu)) {
        return mysql_insert_id();
    }
    if($create == 0) {
        mysql_query("CREATE TABLE IF NOT EXISTS `_adittrack_activities` (
		  `ID` int(11) NOT NULL auto_increment,
		  `Activity` varchar(255) NOT NULL,
		  PRIMARY KEY  (`ID`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
        return df_checkActivity($Act, 1);
    }
    return false;
}

function dr_trackActivity($Act, $EID, $ReturnValue, $Level = 0) {
    //return;
    $Table = '';
    if(!empty($EID)) {
        $Setup = getelement($EID);
        $Table = $Setup['Content']['_main_table'];
    }
    //$ReturnValue = $Config['Content']['_ReturnFields'][0];
    $Activity = df_checkActivity($Act);
    $UserID = 0;
    if(!empty($_SESSION['UserBase']['Member']['EmailAddress'])) {
        $UserID = $_SESSION['UserBase']['Member']['EmailAddress'];
    }
    if(mysql_query("INSERT INTO `_adittrack_entries` (
						`ID` ,
						`User` ,
						`Activity` ,
						`Table` ,
						`Entry` ,
						`Element`,
						`Date`
						)
						VALUES (
						NULL , '".$UserID."', '".$Activity."', '".$Table."', '".$ReturnValue."', '".$EID."', '".date('Y-m-d H:i:s')."'
						);")) {
        return true;
    }
    if($Level == 0) {
        mysql_query("CREATE TABLE IF NOT EXISTS `_adittrack_entries` (
					  `ID` int(11) NOT NULL auto_increment,
					  `User` varchar(255) NOT NULL,
					  `Activity` int(11) NOT NULL,
					  `Table` varchar(255) NOT NULL,
					  `Entry` varchar(255) NOT NULL,
					  `Element` int(11) NOT NULL,
					  `Date` datetime NOT NULL,
					  PRIMARY KEY  (`ID`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8");
        return dr_trackActivity($Act, $EID, $ReturnValue, 1);
    }
    return false;
}




// autocomplete Processor
if(!empty($_GET['q_eid'])) {
    $Element = getelement($_GET['q_eid']);
    $Config = $Element['Content'];
    $Table = $Config[decodestring($_GET['f_i'])];
    $Setup = $Config[$Table];

    $Res = mysql_query("SELECT ".$Setup['ID'].",".$Setup['Value']." FROM `".$Table."` WHERE `".$Setup['Value']."` LIKE '%".$_GET['q']."%' OR `".$Setup['ID']."` LIKE '%".$_GET['q']."%' ORDER BY `".$Setup['Value']."` ASC;");
    while($Out = mysql_fetch_assoc($Res)) {
        echo $Out[$Setup['ID']]." (".$Out[$Setup['Value']].")|".$Out[$Setup['ID']]."\n";
    }
    die;
}





//Validator Unique Processing
if(!empty($_GET['validatorUniques'])) {
    $Part = explode('_', str_replace('entry_', '', $_POST['validateId']),3);

    $Element = getelement('dt_'.$Part[1]);
    $Config = $Element['Content'];

    $Query = "SELECT count(".$Config['_ReturnFields'][0].") as total FROM ".$Config['_main_table']." WHERE `".$Part[2]."` = '".$_POST['validateValue']."' LIMIT 1;";

    $Res = mysql_query($Query);
    $count = mysql_fetch_assoc($Res);
    if($count['total'] >= 1) {
        echo '{"jsonValidateReturn":["'.$_POST['validateId'].'","ajaxUnique","false"]}';
    }else {
        echo '{"jsonValidateReturn":["'.$_POST['validateId'].'","ajaxUnique","true"]}';
    }

    mysql_close();
    die;
}


function dt_buildNewTable($name){

    global $wpdb;
    $Data = $wpdb->get_results( "SHOW TABLES", ARRAY_N);
    foreach($Data as $Table){
        if($name == $Table[0]){
            $return['error'] = 'Table Already Exists';
            return $return;
        }
    }

    // create table
    $tablename = str_replace('-', '_', sanitize_title($name));


    $query = "CREATE TABLE `".$wpdb->prefix.'dbt_'.$tablename."` (
  `".$tablename."ID` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`".$tablename."ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

    if($wpdb->query($query)){
        $output['html'] = df_listTables('_main_table', 'dr_fetchPrimSetup', $wpdb->prefix.'dbt_'.$tablename);
        $output['table'] = $wpdb->prefix.'dbt_'.$tablename;
        return $output;
    }else{
        $return['error'] = mysql_error();
    return $return;
    }

}

function dt_buildNewField($Field){


    $Field = str_replace('-', '_', sanitize_title($Field));
    return df_makeFieldConfigBox($Field, false);


}

?>