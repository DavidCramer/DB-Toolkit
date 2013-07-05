<h2>Custom WHERE statements</h2>
<div class="warning">WARNING: Incorrect statements will cause the interface to malfunction. Consider this an Advanced Feature.</div>
<p>Custom WHERE statements allow you specify a sort of HARD Filter to the interface. [<a style="cursor: pointer;" onclick="jQuery('#whereHelp').toggle();">help</a>]</p>
<div class="description" id="whereHelp" style="display:none;">
    <p>Each fieldtype can affect the final query in many ways, including the WHERE statement.</p>
    <p>When you apply a filter from the filter panel, it builds the WHERE statement into the query based on your filter selection.<br />
        Selecting a date range, for example, will apply the following WHERE to the query:<br />
    <blockquote>WHERE (`date_field` BETWEEN '2009-10-01' AND '2009-10-30')</blockquote> </p>
    <p>Setting a second filter applies the WHERE as an AND requirement. This means that the results will be WHERE all filters match up. So by adding an user selector to the filters and selecting user David, the WHERE looks like this:
    <blockquote>WHERE (`date_field` BETWEEN '2009-10-01' AND '2009-10-30') AND (`user` = 'David')</blockquote></p>
<p>Adding in your own Custom WHERE, it will be added to the WHERE sets of the Filters by means of the Requirement selector. The Requirement selector only applies if there are filters been set.</p>
<p>So if you where to add the this: "`user` = 'Bob'" with the requirement set as OR the final WHERE would look like this:</p>
<blockquote>WHERE ((`date_field` BETWEEN '2009-10-01' AND '2009-10-30') AND (`user` = 'David')) OR (`user` = 'Bob')</blockquote>
<p>If there have been no filters set, your WHERE would be like this:</p>
<blockquote>WHERE (`user` = 'Bob')</blockquote>
</div>


<br />

<div id="customWHERE">
    <?php

    if(empty($Element['Content']['_customWHERE'])){
    $whereID = uniqid();
    ?>
    <div id="<?php echo $whereID; ?>">Requirement: <select name="Data[Content][_customWHERE][<?php echo $whereID; ?>][_Req]" ><option value="AND">AND</option><option value="OR">OR</option></select> WHERE (<input type="text" name="Data[Content][_customWHERE][<?php echo $whereID; ?>][_Where]" style="width:550px;" />)</div>
    <?php
    }else{
        foreach($Element['Content']['_customWHERE'] as $whereID=>$whereSet){            
            $selO = '';
            $selA = 'selected="selected"';
            if($whereSet['_Req'] == 'OR'){
                $selO = 'selected="selected"';
                $selA = '';
            }
            ?>
            <div id="<?php echo $whereID; ?>">Requirement: <select name="Data[Content][_customWHERE][<?php echo $whereID; ?>][_Req]" ><option value="AND" <?php echo $selA; ?>>AND</option><option value="OR" <?php echo $selO; ?>>OR</option></select> WHERE (<input type="text" name="Data[Content][_customWHERE][<?php echo $whereID; ?>][_Where]" value="<?php echo $whereSet['_Where']; ?>" style="width:550px;" />)</div>
            <?php
        }
    }


            $Sel = '';
            if (!empty($Element['Content']['_useCustomWhere'])) {
                $Sel = 'checked="checked"';
            }
            echo '<p>'.dais_customfield('checkbox', 'Enable', '_useCustomWhere', '_useCustomWhere', 'list_row1', 1, $Sel, 'Enable custom WHERE.').'</p>';

    ?>
</div>