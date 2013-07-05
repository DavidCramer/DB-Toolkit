<?php
switch($Types[1]) {
    case 'image':

        $Value = explode('?', $Data[$Field]);
        $Value = $Value[0];

            $imageWidth = ($Config['_ImageSizeX'][$Field] == 'auto' ? '0' : $Config['_ImageSizeX'][$Field]);
            $imageHeight = ($Config['_ImageSizeY'][$Field] == 'auto' ? '0' : $Config['_ImageSizeY'][$Field]);

            $iconWidth = ($Config['_IconSizeX'][$Field] == 'auto' ? '0' : $Config['_IconSizeX'][$Field]);
            $iconHeight = ($Config['_IconSizeY'][$Field] == 'auto' ? '0' : $Config['_IconSizeY'][$Field]);

            if($iconWidth == '0' && $iconHeight == '0'){
                $iconWidth = '100';
            }
            if($imageWidth == '0' && $imageHeight == '0'){
                $imageWidth = '100';
            }
            $uploadVars = wp_upload_dir();

            $SourceFile = str_replace($uploadVars['url'], $uploadVars['path'], $Value);
            if(!file_exists($SourceFile)){
                $Return .= 'Image does not exists.';
            }
            if(file_exists($SourceFile)){
                $dim = getimagesize($SourceFile);
                $newDim = image_resize_dimensions($dim[0], $dim[1], $imageWidth, $imageHeight, true);
            }else{
                $Out .= 'Image not available';
                return;
            }
            if(!empty($newDim)){
            $Sourcepath = pathinfo($SourceFile);
            $URLpath = pathinfo($Value);
            $imageURL = $URLpath['dirname'].'/'.$URLpath['filename'].'-'.$newDim[4].'x'.$newDim[5].'.'.$URLpath['extension'];
            if(!file_exists($Sourcepath['dirname'].'/'.$Sourcepath['filename'].'-'.$newDim[4].'x'.$newDim[5].'.'.$Sourcepath['extension'])){
                $image = image_resize($SourceFile, $imageWidth, $imageHeight, true);
                $icon = image_resize($SourceFile, $iconWidth, $iconHeight, true);
            }
            }else{
                $imageURL = $Value;
                $imageWidth = $dim[0];
                $imageHeight = $dim[1];
            }
            $ClassName = '';
            if(!empty($Config['_ImageClassName'][$Field])){
                $ClassName = 'class="'.$Config['_ImageClassName'][$Field].'" ';
            }

            if(!empty($Config['_IconURLOnly'][$Field])){
                $Out .=  $imageURL;
            }else{
                $Out .=  '<img src="'.$imageURL.'" '.$ClassName.image_hwstring($imageWidth, $imageHeight).'>';
            }

        break;
    case 'file':
        if(!empty($Data[$Field])) {

            $File = explode('?', $Data[$Field]);

            $Dets = pathinfo($File[1]);
            $ext = strtolower($Dets['extension']);
            if(file_exists(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/file/icons/'.$ext.'.gif')){
                    $Icon = '<img src="'.WP_PLUGIN_URL.'/db-toolkit/data_form/fieldtypes/file/icons/'.$ext.'.gif" align="absmiddle" />&nbsp;';
            }else{
                    $Icon = '<img src="'.WP_PLUGIN_URL.'/db-toolkit/data_form/fieldtypes/file/icons/file.gif" align="absmiddle" />&nbsp;';
            }
            //vardump($Data[$Field]);
            $FileSrc = str_replace(WP_CONTENT_URL, WP_CONTENT_DIR, $File[0]);
            //echo filesize($File[0]);
            $Size = file_return_bytes(filesize($FileSrc));

            //$Out .= $Data[$Field];
            //$Out .= '<div class="captions">'.df_parsecamelcase($Image[1]).'</div>';
            $Out .= $Icon.'<a href="'.$File[0].'" target="_blank" >'.$File[1].'</a> ('.$Size.')';
        }else {
            $Out .= 'No file uploaded.';
        }
        break;
    case 'multi';
        if(empty($Data[$Field])) {
            break;
        }
        $Out = $Data[$Field];
        if($Values = unserialize($Data[$Field])) {
            if(!empty($Values['Files'])) {
                $Out = false;
                foreach($Values['Files'] as $File) {

                    $Value = $File['StoredFilename'];
                    $Row = dais_rowswitch($Row);
                    //$File = explode('|', $Value);
                    $Dets = pathinfo($File['StoredFilename']);
                    $ext = strtolower($Dets['extension']);
                    if(file_exists(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/file/icons/'.$ext.'.gif')) {
                        $Icon = '<img src="'.WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/file/icons/'.$ext.'.gif" border="0" align="absmiddle" title="'.$File['OriganalFileName'].'" /> ';
                    }else {
                        $Icon = '<img src="'.WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/file/icons/file.gif" border="0" align="absmiddle" title="'.$File['OriganalFileName'].'" /> ';
                    }
                    $Out .= '<div class="'.$Row.'"><a href="'.$File['StoredFilename'].'" target="_blank">'.$Icon.' '.$File['OriganalFileName'].'</a></div>';

                }

                break;
            }
            //$Values = $Data[$Field];
            $Out = '';
            $Row = 'list_row2';
            foreach($Values as $Value) {
                $Row = dais_rowswitch($Row);
                $File = explode('|', $Value);
                $Dets = pathinfo($File[1]);
                $ext = strtolower($Dets['extension']);
                if(file_exists(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/file/icons/'.$ext.'.gif')) {
                    $Icon = '<img src="'.WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/file/icons/'.$ext.'.gif" border="0" align="absmiddle" title="'.$File[1].'" /> ';
                }else {
                    $Icon = '<img src="'.WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/file/icons/file.gif" border="0" align="absmiddle" title="'.$File[1].'" /> ';
                }
                $Out .= '<div class="'.$Row.'"><a href="'.$File[0].'" target="_blank">'.$Icon.' '.$Dets['basename'].'</a></div>';
            }
            break;
        }else {
            $File = explode('|', $Value);
            $Dets = pathinfo($File[1]);
            $ext = strtolower($Dets['extension']);
            if(file_exists(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/file/icons/'.$ext.'.gif')) {
                $Icon = '<img src="'.WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/file/icons/'.$ext.'.gif" align="absmiddle" /> ';
            }else {
                $Icon = '<img src="'.WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/file/icons/file.gif" align="absmiddle" /> ';
            }
        }

        $Out = $Icon.$File[1];
        break;

}
?>