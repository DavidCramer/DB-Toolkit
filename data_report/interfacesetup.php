<h2>Interface Setup</h2>
<?php
if(empty($Element['_ReportDescription']))
    $Element['_ReportDescription'] = '';
echo dais_customfield('text', 'Interface Title', '_ReportDescription', '_ReportDescription', 'list_row2', $Element['_ReportDescription'], '','Sets the title of the interface which is whoen when using the interface.');
if(empty($Element['_ReportExtendedDescription']))
    $Element['_ReportExtendedDescription'] = '';

echo dais_customfield('text', 'Description', '_ReportExtendedDescription', '_ReportExtendedDescription', 'list_row1', $Element['_ReportExtendedDescription'], '','Give the interface a description to quickly identify it\'s function.');




if (!empty($Element['_Application'])) {
    $Application = $Element['_Application'];
} else {
    $Application = 'Base';
    if (!empty($_SESSION['activeApp'])) {
        $Application = $_SESSION['activeApp'];
    }
}

?>

<h2>Navigation</h2>
<?php
                    if(empty($Element['_InterfaceCategory']))
                        $Element['_InterfaceCategory'] = 'Uncategorized';

                    //echo dais_customfield('text', 'Interface Category', '_InterfaceCategory', '_InterfaceCategory', 'list_row2', $Element['_InterfaceCategory'], '', 'Categorizes this interface.');
                    
                    if(empty($Element['_ItemGroup']))
                        $Element['_ItemGroup'] = '';

                    echo dais_customfield('text', 'Menu Group', '_ItemGroup', '_ItemGroup', 'list_row2', $Element['_ItemGroup'], '', 'Sets the menu group on the left navigation bar within Wordpress admin.');
                    if(empty($Element['_interfaceName']))
                        $Element['_interfaceName'] = '';

                    echo dais_customfield('text', 'Menu Label', '_ReportTitle', '_ReportTitle', 'list_row1', $Element['_interfaceName'], '', 'Sets the title of the menu link.');
                    $Sel = '';
                    if (!empty($Element['Content']['_SetDashboard'])) {
                        $Sel = 'checked="checked"';
                    }
                    echo dais_customfield('checkbox', 'On Dashboard', '_SetDashboard', '_SetDashboard', 'list_row1', 1, $Sel, 'Place the interface as a Dashboard widget.');
                    $Sel = '';
                    if (!empty($Element['Content']['_SetAdminMenu'])) {
                        $Sel = 'checked="checked"';
                    }
                    echo dais_customfield('checkbox', 'Admin Menu', '_SetAdminMenu', '_SetAdminMenu', 'list_row1', 1, $Sel, 'Sets the interface to the admin bar. (Requires a menu group)');

                    if (empty($Element['Content']['_menuAccess'])) {
                        $Element['Content']['_menuAccess'] = 'read';
                    }

?>
<h2>Shortcode</h2>
<?php

    if(empty($Element['_shortCode']))
        $Element['_shortCode'] = '';

    echo dais_customfield('text', 'Shortcode', '_shortCode', '_shortCode', 'list_row1', $Element['_shortCode'], '', 'give this interface its own shortcode. Be careful not to overwrite existing shortcodes, so make it unique.');

?>