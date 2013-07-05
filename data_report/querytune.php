<h2>Query Tuning</h2>
        
        <span class="description">Customize the generated query to get the most of your results.</span>
        <div class="warning">WARNING: improper setting of the query can and will result in malfunction.<br/>
        To Reset: Clear the custom query and save.</div>
        
        <div class="controls">
        <textarea style="width: 100%;" rows="20" name="Data[Content][_QueryOveride]"><?php
        if(!empty($Element['ID'])){
            if(!empty($Element['Content']['_QueryOveride'])){                
                $query =  $Element['Content']['_QueryOveride'];
            }else{
                $query = dr_BuildReportGrid($Element['ID'], false, false, false, 'sql');
            }
            preg_match('/(LIMIT [ 0-9]+,[ 0-9]+)/', $query, $Limits);
            if(!empty($Limits[0])){
                $query = str_replace($Limits[0], '', $query);
            }else{
                $query .= $queryLimit;
            }

            echo str_replace(';', '', $query);
        }
        ?></textarea>
        </div>
        <?php
        
            $Sel = '';
            if (!empty($Element['Content']['_UserQueryOveride'])) {
                $Sel = 'checked="checked"';
            }

            echo dais_customfield('checkbox', 'Use Overide', '_UserQueryOveride', '_UserQueryOveride', 'list_row1' , 1, $Sel, 'Use the "Tuned" Query.');
        ?>
        