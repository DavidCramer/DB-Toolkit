<?php
    $app = get_option('_dbt_activeApp');
    $appConfig = get_option('_'.$app.'_app');
?>

<h2 id="appTitle"><?php echo $appConfig['name']; ?></h2>
<?php 
        if($_GET['page'] == 'New_Cluster'){
            ?>
        <div class="wrap">
            <div><img src="<?php echo WP_PLUGIN_URL . '/db-toolkit/images/dbtoolkit-logo.png'; ?>" name="DB-Toolkit" title="DB-Toolkit" align="absmiddle" />Create new Cluster
            <div class="clear"></div>
                <br />
                <div id="poststuff">
                    <form name="newInterfaceForm" method="post" action="admin.php?page=dbt_builder#clusters">
                            <?php
                            include('data_report/cluster.plug.php');
                            ?>
                    </form>
                </div>
            </div>
        </div>
            <?php
            return;
        }
?>