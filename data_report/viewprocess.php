        <h2>View Processors</h2>
        <div class="admin_config_toolbar">
            <ul class="tools_widgets">
                <li class="root_item"><a class="parent hasSubs"><strong>View Processors</strong></a>
                    <ul id="" style="visibility: hidden; display: block;">

                        <?php
                            echo df_listViewProcessors();
                        ?>

                    </ul>
                </li>
                <li class="root_item" id="processorIndicator"></li>
            </ul>
            <div style="clear:both;"></div>
        </div>
        <div class="inside">
            <p>View Processors can be thought of as Predefined Output Templates.</p>
            
            <div id="viewProcessList" class="columnSorter ui-sortable">
                <?php
                echo df_buildSetViewProcessors($Element['Content'])
                ?>
            </div>
        </div>




<?php
$_SESSION['dataform']['OutScripts'] .= "

    // activate menus
    jQuery('.tools_widgets ul').css({
        display: \"none\"
    });
    jQuery('.tools_widgets li').hover(function(){
        jQuery(this).find('ul:first').css({
            visibility: \"visible\",
            display: \"none\"
        }).fadeIn(250);
    },function(){
        jQuery(this).find('ul:first').css({
            visibility: \"hidden\"
        });
    });
";
?>