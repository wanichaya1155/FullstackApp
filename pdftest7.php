<?php
//============================================================+
// File name   : example_000.php
// Begin       : 2022-09-13
// Last Update : 2022-09-13
//
// Description : Example 000 for TCPDF class
//               Default Header and Footer
//
// Author: Kanapot.com
//
// (c) Copyright: Kanapot.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package 
 * @abstract TCPDF - Example: Table and Thai Font
 * @author Kanapot.com
 * @since 2022-09-13
 */

// Include the main TCPDF library (search for installation path).
require_once('TCPDF-main/tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

define('PROMPT_REGULAR', TCPDF_FONTS::addTTFfont(dirname(__FILE__) . '/fonts/Sarabun-Regular.ttf', 'TrueTypeUnicode'));
define('PROMPT_BOLD', TCPDF_FONTS::addTTFfont(dirname(__FILE__) . '/fonts/Sarabun-Regular.ttf', 'TrueTypeUnicode'));

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Kanapot.com');
$pdf->setTitle('TCPDF Example Thai Font');
$pdf->setSubject('TCPDF Tutorial');
$pdf->setKeywords('TCPDF, PDF, example, Thai Font');

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default header data
$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->setHeaderMargin(PDF_MARGIN_HEADER);
$pdf->setFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
	require_once(dirname(__FILE__) . '/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->setFont(PROMPT_REGULAR, '', 18, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
//$pdf->AddPage();
//$pdf->AddPage('P', 'A4');
//$pdf->Cell(0, 0, 'A4 PORTRAIT', 1, 1, 'C');
$pdf->AddPage('L', 'A4');

// set text shadow effect
//$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print
$html = <<<EOD
<style>
html,
body {
    margin: 0;
    padding: 0;
    height: 100%;
    font-family: "Sarabun", serif;
}

table {
    font-family: "Sarabun";
    margin-left: auto; 
    margin-right: auto;
    border-collapse: collapse;
    width: 95%;
    height: 110%;
    border: 1px solid #ddd;
}

th {
    font-family: "Sarabun";
	font-weight: bold;
    padding: 5px;
    border: 1px solid black;
    border-collapse: collapse;
}

td {
    font-family: "Sarabun";
    padding: 5px;
    border: 1px solid black;
    border-collapse: collapse;
}
</style>
<table>
<tr>
	<th>ที่</th>
	<th>รหัส 10 หลัก</th>
	<th>รหัส smis</th>
	<th>โรงเรียน</th>
	<th>อำเภอ</th>
	<th>ระดับ</th>
	<th>นักเรียน</th>
	<th>ครู</th>
</tr>
<tr>
	<td>1</td>
	<td>1064620359</td>
	<td>64012001</td>
	<td>สุโขทัยวิทยาคม</td>
	<td>เมืองสุโขทัย</td>
	<td>มัธยมศึกษา</td>
	<td>2,663</td>
	<td>121</td>
</tr>
<tr>
	<td>2</td>
	<td>1064620364</td>
	<td>64012002</td>
	<td>บ้านด่านลานหอยวิทยา</td>
	<td>บ้านด่านลานหอย</td>
	<td>มัธยมศึกษา</td>
	<td>1,260</td>
	<td>59</td>
</tr>
<tr>
	<td>3</td>
	<td>1064620366</td>
	<td>64012003</td>
	<td>คีรีมาศพิทยาคม</td>
	<td>คีรีมาศ</td>
	<td>มัธยมศึกษา</td>
	<td>1,175</td>
	<td>54</td>
</tr>
<tr>
	<td>4</td>
	<td>1064620368</td>
	<td>64012004</td>
	<td>กงไกรลาศวิทยา</td>
	<td>กงไกรลาศ</td>
	<td>มัธยมศึกษา</td>
	<td>1,029</td>
	<td>48</td>
</tr>
<tr>
	<td>5</td>
	<td>1064620365</td>
	<td>64012005</td>
	<td>ตลิ่งชันวิทยานุสรณ์</td>
	<td>บ้านด่านลานหอย</td>
	<td>มัธยมศึกษา</td>
	<td>104</td>
	<td>12</td>
</tr>
<tr>
	<td>6</td>
	<td>1064620369</td>
	<td>64012006</td>
	<td>หนองตูมวิทยา</td>
	<td>กงไกรลาศ</td>
	<td>มัธยมศึกษา</td>
	<td>254</td>
	<td>16</td>
</tr>
<tr>
	<td>7</td>
	<td>1064620361</td>
	<td>64012007</td>
	<td>บ้านสวนวิทยาคม</td>
	<td>เมืองสุโขทัย</td>
	<td>มัธยมศึกษา</td>
	<td>283</td>
	<td>18</td>
</tr>
<tr>
	<td>8</td>
	<td>1064620370</td>
	<td>64012008</td>
	<td>ไกรในวิทยาคม</td>
	<td>รัชมังคลาภิเษก</td>
	<td>มัธยมศึกษา</td>
	<td>417</td>
	<td>22</td>
</tr>
</table>
EOD;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('example_000.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
