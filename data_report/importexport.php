<h2>Import/Export</h2>
        
        <span class="description">This is a text version of the interface setup. you can copy and paste this into another interface to copy its configuration.</span><br /><br />
        <div class="controls">
        <textarea style="width: 100%;" rows="10"><?php
        if(!empty($Element['ID'])){
            echo base64_encode(serialize($Element));
        }
        ?></textarea>
        <strong>Paste Import Here For Processing</strong>
        <textarea style="width: 100%;" rows="10" name="Data[_SerializedImport]"></textarea>        
        </div>
        <?php
            echo dais_customfield('checkbox', 'Process Import', '_ProcessImport', '_ProcessImport', 'list_row1' , 1, '', 'Run the above code as an import. Be carefull as this can damage an interface.');
        ?>
        