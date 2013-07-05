<?php

interface ReportOutPut
{
    //public function setVariable($name, $var);
   // public function getHtml($template);
	 public function cf_report_title($string);
	 public function cf_report_header($string, $size=12);
	 public function cf_report_footer($string);
	 public function cf_report_summary($string);
	 public function cf_report_image($source, $caption=null);
	 public function cf_report_datagrid($data, $size=8);
	 public function cf_report_data_col_grid($headers, $data, $OutData, $Config);
	 public function cf_report_spacer($string=null);
	 public function cf_report_generate_output();

}

class PDF extends FPDF {
	var $headertext;
	var $logo;
	//Page header
	function Header($or){            
		if($or == 'L'){
                    $this->Image(WP_PLUGIN_DIR.'/db-toolkit/images/pdf_l.jpg',0,0, 297);
		}else{
                    //$this->SetFont('helvetica','',12);
                    $this->Image(WP_PLUGIN_DIR.'/db-toolkit/images/pdf_p.jpg',0,0, 210);
		}
                
		$this->SetFont('helvetica','B',15);
		$this->Ln(20);
	}

	//Page footer
	function Footer(){
		//Position at 1.5 cm from bottom
		$this->SetY(-15);
		//Arial italic 8
		$this->SetFont('helvetica','i', 7);
		//Page number
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
                $this->SetFillColor(50,50,50);
                $this->Ln(9);
                $this->SetTextColor(255,255,255);
                $this->SetFont('helvetica','i', 5);
                $this->SetX(0);
                $this->Cell(1000,6.5, 'Powered by DB-Toolkit',0,7,'L',true, 'http://dbtoolkit.digilab.co.za?pdfexport=link');
                //$this->Cell($w, $h, $txt, $border, $ln, $align, $fill)
	}
}

class PDFReport implements ReportOutPut {
	private $pdf;
	private $y;
	private $orentation;
	function PDFReport($Or, $Title){
        $user = wp_get_current_user();
        
		$this->orentation = $Or;
		$this->pdf=new PDF($Or);
		$this->pdf->SetCreator(get_bloginfo());
		$this->pdf->SetProducer('DB Toolkit');

		$this->pdf->SetTitle($Title);

		$this->pdf->SetAuthor($user->user_nicename);
		

		$this->pdf->headertext = $string;
		$this->pdf->logo = $logo;
		$this->pdf->AliasNbPages();
		$this->pdf->AddPage($Or);
		$this->pdf->SetFont('helvetica','',10);
		$this->pdf->SetMargins(0, 25, 10);
		$this->pdf->SetAutoPageBreak(false, 35);
	}

        function addPage(){
            //$this->addPage();
            $this->pdf->AddPage($this->orentation);
        }

	function Header($or){
		$this->pdf->SetFont('helvetica','B',15);
		$l = $this->pdf->GetStringWidth($this->companyname);
		$x = (125) - ($l / 2);
		$this->pdf->Text($x, 33, $this->companyname);
		$this->Ln(20);
	}

	function cf_shop_name(){

		$this->pdf->Ln(1);
                $this->pdf->SetFont('helvetica','B',14);
                $this->pdf->SetTextColor(50, 50, 50);
		$this->pdf->Text(8, 16, get_bloginfo());
                $this->pdf->SetFont('helvetica','',8);
                $this->pdf->SetTextColor(90, 90, 90);
		$this->pdf->Text(8, 19, get_bloginfo('description'));
		$this->pdf->y = 29;
	}
	function cf_report_title($string){
		$this->pdf->SetFont('helvetica','B',10);
		$this->pdf->SetTextColor(50, 50, 50);

		$this->pdf->Ln(5);
		$this->pdf->Text(10, $this->pdf->GetY(), $string);
		//Line break
		$this->pdf->Ln(4);
		//$this->y += 30;
	}

	function cf_report_header($string, $size=12){
		$this->pdf->SetFont('helvetica','B',$size);
		$this->pdf->SetTextColor(150, 150, 150);
		$this->pdf->Text(10, $this->pdf->GetY(), $string);
	}

	function cf_report_footer($string){
            //Go to 1.5 cm from bottom
            $this->pdf->SetY(-15);
            //Select Arial italic 8
            $this->pdf->SetFont('helvetica','I',8);
            //Print centered page number
            $this->pdf->Cell(0,10,$string,0,0,'C');
	}

	function cf_report_summary($string){
		$this->pdf->SetFont('helvetica','',10);
		$this->pdf->SetTextColor(50, 50, 50);
		$this->pdf->SetX(20);
		$this->pdf->Write(5, $string);
		//$this->pdf->Text(10, $this->pdf->GetY(), $string);
		//Line break
		//$this->pdf->Ln(20);
	}

	function cf_report_image($source, $caption=null){
		$size = getimagesize($source);
		$ratio = $size[0] / 170;
		$height = $size[1] / $ratio;
		$this->pdf->Image($source,20,$this->pdf->GetY(), 170);
		$this->pdf->SetY($this->pdf->GetY()+$height);
	}
	function cf_report_chart($file){

            $size = getimagesize($file);
            $height = $size[1];

            if($this->orentation == 'P'){
                $this->pdf->Image($file,10,$height,210);
            }else{
                $this->pdf->Image($file,10,$height,210);
            }

            $this->pdf->SetY($this->pdf->GetY()+$height);

        }
	function cf_report_datagrid($data, $size = 8){

		$this->pdf->Cell(10);
		$this->pdf->SetFont('helvetica','',$size);
		$this->pdf->SetTextColor(50, 50, 50);
		$data = is_array($data) ? $data : array();
                $starty = $this->pdf->GetY();
                $MaxWidth = 50;
		foreach ($data as $key=>$value) {
                        $this->pdf->SetFont('helvetica','B',$size);
			$y = $this->pdf->GetY();
                        //$this->pdf->SetTextColor(227, 6, 19);
			$this->pdf->Text(10, $y, $key);
                        //$X = $this->pdf->GetX();
                        $X = $this->pdf->GetStringWidth($key);
                        if($X > $MaxWidth)
                            $MaxWidth = $X;
			$this->pdf->Ln(3);
			//$this->pdf->Cell(10);
		}
                $this->pdf->SetY($starty);
		foreach ($data as $key=>$value) {
                        $this->pdf->SetFont('helvetica','',$size);
			$y = $this->pdf->GetY();
                        //$this->pdf->SetTextColor(50, 50, 50);
			$this->pdf->Text($MaxWidth, $y, $value);
			$this->pdf->Ln(3);
			//$this->pdf->Cell(10);
		}
		//$this->pdf->Ln(20);
	}

        function cf_report_headersMain($OutData, $Config){
            // START HEADERS

            $this->cf_shop_name();
            $this->cf_report_header(date("jS F Y"), 8);
            $this->cf_report_title($Config['_ReportTitle']);

                //vardump($OutData['filters']);
                //vardump($Config['_Keyword_Title']);

                //dump($_SESSION['reportFilters']);
                if(!empty($OutData['filters'])) {
                    $sum = 'filtered: ';
                    $this->cf_report_header('', 9);
                    if(!empty($OutData['filters']['EID'])){
                        unset($OutData['filters']['EID']);
                    }
                    foreach($OutData['filters'] as $Field=>$Value) {
                        if(!empty ($Value)){
                            if($Field == '_keywords'){
                                $Field = $Config['_Keyword_Title'];
                            }
                            //echo $Field.' - '.$Value.'<br>';

                            if(!empty($Config['_FieldTitle'][$Field])) {
                                $FieldTitle = $Config['_FieldTitle'][$Field];
                            }else {
                                $FieldTitle = $Field;
                            }

                            $fieldset = array();
                            $index=0;
                            if(is_array($Value)){
                                foreach($Value as $fil) {
                                    $fieldset[] = $fil;
                                    $index++;
                                }
                                sort($fieldset);
                                if(strpos($Config['_Field'][$Field], 'date_') !== false) {
                                    $filterData[$FieldTitle] = $fieldset[0].' to '.$fieldset[count($fieldset)-1];
                                }else {
                                    $filterData[$FieldTitle] = implode(', ',$fieldset);
                                }
                            }else{
                                $filterData[$FieldTitle] = $Value;
                            }
                        }
                    }
                    $this->cf_report_datagrid($filterData, 6.5);
                    unset($OutData['filters']);
                }
                // END HEADERS
                $this->pdf->SetY(50);
        }

	function cf_report_data_col_grid($headers, $data, $OutData, $Config){
                //dump($headers);
                //Colors, line width and bold font
                if(empty($data)){
                    $this->pdf->SetFont('helvetica','i', 8);
                    $this->pdf->text(10, 65, $Config['_NoResultsText']);
                    return;
                }
                $this->pdf->SetFillColor(20,20,20);
                $this->pdf->SetTextColor(255);
                $this->pdf->SetDrawColor(20,20,20);
                //$this->pdf->SetLineWidth(10);
                $this->pdf->SetFont('','', 6);
                //Header
                //Max Withs
                $this->pdf->SetLeftMargin(10);
                $w=array();//40,35,40,45);
                
                foreach($data as $row){
                    foreach($row as $key=>$col){
                        $col = strip_tags(htmlspecialchars_decode($col));
                        $pw = $this->pdf->GetStringWidth($col);
                        //if($pw > $w[$key]){
                        $w[$key] = $pw;
                        //}
                    }
                }
                //vardump($data);
                foreach($w as $pkey=>$p){
                    $totalrows=0;
                    foreach($data as $predata){
                        if(!empty($predata[$pkey])){
                            $totalrows++;
                        }
                    }

                    if($totalrows < 20){
                        $totalrows = 20;
                    }

                    $width = ceil($p/$totalrows);
                    $headerWidth = $this->pdf->GetStringWidth($headers[$pkey]);
                    if($width < $headerWidth){
                        $widths[] = $headerWidth+5;
                    }else{
                        $widths[] = $width;
                    }
                }

                
                //my widths calculator
                if($this->orentation == 'P'){
                    $Max = 190;
                }else{
                    $Max = 277;
                }

                $total = array_sum($widths);
                $diff = $Max-$total;
                $toAdd = $diff/count($widths);
                $newWidths = array();
                foreach($widths as $width){
                    $newWidths[] = round($width+$toAdd, 2);
                }
                //vardump($widths);
                $widths = array();
                $widths = $newWidths;
                $this->pdf->SetFillColor(242,242,242);
                $this->pdf->SetTextColor(0);
                $this->pdf->SetFont('');
                $fill=false;
                $rows = 0;
                $runheaders = true;
                $firstrun = true;
                $maxStuff = 184;
                if($this->orentation == 'P'){
                    $maxStuff = 274;
                }

                foreach($data as $row){
                    // place headers
                    if($runheaders == true){
                        if(empty($firstrun)){
                            $this->cf_report_headersMain($OutData, $Config);
                            $this->pdf->SetY(60);
                        }
                        $this->pdf->SetFont('','', 6);
                        $this->pdf->SetFillColor(20,20,20);
                        $this->pdf->SetTextColor(255);
                        for($i=0;$i<count($headers);$i++){
                            $this->pdf->Cell($widths[$i],7,$headers[$i],1,0,'C',true);
                        }
                        $this->pdf->Ln();
                        $this->pdf->SetFillColor(242,242,242);
                        $this->pdf->SetTextColor(0);
                        $runheaders = false;
                        $firstrun = false;
                    }
                    foreach($row as $key=>$col){
                        if($fill){
                            $this->pdf->SetFillColor(242,242,242);
                           // $this->pdf->SetDrawColor(224,235,255);
                        }else{
                            $this->pdf->SetFillColor(255,255,255);
                           // $this->pdf->SetDrawColor(255,255,255);
                        }

                        $col = strip_tags(htmlspecialchars_decode($col));
                        $this->pdf->Cell($widths[$key],6,$col,'LR',0,'L',true);
                    }
                    $this->pdf->Ln();

                    $rows++;
                    if($this->pdf->GetY() >= $maxStuff){
                        $this->pdf->SetFillColor(20,20,20);
                        foreach($row as $key=>$col){
                            $this->pdf->Cell($widths[$key],0.01,'','LR',0,'L',true);
                        }
                        $this->pdf->AddPage($this->orentation);

                        $runheaders = true;
                        $rows = 0;
                    }
                    $fill=!$fill;
                }
                $this->pdf->SetFillColor(20,20,20);
                    foreach($row as $key=>$col){
                    $this->pdf->Cell($widths[$key],0.1,'','LR',0,'L',true);
                }

                //$this->pdf->Cell(array_sum($w),0,'','T');

	}

	function cf_report_spacer($string=null){
		$this->pdf->Ln(10);
	}

	function cf_report_generate_output(){
		$this->pdf->Output();
	}

	function cf_report_return_pdf(){
		$rand = rand(10, 100000);
		$name = "reports/report_$rand.pdf";
		$this->pdf->Output($name, "F");
		return $name;
	}
}


?>
