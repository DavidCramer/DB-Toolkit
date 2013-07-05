//<script>

function select_addOption(Field){

    var id = Math.round(Math.random() * 100);
    jQuery('#optionsSetup'+Field+'').append('<div style="padding:5px;" id="option'+id+'">Option: <input type="text" name="Data[Content][_SelectOptions]['+Field+'][]" value="" class="textfield" size="5" style="width:100px" /> [<span style="cursor:pointer;" onclick="jQuery(\'#option'+id+'\').remove()">remove</span>]</div>');
}