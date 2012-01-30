<?php
/*
 * Grid Layout Generator from a layout string
 * 2012 - David Cramer
 *
 */


function buildLayout($str, $data = false){


// Setup values for output
$lastChar = '';
$output = '';


if(!empty($_GET['debug'])){
    $output .= '<style>.show-grid {
    margin-bottom: 10px;
    margin-top: 10px;
}
.show-grid [class*="span"] {
    background: none repeat scroll 0 0 #EEEEEE;
    border-radius: 3px 3px 3px 3px;
    line-height: 30px;
    min-height: 30px;
    text-align: center;
}
.show-grid:hover [class*="span"] {
    background: none repeat scroll 0 0 #DDDDDD;
}
.show-grid .show-grid {
    margin-bottom: 0;
    margin-top: 0;
}
.show-grid .show-grid [class*="span"] {
    background-color: #CCCCCC;
}</style>';
}


$areaIndex = 0;
for($i=0; $i<= strlen($str); $i++){

    //// Get the current character
    $char = substr($str, $i,1);
    //echo $char.'-';
    if(!empty($lastChar)){
        if($lastChar == '320'){
            $Class = "span-one-third";
        }elseif($lastChar == '640'){
            $Class = "span-two-thirds";
        }elseif($lastChar != '640' || $lastChar != '320'){
            $Class = "span".($lastChar/960*16)."";
        }
    }

        switch($char){
            case ':';
                if(!empty($rowStarted)){
                    echo ']';
                    echo '[';
                    $rowStarted = false;
                }

                echo $lastChar;
                $lastChar = '';
                break;
            case '|';
                if(!empty($rowStarted)){
                    echo ']';
                }
                echo '['.$lastChar;
                $rowStarted = true;
                $lastChar = '';
                break;
            case '[';
                
                $lastChar = '';
                break;
            case ']';
                
                $lastChar = '';
                break;
             default:
                 $lastChar .= $char;
                 break;

        }
    
}

// End the last Column if there is one
if(!empty($lastChar)){
    echo '/'.$lastChar;
    echo ']';
}

return $output;
}

?>