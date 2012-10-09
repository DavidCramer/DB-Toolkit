<?php
// Run View Processes
    if(!empty($Config['_viewProcessors'])){

        foreach($Config['_viewProcessors'] as $viewProcess){
            if(empty($_GET['format_'.$Config['_ID']])){
                //ignore on export
                if(file_exists(DBT_PATH.'processors/view/'.$viewProcess['_process'].'/functions.php')){
                    include_once(DBT_PATH.'processors/view/'.$viewProcess['_process'].'/functions.php');
                    $func = 'pre_process_'.$viewProcess['_process'];
                    $Data = $func($Data, $viewProcess, $Config, $EID);
                    if(empty($Data)){
                        return;
                    }
                }
            }
        }

    }    
    ob_start();
    if(empty($Data['__noResults'])){
        foreach($Config['_layoutTemplate']['_Content']['_name'] as $templateKey=>$templateName){

                echo $Config['_layoutTemplate']['_Content']['_before'][$templateKey]."\n";
                    $content = $Config['_layoutTemplate']['_Content']['_content'][$templateKey];
                    foreach($Data as $FieldKey=>$FieldOutput){
                        $content = $Config['_layoutTemplate']['_Content']['_content'][$templateKey];
                        foreach($FieldOutput as $Field=>$fieldData){
                            $content = str_replace('{{'.$Field.'}}', $fieldData, $content);
                        }
                        eval (" ?> ".$content." <?php ");
                    }

                echo $Config['_layoutTemplate']['_Content']['_after'][$templateKey]."\n";
        }
    }else{
        echo $Config['_layoutTemplate']['_noResults'];
    }
    $Output = do_shortcode(ob_get_clean());

    echo $Output;


?>