<?php

/*
 * Grid Layout Generator from a layout string
 * 2012 - David Cramer
 *
 *Example of use
 *
         $Data = '<h2>Heading<small>Has sub-heading…</small></h2>
          <p>Etiam porta sem malesuada magna mollis euismod. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</p>
          <p><a class="btn" href="#">View details &raquo;</a></p>';
        $str = '960|960|480:480|480:480';

        $page = new Layout();
        $page->setLayout($str);
        $page->setData($Data, 4);
        $page->renderLayout();
 *
 *
 *
 */



class Layout {

    static $layoutString;
    static $html;

    function Layout() {
        $this->html = array();
    }
    public function setLayout($str) {
        $this->layoutString = $str;
    }
    public function gethtml($index) {
        if(isset($this->html[$index])){
            return $this->html[$index];
        }else{
            return false;
        }
    }
    public function html($html, $index) {
        $this->html[$index] = $html;
    }
    public function append($html, $index) {
        if(isset($this->html[$index])){
            $this->html[$index] = $this->html[$index].$html;
        }else{
            $this->html[$index] = $html;
        }
    }
    public function prepend($html, $index) {
        if(isset($this->html[$index])){
            $this->html[$index] = $html.$this->html[$index];
        }else{
            $this->html[$index] = $html;
        }
    }

    public function renderLayout() {
        $lastChar = '';
        //start row
        $output = "<div class=\"row\">\n";
        $contentIndex = 0;
        for ($i = 0; $i <= strlen($this->layoutString); $i++) {

            //// Get the current character
            $char = substr($this->layoutString, $i, 1);
            //$output .=$char.'-';
            if (!empty($lastChar)) {
                if ($lastChar == '320') {
                    $Class = "span-one-third";
                } elseif ($lastChar == '640') {
                    $Class = "span-two-thirds";
                } elseif ($lastChar != '640' || $lastChar != '320') {
                    $Class = "span" . ($lastChar / 960 * 16) . "";
                }
            }            
            switch ($char) {
                case ':';
                    if(!empty($lastChar)){
                        $output .= "<div class=\"".$Class."\">\n";
                        if(isset($this->html[$contentIndex])){
                            $output .= $this->html[$contentIndex];
                        }else{
                            $output .= '&nbsp;';
                        }
                        $output .= "</div>\n";
                    }
                    $lastChar = '';
                    $openCol = true;
                    break;
                case '|';
                    if(!empty($lastChar)){
                        $output .= "<div class=\"".$Class."\">\n";
                        if(isset($this->html[$contentIndex])){
                            $output .= $this->html[$contentIndex];
                        }else{
                            $output .= '&nbsp;';
                        }
                        $output .= "</div>\n";
                    }
                    $output .= "</div>\n<div class=\"row show-grid\">\n";

                    $lastChar = '';
                    break;
                case '[';
                    $output .= "<div class=\"".$Class."\">\n";
                    $output .= "<div class=\"row show-grid\">\n";
                    $lastChar = '';
                    break;
                case ']';
                    if(!empty($lastChar)){
                        $output .= "<div class=\"".$Class."\">\n";
                        if(isset($this->html[$contentIndex])){
                            $output .= $this->html[$contentIndex];
                        }else{
                            $output .= '&nbsp;';
                        }
                        $output .= "</div>\n";
                    }
                    $output .= "</div>\n";
                    $output .= "</div>\n";
                    $lastChar = '';
                    break;
                default:
                    $lastChar .= $char;
                    $contentIndex--;
                    break;
            }
            $contentIndex++;
        }

        // End the last Column if there is one
        if (!empty($lastChar)) {
            $output .= "<div class=\"".$Class."\">\n";
            if(isset($this->html[$contentIndex])){
                $output .= $this->html[$contentIndex];
            }else{
                $output .= '&nbsp;';
            }
            $output .= "</div>\n";

            $output .="</div>\n";
        }

        return $output;
    }

}

?>