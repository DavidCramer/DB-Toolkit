<div id="tabs-permissions" class="setupTab">
<?php
    InfoBox('Permissions');
    
    $selAd = '';
    $selSi = 'checked="checked"';
    $sibox = 'block';
    $adbox = 'none';
    if(!empty($Element['Content']['_permissions']['_permissionsType'])){
        if($Element['Content']['_permissions']['_permissionsType'] == 'simple'){
            $selAd = '';
            $selSi = 'checked="checked"';
            $sibox = 'block';
            $adbox = 'none';
        }elseif($Element['Content']['_permissions']['_permissionsType'] == 'advanced'){
            $selAd = 'checked="checked"';
            $selSi = '';
            $sibox = 'none';
            $adbox = 'block';
        }
    }
?>
    <div style="padding: 3px;">
        Setup Type: <input type="radio" id="permType-simple" name="Data[Content][_permissions][_permissionsType]" value="simple" <?php echo $selSi; ?> /> <label for="permType-simple">Simple</label> <span class="description">Still busy working in the advanced permissions setting' Sorry for the delay :)</span>
        <?php /*<input type="radio" id="permType-advanced" name="Data[Content][_permissions][_permissionsType]" value="advanced" <?php echo $selAd; ?> /> <label for="permType-advanced">Advanced</label> */ ?>
    </div>

    <div id="permSetup-simple" style="padding: 5px; display: <?php echo $sibox; ?>;">
        <div id="menuPermissions" class="list_row2" style="padding: 3px;">Effective Capability Permission: <select name="Data[Content][_menuAccess]">
                <option value="null" <?php if($Element['Content']['_menuAccess'] == 'null'){ echo 'selected="selected"'; } ?>>Public</option>
                <?php
                global $wp_roles;
                foreach($wp_roles->roles as $key=>$role){
                    echo '<optgroup label="'.$role['name'].'">';
                    ksort($role['capabilities']);
                    foreach($role['capabilities'] as $cap=>$null){
                        $sel = '';
                        if($Element['Content']['_menuAccess'] == $cap){
                            $sel = 'selected="selected"';
                        }
                        echo '<option value="'.$cap.'" '.$sel.'>'.$cap.'</option>';
                    }
                }
                ?>

            </select>            
        </div>
    </div>

    <div id="permSetup-advanced" style="padding: 3px;  display: <?php echo $adbox; ?>;">
        advanced
    </div>


<?php
    EndInfoBox();
?>
</div>