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
    static $data;

    function Layout() {
        $this->data = array();
    }
    public function setLayout($str) {
        $this->layoutString = $str;
    }
    public function setData($html, $index) {
        $this->data[$index] = $html;
    }
    public function appendData($html, $index) {
        $this->data[$index] = $this->data[$index].$html;
    }
    public function prependData($html, $index) {
        $this->data[$index] = $this->data[$index].$html;
    }


    public function renderLayout() {
        $lastChar = '';
        $output = '';


        if (!empty($_GET['debug'])) {
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
        for ($i = 0; $i <= strlen($this->layoutString); $i++) {

            //// Get the current character
            $char = substr($this->layoutString, $i, 1);
            //echo $char.'-';
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
                    if (!empty($rowStarted)) {
                        echo ']';
                        echo '[';
                        $rowStarted = false;
                    }

                    echo $lastChar;
                    $lastChar = '';
                    break;
                case '|';
                    if (!empty($rowStarted)) {
                        echo ']';
                    }
                    echo '[' . $lastChar;
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
        if (!empty($lastChar)) {
            echo '/' . $lastChar;
            echo ']';
        }

        return $output;
    }

}

?>