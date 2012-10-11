<?php
//style=\"width:".$Config['_formWidth']."px\"
    //dump($Config,0);
    $entry = false;
    $buttonText = $Config['_submitText'];
    $buttonClass = $Config['_submitClass'];
    $nounceType = '_dbt_insert';
    if(!empty($Data[0])){
        $entry = $Data[0];
        $buttonText = $Config['_updateText'];
        $buttonClass = $Config['_updateClass'];
        $nounceType = '_dbt_update';
    }
    echo "<ul class=\"breadcrumb\">\n";
    echo "<li><a href=\"#\">Home</a> <span class=\"divider\">".$Config['_addItemDivider']."</span></li>\n";
    echo "<li><a href=\"#\">Library</a> <span class=\"divider\">".$Config['_addItemDivider']."</span></li>\n";
    echo "<li class=\"active\">Data</li>\n";
    echo "</ul>\n";
    
    echo "<form method=\"POST\" class=\"".$Config['_formClass']."\">\n";
        echo dbt_buildFormView($Config, 'form', $entry);
        
        // the form submit buttons
        echo "<div class=\"".$Config['_formActionClass']."\">\n";
        echo "<button type=\"submit\" class=\"".$buttonClass."\">".$buttonText."</button> ";
        if(!empty($_SERVER['HTTP_REFERER'])){
            echo "<a href=\"".$_SERVER['HTTP_REFERER']."\" class=\"".$Config['_cancelClass']."\">".$Config['_cancelText']."</a>";
        }
        echo wp_nonce_field($nounceType, '_dbt_nounce', true, false);
        echo "</div>";        
    echo "</form>";

        
?>