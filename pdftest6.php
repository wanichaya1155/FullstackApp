<?php

// ติดตั้งฟอนต์ภาษาไทย
require_once('TCPDF-main/tcpdf.php');

// กำหนดค่าฟอนต์
$fontlist = array(
    'Sarabun' => array(
        'normal' => 'Sarabun-Regular.ttf',
        'bold' => 'Sarabun-Bold.ttf',
        'italic' => 'Sarabun-Italic.ttf',
        'bolditalic' => 'Sarabun-BoldItalic.ttf'
    ),
);

// ตั้งค่าการเข้ารหัส
header('Content-Type: text/html; charset=UTF-8');

// เขียนข้อความภาษาไทย
$pdf = new TCPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->writeHTML('<p>สวัสดีครับ</p>');
$pdf->Output();

?>
