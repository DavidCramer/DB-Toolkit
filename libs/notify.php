<div><span class="description">Setup Notifications and Texts</span></div>
<?php
    echo dbt_configOption('insertEntryText', 'insertEntryText', 'textfield', 'Insert Success Text', $Config);
    echo dbt_configOption('updateEntryText', 'updateEntryText', 'textfield', 'Update Success Text', $Config);
    echo dbt_configOption('insertFailText', 'insertFailText', 'textfield', 'Insert Fail Text', $Config);
    echo dbt_configOption('updateFailText', 'updateFailText', 'textfield', 'Update Fail Text', $Config);
    echo dbt_configOption('submitText', 'submitText', 'textfield', 'Submit Button Text', $Config);
    echo dbt_configOption('updateText', 'updateText', 'textfield', 'Update Button Text', $Config);
    echo dbt_configOption('cancelText', 'cancelText', 'textfield', 'Cancel Button Text', $Config);
    echo dbt_configOption('addItemName', 'addItemName', 'textfield', 'Add Button Text', $Config);
    echo dbt_configOption('addItemText', 'addItemText', 'textfield', 'Add Form Title', $Config);
    echo dbt_configOption('addItemSubText', 'addItemSubText', 'textfield', 'Add Form Breadcrumb', $Config);
    echo dbt_configOption('addItemDivider', 'addItemDivider', 'textfield', 'Add Form Breadcrumb Divider', $Config);
    echo dbt_configOption('editFormText', 'editFormText', 'textfield', 'Edit Form Title', $Config);
    echo dbt_configOption('editFormSubText', 'editFormSubText', 'textfield', 'Edit Form Breadcrumb', $Config);
    echo dbt_configOption('editFormDivider', 'editFormDivider', 'textfield', 'Edit Form Breadcrumb Divider', $Config);
    echo dbt_configOption('viewEntryText', 'viewEntryText', 'textfield', 'View Entry Title', $Config);
    echo dbt_configOption('viewEntrySubText', 'viewEntrySubText', 'textfield', 'View Entry Breadcrumb', $Config);
    echo dbt_configOption('viewEntryDivider', 'viewEntryDivider', 'textfield', 'View Entry Breadcrumb Divider', $Config);
    echo dbt_configOption('noResultsText', 'noResultsText', 'textfield', 'No Results Text', $Config);
    echo dbt_configOption('disableBreadcrumbs', 'disableBreadcrumbs', 'checkbox', 'Disable Frontend Breadcrumbs', $Config);
    echo dbt_configOption('disableNotifications', 'disableNotifications', 'checkbox', 'Disable Notifications', $Config);
    echo dbt_configOption('inlineNotifications', 'inlineNotifications', 'checkbox', 'Inline Notifications', $Config);
    
?>