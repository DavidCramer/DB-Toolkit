<?php
/* 
 * emailer functions
 * function naming:
 * 
 *      post_process_{{folder}}($Data)
 *      pre_process_{{folder}}($Data)
 *      config_{{folder}}($Config = false)
 *
 */

function pre_process_translate($Data, $Setup, $Config){

// localhost API Key
// ABQIAAAA2WN8lhaozxFRSRKYswV1xxT2yXp_ZAY8_ufC3CFXhHIE1NvwkxS2ozqw2hkXqvyagCamKwZjQa60pw
// vardump($Setup);
    foreach($Data as $Field=>$SubData){
       
        if(!empty($Setup['_toTranslate'][$Field])){            
            $URL = 'https://ajax.googleapis.com/ajax/services/language/translate?v=1.0&q='.urlencode($SubData).'&langpair=|'.$Setup['_translateTo'];
            $data = json_decode(file_get_contents($URL));
            if(!empty($data->responseData->translatedText)){
                $Data[$Field] = $data->responseData->translatedText;
            }
        }
    }

return $Data;
}

//function pre_process_emailer($Data, $Setup, $Config){

//    return $Data;
//}

function config_translate($ProcessID, $Table, $Config = false){
    
    global $wpdb;


    $langs = array('af'=>'Afrikaans',
    'sq'=>'Albanian',
    'ar'=>'Arabic',
    'hy'=>'Armenian',
    'az'=>'Azerbaijani',
    'eu'=>'Basque',
    'be'=>'Belarusian',
    'bg'=>'Bulgarian',
    'ca'=>'Catalan',
    'zh-CN'=>'Chinese',
    'hr'=>'Croatian',
    'cs'=>'Czech',
    'da'=>'Danish',
    'nl'=>'Dutch',
    'en'=>'English',
    'et'=>'Estonian',
    'tl'=>'Filipino',
    'fi'=>'Finnish',
    'fr'=>'French',
    'gl'=>'Galician',
    'ka'=>'Georgian',
    'de'=>'German',
    'el'=>'Greek',
    'ht'=>'Haitian Creole',
    'iw'=>'Hebrew',
    'hi'=>'Hindi',
    'hu'=>'Hungarian',
    'is'=>'Icelandic',
    'id'=>'Indonesian',
    'ga'=>'Irish',
    'it'=>'Italian',
    'ja'=>'Japanese',
    'ko'=>'Korean',
    'la'=>'Latin',
    'lv'=>'Latvian',
    'lt'=>'Lithuanian',
    'mk'=>'Macedonian',
    'ms'=>'Malay',
    'mt'=>'Maltese',
    'no'=>'Norwegian',
    'fa'=>'Persian',
    'pl'=>'Polish',
    'pt'=>'Portuguese',
    'ro'=>'Romanian',
    'ru'=>'Russian',
    'sr'=>'Serbian',
    'sk'=>'Slovak',
    'sl'=>'Slovenian',
    'es'=>'Spanish',
    'sw'=>'Swahili',
    'sv'=>'Swedish',
    'th'=>'Thai',
    'tr'=>'Turkish',
    'uk'=>'Ukrainian',
    'ur'=>'Urdu',
    'vi'=>'Vietnamese',
    'cy'=>'Welsh',
    'yi'=>'Yiddish');



    $rval = 'en';
    if(!empty($Config['_FormProcessors'][$ProcessID]['_translateTo'])){
        $rval = $Config['_FormProcessors'][$ProcessID]['_translateTo'];
    }

    $Return = '<p>Translate to: ';
    $Return .= '<select name="Data[Content][_FormProcessors]['.$ProcessID.'][_translateTo]" >';
        foreach($langs as $key=>$val){
            $sel = '';
            if($key == $rval){
                $sel = 'selected="selected"';
            }
            $Return .= '<option value="'.$key.'" '.$sel.'>'.$val.'</option>';
        }
    $Return .= '</select>';
    




    $Fields = $wpdb->get_results( "SHOW COLUMNS FROM `".$Table."`", ARRAY_N);
    
    $Return .= '<p><strong>Fields to Translate</strong></p>';
    
    foreach($Fields as $FieldName){
        
        $check = '';
        if(!empty($Config['_FormProcessors'][$ProcessID]['_toTranslate'][$FieldName[0]])){
            $check = 'checked="checked"';
        }
        

        $Return .= '<p><input id="translateField_'.$FieldName[0].'" type="checkbox" name="Data[Content][_FormProcessors]['.$ProcessID.'][_toTranslate]['.$FieldName[0].']" value="1" '.$check.' /> <label for="translateField_'.$FieldName[0].'">'.$FieldName[0].'</label></p>';

    }    

    return $Return;
}

?>
