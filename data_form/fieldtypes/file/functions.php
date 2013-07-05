<?php

// Functions

function file_filesetup($Field, $Table, $Config = false) {

    $viewValue = 'iconlink';
    if (!empty($Config['Content']['_fileReturnValue'][$Field])) {
        $viewValue = $Config['Content']['_fileReturnValue'][$Field];
    }
    $Return = 'Value Returned: <select name="Data[Content][_fileReturnValue][' . $Field . ']" >';

    $sel = '';
    if ($viewValue == 'iconlink') {
        $sel = 'selected="selected"';
    }
    $Return .= '<option value="iconlink" ' . $sel . '>Icon and Link</option>';
    $sel = '';
    if ($viewValue == 'filesize') {
        $sel = 'selected="selected"';
    }
    $Return .= '<option value="filesize" ' . $sel . '>Size (Readable)</option>';
    $sel = '';
    if ($viewValue == 'filesizeraw') {
        $sel = 'selected="selected"';
    }
    $Return .= '<option value="filesizeraw" ' . $sel . '>Size (bytes)</option>';
    $sel = '';
    if ($viewValue == 'filename') {
        $sel = 'selected="selected"';
    }
    $Return .= '<option value="filename" ' . $sel . '>Filename</option>';
    $sel = '';
    if ($viewValue == 'filepath') {
        $sel = 'selected="selected"';
    }
    $Return .= '<option value="filepath" ' . $sel . '>URL</option>';
    $sel = '';
    if ($viewValue == 'ext') {
        $sel = 'selected="selected"';
    }
    $Return .= '<option value="ext" ' . $sel . '>Extention</option>';
    $sel = '';
    if ($viewValue == 'mimetype') {
        $sel = 'selected="selected"';
    }
    $Return .= '<option value="mimetype" ' . $sel . '>MIME type</option>';
    $sel = '';

    $Return .= '</select>';
    $sel = '';
    if (!empty($Config['Content']['_filesToLibrary'])) {
        $sel = 'checked="checked"';
    }

    $Return .= '&nbsp; Add Files to Media Library: <input type="checkbox" value="1" name="Data[Content][_filesToLibrary][' . $Field . ']" ' . $sel . ' /><br />';
    $sel = '';
    if (!empty($Config['Content']['_enableS3'])) {
        $sel = 'checked="checked"';
    }
    $Return .= 'Enable S3 uploading: <input type="checkbox" value="1" name="Data[Content][_enableS3][' . $Field . ']" ' . $sel . ' /><br />';
    $accessKey = '';
    if (!empty($Config['Content']['_AWSAccessKey'][$Field])) {
        $accessKey = $Config['Content']['_AWSAccessKey'][$Field];
    }
    $secretKey = '';
    if (!empty($Config['Content']['_AWSSecretKey'][$Field])) {
        $secretKey = $Config['Content']['_AWSSecretKey'][$Field];
    }

    $Return .= 'AWS AccessKey: <input type="text" value="' . $accessKey . '" name="Data[Content][_AWSAccessKey][' . $Field . ']" ' . $sel . ' /><br />';
    $Return .= 'AWS SecretKey: <input type="text" value="' . $secretKey . '" name="Data[Content][_AWSSecretKey][' . $Field . ']" ' . $sel . ' /><br />';
    if (!empty($Config['Content']['_AWSAccessKey'][$Field]) && !empty($Config['Content']['_AWSSecretKey'][$Field])) {
        $Return .= 'Upload Bucket: <select name="Data[Content][_AWSBucket][' . $Field . ']">';
        include_once(DB_TOOLKIT . 'data_form/fieldtypes/file/s3.php');
        $s3 = new S3($Config['Content']['_AWSAccessKey'][$Field], $Config['Content']['_AWSSecretKey'][$Field]);
        foreach ($s3->listBuckets() as $bucket) {
            $Return .= '<option value="' . $bucket . '">' . $bucket . '</option>';
        }

        $Return .= '</select>';
    }else{
        $Return .= '<p>NB: Please save and re-edit to select bucket</p>';
    }

    return $Return;
}

function file_imageConfig($Field, $Table, $Config = false) {


    $Compression = 75;
    $Width = 'auto';
    $Height = 'auto';
    if (!empty($Config['Content']['_IconSizeX'][$Field])) {
        $Width = $Config['Content']['_IconSizeX'][$Field];
    }
    if (!empty($Config['Content']['_IconSizeY'][$Field])) {
        $Height = $Config['Content']['_IconSizeY'][$Field];
    }
    $Return = '<h2>List Icon</h2>';
    $Return .= '<div class="list_row1" style="padding:3px;">Icon Size: <input type="text" name="Data[Content][_IconSizeX][' . $Field . ']" value="' . $Width . '" class="textfield" size="3" maxlength="4" style="width:40px;" /> X <input type="text" name="Data[Content][_IconSizeY][' . $Field . ']" value="' . $Height . '" class="textfield" size="3" maxlength="4" style="width:40px;" /></div>';
    if (!empty($Config['Content']['_IconCompression'][$Field])) {
        $Compression = $Config['Content']['_IconCompression'][$Field];
    }
    $Return .= '<div class="list_row1" style="padding:3px;">Icon Compression: <input type="text" name="Data[Content][_IconCompression][' . $Field . ']" value="' . $Compression . '" class="textfield" size="3" maxlength="4" style="width:40px;" />%</div>';
    $Class = '';
    if (!empty($Config['Content']['_IconClassName'][$Field])) {
        $Class = $Config['Content']['_IconClassName'][$Field];
    }
    $Return .= '<div class="list_row1" style="padding:3px;">Icon Class: <input type="text" name="Data[Content][_IconClassName][' . $Field . ']" value="' . $Class . '" class="textfield" /></div>';
    $Sel = '';
    if (!empty($Config['Content']['_IconURLOnly'][$Field])) {
        $Sel = 'checked="checked"'; //$Config['Content']['_ImageSquare'][$Field]
    }
    $Return .= '<div class="list_row2" style="padding:3px;">URL Only: <input type="checkbox" name="Data[Content][_IconURLOnly][' . $Field . ']" value="1" ' . $Sel . ' /></div>';



    $Compression = 75;
    $Width = 'auto';
    $Height = 'auto';
    if (!empty($Config['Content']['_ImageSizeX'][$Field])) {
        $Width = $Config['Content']['_ImageSizeX'][$Field];
    }
    if (!empty($Config['Content']['_ImageSizeY'][$Field])) {
        $Height = $Config['Content']['_ImageSizeY'][$Field];
    }
    $Return .= '<h2>View Image</h2>';
    $Return .= '<div class="list_row1" style="padding:3px;">Image Size: <input type="text" name="Data[Content][_ImageSizeX][' . $Field . ']" value="' . $Width . '" class="textfield" size="3" maxlength="4" style="width:40px;" /> X <input type="text" name="Data[Content][_ImageSizeY][' . $Field . ']" value="' . $Height . '" class="textfield" size="3" maxlength="4" style="width:40px;" /></div>';
    if (!empty($Config['Content']['_ImageCompression'][$Field])) {
        $Compression = $Config['Content']['_ImageCompression'][$Field];
    }
    $Return .= '<div class="list_row1" style="padding:3px;">Image Compression: <input type="text" name="Data[Content][_ImageCompression][' . $Field . ']" value="' . $Compression . '" class="textfield" size="3" maxlength="4" style="width:40px;" />%</div>';
    $Class = '';
    if (!empty($Config['Content']['_ImageClassName'][$Field])) {
        $Class = $Config['Content']['_ImageClassName'][$Field];
    }
    $Return .= '<div class="list_row1" style="padding:3px;">Image Class: <input type="text" name="Data[Content][_ImageClassName][' . $Field . ']" value="' . $Class . '" class="textfield" /></div>';
    $Sel = '';
    if (!empty($Config['Content']['_ImageURLOnly'][$Field])) {
        $Sel = 'checked="checked"'; //$Config['Content']['_ImageSquare'][$Field]
    }
    $Return .= '<div class="list_row2" style="padding:3px;">URL Only: <input type="checkbox" name="Data[Content][_ImageURLOnly][' . $Field . ']" value="1" ' . $Sel . ' /></div>';



    return $Return;
}

function file_handleInput($Field, $Input, $FieldType, $Config, $predata) {



    if (strtolower($Config['Content']['_ViewMode']) == 'api') {
        if (!empty($_FILES[$Field]['size'])) {
            $_FILES['dataForm']['name'][$Config['ID']][$Field] = $_FILES[$Field]['name'];
            $_FILES['dataForm']['size'][$Config['ID']][$Field] = $_FILES[$Field]['size'];
            $_FILES['dataForm']['tmp_name'][$Config['ID']][$Field] = $_FILES[$Field]['tmp_name'];
        }
    }

    if ($FieldType == 'multi') {
        return $Input;
    }
    if (!empty($_POST['deleteImage'][$Field])) {
        $FileInfo = explode('?', $predata[$Field]);
        if (file_exists($FileInfo[0])) {
            unlink($FileInfo[0]);
        }
        return '';
    }
    if (empty($_FILES['dataForm']['name'][$Config['ID']][$Field])) {
        return $predata[$Field];
    }
    // Create Directorys
    if (!empty($_FILES['dataForm']['size'][$Config['ID']][$Field])) {

        $path = wp_upload_dir();

        // set filename and paths
        $Ext = pathinfo($_FILES['dataForm']['name'][$Config['ID']][$Field]);
        $newFileName = uniqid($Config['ID'] . '_') . '.' . $Ext['extension'];
        $newLoc = $path['path'] . '/' . $newFileName;
        //$urlLoc = $path['url'].'/'.$newFileName;
        $GLOBALS['UploadedFile'][$Field] = $newLoc;

        $upload = wp_upload_bits($_FILES['dataForm']['name'][$Config['ID']][$Field], null, file_get_contents($_FILES['dataForm']['tmp_name'][$Config['ID']][$Field]));


        if (!empty($Config['Content']['_filesToLibrary'])) {
            global $user_ID;
            $type = wp_check_filetype($upload['file']);
            $new_post = array(
                'post_title' => $Ext['filename'],
                'post_status' => 'inherit',
                'post_date' => date('Y-m-d H:i:s'),
                'post_author' => $user_ID,
                'post_type' => 'attachment',
                'post_mime_type' => $type['type'],
                'guid' => $upload['url'],
            );

            // This should never be set as it would then overwrite an existing attachment.
            if (isset($attachment['ID']))
                unset($attachment['ID']);

            // Save the data
            //$id = wp_insert_attachment($new_post, $upload['file']);
            //if ( !is_wp_error($id) ) {
            //    if(!function_exists('wp_generate_attachment_metadata')){
            //require_once('includes/image.php');
            //    }
            //wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
            //}
        }

        //move_uploaded_file($_FILES['dataForm']['tmp_name'][$Config['ID']][$Field], $newLoc);
        //return $newLoc;

        if ($FieldType == 'image') {

            $imageWidth = ($Config['Content']['_ImageSizeX'][$Field] == 'auto' ? '0' : $Config['Content']['_ImageSizeX'][$Field]);
            $imageHeight = ($Config['Content']['_ImageSizeY'][$Field] == 'auto' ? '0' : $Config['Content']['_ImageSizeY'][$Field]);

            $iconWidth = ($Config['Content']['_IconSizeX'][$Field] == 'auto' ? '0' : $Config['Content']['_IconSizeX'][$Field]);
            $iconHeight = ($Config['Content']['_IconSizeY'][$Field] == 'auto' ? '0' : $Config['Content']['_IconSizeY'][$Field]);

            // crunch sizes

            $image = image_resize($upload['file'], $imageWidth, $imageHeight, true);
            $icon = image_resize($upload['file'], $iconWidth, $iconHeight, true);
        }
        //vardump($upload);
        //vardump($Config['Content']['_AWSBucket']);
        //die;
        if (!empty($Config['Content']['_enableS3'][$Field]) && !empty($Config['Content']['_AWSAccessKey'][$Field]) && !empty($Config['Content']['_AWSSecretKey'][$Field])) {

            include_once(DB_TOOLKIT . 'data_form/fieldtypes/file/s3.php');
            $s3 = new S3($Config['Content']['_AWSAccessKey'][$Field], $Config['Content']['_AWSSecretKey'][$Field]);
            $input = $s3->inputFile($upload['file']);
            $fileName = date('Y') . '/' . date('m') . '/' . $newFileName;
            if ($s3->putObject($input, $Config['Content']['_AWSBucket'][$Field], $fileName, 'public-read')) {
                unlink($upload['file']);
                return $fileName;
            }
        }
        return $upload['url'];
    }
}

function file_processValue($Value, $Type, $Field, $Config, $EID) {

    if (!empty($Value)) {
        //dump($Value);
        //dump($Type);
        //dump($Field);
        //dump($Config);
        //die;
        switch ($Type) {
            case 'image';

                $Value = strtok($Value, '?');
                $imageWidth = ($Config['_ImageSizeX'][$Field] == 'auto' ? '100' : $Config['_ImageSizeX'][$Field]);
                $imageHeight = ($Config['_ImageSizeY'][$Field] == 'auto' ? '100' : $Config['_ImageSizeY'][$Field]);

                $iconWidth = ($Config['_IconSizeX'][$Field] == 'auto' ? '100' : $Config['_IconSizeX'][$Field]);
                $iconHeight = ($Config['_IconSizeY'][$Field] == 'auto' ? '100' : $Config['_IconSizeY'][$Field]);

                $uploadVars = wp_upload_dir();



                $SourceFile = str_replace($uploadVars['baseurl'], $uploadVars['basedir'], $Value);
                if (!file_exists($SourceFile)) {
                    return 'Image does not exists.';
                }
                $dim = getimagesize($SourceFile);
                $newDim = image_resize_dimensions($dim[0], $dim[1], $iconWidth, $iconHeight, true);
                if (!empty($newDim)) {
                    $Sourcepath = pathinfo($SourceFile);
                    $URLpath = pathinfo($Value);
                    $iconURL = $URLpath['dirname'] . '/' . $URLpath['filename'] . '-' . $newDim[4] . 'x' . $newDim[5] . '.' . $URLpath['extension'];
                    if (!file_exists($Sourcepath['dirname'] . '/' . $Sourcepath['filename'] . '-' . $newDim[4] . 'x' . $newDim[5] . '.' . $Sourcepath['extension'])) {
                        $image = image_resize($SourceFile, $imageWidth, $imageHeight, true);
                        $icon = image_resize($SourceFile, $iconWidth, $iconHeight, true);
                    }
                } else {
                    $iconURL = $Value;
                    $iconWidth = $dim[0];
                    $iconHeight = $dim[1];
                }
                $ClassName = '';
                if (!empty($Config['_ImageClassName'][$Field])) {
                    $ClassName = 'class="' . $Config['_ImageClassName'][$Field] . '" ';
                }

                if (!empty($Config['_IconURLOnly'][$Field])) {
                    return $iconURL;
                }
                return '<img src="' . $iconURL . '" ' . $ClassName . image_hwstring($iconWidth, $iconHeight) . '>';


                break;
            case 'mp3';
                $File = explode('?', $Value);
                $UniID = uniqid($EID . '_');
                //$ReturnData = '<span id="'.$UniID.'">'.$File[1].'</span>';
                $ReturnData = '<audio id="' . $UniID . '" src="' . $File[0] . '">unavailable</audio>';

                $_SESSION['dataform']['OutScripts'] .= "
					AudioPlayer.embed(\"" . $UniID . "\", {
					";
                if (!empty($Config['_PlayerCFG']['Autoplay'][$Field])) {
                    $_SESSION['dataform']['OutScripts'] .= " autostart: 'yes', ";
                }
                if (!empty($Config['_PlayerCFG']['Animation'][$Field])) {
                    $_SESSION['dataform']['OutScripts'] .= " animation: 'yes', ";
                }
                $_SESSION['dataform']['OutScripts'] .= "
                                                transparentpagebg: 'yes',
						soundFile: \"" . $File[0] . "\",
						titles: \"" . $File[1] . "\"
					});

				";
                $_SESSION['dataform']['OutScripts'] .="
                                jQuery(document).ready(function($) {
                                    AudioPlayer.setup(\"" . WP_PLUGIN_URL . "/db-toolkit/data_form/fieldtypes/file/player.swf\", {
                                        width: '100%',
                                        initialvolume: 100,
                                        transparentpagebg: \"yes\",
                                        left: \"000000\",
                                        lefticon: \"FFFFFF\"
                                    });
                                 });";
                return $ReturnData;
                break;
            case 'file';
            case 'multi';

                if (empty($Config['_fileReturnValue'][$Field])) {
                    $Config['_fileReturnValue'][$Field] = 'iconlink';
                }

                $pathInfo = pathinfo($Value);
                $s3Enabled = false;
                $prime = $Field;
                if(!empty($Config['_CloneField'][$Field]['Master'])){
                    $prime = $Config['_CloneField'][$Field]['Master'];
                }
                if (!empty($Config['_enableS3'][$prime]) && !empty($Config['_AWSAccessKey'][$prime]) && !empty($Config['_AWSSecretKey'][$prime])) {
                    include_once(DB_TOOLKIT . 'data_form/fieldtypes/file/s3.php');
                    $s3 = new S3($Config['_AWSAccessKey'][$prime], $Config['_AWSSecretKey'][$prime]);
                    $s3Enabled = true;
                }

                switch ($Config['_fileReturnValue'][$Field]) {
                    case 'iconlink':
                            
                            if (empty($Value)) {
                                return 'no file uploaded';
                            }
  
                            if (!empty($Config['_enableS3'][$prime]) && !empty($Config['_AWSAccessKey'][$prime]) && !empty($Config['_AWSSecretKey'][$prime])) {
                                $File = 'http://'.$Config['_AWSBucket'][$prime].'.s3.amazonaws.com/'.$Value;                                
                            }else{
                                $File = $Value;
                                
                            }
                            $Dets = pathinfo($File);
                            $ext = strtolower($Dets['extension']);
                            if (file_exists(WP_PLUGIN_DIR . '/db-toolkit/data_form/fieldtypes/file/icons/' . $ext . '.gif')) {
                                $Icon = '<img src="' . WP_PLUGIN_URL . '/db-toolkit/data_form/fieldtypes/file/icons/' . $ext . '.gif" align="absmiddle" />&nbsp;';
                            } else {
                                $Icon = '<img src="' . WP_PLUGIN_URL . '/db-toolkit/data_form/fieldtypes/file/icons/file.gif" align="absmiddle" />&nbsp;';
                            }
                        
                        return '<a href="' . $File . '">' . $Icon . ' ' . basename($File) . '</a>';
                        break;
                        
                    case 'filesize':
                        
                        if(!empty($s3Enabled)){
                            $object = $s3->getObjectInfo($Config['_AWSBucket'][$prime], $Value);
                            return file_return_bytes($object['size']);
                        }else{
                            $uploadDir = wp_upload_dir();
                            $file = str_replace($uploadDir['baseurl'], $uploadDir['basedir'], $Value);
                            return file_return_bytes(filesize($file));
                        }
                        break;
                    case 'filesizeraw':
                        if(!empty($s3Enabled)){
                            $object = $s3->getObjectInfo($Config['_AWSBucket'][$prime], $Value);
                            return $object['size'];
                        }else{

                            $uploadDir = wp_upload_dir();
                            $file = str_replace($uploadDir['baseurl'], $uploadDir['basedir'], $Value);
                            return filesize($file);
                        }
                        break;
                    case 'filename':
                        if(!empty($s3Enabled)){
                            return basename($Value);
                        }else{
                            return $pathInfo['basename'];
                        }
                        break;
                    case 'filepath':
                        return $Value;
                        break;
                    case 'ext':
                        return $pathInfo['extension'];
                        break;
                    case 'mimetype':
                        $uploadDir = wp_upload_dir();
                        $file = str_replace($uploadDir['baseurl'], $uploadDir['basedir'], $Value);
                        $type = wp_check_filetype($file);
                        return $type['type'];
                        break;
                }
                break;
        }

        return;
    }
}

/// Uploader Processessor
if (!empty($_GET['uploadify'])) {

    $string = base64_decode(urldecode($_GET['uploadify']));
    $fieldData = explode('_', $string);
    //vardump($fieldData);
    //vardump($string);
    //vardump($_FILES);

    if (!empty($_FILES['Filedata']['size'])) {

        $path = wp_upload_dir();
        // set filename and paths
        $Ext = pathinfo($_FILES['Filedata']['name']);
        $newFileName = uniqid() . '.' . $Ext['extension'];
        $newLoc = $path['path'] . '/' . $newFileName;

        $upload = wp_upload_bits($newFileName, null, file_get_contents($_FILES['Filedata']['tmp_name']));
        //move_uploaded_file($_FILES['dataForm']['tmp_name'][$Config['ID']][$Field], $newLoc);
        //return $newLoc;
        //vardump($upload);
        //return $upload['url'].'?'.$_FILES['dataForm']['name'][$Config['ID']][$Field];

        echo '<input type="hidden" value="' . $upload['url'] . '?' . $_FILES['Filedata']['name'] . '" id="entry' . $string . '" name="dataForm[' . $fieldData[1] . '_' . $fieldData[2] . '][' . $fieldData[3] . ']">';
    }

    exit;
}

function file_playerSetup($Field, $Table, $Config = false) {
    //$Return = '<div class="list_row1" style="padding:3px;">Icon Size (px): <input type="text" name="Data[Content][_ImageSizeI]['.$Field.']" value="'.$icon.'" class="textfield" size="3" maxlength="3" /> Square Crop: <input type="checkbox" name="Data[Content][_ImageSquareI]['.$Field.']" value="1" '.$Sel1.' /></div>';

    $Return = 'Player Preview<div style="padding:5px; width: 200px;" id="' . $Field . '_preview"></div>';
    $Sel = '';
    if (!empty($Config['Content']['_PlayerCFG']['Autoplay'][$Field])) {
        $Sel = 'checked="checked"';
    }
    $Return .= '<div style="padding:3px;">Auto Play: <input type="checkbox" name="Data[Content][_PlayerCFG][Autoplay][' . $Field . ']" id="' . $Field . '_autoPlay" ' . $Sel . ' value="yes" /> In a list, the last item will auto play</div>';
    //$Return .= '<div style="padding:3px;">Animation: <input type="checkbox" name="Data[Content][_PlayerCFG][Animation]['.$Field.']" id="'.$Field.'_autoPlay" value="no" /> Unchecked, the player will be open, checked minimized.</div>';
    $_SESSION['dataform']['OutScripts'] .= "
			AudioPlayer.embed(\"" . $Field . "_preview\", {
			";
    if (!empty($Config['Content']['_PlayerCFG']['Autoplay'][$Field])) {
        $_SESSION['dataform']['OutScripts'] .= " autoplay: 'yes', ";
    }
    if (!empty($Config['Content']['_PlayerCFG']['Animation'][$Field])) {
        $_SESSION['dataform']['OutScripts'] .= " animation: 'yes', ";
    }
    $_SESSION['dataform']['OutScripts'] .= "
				demomode: \"yes\"
			});
		";

    return $Return;
}

function file_return_bytes($size, $max = null, $system = 'si', $retstring = '%01.2f %s') {
    // Pick units
    $systems['si']['prefix'] = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    $systems['si']['size'] = 1000;
    $systems['bi']['prefix'] = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB');
    $systems['bi']['size'] = 1024;
    $sys = isset($systems[$system]) ? $systems[$system] : $systems['si'];

    // Max unit to display
    $depth = count($sys['prefix']) - 1;
    if ($max && false !== $d = array_search($max, $sys['prefix'])) {
        $depth = $d;
    }

    // Loop
    $i = 0;
    while ($size >= $sys['size'] && $i < $depth) {
        $size /= $sys['size'];
        $i++;
    }

    return sprintf($retstring, $size, $sys['prefix'][$i]);
}

?>