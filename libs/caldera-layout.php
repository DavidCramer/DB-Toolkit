<?php

/*
 * Caldera Layout Engine
 * Used to build responsive grid layouts
 * Based on PHP Scaffold https://github.com/Desertsnowman/PHP-Scaffold (yes I built it :)
 * 2012 - David Cramer
 */

class dbt_calderaLayout {

    private $layoutString = array();
    private $html = array();
    private $offset = array();
    private $classes = array();
    private $rowClasses = array();
    private $debug = false;
    private $layoutType = false;
    private $rowID = array();
    private $columnID = array();
    private $beforeRows = array();
    private $output = array();

    function __construct($layoutType = 'row-fluid') {
        $this->layoutType = strtolower($layoutType);
    }
    function Layout($layoutType = 'row-fluid'){
        $this->layoutType = strtolower($layoutType);
    }
    public function debug(){
        $this->debug = true;
    }
    public function setLayout($str){
        
        $rows = explode('|', $str);
        foreach($rows as $row=>$cols){
            $cols = explode(':',$cols);
            foreach($cols as $col=>$span){
                $this->output[$row][$col]['span'] = $span;
                $this->output[$row][$col]['html'] = '';
            }            
        }
        //dump($this->output);
        //$this->layoutString = $str;
    }
    public function gethtml($column) {
        if(!empty($this->output[$row][$column]['html'])){
            return $this->output[$row][$column]['html'];
        }else{
            return false;
        }
    }
    public function html($html, $row, $column) {
        $this->output[$row][$column]['html'] = $html;
    }
    public function append($html, $row, $column) {
        
        if(!empty($this->output[$row][$column]['html'])){
            $this->output[$row][$column]['html'] .= $html;
        }else{
            $this->output[$row][$column]['html'] = $html;
        }
    }
    public function prepend($html, $row, $column) {
        if(!empty($this->output[$row][$column]['html'])){
            $this->output[$row][$column]['html'] = $html.$this->output[$row][$column]['html'];
        }else{
            $this->output[$row][$column]['html'] = $html;
        }
    }
    public function setColumnClass($class, $row, $column = 'generic'){
        $this->output[$row][$column]['class'] = $class;
    }
    public function appendColumnClass($class, $row, $column){
        if(!empty($this->output[$row][$column]['class'])){
           $this->output[$row][$column]['class'] = $this->output[$row][$column]['class'].' '.$class;
        }else{
            $this->output[$row][$column]['class'] = $class;
        }
    }
    public function prependColumnClass($class, $row, $column){
        if(!empty($this->output[$row][$column]['class'])){
            $this->output[$row][$column]['class'] = $class.' '.$this->output[$row][$column]['class'];
        }else{
            $this->output[$row][$column]['class'] = $class;
        }
    }
    public function setRowClass($class, $row='generic'){
        $this->output[$row]['class'] = $class;
    }
    public function appendRowClass($class, $row='generic'){
        if(!empty($this->output[$row]['class'])){
            $this->output[$row]['class'] .= ' '.$class;
        }else{
            $this->output[$row]['class'] = $class;
        }
    }
    public function prependRowClass($class, $row){
        if(!empty($this->output[$row]['class'])){
            $this->output[$row]['class'] = $class.' '.$this->output[$row]['class'];
        }else{
            $this->output[$row]['class'] = $class;
        }
    }
    public function offset($column, $offset){
            $this->offset[$column] = 'offset'.$offset;
    }
    public function setRowID($ID, $row, $column){
            $this->rowID[$column] = $ID;
    }
    public function setColumnID($ID, $row, $column){
            $this->output[$row][$column]['id'] = $ID;
    }
    public function appendRow($rowLayout){
        $cols = explode(':', $rowLayout);
        array_push($this->output, $cols);        
    }
    public function prependRow($rowLayout){
        $cols = explode(':', $rowLayout);
        array_unshift($this->output, $cols);        
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

    private function inttotext($int){
        $ints = array(
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve'
        );
        return $ints[$int];
    }
    public function renderLayout($blank = '') {

        if(empty($this->output))
                return 'ERROR: Layout string not set.';
        
        
        $gridClass = 'row-fluid';        
        if(!empty($this->debug)){            
            $gridClass .= ' show-grid';
        }        
        //dump($this->output,0);
        $output = '';
        foreach($this->output as $row=>$cols){
            $rowID = '';
            $rowClass = '';
            $rowBefore = '';
            $rowAfter = '';
            
            if(isset($cols['id'])){
                $rowID = $cols['id'];
                unset($cols['id']);
            }
            if(isset($cols['class'])){
                $rowClass= $cols['class'];
                unset($cols['class']);
            }
            $output .= "<div class=\"".$gridClass." ".$rowClass."\">\n";
                foreach($cols as $col=>$content){
                    //dump($content,0);
                    $output .= "    <div class=\"span".$content['span']."\">\n";
                    $output .= "        ".$content['html']."\n";
                    $output .= "    </div>\n";
                }
            $output .= "</div>\n";
            //dump($cols, 0);
        }


        return $output;
    }

}

?>