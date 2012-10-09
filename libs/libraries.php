<div style="padding: 3px 0 10px;">
    <input type="button" value="Add Library" onclick="dbt_addLib();" class="button" />
</div>
<div id="assetPane">
    <?php
    if(!empty($Config['_assetLabel'])){
     foreach($Config['_assetLabel'] as $assetKey=>$Label){
        echo '<div id="'.$assetKey.'" class="attributeItem assetItem">';
            echo '<label for="lable_'.$assetKey.'">Label: </label>';
                echo '<input type="text" value="'.$Label.'" name="data[_assetLabel]['.$assetKey.']" style="width:60px;margin-right:10px" id="lable_'.$assetKey.'">';
            echo '<label for="upload_'.$assetKey.'">File: </label>';
                echo '<input type="text" value="'.$Config['_assetURL'][$assetKey].'" name="data[_assetURL]['.$assetKey.']" class="fileURL" style="width:350px;" id="upload_'.$assetKey.'">';
            echo '<input type="button" value="Browse" id="button_'.$assetKey.'" class="upload_file" style="width:60px;margin-right:10px">';
            echo '<label for="assetType_'.$assetKey.'">Type: </label>';
            echo '<select name="data[_assetType]['.$assetKey.']" id="assetType_'.$assetKey.'" style="margin-right:10px">';
                echo '<option value="script_header">Javascript Header</option>';
                echo '<option value="script_footer">Javascript Footer</option>';
                echo '<option value="css">CSS</option>';
            echo '</select>';
            echo ' <span class="button"><a onclick="jQuery(\'#'.$assetKey.'\').remove(); return false;" href="#" class="icon-remove"></a></span>';
        echo '</div>';

     }
    }
    ?>
</div>
<script type="text/javascript">

    function dbt_addLib(){
        var rowID = randomUUID();
        //jQuery('#assetPane').append('<div class="attributeItem assetItem" id="'+rowID+'"><label for="lable_'+rowID+'">Label: </label><input type="text" id="lable_'+rowID+'" style="width:80px;margin-right:20px" name="data[_assetLabel]['+rowID+']" value="inc-'+rowID+'" /><label for="upload_'+rowID+'">File: </label><input id="upload_'+rowID+'" type="text" style="width:350px;" class="fileURL" name="data[_assetURL]['+rowID+']" value="" /><input class="upload_file" id="button_'+rowID+'" type="button" value="Browse & Upload" /> [<a href="#" onclick="jQuery(\'#'+rowID+'\').remove(); return false;">Remove</a>]');
        jQuery('#assetPane').append('<div class="attributeItem assetItem" id="'+rowID+'"><label for="lable_'+rowID+'">Label: </label><input type="text" id="lable_'+rowID+'" style="width:60px;margin-right:10px" name="data[_assetLabel]['+rowID+']" value="inc-'+rowID+'"><label for="upload_'+rowID+'">File: </label><input type="text" id="upload_'+rowID+'" style="width:350px;" class="fileURL" name="data[_assetURL]['+rowID+']" value=""><input type="button" style="width:60px;margin-right:10px" class="upload_file" id="button_'+rowID+'" value="Browse"><label for="assetType_'+rowID+'">Type: </label><select id="assetType_'+rowID+'" name="data[_assetType]['+rowID+']" style="margin-right:10px"><option value="script_header">Javascript Header</option><option value="script_footer">Javascript Footer</option><option value="css">CSS</option></select> <span class="button"><a class="icon-remove" href="#" onclick="jQuery(\'#'+rowID+'\').remove(); return false;"></a></span></div>');
    }
    jQuery(document).ready(function() {

        jQuery('.upload_file').live('click', function() {
         formfield = jQuery(this).parent().find('.fileURL');
         tb_show('', 'media-upload.php?type=file&amp;post_id=0&amp;TB_iframe=true');

            window.send_to_editor = function(html) {
             linkurl = jQuery(html).attr('href');
             jQuery(formfield).val(linkurl);
             tb_remove();
            }

         return false;
        });

    });

</script>