<?php
    if(empty($Config['_enableFilters']) || empty($Config['_IndexType'])){
        return;
    }

    $hide = true;
    $Filters = '';
        foreach($Config['_IndexType'] as $Field=>$Setting){
            if(!empty($Setting['Filter'])){
                $Type = explode('_', $Config['_Field'][$Field]);
                if(file_exists(DBT_PATH.'fieldtypes/'.$Type[0].'/functions.php')){
                    include_once DBT_PATH.'fieldtypes/'.$Type[0].'/functions.php';
                    $func = $Type[0].'_showFilter';
                    if(function_exists($func)){
                        $hide = false;
                        $Default[$Field] = false;
                        if(!empty($_GET[$Field])){
                            $Default[$Field] = $_GET[$Field];
                        }
                        $Filters .= "<span class=\"dbt-filterbox\"><label>".$Config['_FieldTitle'][$Field]."</label>: ".$func($Field, $Type[1], $Default, $Config, $Config['_ID'])."</span>\n";
                    }
                }
            }
        }
if(!$hide){
?>

    <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
    <input type="hidden" name="action" value="<?php echo $_GET['action']; ?>" />
    <input type="hidden" name="interface" value="<?php echo $_GET['interface']; ?>" />
    <?php
    if(!empty($_GET['s'])){
    ?>
    <input type="hidden" name="s" value="<?php echo $_GET['s']; ?>" />
    <?php
    }
    ?>
    <?php
        echo $Filters;
    ?>
        <input type="submit" value="Filter" class="button-secondary" id="post-query-submit" name="">

<?php
}
?>
