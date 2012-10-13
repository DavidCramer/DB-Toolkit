<?php
$FieldLayouts = array();
if (!empty($Config['_fieldLayout'])) {
    foreach ($Config['_fieldLayout'] as $Field => $Layout) {
        $rowCol = explode('_', $Layout);
        if (isset($rowCol[1])) {
            $FieldLayouts[$rowCol[0]][$rowCol[1]][] = $Field;
        }
    }
}
?>

<div class="itemField">
    <div class="dbt-elementItem med" id="formhelperBar">
        <div class="dbt-elementInfoPanel mid">
            <div class="title">
                <span class="fbutton"><a class="button" id="addRowFrom"><i class="icon-tasks"></i> Add Row</a></span>
    <?php
    echo dbt_toggleButton('ajaxForms', 'ajaxForm', false, 'Ajax Form', $Config, 'icon-retweet', true);
    echo dbt_toggleButton('modalForm', 'modalForm', false, 'Modal Dialog', $Config, 'icon-move', true);
    echo dbt_toggleButton('placeHolders', 'placeHolders', false, 'Placeholder Lables', $Config, '', true);
    ?>
            </div>
        </div>
        <div class="dbt-elementInfoPanel mid" style="width: 140px; min-width: 140px;">
            <div class="title" style="padding-top:5px;">
    <?php
    if (empty($Config['_formWidth'])) {
        $Config['_formWidth'] = 960;
    }
    ?>
                Form Width: <input type="text" value="<?php echo $Config['_formWidth']; ?>" id="formWidth" name="data[_formWidth]" class="regular-text" style="width:40px;"> px
            </div>
        </div>
        <div class="dbt-elementInfoPanel mid" style="width: 140px; min-width: 140px; padding-left: 20px;">
    <?php
    //<span class="fbutton"><a class="button" id="addRowFrom"><i class="icon-cog"></i> Field Templates</a></span>
    ?>
        </div>
    </div>
</div>
<div class="dbt-formlayout-fields">
    <div id="formFields" class="fieldHolder"></div>
    <div class="clear"></div>
</div>
<div class="dbt-formlayout-board formFields" id="formLayoutBoard">
    <?php
    if (!empty($Config['_formLayout'])) {

        $rowIndex = 1;
        foreach ($Config['_formLayout'] as $rowID => $Row) {



            echo "<div>\n";
            echo "<div id=\"" . $rowID . "\" class=\"formRow\" ref=\"" . $rowID . "\">";

            $columns = explode(':', $Row);
            $colindex = 1;
            foreach ($columns as $column) {

                echo "<div class=\"formColumn\" style=\"width:" . (($column / 12) * 100) . "%;\" ref=\"" . $colindex . "\">\n";
                echo "<div class=\"fieldHolder\">";
                // the elements for that row here
                if (!empty($FieldLayouts[$rowID][$colindex])) {
                    foreach ($FieldLayouts[$rowID][$colindex] as $Field) {
                        if (!empty($Config['_Field'][$Field])) {
                            echo "<div id=\"formField_" . $Field . "\" class=\"button formFieldElement\"><i class=\"icon-cog formElementConfig\" style=\"cursor:pointer;\"></i> " . $Config['_FieldTitle'][$Field] . "<input class=\"fieldLocationCapture\" type=\"hidden\" value=\"" . $Config['_fieldLayout'][$Field] . "\" name=\"data[_fieldLayout][" . $Field . "]\" style=\"width:50px;\" />";
                                echo '<div class="fieldFineTune">';
                                echo '<div class="fieldFineTuneTitle">Form Field Settings</div>';    
                                
                                $fieldWidth = '';
                                if (!empty($Config['_FormFieldWidth'][$Field])) {
                                    $fieldWidth = $Config['_FormFieldWidth'][$Field];
                                }
                                
                                echo '<div class="configControlLabel">';
                                    echo '<label for="">Wrapper Class</label>';
                                echo '</div>';
                                echo '<div class="configControlField">';
                                echo '<input type="text" name="data[_FormFieldWidth]['.$Field.']" value="'.$fieldWidth.'" style="width:298px;">';
                                echo '</div>';
                                
                                $FieldCaption = '';
                                if(!empty($Config['_FieldCaption'][$Field])){
                                    $FieldCaption = $Config['_FieldCaption'][$Field];
                                }
                                $formFieldTemplate = '';
                                if(!empty($Config['_formFieldTemplate'][$Field])){
                                    $formFieldTemplate = $Config['_formFieldTemplate'][$Field];
                                }
                                echo '<div class="configControlLabel">';
                                    echo '<label for="">Caption</label>';
                                echo '</div>';
                                echo '<div class="configControlField">';
                                    echo '<input type="text" name="data[_FieldCaption]['.$Field.']" value="'.$FieldCaption.'" style="width:298px;">';
                                echo '</div>';
                                echo '<div class="configControlLabel">';
                                    echo '<label for="">Field Template</label>';
                                echo '</div>';
                                echo '<div class="configControlField">';
                                    echo '<textarea name="data[_formFieldTemplate]['.$Field.']" style="width:298px; height:100px; resize:vertical;">'.  htmlentities($formFieldTemplate).'</textarea>';
                                echo '</div>';
                                
                                echo '</div>';
                            echo "</div>";
                        }
                    }
                }

                echo "</div>\n";
                echo "</div>";
                $colindex++;
            }

            echo "</div>\n";
            echo "<div id=\"controls_" . $rowID . "\" class=\"formRowControls\"><input type=\"hidden\" name=\"data[_formLayout][" . $rowID . "]\" value=\"" . $Row . "\" id=\"layout_" . $rowID . "\" />\n";
            echo "<span class=\"fbutton addCol\"><span class=\"button\"><i class=\"icon-plus-sign\"></i></span></span>\n";
            echo "<span class=\"fbutton removeCol\"><span class=\"button\"><i class=\"icon-minus-sign\"></i></span></span>\n";
            echo "<span class=\"fbutton removeRow\"><span class=\"button\"><i class=\"icon-remove-sign\"></i></span></span>\n";
            echo "<span class=\"fbutton rowSorter\"><span class=\"button\"><i class=\"icon-move\"></i></span></span>\n";
            echo "</div>\n";

            echo "<div style=\"clear:both;\"></div></div>\n";

            $rowIndex++;
        }

        $footerscripts .= "
        jQuery( \".fieldHolder\" ).sortable({
            connectWith: \".fieldHolder\",
            placeholder: \"formLayoutShadow\"
        });

        jQuery(\"#formLayoutBoard\" ).sortable({
            placeholder: \"formLayoutShadow\",
            handle: \".rowSorter\"

        });

        jQuery(\".fieldHolder\").bind(\"sortupdate\", function(event, ui) {
            if(jQuery(this).parent().parent().attr('ref')){
                var row = jQuery(this).parent().parent().attr('ref');
                var col = jQuery(this).parent().attr('ref');
                jQuery(this).find('.fieldLocationCapture').val(row+'_'+col);
            }else{
                jQuery(this).find('.fieldLocationCapture').val('');
            }
        });

    ";
    }
    ?>


</div>

<script>
<?php
ob_start();
?>

    var gridCols = 12;
    jQuery('#addRowFrom').click(function(){
        var id= "row" + (((1+Math.random())*0x10000)|0).toString(16).substring(1)+(((1+Math.random())*0x10000)|0).toString(16).substring(1);
        
        
        jQuery('#formLayoutBoard').append('\
        <div>\n\
            <div id="'+id+'" class="formRow" ref="'+id+'">\n\
                <div class="formColumn" style="width:100%;" ref="1">\n\
                    <div class="fieldHolder"></div>\n\
                </div>\n\
            </div>\n\
            <div id="controls_'+id+'" class="formRowControls">\n\
                <input type="hidden" name="data[_formLayout]['+id+']" value="'+gridCols+'" id="layout_'+id+'" />\n\
                <span class="fbutton addCol"><span class="button"><i class="icon-plus-sign"></i></span></span>\n\
                <span class="fbutton removeCol"><span class="button"><i class="icon-minus-sign"></i></span></span>\n\
                <span class="fbutton removeRow"><span class="button"><i class="icon-remove-sign"></i></span></span>\n\
                <span class="fbutton rowSorter"><span class="button"><i class="icon-move"></i></span></span>\n\
            </div>\n\
            <div style="clear:both;"></div>\n\
            </div>\n\ ');

        

                        jQuery( ".fieldHolder" ).sortable({
                            connectWith: ".fieldHolder",
                            placeholder: "formLayoutShadow"
                        });


                        jQuery( "#formLayoutBoard" ).sortable({
                            placeholder: "formLayoutShadow",
            
                        });

                        jQuery(".fieldHolder").bind("sortupdate", function(event, ui) {
                            if(jQuery(this).parent().parent().attr('ref')){
                                var row = jQuery(this).parent().parent().attr('ref');
                                var col = jQuery(this).parent().attr('ref');
                                jQuery(this).find('.fieldLocationCapture').val(row+'_'+col);
                            }else{
                                jQuery(this).find('.fieldLocationCapture').val('');
                            }
                        });

                    });
                    jQuery('.formElementRemove').live('click', function(){
                        jQuery(this).parent().find('input.fieldLocationCapture').val('');
                        jQuery(this).parent().appendTo('#formFields');
                    });
                    jQuery('.formElementConfig').live('click', function(){
                        jQuery('.formFieldElement').removeAttr('style');
                        var field = jQuery(this).parent();
                        if(!field.hasClass('editing')){
                            var offset = field.position();                            
                            jQuery('.formFieldElement').                                
                                removeClass('editing').
                                removeAttr('style').
                                find('.fieldFineTune').hide();
                            field.addClass('editing');
                            field.css({
                                position: 'absolute',
                                left: offset.left,
                                top: offset.top,
                                zIndex: 999999,
                                boxShadow: '0 1px 3px rgba(0,0,0,0.2)'                            
                            });
                            field.animate({
                                width: 300
                                
                            }, 100, function(){
                                field.find('.fieldFineTune').fadeIn(100);
                            })
                        }else{
                            field.removeClass('editing');
                            field.find('.fieldFineTune').hide();
                        }
                        
                    });
                    

                    jQuery('.addCol').live('click', function(){
        

                        if(jQuery(this).parent().prev().find('div.formColumn').length === 12){
                            return;
                        }       
                        var curCount = jQuery(this).parent().prev().find('div.formColumn').length;
                        var newCount = curCount+1;
                        if(newCount == 5){
                            newCount = 6;
                        }else{
                            if(newCount >= 7){
                                newCount = gridCols;
                            }
                        }
                        for(i=jQuery(this).parent().prev().find('div.formColumn').length; i<newCount;i++){
                            jQuery(this).parent().prev().append('<div class="formColumn" ref="'+(i+1)+'"><div class="fieldHolder"></div></div>');
                        }

                       var wid = 100/parseFloat(jQuery(this).parent().prev().find('div.formColumn').length);
                       jQuery(this).parent().prev().find('div.formColumn').css('width', wid+'%');
                       var colsArray = new Array();       
                       for(i=0;i<jQuery(this).parent().prev().find('div.formColumn').length;i++){
                           colsArray[i] = gridCols/jQuery(this).parent().prev().find('div.formColumn').length;
                       }       
                       jQuery('#layout_'+jQuery(this).parent().prev().attr('id')).val(colsArray.join(':'));

                        jQuery( ".fieldHolder" ).sortable({
                            connectWith: ".fieldHolder",
                            placeholder: "formLayoutShadow"
                        });
                        jQuery(".fieldHolder").bind("sortupdate", function(event, ui) {
                            if(jQuery(this).parent().parent().attr('ref')){
                                var row = jQuery(this).parent().parent().attr('ref');
                                var col = jQuery(this).parent().attr('ref');
                                jQuery(this).find('.fieldLocationCapture').val(row+'_'+col);
                            }else{
                                jQuery(this).find('.fieldLocationCapture').val('');
                            }
                        });                        
                    });
                    jQuery('.removeCol').live('click', function(){
                        if(jQuery(this).parent().prev().find('div.formColumn').length === 1){
                            return;
                        }
                        var curCount = jQuery(this).parent().prev().find('div.formColumn').length;
                        var newCount = curCount-1;
                        if(newCount == 5){
                            newCount = 4;
                        }
                        if(newCount >= 7){
                            newCount = 6;
                        }
                        var diff = curCount-newCount;
                        for(i=0; i<diff;i++){
                            var lastContent = jQuery(this).parent().prev().find('div.formColumn').last().find('.fieldHolder').html();
                            jQuery(this).parent().prev().find('div.formColumn').last().remove();
                            var wid = 100/(parseFloat(jQuery(this).parent().prev().find('div.formColumn').length));
                            var row = jQuery(this).parent().prev().attr('ref');
                            var col = jQuery(this).parent().prev().find('div.formColumn').last().attr('ref');
                            jQuery(this).parent().prev().find('div.formColumn').last().find('.fieldHolder').append(lastContent).find('input.fieldLocationCapture').val(row+'_'+col);
                            jQuery(this).parent().prev().find('div.formColumn').css('width', wid+'%');
                        }
                        var colsArray = new Array();
                        for(i=0;i<jQuery(this).parent().prev().find('div.formColumn').length;i++){
                            colsArray[i] = gridCols/jQuery(this).parent().prev().find('div.formColumn').length;
                        }
                        jQuery('#layout_'+jQuery(this).parent().prev().attr('id')).val(colsArray.join(':'));

                    });
                    jQuery('.removeRow').live('click', function(){
                        jQuery(this).parent().prev().find('.fieldLocationCapture').val('');
                        jQuery(this).parent().prev().find('.formFieldElement').appendTo('#formFields');        
                        jQuery(this).parent().prev().remove();
                        jQuery(this).parent().remove();
                    });
                    jQuery('.formColumn').live('mouseenter', function(){
                        if(jQuery(this).prev().attr('class')){
                            jQuery('.columnMerge').remove();
                            jQuery(this).prepend('<i class="icon-chevron-left columnMerge"></i>');
                            jQuery(this).find('.columnMerge').click(function(){

                                var row = jQuery(this).parent().parent().attr('id');                

                                jQuery(this).parent().find('.formFieldElement').appendTo(jQuery(this).parent().prev().find('.fieldHolder'));                
                                var leftWidth = parseFloat(jQuery(this).parent().prev().attr('style').replace(/[width: ]/g, "").replace(/[%;]/g, ""));
                                var rightWidth = parseFloat(jQuery(this).parent().attr('style').replace(/[width: ]/g, "").replace(/[%;]/g, ""));
                                var newWidth = leftWidth+rightWidth;
                                jQuery(this).parent().prev().css('width', newWidth+'%');
                                jQuery(this).parent().remove();
                                var colsArray = new Array();
                                var i = 0;
                                jQuery('#'+row).find('.formColumn').each(function(){
                                    jQuery(this).attr('ref', i+1);
                                    jQuery(this).find('input.fieldLocationCapture').val(jQuery(this).parent().attr('ref')+'_'+(i+1));
                                    colsArray[i++] = Math.round(gridCols/(100/parseFloat(jQuery(this).attr('style').replace(/[width: ]/g, "").replace(/[%;]/g, ""))));
                                })
                                jQuery('#layout_'+row).val(colsArray.join(':'));                                               

                            });
                            jQuery('.formColumn').live('mouseleave', function(){
                                jQuery(this).find('.columnMerge').remove();
                            });
                        }

                    });

<?php
$footerscripts .= ob_get_clean();
?>
</script>
