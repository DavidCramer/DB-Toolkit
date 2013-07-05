<?php

/*
 * Grid Layout Generator from a layout string
 * 2012 - David Cramer
 */

class Layout {

    private $layoutString = array();
    private $html = array();
    private $offset = array();
    private $classes = array();
    private $rowClasses = array();
    private $debug = false;
    private $layoutType = 'row';
    private $rowID = array();
    private $columnID = array();
    private $beforeRows = array();

    function Layout($layoutType = 'fixed') {
        if(strtolower($layoutType) == 'fluid'){
            $this->layoutType = 'row-fluid';
        }
    }
    public function debug(){
        $this->debug = true;
    }
    public function setLayout($str) {
        $this->layoutString = $str;
    }
    public function gethtml($column) {
        if(!empty($this->html[$column])){
            return $this->html[$column];
        }else{
            return false;
        }
    }
    public function html($html, $column) {
        $this->html[$column] = $html;
    }
    public function append($html, $column) {
        if(!empty($this->html[$column])){
            $this->html[$column] = $this->html[$column].$html;
        }else{
            $this->html[$column] = $html;
        }
    }
    public function prepend($html, $column) {
        if(!empty($this->html[$column])){
            $this->html[$column] = $html.$this->html[$column];
        }else{
            $this->html[$column] = $html;
        }
    }
    public function setColumnClass($class, $column){
        $this->classes[$column] = $class;
    }
    public function appendColumnClass($class, $column){
        if(!empty($this->classes[$column])){
            $this->classes[$column] = $this->classes[$column].' '.$class;
        }else{
            $this->classes[$column] = $class;
        }
    }
    public function prependColumnClass($class, $column){
        if(!empty($this->classes[$column])){
            $this->classes[$column] = $class.' '.$this->classes[$column];
        }else{
            $this->classes[$column] = $class;
        }
    }
    public function setRowClass($class, $row){
        $this->rowClasses[$row] = $class;
    }
    public function appendRowClass($class, $row){
        if(!empty($this->rowClasses[$row])){
            $this->rowClasses[$row] = $this->rowClasses[$row].' '.$class;
        }else{
            $this->rowClasses[$row] = $class;
        }
    }
    public function prependRowClass($class, $row){
        if(!empty($this->rowClasses[$row])){
            $this->rowClasses[$row] = $class.' '.$this->rowClasses[$row];
        }else{
            $this->rowClasses[$row] = $class;
        }
    }
    public function offset($column, $offset){
            $this->offset[$column] = 'offset'.$offset;
    }
    public function setRowID($ID, $column){
            $this->rowID[$column] = $ID;
    }
    public function setColumnID($ID, $column){
            $this->columnID[$column] = $ID;
    }
    public function appendRow($rowLayout){
        if(!empty($this->layoutString)){
            $this->layoutString = $this->layoutString.'|'.$rowLayout;
        }else{
            $this->layoutString = $rowLayout;
        }
    }
    public function prependRow($rowLayout){
        if(!empty($this->layoutString)){
            $this->layoutString = $rowLayout.'|'.$this->layoutString;
        }else{
            $this->layoutString = $rowLayout;
        }
    }
    public function appendBeforeRow($html, $row){
        if(!empty($this->beforeRows[$row])){
            $this->beforeRows[$row] = $this->beforeRows[$row].$html;
        }else{
            $this->beforeRows[$row] = $html;
        }
    }
    public function prependBeforeRow($html, $row){
        if(!empty($this->beforeRows[$row])){
            $this->beforeRows[$row] = $html.$this->beforeRows[$row];
        }else{
            $this->beforeRows[$row] = $html;
        }

    }
    public function htmlBeforeRow($html,$row){
        $this->beforeRows[$row] = $html;
    }
    public function appendAfterRow($html, $row){
        if(!empty($this->afterRows[$row])){
            $this->afterRows[$row] = $this->afterRows[$row].$html;
        }else{
            $this->afterRows[$row] = $html;
        }
    }
    public function prependAfterRow($html,$row){
        if(!empty($this->afterRows[$row])){
            $this->afterRows[$row] = $html.$this->afterRows[$row];
        }else{
            $this->afterRows[$row] = $html;
        }
    }
    public function htmlAfterRow($html,$row){
        $this->afterRows[$row] = $html;
    }


    public function renderLayout() {

        if(empty($this->layoutString))
                return 'ERROR: Layout string not set.';

        $lastChar = '';
        //start row
        $debugclass = '';
        $rowClass = '';
        if(!empty($this->debug)){
            $debugclass = '  show-grid';
        }        
        $contentIndex = 1;
        $rowIndex = 1;
        $rowID = '';
        $output = '';
        if(isset($this->rowID[$rowIndex])){
            $rowID = ' id="'.$this->rowID[$rowIndex].'"';
        }
        if(isset($this->rowClasses[$rowIndex])){
            $rowClass = ' '.$this->rowClasses[$rowIndex];
        }
        if(!empty($this->beforeRows[$rowIndex])){
            $output .= $this->beforeRows[$rowIndex];
        }
        $output .= "<div".$rowID." class=\"".$this->layoutType.$debugclass.$rowClass."\">\n";

        $rowIndex++;
        
        for ($i = 0; $i <= strlen($this->layoutString); $i++) {

            //// Get the current character
            $char = substr($this->layoutString, $i, 1);
            //$output .=$char.'-';
            if (!empty($lastChar)) {
                $Class = "span". $lastChar;
                if(isset($this->offset[$contentIndex])){
                    $Class .= ' '.$this->offset[$contentIndex];
                }
                $addClass = '';
                if(isset($this->classes[$contentIndex])){
                    $addClass = ' '.$this->classes[$contentIndex];
                }
                $Class .= $addClass;
            }
            $columnID = '';
            $rowID = '';
            $rowClass = '';
            if(isset($this->rowID[$rowIndex])){
                $rowID = ' id="'.$this->rowID[$rowIndex].'"';
                //$rowIndex++;
            }
            $helper = '';
            if(!empty($this->debug)){
                $helper = "title=\"Column ".$contentIndex."\"";
            }
            switch ($char) {
                case ':';
                    if(!empty($lastChar)){
                        $columnID = '';
                        if(isset($this->columnID[$contentIndex])){
                            $columnID = ' id="'.$this->columnID[$contentIndex].'"';
                        }
                        $output .= "<div".$columnID." class=\"".$Class."\" ".$helper.">\n";
                        if(isset($this->html[$contentIndex])){
                            $output .= $this->html[$contentIndex];
                        }
                        $output .= "</div>\n";
                    }
                    $lastChar = '';
                    $openCol = true;
                    break;
                case '|';
                    if(!empty($lastChar)){
                        $columnID = '';
                        if(isset($this->columnID[$contentIndex])){
                            $columnID = ' id="'.$this->columnID[$contentIndex].'"';
                        }
                        $output .= "<div".$columnID." class=\"".$Class."\" ".$helper.">\n";
                        if(isset($this->html[$contentIndex])){
                            $output .= $this->html[$contentIndex];
                        }
                        $output .= "</div>\n";
                    }
                    if(isset($this->rowClasses[$rowIndex])){
                        $rowClass = ' '.$this->rowClasses[$rowIndex];
                    }
                    $output .= "</div>\n";
                    if(!empty($this->afterRows[$rowIndex-1])){
                        $output .= $this->afterRows[$rowIndex-1];
                    }
                    if(!empty($this->beforeRows[$rowIndex])){
                        $output .= $this->beforeRows[$rowIndex];
                    }
                    $output .= "<div".$rowID." class=\"".$this->layoutType.$debugclass.$rowClass."\">\n";
                    $rowIndex++;
                    $lastChar = '';
                    break;
                case '[';
                        $columnID = '';
                        if(isset($this->columnID[$contentIndex])){
                            $columnID = ' id="'.$this->columnID[$contentIndex];
                        }
                    $output .= "<div".$columnID." class=\"".$Class."\" ".$helper.">\n";
                    if(isset($this->rowClasses[$rowIndex])){
                        $rowClass = ' '.$this->rowClasses[$rowIndex].'"';
                    }
                    $output .= "<div".$rowID." class=\"".$this->layoutType.$debugclass.$rowClass."\">\n";
                    $lastChar = '';
                    $contentIndex--;
                    $rowIndex++;
                    break;
                case ']';
                    if(!empty($lastChar)){
                        $columnID = '';
                        if(isset($this->columnID[$contentIndex])){
                            $columnID = ' id="'.$this->columnID[$contentIndex].'"';
                        }
                        $output .= "<div".$columnID." class=\"".$Class."\" ".$helper.">\n";
                        if(isset($this->html[$contentIndex])){
                            $output .= $this->html[$contentIndex];
                        }
                        $output .= "</div>\n";
                    }
                    $output .= "</div>\n";
                    $output .= "</div>\n";
                    $lastChar = '';
                    $contentIndex--;
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
            $columnID = '';
            if(isset($this->columnID[$contentIndex])){
                $columnID = ' id="'.$this->columnID[$contentIndex].'"';
            }
            $output .= "<div".$columnID." class=\"".$Class."\" ".$helper.">\n";
            if(isset($this->html[$contentIndex])){
                $output .= $this->html[$contentIndex];
            }
            $output .= "</div>\n";

            $output .="</div>\n";
            if(!empty($this->afterRows[$rowIndex-1])){
                $output .= $this->afterRows[$rowIndex-1];
            }

        }

        return $output;
    }

}

?>