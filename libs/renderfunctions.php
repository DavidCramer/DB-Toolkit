<?php
function dbt_buildFormView($Config, $viewType, $entry=false){

        
    include_once DBT_PATH.'libs/caldera-layout.php';
    $layout = new dbt_calderaLayout();
    
    //combine all rows first.
    if(empty($Config['_formLayout'])){
        $Config['_formLayout'] = array(1=>1);
        $autoLayout = true;
        $layout->setLayout(12);
    }else{
        $layoutStr = implode('|', $Config['_formLayout']);
        $layout->setLayout($layoutStr);
    }
    // breakup the fields into a structure we can use for the from layout
    
    //prepare our field Layout variable
    $fieldLayout = array();
    foreach($Config['_fieldLayout'] as $field=>$location){        
        // only work with a field that has a location
        // if no layout has been made, auto locate
        if(isset($autoLayout)){
            $location = '1_1';
        }
        
        if(!empty($location)){
            //break location into Row & Column
            $location = explode('_', $location);
            // $fieldLayout[row][column] = field
            $fieldLayout[$location[0]][$location[1]][] = $field;
        }
    }
    
    // go through the rows and append thier column structures
    $row = 0; //since its an ID, here we make an incremental index for row 0,2,3,4,etc...
    $columnNo = 1;
    foreach($Config['_formLayout'] as $rowID=>$columnStructure){
        
        // append the row to the layout
        //$layout->appendRow($columnStructure);
        // go through the fieldLayouts and push the field to its column
        //but only if its got fields
        if(!empty($fieldLayout[$rowID])){
            foreach($fieldLayout[$rowID] as $column=>$fields){
                foreach($fields as $Field){
                    $type = explode('_', $Config['_Field'][$Field]);
                    if(file_exists(DBT_PATH.'fieldtypes/'.$type[0].'/conf.php') && !empty($type[1])){
                        include(DBT_PATH.'fieldtypes/'.$type[0].'/conf.php');
                        if(isset($FieldTypes[$type[1]]['display'])){
                            // Validation Start
                            $isValid = '';
                            if(!empty($Validation['missing'][$Field])){
                                $isValid = 'fail';
                            }
                            // Setup Default value (for editing)
                            $Val = '';
                            if(!empty($entry[$Field])){
                                $Val = $entry[$Field];
                            }
                            // Override default if submited and returned for validation
                            if(!empty($_POST['dataForm'][$Config['_ID']][$Field])){
                                $Val = $_POST['dataForm'][$Config['_ID']][$Field];
                            }
                            //Override field Width
                            $Span = '';
                            if(!empty($Config['_FormFieldWidth'][$Field])){
                                $Span = $Config['_FormFieldWidth'][$Field];
                            }
                            // set field to disabled for read only fields
                            $disabled = '';
                            if(!empty($Config['_readOnly'][$Field])){
                                $disabled = 'disabled="disabled"';
                            }
                            $Req = '';
                            $formhtml = "<div id=\"".$Field."_control\" class=\"control-group ".$isValid." ".$Span."\">\n";
                                $formhtml .= dbt_makeFormField($Field, $FieldTypes[$type[1]]['display'], $Config['_FieldTitle'][$Field], $Config['_FieldCaption'][$Field], $Val, $viewType, false);
                            $formhtml .= "</div>\n";                            
                            
                            $layout->append($formhtml, $row, $column-1);
                        }
                    }
                 $columnNo++;
                }
            }
        }
        $row++;        
    }
    $layout->debug();
    echo $layout->renderLayout();
    
}
function dbt_makeFormField($field, $options, $title, $caption, $value, $type, $template=false){
    //process over the template if provided
    
    
    
    
    //auto field building
    $formhtml = '';
    
    $displayOptions = array(
        'title'=>true,
        'placeholder'=>false,
        'caption'=>true,
        'type'=>'text',
        'span'=> 12,
        'rows'=> 4,
        'addon-prepend'=>false,
        'addon-prepend-class'=>'add-on',
        'addon-prepend-element'=>'span',
        'addon-append'=>false,
        'addon-append-class'=>'add-on',
        'addon-append-element'=>'span',
        'preprocessor'=>false,
        'processor'=>false,
        'postprocessor'=>false,
        'displayhandler'=>false
    );
    
    // set the options against the defaults
    foreach($displayOptions as $option=>&$config){
        if(isset($options[$option])){
            $config = $options[$option];
        }
    }
        
    if($type == 'form'){
    // Place the title down
    if(!empty($title) && !empty($displayOptions['title']) && empty($displayOptions['placeholder'])){        
        $formhtml .= "<label class=\"control-label\" for=\"".$field."\">".$title."</label>";
    }elseif(!empty($title) && !empty($displayOptions['title']) && !empty($displayOptions['placeholder'])){
        $placeHolderTitle = $title;
    }        
    $formhtml .= "<div class=\"controls\">\n";
    // wrap input with addons if specified
    if(!empty($displayOptions['addon-prepend']) || !empty($displayOptions['addon-append'])){
        if($displayOptions['addon-prepend']){
            $addonClass[] = 'input-prepend';
            if($displayOptions['span'] == 12){
                $displayOptions['span']--;
            }
        }
        if($displayOptions['addon-append']){
            if($displayOptions['span'] == 12 || $displayOptions['span'] == 11){
                $displayOptions['span']--;
            }
            $addonClass[] = 'input-append';
        }
        $formhtml .= '<div class="'.implode(' ', $addonClass).'">';
        if($displayOptions['addon-prepend']){
            $formhtml .= '<'.$displayOptions['addon-prepend-element'].' class="'.$displayOptions['addon-prepend-class'].'">'.$displayOptions['addon-prepend'].'</'.$displayOptions['addon-prepend-element'].'>';
        }
    }
    switch ($displayOptions['type']){
        case 'text':
            $placeHolder = '';
            if(!empty($placeHolderTitle)){
                $placeHolder = 'placeholder="'.htmlentities($placeHolderTitle).'"';
            }
            $formhtml .= '<input class="span'.$displayOptions['span'].'" id="'.$field.'" name="'.$field.'" type="text" '.$placeHolder.' value="'.$value.'">';
            break;
        case 'textarea':
            $placeHolder = '';
            if(!empty($placeHolderTitle)){
                $placeHolder = 'placeholder="'.htmlentities($placeHolderTitle).'"';
            }
            $formhtml .= '<textarea class="span'.$displayOptions['span'].'" rows="'.$displayOptions['rows'].'" id="'.$field.'" name="'.$field.'" type="text" '.$placeHolder.'>'.  htmlentities($value).'</textarea>';
            break;
    }
    
    // close addons
    if(!empty($displayOptions['addon-prepend']) || !empty($displayOptions['addon-append'])){
        if($displayOptions['addon-append']){
            $formhtml .= '<'.$displayOptions['addon-append-element'].' class="'.$displayOptions['addon-append-class'].'">'.$displayOptions['addon-append'].'</'.$displayOptions['addon-append-element'].'>';
        }        
        $formhtml .= "</div>";
    }
    if(!empty($caption) && !empty($options['caption'])){
        $formhtml .= "<span class=\"help-block\">".$caption."</span>\n";
    }
    $formhtml .= "</div>\n";
    }elseif($type == 'view'){
        if(!empty($title) && !empty($displayOptions['title']) && empty($displayOptions['placeholder'])){        
            $formhtml .= "<h4>".$title."</h4>";
        }        
        $formhtml .= $value;
    }
    
    //dump($formhtml);
    //dump($options);
    return $formhtml;
}

function olddbt_buildFormView($Config, $viewType, $formOnly = false){
    if(!is_admin()){
        global $post;
    }
    if(!is_array($Config)){
        $Config = get_option($Config);
    }
    
    if(!empty($_POST['dataForm'][$Config['_ID']])){
        if(wp_verify_nonce($_POST['_wpnonce'],'dbt-interface-form')){
            $Validation = dbt_processInput(stripslashes_deep($_POST), 'insert');
        }
        if(wp_verify_nonce($_POST['_wpnonce'],'dbt-interface-form-update')){
            $Validation = dbt_processInput(stripslashes_deep($_POST), 'update');
        }
        if(!empty($Validation)){
            //vardump($return);
            if($Validation['status'] != 'validate' && $Validation['status'] != 'invalid'){
                $Config = get_option($_POST['master']);
                // Get redirect rules
                if(!empty($Config['_redirect'])){
                    if($Config['_redirect'] == '_URL'){
                        $url = $Config['_customRedirect'];
                    }else{
                        $redirect = get_option($Config['_redirect']);
                        $url = get_permalink($redirect['_basePost']);
                    }
                }else{
                    $url = get_permalink($Config['_basePost']);
                }
                $currentURL = parse_url($_POST['_wp_http_referer']);
                parse_str($currentURL['query'], $gets);
                unset($gets['mode']);
                if(!empty($Validation['passback'])){
                    foreach($Validation['passback'] as $key=>$passback){
                        if($key == $Config['_primaryField']){
                            $url .= $passback.'/';
                        }else{
                            $gets[$key] = $passback;
                        }
                    }
                }
                $extragets = '';
                if(!empty($gets)){
                    $extragets = '?'.http_build_query($gets);
                }
                //echo $url.$extragets;
                wp_redirect($url.$extragets);
                exit;
            }
        }
    }


    include_once DBT_PATH.'libs/caldera-layout.php';
    $isFluid = false;
    if(!empty($Config['_formWidth'])){
        if($Config['_formWidth'] != 960){
            $isFluid = 'fluid';
        }
    }
    if(empty($Config['_formLayout'])){
        //setup default layout
        $RowID = uniqid();
        $Config['_formLayout'] = array($RowID=>12);
        foreach($Config['_Field'] as $Field=>$Type){
            $Config['_fieldLayout'][$Field] = $RowID.'_1';
        }
        $Config['_formWidth'] = 420;
        $isFluid = 'fluid';
    }

    $form = new calderaLayout('fluid');
    $form->setLayout(implode('|',$Config['_formLayout']));

    $columnNo = 1;

    if(!empty($_GET[$Config['_primaryField']])){
        $format = 'data';
        if($viewType == 'view'){
            $format = 'data';
        }
        if($viewType == 'form'){
            $format = 'rawdata';
        }
        //if(!empty($_POST
                
            $Defaults = dbt_buildQuery($Config, $format, false, false, false, false, $_GET[$Config['_primaryField']]);
            $Defaults = $Defaults[0];
        if(!empty($_POST['dataForm'][$Config['_ID']]) && !empty($_POST['primary'])){
            $prevData = $Defaults;
            $Defaults[$Config['_primaryField']] = $_POST['primary'];
            $Defaults = array_merge($Defaults, $_POST['dataForm'][$Config['_ID']]);
            $Defaults['__primary__'] = $_GET[$Config['_primaryField']];
        }
    }
    if(empty($Config['_addItem']) && empty($Defaults)){
        echo '<h2>Error 404</h2>';
        echo '<div class="error">Page not found</div>';
        return;
    }

    foreach($Config['_formLayout'] as $row=>$layout){
        
        $columns = explode(':',$layout);        
        $curRowCol = 0;
        foreach($columns as $column=>$span){
            $Fields = array_keys($Config['_fieldLayout'], $row.'_'.($column+1));
            if(empty($Fields)){
                $form->append('&nbsp;', $columnNo);
            }
            foreach($Fields as $Field){
                $type = explode('_', $Config['_Field'][$Field]);
                if(file_exists(DBT_PATH.'fieldtypes/'.$type[0].'/conf.php') && !empty($type[1])){
                    include(DBT_PATH.'fieldtypes/'.$type[0].'/conf.php');
                    if($FieldTypes[$type[1]]['visible'] === true){
                        if(file_exists(DBT_PATH.'fieldtypes/'.$type[0].'/input.php')){
                            $isValid = '';
                            if(!empty($Validation['missing'][$Field])){
                                $isValid = 'fail';
                            }

                            $Val = '';
                            if(!empty($Defaults[$Field])){
                                $Val = $Defaults[$Field];
                            }
                            if(!empty($_POST['dataForm'][$Config['_ID']][$Field])){
                                $Val = $_POST['dataForm'][$Config['_ID']][$Field];
                            }
                            $Span = 'span'.$columns[$curRowCol];
                            if($isFluid){
                                $Span = 'span12';
                            }
                            if(!empty($Config['_FormFieldWidth'][$Field])){
                                $Span = $Config['_FormFieldWidth'][$Field];
                            }
                            $disabled = '';
                            if(!empty($Config['_readOnly'][$Field])){
                                $disabled = 'disabled="disabled"';
                            }
                            $Req = '';
                            ob_start();
                            if($viewType == 'form'){
                                include DBT_PATH.'fieldtypes/'.$type[0].'/input.php';
                            }
                            if($viewType == 'view'){
                                $Out = '';
                                include DBT_PATH.'fieldtypes/'.$type[0].'/output.php';
                                echo $Out;
                            }
                            $input = ob_get_clean();
                            
                            $formhtml = "<div id=\"field_".$Field."\" class=\"control-group ".$isValid."\">\n";
                            $lableType = 'label';
                            if($viewType == 'view'){
                                $lableType = 'strong';
                            }
                            $formhtml .= "  <".$lableType." class=\"control-label\" for=\"entry_dbt4fae1c2c03bde_name\">".$Config['_FieldTitle'][$Field]."</".$lableType.">\n";
                            $formhtml .= "  <div class=\"controls\">\n";
                            $formhtml .= "      ".$input."\n";
                            if(!empty($Config['_FieldTitle'][$Field]) && $viewType == 'form'){
                                $formhtml .= "      <p class=\"help-block\">".$Config['_FieldCaption'][$Field]."&nbsp;</p>\n";
                            }
                            $formhtml .= "  </div>\n";
                            $formhtml .= "  </div>\n";

                            $form->append($formhtml, $columnNo);
                        }
                    }
                }
                                
            }
            $columnNo++;
            $curRowCol++;
        }

    }

    if(!empty($prevData)){
        $Defaults = $prevData;
    }

    if(empty($formOnly)){
        //
        if(!empty($isFluid) || is_admin()){
            $formWidth = 'width:'.$Config['_formWidth'].'px;';
        }else{
            $formWidth = '';
        }
        
        $formTitle = $Config['_addItemText'];
        $breadCrumb = $Config['_addItemSubText'];
        $Divider = $Config['_addItemDivider'];
        if(!empty($Defaults)){
            if($viewType == 'form'){
                $formTitle = $Config['_editFormText'];
                $breadCrumb = $Config['_editFormSubText'];
                $Divider = $Config['_editFormDivider'];
            }
            if($viewType == 'view'){
                $formTitle = $Config['_viewEntryText'];
                $breadCrumb = $Config['_viewEntrySubText'];
                $Divider = $Config['_viewEntryDivider'];
            }
            foreach($Defaults as $Field=>$Value){                
                $formTitle = str_replace('{{'.$Field.'}}', $Value, $formTitle);
                $breadCrumb = str_replace('{{'.$Field.'}}', $Value, $breadCrumb);
            }
        }
        if(!is_admin()){
            $post->post_name = $formTitle;
            $post->post_title = $formTitle;
        }else{
            echo "<h2>".$formTitle."</h2>\n";
        }
        
        if(empty($Config['_disableBreadcrumbs']) || is_admin()){
            if(is_admin()){
                echo "<ul class=\"subsubsub\">\n";                
                echo "  <li class=\"all\"><a href=\"admin.php?page=app_builder&amp;action=render&amp;interface=".$Config['_ID']."\">".$Config['_ReportDescription']."</a> /</li>\n";
                echo "  <li class=\"draft\">".$breadCrumb."</li>\n";
                echo "  </ul><br class=\"clear\" /></div>\n";
            }else{
                $app = get_option('_'.$Config['_app'].'_app');
                
                echo "<ul class=\"breadcrumb\">\n";
                    echo "<li>\n";
                        echo "<a href=\"".get_site_url()."\">Home</a> <span class=\"divider\">".$Divider."</span>\n";
                    echo "</li>\n";
                    echo "<li>\n";
                        echo "<a href=\"".get_permalink($app['basePage'])."\">".$app['name']."</a> <span class=\"divider\">".$Divider."</span>\n";
                    echo "</li>\n";
                    echo "<li>\n";
                        echo "<a href=\"".get_permalink($Config['_basePost'])."\">".$Config['_ReportDescription']."</a> <span class=\"divider\">".$Divider."</span>\n";
                    echo "</li>\n";
                    echo "<li class=\"active\">".$breadCrumb."</li>\n";
                echo "</ul>\n";
            }
        }
    }

        if($viewType == 'form'){
            echo "<form method=\"POST\" enctype=\"multipart/form-data\" class=\"formular\" style=\"".$formWidth."\">\n";
            echo "<input type=\"hidden\" value=\"".$Config['_ID']."\" name=\"master\">";
            if(empty($Defaults)){
                echo wp_nonce_field('dbt-interface-form');
            }else{
                echo wp_nonce_field('dbt-interface-form-update');
                echo "<input type=\"hidden\" value=\"".$Defaults[$Config['_primaryField']]."\" name=\"primary\">";
            }

            $form->appendRow('12');
            $buttonBar = "<div class=\"form-actions\">\n";
                $buttonText = $Config['_submitText'];
                $buttonClass = $Config['_submitClass'];
                if(!empty($Defaults)){
                    $buttonText = $Config['_updateText'];
                    $buttonClass = $Config['_updateClass'];
                }                
            $buttonBar .= "     <button class=\"btn ".$buttonClass."\" type=\"submit\">".$buttonText."</button> <button class=\"btn ".$Config['_cancelClass']."\" type=\"button\">Cancel</button>\n";
            $buttonBar .= "</div>\n";
            $form->append($buttonBar, $columnNo++);
        }
        if($viewType == 'view'){
            echo "<div class=\"formular\" style=\"".$formWidth."\">\n";
        }
        
    
    
    echo $form->renderLayout();

    if(empty ($formOnly)){
        if($viewType == 'form'){
            echo "</form>\n";
        }
        if($viewType == 'view'){
            echo "</div>\n";
        }
    }

}
function dbt_renderField($Field, $Config, $Default = false){

    return 'ping:'.$Field;


}
function dbt_buildQuery($Config, $Format = 'data', $SortField = false, $SortDir = false, $getOverride = false, $page=false, $primary = false) {

    global $wpdb;

    if(!empty($getOverride)){
        parse_str($getOverride, $_GET);
    }

    $queryJoin = array();
    $queryWhere = array();
    $queryJoinStruct = array();
    $queryWhereStruct = array();
    $queryLimit = '';
    $querySelects = array();
    $WhereTag = '';
    $groupBy = '';

    if(!is_array($Config)){
        $Config = get_option($Config);
    }

    /// Explode types for later use
    if(!empty($Config['_IndexType'])){
        foreach ($Config['_Field'] as $Field => $Type) {
            if(!is_array($Type)){
                $Config['_Field'][$Field] = explode('_', $Type);
            }
        }
    }
    
    /// Load queryfilters
    $joinIndex = 'a';
    if(!empty($Config['_IndexType'])){
        foreach ($Config['_Field'] as $Field => $Type) {

            // Create a Join ID
            $joinIndex++;

            // SELECT: if clone find masters
            if(!empty($Config['_CloneField'][$Field])){
                $prime = $querySelects[$Config['_CloneField'][$Field]['Master']];
            }else{
                $prime = '`prim`.`'.$Field.'`';
            }
            //$prim = $Field;
            //SELECT: Add Field to SELECT if visible
            $querySelects[$Field] = $prime;

            // SELECT: Add return values select
            if($Config['_primaryField'] == $Field){
                $querySelects['_primary_'.$Field] = '`prim`.`'.$Field.'`';
            }
            //$querySelects[$Field]
            // Run Filters that have been set through each field type
            if (file_exists(DBT_PATH.'fieldtypes/' . $Type[0] . '/queryfilter.php')){
                include(DBT_PATH.'fieldtypes/' . $Type[0] . '/queryfilter.php');
            }
        }
    }
    //Apply Ordering and sorting.
    $OrderArray = array();
    $orderStr = '';
    if(!empty($Config['_sorting']['Field'])){
        foreach($Config['_sorting']['Field'] as $Key=>$Field){

            if(!empty($querySelects[$Field])){
                $SField = $querySelects[$Field];
            }else{
                $SField = '`prim`.`'.$Field.'`';
            }
            $OrderArray[] = $SField.' '.$Config['_sorting']['Direction'][$Key];

        }
        $orderStr = 'ORDER BY '.  implode(',', $OrderArray);
    }
    //vardump($_SESSION['report_' . $Config['_ID']]);
    // apply AS types to selects
    // Remove hidden fields from query to save memory
    foreach($querySelects as $Field=>$Value){
        if(!empty($Config['_IndexType'][$Field]['Visibility']) || !empty($Config['_IndexType'][$Field]['PassbackValue'])){
            $querySelects[$Field] = $Value.' AS `'.$Field.'`';
        }else{
            unset($querySelects[$Field]);
        }
    }

    if(!empty($queryWhere)){
        $WhereTag = " WHERE ";
        foreach($queryWhere as $whereType => $whereSet){
            if(is_array($whereSet)){
                $queryWhereStruct[] = '('.implode(' '.$whereType.' ', $whereSet).')';
            }else{
                $queryWhereStruct[] = $whereSet;
            }
        }
    }



    foreach($queryJoin as $Field=>$joinField){
        foreach($joinField as $joinTo){
            foreach($joinTo as $joinSet){
                $queryJoinStruct[] = implode('', $joinSet);
            }
        }
    }

    //add primary to select
    $querySelects['__'.$Config['_primaryField']] = '`prim`.`'.$Config['_primaryField'].'` AS `__primary__`';

    if($Format == 'count'){
        
        $querySelects = array();
        $querySelects[] = 'COUNT(`prim`.`'.$Config['_primaryField'].'`)';
    }
    $start=0;
    if(!empty($page)){
        $Count = dbt_buildQuery($Config, 'count');
        if(empty($Config['_Items_Per_Page'])){
            $pages = 1;
        }else{
            $pages = ceil($Count/$Config['_Items_Per_Page']);
        }
        if($page > $pages){
            $page = $pages;
        }
        $start = $Config['_Items_Per_Page']*($page-1);
    }
    if(!empty($pages)){
        if($pages > 1){
            $queryLimit = 'LIMIT '.$start.','.$Config['_Items_Per_Page'];
        }
    }
    if(!empty($primary)){
        
        foreach($Config['_Field'] as $Field=>$types){
            $querySelects[$Field] = '`prim`.`'.$Field.'` AS `'.$Field.'`';
        }
        $queryLimit = "LIMIT 1";
        $WhereTag = " WHERE ";
        $queryWhereStruct[] = '`prim`.`'.$Config['_primaryField'].'` = \''.mysql_real_escape_string($primary).'\'';
    }
    // Allow to remove if a field said so
    if(!empty($haltRender)){
        return 'false';
    }

    // Build Query
    $Query = "SELECT ";
        // add selects
        $Query .= implode(", ", $querySelects)." ";
        // add table
        $Query .= "FROM " . $Config['_main_table'] . " AS prim ";
        // add joins
        $Query .= implode(" ", $queryJoinStruct) . " ";
        // add where tag if needed wheres
        $Query .= $WhereTag." ";
        // add wheres
        $Query .= implode(" AND ", $queryWhereStruct) . " ";
        // add group by
        if(!empty($groupBy)){
            $Query .= ' GROUP BY ';
            if(count($groupBy) > 1){
                $Query .= implode(", ", $groupBy) . " ";
            }else{
                $Query .= implode(" ", $groupBy) . " ";
            }
        }
        // add orderby
        $Query .= $orderStr . "  ";
        // add query limits
        $Query .= $queryLimit . "";
        
//return query structure to system.
        if(!empty($_GET['debug'])){

            echo "<textarea style=\"width:100%; height; auto;\">".$Query."</textarea>";

        }

        if(strtolower($Format) == 'sql'){
            return $Query;
        }
        if(strtolower($Format) == 'count'){
            return $wpdb->get_var($Query);
        }
        if(strtolower($Format) == 'rawdata'){
            $Data = $wpdb->get_results($Query, ARRAY_A);
            return $Data;
        }
        if(strtolower($Format) == 'data'){
            $Data = $wpdb->get_results($Query, ARRAY_A);
            // apply field templates
            foreach($Data as $Key=>$Set){
                foreach($Set as $Field=>$Value){
                    if(!empty($Config['_FieldTemplate'][$Field]['before']) || !empty($Config['_FieldTemplate'][$Field]['before'])){
                        $Before = $Config['_FieldTemplate'][$Field]['before'];
                        $After = $Config['_FieldTemplate'][$Field]['after'];
                        foreach($Set as $innerField=>$InnerValue){
                            $Before = str_replace('{{'.$innerField.'}}', $InnerValue, $Before);
                            $After = str_replace('{{'.$innerField.'}}', $InnerValue, $After);
                        }
                        ob_start();
                        eval(' ?> '.$Before.$Data[$Key][$Field].$After.' <?php ');
                        $Data[$Key][$Field] = ob_get_clean();
                    }
                }
            }
            return $Data;
        }

}


function dbt_toolbarButton($Title, $Script = false, $Class = 'noicon', $Type = 'a', $Link = false, $Target = '_blank'){

    $onClick = '';
    if(!empty($Script)){
        $onClick = 'onClick="'.$Script.'"';
    }
    $linkStart = '';
    if(!empty($Link)){
        $linkStart = 'href="'.$Link.'" target="'.$Target.'"';
    }
    if(is_admin ()){
        return '<span class="fbutton"><'.$Type.' '.$linkStart.' class="button" '.$onClick.' ><span class="'.$Class.'"></span> '.$Title.'</'.$Type.'></span>';
    }else{
        return '<'.$Type.' '.$linkStart.' class="btn" '.$onClick.' ><span class="'.$Class.'"></span> '.$Title.'</'.$Type.'>';
    }
}
?>