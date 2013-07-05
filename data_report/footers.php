<?php
if(!empty($_SESSION['dataform']['OutScripts'])){
?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        <?php
            echo $_SESSION['dataform']['OutScripts'];
            unset($_SESSION['dataform']['OutScripts']);
        ?>
    });
    </script>
<?php
}
?>