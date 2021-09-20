<?php
mysql_connect("localhost","root","123456");
mysql_select_db("practica");
// if(!empty($_POST['dato'])){


//============================================================+
// File name   : example_061.php
// Begin       : 2010-05-24
// Last Update : 2010-08-08
//
// Description : Example 061 for TCPDF class
//               XHTML + CSS
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com s.r.l.
//               Via Della Pace, 11
//               09044 Quartucciu (CA)
//               ITALY
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: XHTML + CSS
 * @author Nicola Asuni
 * @since 2010-05-25
 */

require_once('../tcpdf/config/lang/eng.php');
require_once('../tcpdf/tcpdf.php');

class MYPDF extends TCPDF {
	
public function ColoredTable($header,$data) {
        // Colors, line width and bold font
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');
        // Header
        $w = array(15, 20, 40, 40, 20, 70, 50, 20);
        $num_headers = count($header);
        for($i = 0; $i < $num_headers; ++$i) {  
		$this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = 0;
        foreach($data as $row) {
            $this->Cell($w[0], 4, $row[0], 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 4, $row[1], 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 4, $row[7], 'LR', 0, 'L', $fill);
			/////////////
			$this->Cell($w[3], 4, $row[6], 'LR', 0, 'L', $fill);
			$this->Cell($w[4], 4, $row[10], 'LR', 0, 'L', $fill);			
			$this->Cell($w[5], 4, $row[8], 'LR', 0, 'L', $fill);
			$this->Cell($w[6], 4, $row[9], 'LR', 0, 'L', $fill);
			$this->Cell($w[7], 4, $row[27], 'LR', 0, 'L', $fill);
            $this->Ln();
            $fill=!$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }

	//Page header
	public function Header() {
		// Logo
		$image_file = 'images/ARTISOL.png';
		$this->Image($image_file, 10, 10, 280, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);             
		// Set font
		//$this->SetFont('helvetica', 'B', 20);
		// Title
		//$this->Cell(0, 25, 'Tope imagen', 0, false, 'C', 0, '', 0, false, 'M', 'M');
//                $this->datos_actividad();
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->setPrintFooter(true);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(10, 25, 20, 5);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM-15);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 8);

// add a page
$pdf->AddPage('L', 'A4');

//Column titles
$header = array('No ticket', 'Incidencia', 'Usuario', 'Email', 'Teléfono', 'Caso', 'Oficina', 'Analista');
 
//Data loading

			$sql = "SELECT * FROM 	ost_ticket
					LEFT JOIN ost_staff 
					ON ost_ticket.staff_id=ost_staff.staff_id";
$rs = mysql_query($sql);
if (mysql_num_rows($rs)>0){
    while($rw = mysql_fetch_array($rs)){
        $data[] = $rw;
    }
}
 
// print colored table
$pdf->ColoredTable($header, $data);
 
//$pdf->writeHTML($html, true, false, true, false, '');
// output the HTML content


// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_061.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+

// }else{     
//		header('location:inicio.php');
// }