<div class="itemField">
    <div class="dbt-elementItem medium" id="listProcessorhelperBar">
        <div class="title" style="padding-top: 4px;">

            Processor: <?php echo dbt_listlistProcessors($Config); ?>



            <input id="listProcessorsUse" type="button" value="Use">
        </div>
    </div>
</div>
<div id="listProcessorsList">
    <?php
    
    if(!empty($Config['_listprocessor'])){
        foreach($Config['_listprocessor'] as $ProcessorID=>$processor){
            $ProcessorSetup = dbt_loadlistProcessor($processor['processor'], $Config['_main_table'], $ProcessorID, $Config);
            echo $ProcessorSetup['html'];
        }

    }
    
    ?>
</div>
<script>
<?php
    
?>

    jQuery('#listProcessorsUse').click(function(){

        if(jQuery('#listProcessors').val() == ''){
            return;
        }
        if(jQuery('#_main_table').val() == ''){
            return;
        }
        dbt_ajaxCall('dbt_loadListProcessor', jQuery('#listProcessors').val(), jQuery('#_main_table').val(), function(o){
            jQuery('#listProcessorsList').append(o.html);
            eval(o.scripts);
        })

    })

    
</script>
