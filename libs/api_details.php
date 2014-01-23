<?php 
$apiAuth = '';
if (key_exists('_APIAuthentication', $Config)) {
    $apiAuth .= $Config['_APIAuthentication'];
}

if ($apiAuth != 'open') {
    echo get_bloginfo('url').'/ <em><strong>CallName</strong></em> / <em><strong>Key/Token</strong></em> / <em><strong>Method</strong></em> / <em><strong>Format</strong></em> / <em><strong>? GET Variables</strong></em> '; 
} else {
    echo get_bloginfo('url').'/ <em><strong>CallName</strong></em> / <em><strong>Method</strong></em> / <em><strong>Format</strong></em> / <em><strong>? GET Variables</strong></em> '; 
}
?>
<h2>API Access Details</h2>
<?php
if ($Config['_APISeed'] != '') {
   $APIKey = md5($Media['ID']. $Config['_APISeed']);
} else {
   $APIKey = 'Warning: insecure shared secret';
}
?>

<table class="form-table">
    <tbody>


        <tr>
            <th scope="row">CallName</th>
            <td>
                <?php
                if(!empty($Config['_APICallName'])){
                    echo $Config['_APICallName'];
                    $CallName = $Config['_APICallName'];
                }else{
                    echo $Media['ID'];
                    $CallName = $Media['ID'];
                }
                ?>
            </td>
        </tr>
        
        <?php if ($apiAuth != 'open') { ?>
        <tr>
            <th scope="row" span="2">Key</th>
            <td>
                <?php
                if($apiAuth == 'key'){
                    echo '<div>Your token: '.API_getCurrentUsersKey().'</div>';
                    echo '<span class="description">This is the token for you. You\'ll need to call the Auth Method as indicated below to retrieve the other tokens.</span>';
                }else{
                    echo $APIKey;
                }
                ?>
            </td>
        </tr>
        <?php } ?>


        <?php
        if($apiAuth == 'key'){
        ?>

        <tr>
            <th scope="row" span="2">Authenticating a User</th>
            <td>
                <ul>
                    <li>End Point: <?php
                    echo get_bloginfo('url').'/'.$CallName.'/auth/<em><strong>format [xml | json]</strong></em>';
                    ?></div></li>
                    <li>user : POST <span class="description">Wordpress username</span></li>
                    <li>pass : POST <span class="description">Wordpress password</span></li>
                </ul>
            </td>
        </tr>

        <?php
        }
        ?>
        <tr>
            <th scope="row">Methods</th>
            <td>
                <ul>
                    <?php if(!empty ($Config['_APIMethodSearch'])){ ?><li>list : GET <span class="description">Retrieves an array of all entries matching the field Search for (requires a fieldname get with query text. field needs to be indexed.</span></li><?php } ?>
                    <?php if(!empty ($Config['_APIMethodList'])){ ?><li>list : GET <span class="description">Retrieves an array of all entries.</span></li><?php } ?>
                    <?php if(!empty ($Config['_APIMethodFetch'])){ ?><li>fetch : GET <span class="description">Retrieves a single item. Requires itemID variable.</span></li><?php } ?>
                    <?php if(!empty ($Config['_APIMethodInsert'])){ ?><li>insert : POST <span class="description">Inserts a single item.</span></li><?php } ?>
                    <?php if(!empty ($Config['_APIMethodUpdate'])){ ?><li>update : POST <span class="description">Updates a single item. Requires itemID variable.</span></li><?php } ?>
                    <?php if(!empty ($Config['_APIMethodDelete'])){ ?><li>delete : POST <span class="description">Deletes a single item. Requires itemID variable.</span></li><?php } ?>
                </ul>
            </td>            
        </tr>
        
        <tr>
            <th scope="row">Format</th>
            <td>
                <ul>
                    <li>xml <span class="description">Data is returned in XML Format. [list|fetch|update|delete]</span></li>
                    <li>json <span class="description">Data is returned in json Format. [list|fetch|update|delete]</span></li>
                    <li>html <span class="description">Output is rendered in html.(Great in template mode) [list|fetch]</span></li>
                </ul>
            </td>
        </tr>

        <tr>
            <th scope="row">Variables</th>
            <td>
                <ul>
                    <li>itemID : GET|POST <span class="description">value of field <strong><?php echo $Config['_ReturnFields'][0]; ?></strong> for the item. [fetch GET |update POST |delete POST]</span></li>
                    <li>limit : GET <span class="description">Limits the number of items returned. [list]</span></li>
                    <li>offset : GET <span class="description">Offset page number fore limited results. Requires the limit variable. [list]</span></li>
                </ul>
            </td>
        </tr>

        <?php if(!empty ($Config['_APIMethodUpdate']) || !empty ($Config['_APIMethodInsert'])){ ?>
        <tr>
            <th scope="row">POST Fields</th>
            <td>
                <ul>
                    <?php
                    foreach($Config['_Field'] as $Field=>$Type){
                        
                        $Type = explode('_', $Type);
                        if(!empty($Type[1])){
                            if (file_exists(WP_PLUGIN_DIR . '/db-toolkit/data_form/fieldtypes/' . $Type[0] . '/conf.php')) {
                                include(WP_PLUGIN_DIR . '/db-toolkit/data_form/fieldtypes/' . $Type[0] . '/conf.php');
                                if(!empty($FieldTypes[$Type[1]]['visible']) && empty($Config['_CloneField'][$Field]) ){
                        ?>
                                <li><?php echo $Field; ?> : <span class="description"><?php echo $Type[0]; ?></span></li>
                        <?php
                                }
                            }
                        }
                    }
                    ?>
                </ul>
            </td>
        </tr>
        <?php } ?>

        <tr>
            <th scope="row">Return Types</th>
            <td>
                <ul>                    
                    <?php if(!empty ($Config['_APIMethodSearch'])){
                     ?>
                    <li>search : <span class="description">entries array( entry array(
                    <?php
                        $Fields = array();
                        foreach($Config['_IndexType'] as $Field=>$Index){
                        
                            $viewtype = explode('_', $Index);
                            if($viewtype[1] == 'show'){
                            
                                $Fields[] = $Field;
                    
                            }                        
                        }
                        echo implode(' , ', $Fields);
                    }
                    ?>) )</span></li>
                    <?php if(!empty ($Config['_APIMethodList'])){
                     ?>
                    <li>list : <span class="description">entries array( entry array(
                    <?php
                        $Fields = array();
                        foreach($Config['_IndexType'] as $Field=>$Index){

                            $viewtype = explode('_', $Index);
                            if($viewtype[1] == 'show'){

                                $Fields[] = $Field;

                            }
                        }
                        echo implode(' , ', $Fields);
                    }
                    ?>) )</span></li>

                    
                    <?php if(!empty ($Config['_APIMethodFetch'])){
                    ?>
                    <li>fetch : <span class="description">array(
                    <?php
                        $Fields = array();
                        foreach($Config['_IndexType'] as $Field=>$Index){

                            $viewtype = explode('_', $Index);
                            if($viewtype[1] == 'show'){

                                $Fields[] = $Field;

                            }
                        }
                        echo implode(' , ', $Fields);
                    }
                    ?>)</span></li>
                    
                    
                    <?php if(!empty ($Config['_APIMethodInsert'])){
                    ?>
                    <li>Insert : <span class="description">array(
                    <?php
                        $Fields = array();                        
                        foreach($Config['_ReturnFields'] as $Field){
                            $Fields[] = $Field;
                        }
                        echo implode(' , ', $Fields);
                    }
                    ?>)</span></li>
                    
                    <?php if(!empty ($Config['_APIMethodUpdate'])){
                    ?>
                    <li>Update : <span class="description">Boolean [true|false]</span></li>
                    <?php
                    }
                    ?>

                    <?php if(!empty ($Config['_APIMethodDelete'])){
                    ?>
                    <li>Delete : <span class="description">Boolean [true|false]</span></li>
                    <?php
                    }
                    ?>
                </ul>
            </td>
        </tr>

    </tbody>
</table>
