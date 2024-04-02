<?php
require_once('TCPDF-main/tcpdf.php');
// ตัวอย่างการเชื่อมต่อ MySQL
$cx = mysqli_connect("localhost", "root", "", "shopping");

// ตัวอย่าง SQL query เพื่อดึงข้อมูลจาก MySQL
$sql = "SELECT CustNo, Custname , FROM customer";

// ส่งคำสั่ง SQL ไปยัง MySQL
$result = mysqli_query($cx, $sql);

// สร้าง header ของตาราง
$header = array('Customer Number', 'Customer Name');

class MYPDF extends TCPDF {
    public function ColoredTable($header,$data) {
       // Colors, line width, and bold font
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');

        // Header
        $w = array(40, 50); // Widths of columns
        $num_headers = count($header);
        for ($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();

        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');

        // Data
        $fill = 0;
        while ($row = mysqli_fetch_assoc($data)) {
            $this->Cell($w[0], 6, $row['CustNo'], 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 6, $row['Custname'], 'LR', 0, 'L', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}


// สร้าง PDF
$pdf = new MYPDF();

// เริ่มต้นหน้าใหม่
$pdf->AddPage();

// นำข้อมูลไปใช้ในการสร้าง PDF
$pdf->ColoredTable($header, $result);

// ปิดการเชื่อมต่อ MySQL
mysqli_close($cx);

// ปิด PDF และแสดงให้ดาวน์โหลดหรือแสดงบนเบราว์เซอร์
$pdf->Output('customer_list.pdf', 'D');
?>
