<div class="itemField">
    <div class="dbt-elementItem medium" id="formProcessorhelperBar">
        <div class="title" style="padding-top: 4px;">

            Processor: <?php echo dbt_listFormProcessors($Config); ?>



            <input id="formProcessorsUse" type="button" value="Use">
        </div>
    </div>
</div>
<div id="formProcessorsList">
    <?php
    
    if(!empty($Config['_formprocessor'])){
        foreach($Config['_formprocessor'] as $ProcessorID=>$processor){
            $ProcessorSetup = dbt_loadFormProcessor($processor['processor'], $Config['_main_table'], $ProcessorID, $Config);
            echo $ProcessorSetup['html'];            
        }

    }
    
    ?>
</div>
<script>
<?php
    
?>

    jQuery('#formProcessorsUse').click(function(){

        if(jQuery('#formProcessors').val() == ''){
            return;
        }
        if(jQuery('#_main_table').val() == ''){
            return;
        }
        dbt_ajaxCall('dbt_loadFormProcessor', jQuery('#formProcessors').val(), jQuery('#_main_table').val(), function(o){
            jQuery('#formProcessorsList').append(o.html);
            eval(o.scripts);
        })

    })

    
</script>
