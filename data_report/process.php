
        <h2>Form Processors</h2>
        <div class="admin_config_toolbar">
            <ul class="tools_widgets">
                <li class="root_item"><a class="parent hasSubs"><strong>Processors</strong></a>
                    <ul id="" style="visibility: hidden; display: block;">

                        <?php
                            echo df_listProcessors();
                        ?>

                    </ul>
                </li>
                <li class="root_item" id="processorIndicator"></li>
            </ul>
            <div style="clear:both;"></div>
        </div>
        <div class="inside">
            <p>Once a form is submitted, Form processors will filter through the data submitted and manipulate it in various ways.</p>
            <p>Processes run in order they are presented below, from top to bottom and are stacked. therefore every process will include the changes the previous process has applied.</p>
            <div id="formProcessList" class="columnSorter ui-sortable">
                <?php
                echo df_buildSetProcessors($Element['Content'])
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