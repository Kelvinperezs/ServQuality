<?php
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

require_once('../config/lang/eng.php');
require_once('../tcpdf.php');
class MYPDF extends TCPDF {

	//Page header
	public function Header() {
		// Logo
		//$image_file = K_PATH_IMAGES.'logo_example.jpg';
		//$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('helvetica', 'B', 20);
		// Title
		$this->Cell(0, 25, 'Tope imagen', 0, false, 'C', 0, '', 0, false, 'M', 'M');
	}

	// Page footer
	//public function Footer() {
		// Position at 15 mm from bottom
		//$this->SetY(-15);
		// Set font
		//$this->SetFont('helvetica', 'I', 8);
		// Page number
		//$this->Cell(0, 10, 'Pagina'.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	//}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->setPrintFooter(false);
// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->setPrintFooter(false);
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);	

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 8);

// add a page
$pdf->AddPage('L', 'A4');

$html = '
<table width="100%" border="1" cellspacing="0" cellpadding="4" >
<thead>
<tr bgcolor="#E6E6E6">
<th rowspan="3" width="10%" align="center"><br><br>ESTADO</th>
<th colspan="8" width="75%" align="center">TIPO DE ATENCION MES DE:</th>
<th rowspan="2" width="15%" colspan="2" align="center"><br><br>TOTAL</th>
</tr>
<tr bgcolor="#E6E6E6">
<th colspan="2" align="center">ORIENTACION PSICOLOGICA</th>
<th colspan="2" align="center">ENLACES INTERiNSTITUCIONALES</th>
<th colspan="2" align="center">INFORMACIÓN GÉNERAL</th>
<th colspan="2" align="center">SEGUIMIENTO DE CASOS</th>
</tr>
<tr bgcolor="#E6E6E6">
<th align="center">F</th>
<th align="center">M</th>
<th align="center">F</th>
<th align="center">M</th>
<th align="center">F</th>
<th align="center">M</th>
<th align="center">F</th>
<th align="center">M</th>
<th align="center">F</th>
<th align="center">M</th>
</tr>
<tr >
<td bgcolor="#E6E6E6">AMAZONAS</td>
<td > </td>
<td > </td>
<td > </td>
<td > </td>
<td > </td>
<td > </td>
<td > </td>
<td > </td>
<td > </td>
<td > </td>
</tr>
<tr>
<td bgcolor="#E6E6E6">ANZOATEGUI</td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
</tr>
<tr>
<td bgcolor="#E6E6E6">APURE</td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
</tr>
<tr>
<td bgcolor="#E6E6E6">ARAGUA</td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
</tr>
<tr>
<td bgcolor="#E6E6E6">BARINAS</td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
</tr>
<tr>
<td bgcolor="#E6E6E6">BOLIVAR</td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
</tr>
<tr>
<td bgcolor="#E6E6E6">CARABOBO</td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
</tr>
<tr>
<td bgcolor="#E6E6E6">COJEDES</td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
</tr>
<tr>
<td bgcolor="#E6E6E6">DELTA AMACURO</td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
</tr>
<tr>
<td bgcolor="#E6E6E6">DIST. CAPITAL</td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
</tr>
<tr>
<td bgcolor="#E6E6E6">FALCON</td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
</tr>
<tr>
<td bgcolor="#E6E6E6">GUARICO</td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
</tr>
<tr>
<td bgcolor="#E6E6E6">LARA</td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
</tr>
<tr>
<td bgcolor="#E6E6E6">MERIDA</td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
</tr>
<tr>
<td bgcolor="#E6E6E6">MIRANDA</td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
</tr>
<tr>
<td bgcolor="#E6E6E6">MONAGAS</td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
</tr>
<tr>
<td bgcolor="#E6E6E6">NUEVA ESPARTA</td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
</tr>
<tr>
<td bgcolor="#E6E6E6">PORTUGUESA</td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
</tr>
<tr>
<td bgcolor="#E6E6E6">SUCRE</td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
</tr>
<tr>
<td bgcolor="#E6E6E6">TACHIRA</td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
</tr>
<tr>
<td bgcolor="#E6E6E6">TRUJILLO</td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
</tr>
<tr>
<td bgcolor="#E6E6E6">VARGAS</td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
</tr>
<tr>
<td bgcolor="#E6E6E6">YARACUY</td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
</tr>
<tr>
<td bgcolor="#E6E6E6">ZULIA</td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
</tr>
</thead>
</table>


<table width="100%" border="1" cellspacing="0" cellpadding="4" >
<thead>
<tr bgcolor="#E6E6E6">
<th rowspan="2" width="10%" height="1" align="center">SEXO</th>
<th colspan="15" width="85%" align="center">RANGO DE EDADES DE LAS USUARIAS Y USUARIOS ATENDIDOS</th>
<th rowspan="2" width="5%" align="center">TOTAL</th>
</tr>
<tr bgcolor="#E6E6E6">
<th height="1px" align="center">6-10</th>
<th align="center">11-15</th>
<th align="center">16-20</th>
<th align="center">21-25</th>
<th align="center">26-30</th>
<th align="center">31-35</th>
<th align="center">36-40</th>
<th align="center">41-45</th>
<th align="center">46-50</th>
<th align="center">51-55</th>
<th align="center">56-60</th>
<th align="center">61-65</th>
<th align="center">66-70</th>
<th align="center">71-75</th>
<th align="center">76+</th>

</tr>
<tr >
<td bgcolor="#E6E6E6">MUJERES</td>
<td > </td>
<td > </td>
<td > </td>
<td > </td>
<td > </td>
<td > </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
</tr>
<tr>
<td bgcolor="#E6E6E6">HOMBRES</td>
<td > </td>
<td > </td>
<td > </td>
<td > </td>
<td > </td>
<td > </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
<td> </td>
</tr>
<tr>
<td bgcolor="#E6E6E6">TOTALES</td>
<td bgcolor="#E6E6E6"> </td>
<td bgcolor="#E6E6E6"> </td>
<td bgcolor="#E6E6E6"> </td>
<td bgcolor="#E6E6E6"> </td>
<td bgcolor="#E6E6E6"> </td>
<td bgcolor="#E6E6E6"> </td>
<td bgcolor="#E6E6E6"> </td>
<td bgcolor="#E6E6E6"> </td>
<td bgcolor="#E6E6E6"> </td>
<td bgcolor="#E6E6E6"> </td>
<td bgcolor="#E6E6E6"> </td>
<td bgcolor="#E6E6E6"> </td>
<td bgcolor="#E6E6E6"> </td>
<td bgcolor="#E6E6E6"> </td>
<td bgcolor="#E6E6E6"> </td>
<td bgcolor="#E6E6E6"> </td>
</tr>
</thead>

</table>
'
;
$pdf->writeHTML($html, true, false, true, false, '');
// output the HTML content

//
//// array with names of columns
//$arr_nomes = array(
//    array("ABRANGÃŠNCIA", 40, 59), // array(name, new X, new Y);
//    array("SIGNIFICÃ‚NCIA", 8, 59),
//    array("FÃSICO", 4, 52),
//    array("BIÃ“TICO", 4, 52),
//    array("SOCIOECONÃ”MICO", 4, 52),
//    array("NATUREZA", 4, 52),
//    array("ORIGEM", 4, 52),
//    array("DURAÃ‡ÃƒO", 4, 52),
//    array("hola", 50, 52),
//    array("FREQUÃŠNCIA", 4, 52),
//    array("ESPACIALIZAÃ‡ÃƒO", 4, 52),
//    array("REVERSIBILIDADE", 4, 52),
//    array("MAGNITUDE", 4, 52),
//    array("RELEVÃ‚NCIA", 4, 52)
//);
//
//// num of pages
//$ttPages = $pdf->getNumPages();
//for($i=1; $i<=$ttPages; $i++) {
//    // set page
//    $pdf->setPage($i);
//    // all columns of current page
//    foreach( $arr_nomes as $num => $arrCols ) {
//        $x = $pdf->xywalter[$num][0] + $arrCols[1]; // new X
//        $y = $pdf->xywalter[$num][1] + $arrCols[2]; // new Y
//        $n = $arrCols[0]; // column name
//        // transforme Rotate
//        $pdf->StartTransform();
//        // Rotate 90 degrees counter-clockwise
//        $pdf->Rotate(90, $x, $y);
//        $pdf->Text($x, $y, $n);
//        // Stop Transformation
//        $pdf->StopTransform();
//    }
//}

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_061.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+
