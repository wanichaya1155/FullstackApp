<?php
require_once('TCPDF-main/tcpdf.php');
// ตัวอย่างการเชื่อมต่อ MySQL
$cx = mysqli_connect("localhost", "root", "", "shopping");
$orderNo = $_GET["orderNo"];
$custNo = $_GET["custno"];

// ตัวอย่าง SQL query เพื่อดึงข้อมูลจาก MySQL
$sql_customer = "SELECT CustNo , CustName , `Address`,Tel FROM customer WHERE CustNo = '$custNo'"; 
$sql_order = "SELECT
`o`.`OrderNo`,
`o`.`Date`,
`o`.`ProductQty`,
`o`.`ProductCode`,
`p`.`ProductName`,
`p`.`PricePerUnit`
FROM `order` AS `o`
INNER JOIN `product` AS `p` ON `o`.`ProductCode` = `p`.`ProductCode`
WHERE `o`.`OrderNo` = '$orderNo'";

// ส่งคำสั่ง SQL ไปยัง MySQL
$result_customer = mysqli_query($cx, $sql_customer);
$result_order = mysqli_query($cx, $sql_order);

// สร้าง header ของตาราง
$header_customer = array('CustNo', 'CustName','Address','Tel');

$header_order = array('OrderNo', 'Date', 'ProductQty', 'ProductCode', 'ProductName' , 'PricePerUnit','sum');
class MYPDF extends TCPDF {
  public function ColoredTableOrder($header,$order) {
    // Colors, line width, and bold font
    $this->SetFillColor(255, 0, 0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128, 0, 0);
    $this->SetLineWidth(0.3);
    $this->SetFont('', 'B');

    // Header
    $w = array(40, 50, 30, 30, 30); // Widths of columns
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
    $total = 0;
    while ($row = mysqli_fetch_assoc($order)) {
        //'OrderNo', 'Date', 'ProductQty', 'ProductCode', 'ProductName' , 'PricePerUnit');
      $this->Cell($w[0], 6, $row['OrderNo'], 'LR', 0, 'L', $fill);
      $this->Cell($w[1], 6, $row['Date'], 'LR', 0, 'L', $fill);
      $this->Cell($w[2], 6, $row['ProductQty'], 'LR', 0, 'R', $fill);
      $this->Cell($w[3], 6, $row['ProductCode'], 'LR', 0, 'R', $fill);
      $this->Cell($w[4], 6, $row['ProductName'], 'LR', 0, 'R', $fill);
      $this->Cell($w[5], 6, $row['PricePerUnit'], 'LR', 0, 'R', $fill);
      $sum = number_format($row['ProductQty'] * $row['PricePerUnit'], 2);
      $this->Cell($w[6], 6, $sum, 'LR', 0, 'R', $fill);
      $total += $sum;
      $this->Ln();
      $fill = !$fill;
    }
    // Footer
    $this->Cell(array_sum($w), 0, '', 'T');
  }
}
// สร้าง PDF
$pdf = new MYPDF();

// เริ่มต้นหน้าใหม่
$pdf->AddPage();

// นำข้อมูลไปใช้ในการสร้าง PDF

$pdf->Ln(10); // ขึ้นบรรทัดใหม่ก่อนแสดงข้อมูลสินค้า
$pdf->ColoredTableOrder($header_order, $result_order);

// ปิดการเชื่อมต่อ MySQL
mysqli_close($cx);
?>