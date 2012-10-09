<?php
    echo dbt_configOption('visibilityPermission', 'visibilityPermission', 'permission', 'Interface Visibility', $Config);
    echo dbt_configOption('addingPermission', 'addingPermission', 'permission', 'Adding Entries', $Config);
    echo dbt_configOption('editPermission', 'editPermission', 'permission', 'Editing', $Config);
    echo dbt_configOption('deletePermission', 'deletePermission', 'permission', 'Deleting', $Config);
?>