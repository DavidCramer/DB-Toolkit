<?php

// utility functions for datatoolkit

/*
 * dt_buildConfigPanel
 *
 * $Title - string. Setting for the title of the interface
 * $Pages - content pages for the interface
 *        - array('tab title'=>'file include');
 * $Defaults - array of the default values from the wp option
 *
 */

    $ajaxAllowedFunctions = array(
        "df_inlineedit" => "1",
        "df_processAjaxForm" => "1",
        "dr_loadInsertInterfaceBox" => "1",
        "core_createInterfaces" => "1",
        "core_createTables" => "1",
        "core_populateApp" => "1",
        "core_loadSupportFeed" => "1",
        "dt_removeInterface" => "1",
        "dt_listApps" => "1",
        "dt_listInterfaces" => "1",
        "linked_makeFilterdLinkedFilter" => "1",
        "linked_autocomplete" => "1",
        "linked_makeFilterdLinkedField" => "1",
        "app_dockApp" => "1",
        "app_SaveDesc" => "1",
        "dt_saveInterface" => "1",
        "dbt_sendError" => "1",
        "dr_addListRowTemplate" => "1",
        "dr_addListFieldTemplate" => "1",
        "dr_exportChartImage" => "1",
        "df_addProcess" => "1",
        "dais_addJSLibrary" => "1",
        "dais_addCSSLibrary" => "1",
        "dt_saveFilterLock" => "1",
        "dt_iconSelector" => "1",
        "df_tableReportSetup" => "1",
        "df_ListFields" => "1",
        "dr_loadPassbackFields" => "1",
        "dr_BuildReportGrid" => "1",
        "df_searchReferenceForm" => "1",
        "df_loadReturnFields" => "1",
        "df_loadSortFields" => "1",
        "df_listTables" => "1",
        "df_vlauedFilterSetup" => "1",
        "di_showItem" => "1",
        "dr_callInterface" => "1",
        "dr_BuildUpDateForm" => "1",
        "dr_loadPageElements" => "1",
        "dr_addTotalsField" => "1",
        "dr_db2csv" => "1",
        "df_deleteEntries" => "1",
        "dr_dataSourceMapping" => "1",
        "dr_loadFieldMapping" => "1",
        "dt_buildNewTable" => "1",
        "dt_buildNewField" => "1",
        "app_createApplication" => "1",
        "app_marketLogin" => "1",
        "app_setLanding" => "1",
        "df_buildFieldTypesMenu" => "1",
        "df_tableFormSetup" => "1",
        "df_alignmentSetup" => "1",
        "df_controlFunc" => "1",
        "df_enumOptions" => "1",
        "df_sessionValueSetup" => "1",
        "df_loadlinkedfields" => "1",
        "df_loadlinkedfilteredfields" => "1",
        "df_buildQuickCaptureForm" => "1",
        "dr_importer" => "1",
        "dr_buildImportManager" => "1",
        "dr_cancelImport" => "1",
        "dr_prepairImport" => "1",
        "dr_processImport" => "1",
        "df_loadOutScripts" => "1",
        "df_anewFunctionToBe" => "1",
        "text_runCode" => "1",
        "onoff_setValue" => "1",
        "linked_loadfields" => "1",
        "linked_loadfilterfields" => "1",
        "linked_loadAdditionalValue" => "1",
        "app_fetchApps" => "1",
        "dr_renderField" => "1",
        "dbte_installFieldType" => "1",
        "df_addViewProcess" => "1",
        "dbte_installProcessor" => "1",
        "dbte_installViewProcessor" => "1",
        "dr_rebuildApps" => "1",
        "pinger" => "1",
        "df_reloadFormField" => "1",
        "dr_setFilters" => 1
    );

function dt_buildConfigPanel($Title, $Pages, $Defaults){
    $Element = $Defaults;
?>
<div id="dbt_container" class="wrap poststuff">
        <input type="hidden" name="Data[Content][_FormLayout]" cols="50" rows="10" id="_FormLayout" />
        <div id="header">
            <div class="logo">
                <h2><?php echo $Title; ?></h2>
            </div>
            <div class="icon-option"></div>
            <div class="clear"></div>
        </div>
        <div id="main">
            <div id="dbt-nav">
                <ul>
                <?php
                // Dynamic Listing


                $tabIndex = 1;
                foreach($Pages as $Title=>$File){
                    $Class = '';
                    if(!empty($_GET['ctb'])){
                    if($_GET['ctb'] == $tabIndex){
                        $Class = 'current';
                    }
                    }else{
                        if($tabIndex == 1){
                            $Class= 'current';
                        }
                    }
                    echo '<li class="'.$Class.'">';
                    echo '<a href="#dbt-option-'.$tabIndex++.'" title="'.$Title.'">'.$Title.'</a>';
                    echo '</li>';

                }

                ?>
                </ul>

            </div>

            <div id="content">

                <?php
                // Option Tab

                $tabIndex = 1;
                foreach($Pages as $Title=>$File){
                    $view = 'none';
                    if(!empty($_GET['ctb'])){
                        if($_GET['ctb'] == $tabIndex){
                            $view = 'block';
                        }
                    }else{
                        if($tabIndex == 1){
                            $view = 'block';
                        }
                    }

                    echo '<div id="dbt-option-'.$tabIndex.'" class="group" style="display: '.$view.';">';


                        include($File);
                    

                    echo '</div>';

                    $tabIndex++;
                }




                /*
                <div id="dbt-option-generalsettings" class="group" style="display: block;">
                    <h2>General Settings</h2>
                    <div class="section section-upload ">
                        <h3 class="heading">Website Logo</h3>
                        <div class="option">
                            <div class="controls">

                                <div class="clear"></div>
                            </div>
                            <div class="explain">Upload a custom logo for your Website.</div>
                            <div class="clear"></div>
                        </div>
                    </div>

                </div>
                */
            ?>


            </div>
            <div class="clear"></div>

        </div>
        <div class="save_bar_top">

                <span class="submit-footer-reset">
                    <input type="button" onclick="return window.location='admin.php?page=dbt_builder';" class="button submit-button reset-button" value="Close" name="close">
                    <?php echo dais_standardSetupbuttons($Element); ?>
                </span>
        </div>
    <div style="clear:both;"></div>
</div>

<?php

}

function map($x, $inMin, $inMax, $outMin, $OutMax){
  return ($x - $inMin) * ($OutMax - $outMin) / ($inMax - $inMin) + $outMin;
}

function GetDocument($page) {
    return get_permalink($page);
}

function getelement($optionTitle) {

    $Media = get_option($optionTitle);
    $Media['Content'] = unserialize(base64_decode($Media['Content']));
    return $Media;
}


function InfoBox($Title) {
    if (is_admin()) {
        //echo '<div class="metabox-holder">
        //echo '<div id="' . md5($Title) . '" class="stuffbox" >';
        echo '<h2>' . $Title . '</h2>';
        echo '<div class="option">';
        return;
    }
    echo '<h4>' . $Title . '</h4>';
}

function EndInfoBox() {
    if (is_admin()) {
       echo '</div>';
        //echo '</div></div>';
    }
    echo '<div class="clear;"></div>';
}

function loadFolderContents($Folder) {
    $Index = 0;
    $List = array();
    if (is_dir($Folder)) {
        if ($dh = opendir($Folder)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != '.' && $file != '..') {
                    $type = 0;
                    if (filetype($Folder . '/' . $file) == 'file') {
                        $type = 1;
                    }
                    $List[$type][$Index][0] = urlencode(base64_encode($Folder . '/' . $file));
                    $List[$type][$Index][1] = $file;
                }
                $Index++;
            }
            closedir($dh);
        }
    }
    ksort($List);
    return $List;
}

function vardump($a) {
    echo '<pre>';
    print_r($a);
    echo '</pre>';    
}

function layout_listOption($ID, $Icon, $Title, $Link, $Class, $Script = false) {
    $Image = '';
    $PrefixLink = '';
    $PostfixLink = '';
    $Scriptline = '';
    if (!empty($Icon)) {
        $Image = '<img src="' . $Icon . '" border="0" align="absmiddle" />';
    }
    if (!empty($Script)) {
        $Scriptline = ' onClick="' . $Script . '"';
    }
    if (!empty($Link)) {
        $PrefixLink = '<a href="' . $Link . '" ' . $Scriptline . '>';
        $PostfixLink = '</a>';
    }
    return '<div class="' . $Class . '" id="' . $ID . '">' . $PrefixLink . $Image . ' ' . $Title . $PostfixLink . '</div>';
}

function dais_rowSwitch($a) {
    return '';
}



function UseImage($ImageFile, $Option = '1', $Size = '0', $Quality = 10, $Class = 'table1') {

        $ImageFile = strtok($ImageFile, '?');
        $File = str_replace(WP_CONTENT_URL, WP_CONTENT_DIR, $ImageFile);

        if(!file_exists($File))
        return $ImageFile;

    $fileLoc = str_replace(get_bloginfo('url'), '', $ImageFile);

    $Quality = (!empty($Quality) ? $Quality : $Quality);
    $TDir = $ImageFile;
    $Dir = $File;
    if ($Size == '0') {
        $Size = 100;
    }
    $FullDimen = GetImageDimentions($Dir, 'f');
    $ImageHeight = GetImageDimentions($Dir, 'h');
    $ImageWidth = GetImageDimentions($Dir, 'w');

    // if ($ImageWidth > 450){ $Dir = 450; }
    $Vc = (($ImageWidth));
    $Hc = (($ImageHeight) / 2);
    //$FullSize = GetFileSize($Dir);
    if ($Option === 0) {
        return "<img src=\"".WP_PLUGIN_URL."/db-toolkit/libs/timthumb.php?src=" . $TDir . "&w=" . $Size . "&h=" . $Size . "&q=" . $Quality . "&zc=1\" class=\"" . $Class . "\" border=\"0\">";
    }
    if ($Option == 1) {
        return "<img src=\"".WP_PLUGIN_URL."/db-toolkit/libs/timthumb.php?src=" . $TDir . "&w=" . $Size . "&sy=" . (($Hc) / 2) . "&sw=" . $Hc . "&sh=" . $Hc . "&q=" . $Quality . "\" width=\"" . $Size . "\" height =\"" . $Size . "\" class=\"" . $Class . "\" border=\"0\">";
    }
    if ($Option == 2) {
        return "<img src=\"".WP_PLUGIN_URL."/db-toolkit/libs/timthumb.php?src=" . $TDir . "&w=" . $ImageWidth . "&q=" . $Quality . "\" class=\"" . $Class . "\" border=\"0\">";
    }
    if ($Option == 3) {
        return "<img src=\"".WP_PLUGIN_URL."/db-toolkit/libs/timthumb.php?src=" . $TDir . "&h=" . $Size . "&q=" . $Quality . "\" class=\"" . $Class . "\" border=\"0\">";
    }
    if ($Option == 4) {
        return WP_PLUGIN_URL."/db-toolkit/libs/timthumb.php?src=" . $TDir . "&w=" . $Size . "&h=" . $Size . "&q=" . $Quality . "&zc=1";
    }
    if ($Option == 7) {
        return WP_PLUGIN_URL."/db-toolkit/libs/timthumb.php?src=" . $TDir . "&w=" . $Size . "&q=" . $Quality . "";
    }
    if ($Option == 5) {
        if ($ImageHeight > $ImageWidth) {
            if ($ImageHeight < $Size) {
                $Size = $ImageHeight;
            }
            $new_width = $ImageWidth * ($Size / $ImageHeight);
            //return "<img src=\"libs/useimage.class.php?src=/".$TDir."&h=".$Size."&q=".$Quality."\" height=\"".$Size."\" width=\"".round($new_width)."\" border=\"0\">";
            return "<img src=\"".WP_PLUGIN_URL."/db-toolkit/libs/timthumb.php?src=" . $TDir . "&h=" . $Size . "&q=" . $Quality . "\" height=\"" . $Size . "\" width=\"" . round($new_width) . "\" border=\"0\">";
        } else {
            if ($ImageWidth < $Size) {
                $Size = $ImageWidth;
            }
            $new_height = $ImageHeight * ($Size / $ImageWidth);
            //return "<img src=\"libs/useimage.class.php?src=/".$TDir."&w=".$Size."&q=".$Quality."\" width=\"".$Size."\" height=\"".round($new_height)."\" border=\"0\">";
            return "<img src=\"".WP_PLUGIN_URL."/db-toolkit/libs/timthumb.php?src=" . $TDir . "&w=" . $Size . "&q=" . $Quality . "\" width=\"" . $Size . "\" height=\"" . round($new_height) . "\" border=\"0\">";
        }
    }
    if ($Option == 6) {
        if ($ImageHeight > $ImageWidth) {
            if ($ImageHeight < $Size) {
                $Size = $ImageHeight;
            }
            $new_width = $ImageWidth * ($Size / $ImageHeight);
            //return "<img src=\"libs/useimage.class.php?src=/".$TDir."&h=".$Size."&q=".$Quality."\" height=\"".$Size."\" width=\"".round($new_width)."\" border=\"0\">";
            return "<img src=\"".WP_PLUGIN_URL."/db-toolkit/libs/timthumb.php?src=" . $TDir . "&h=" . $Size . "&q=" . $Quality . "\" height=\"" . $Size . "\" width=\"" . round($new_width) . "\" border=\"0\">";
        } else {
            if ($ImageWidth < $Size) {
                $Size = $ImageWidth;
            }
            
            $new_height = $ImageHeight * ($Size / $ImageWidth);
            //return "<img src=\"libs/useimage.class.php?src=/".$TDir."&w=".$Size."&q=".$Quality."\" width=\"".$Size."\" height=\"".round($new_height)."\" border=\"0\">";
            return "<img src=\"".WP_PLUGIN_URL."/db-toolkit/libs/timthumb.php?src=" . $TDir . "&w=" . $Size . "&q=" . $Quality . "\" width=\"" . $Size . "\" height=\"" . round($new_height) . "\" border=\"0\">";
        }
    }
}

function GetImageDimentions($File, $Option = 'f') {
    if ($Option == 'f') {
        @$Size = getimagesize($File);
        $Dimentions = "" . $Size['0'] . "x" . $Size['1'] . "";
        return $Dimentions;
    }
    if ($Option == 'w') {
        @$Size = getimagesize($File);
        $Dimentions = "" . $Size['0'] . "";
        return $Dimentions;
    }
    if ($Option == 'h') {
        @$Size = getimagesize($File);
        $Dimentions = "" . $Size['1'] . "";
        return $Dimentions;
    }
}


?>