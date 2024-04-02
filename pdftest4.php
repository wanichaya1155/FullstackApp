<?php
require_once('TCPDF-main/tcpdf.php');

// Connect to MySQL (replace with your credentials)
$cx = mysqli_connect("localhost", "root", "", "shopping");


// Get order and customer numbers from GET (handle potential errors)
$orderNo = isset($_GET["orderNo"]) ? $_GET["orderNo"] : '';
$custNo = isset($_GET["custno"]) ? $_GET["custno"] : '';

// SQL queries (adjusted to avoid unnecessary JOIN and ensure column compatibility)
$sql_customer = "SELECT CustNo, CustName, `Address`, Tel FROM customer WHERE CustNo = '$custNo'";
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

// Execute queries and fetch results (check for errors)
$result_customer = mysqli_query($cx, $sql_customer);
$result_order = mysqli_query($cx, $sql_order);

// Create headers (consistent naming)
$header_customer = array('Customer Number', 'Customer Name', 'Address', 'Phone Number');
$header_order = array('OrderNo', 'Date', 'Qty', 'ProductNo', 'ProductName', 'Price/Unit', 'Sum');

// Extend TCPDF class
class MYPDF extends TCPDF {
    public function ColoredTableOrder($header, $data) {
        // Color and font settings
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');

        // Header (adjusted column widths)
        $w = array(30, 50, 15, 20, 40, 15, 15); // Widths of columns
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
        while ($row = mysqli_fetch_assoc($data)) {
            $qty = $row['ProductQty'];
            $price = $row['PricePerUnit'];
            $sum = $qty * $price; // Calculate total correctly
            $total += $sum;

            $this->Cell($w[0], 6, $row['OrderNo'], 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 6, $row['Date'], 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, $qty, 'LR', 0, 'R', $fill);
            $this->Cell($w[3], 6, $row['ProductCode'], 'LR', 0, 'R', $fill);
            $this->Cell($w[4], 6, $row['ProductName'], 'LR', 0, 'R', $fill);
            $this->Cell($w[5], 6, number_format($price, 2), 'LR', 0, 'R', $fill);
            $this->Cell($w[5], 6, $sum, 'LR', 0, 'R', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        $this->Cell($w[0], 6, '', 'LR', 0, 'L', $fill);
        $this->Cell($w[1], 6, '', 'LR', 0, 'L', $fill);
        $this->Cell($w[2], 6, '', 'LR', 0, 'R', $fill);
        $this->Cell($w[3], 6, '', 'LR', 0, 'R', $fill);
        $this->Cell($w[4], 6, '', 'LR', 0, 'R', $fill);
        $this->Cell($w[5], 6, '', 'LR', 0, 'R', $fill);
        $this->Cell($w[5], 6, $total, 'LR', 0, 'R', $fill);
        $this->Ln();

        
        $fill = !$fill;
        $this->Cell(array_sum($w), 0, '', 'T');
        //$this->Cell(0, 6, 'Total  '.$total, 0, 1, 'L');
    }

    public function ShowCustomer($header, $data){
        // Color and font settings
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('', 'B');

        // Get customer name
        $row = mysqli_fetch_assoc($data);
        $customer_name = $row['CustName'];
        $customer_address = $row['Address'];
        $customer_tel = $row['Tel'];

        // Display customer name
        $this->Cell(0, 6, 'Name : '.$customer_name, 0, 1, 'L');
        $this->Cell(0, 6, 'Address : '.$customer_address, 0, 1, 'L');
        $this->Cell(0, 6, 'Phone Number : '.$customer_tel, 0, 1, 'L');
    }
}
// สร้าง PDF
$pdf = new MYPDF();

// เริ่มต้นหน้าใหม่
$pdf->AddPage();

// นำข้อมูลไปใช้ในการสร้าง PDF


$pdf->Ln(10); // ขึ้นบรรทัดใหม่ก่อนแสดงข้อมูลสินค้า
$pdf->ShowCustomer($header_customer,$result_customer);
$pdf->ColoredTableOrder($header_order, $result_order);

// ปิดการเชื่อมต่อ MySQL
mysqli_close($cx);

$pdf->Output('customer_list.pdf', 'D');
?>
