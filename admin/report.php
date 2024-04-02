<?php
// Include TCPDF library
require_once('D:/xampp/htdocs/full/project/TCPDF/tcpdf.php');

// ตัวอย่างการเชื่อมต่อ MySQL
$cx = mysqli_connect("localhost", "root", "", "app");

if(isset($_GET["startDate"]) && isset($_GET["endDate"])) {
    $startDate = mysqli_real_escape_string($cx, $_GET["startDate"]);
    $endDate = mysqli_real_escape_string($cx, $_GET["endDate"]);

    $sql_order = "SELECT
        `o`.`OrderNo`,
        `ps`.`Date`,
        `o`.`ProductQty`,
        `o`.`ProductCode`,
        `p`.`ProductName`,
        `p`.`PricePerUnit`
    FROM `orders` AS `o`
    INNER JOIN `product` AS `p` ON `o`.`ProductCode` = `p`.`ProductCode` 
    INNER JOIN purchase AS ps ON `o`.`PurchaseID` = `ps`.`PurchaseID`
    WHERE `ps`.`Date` BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59'";
    // -- WHERE `o`.`Date` BETWEEN '$startDate' AND '$endDate'";

    
    // ส่งคำสั่ง SQL ไปยัง MySQL
    $result_order = mysqli_query($cx, $sql_order);
    
    // สร้าง header ของตาราง
    $header_order = array('OrderNo', 'Date', 'ProductQty', 'ProductCode', 'ProductName' , 'PricePerUnit','sum');
    // ตรวจสอบว่ามีข้อมูลลูกค้าและรายการสินค้าหรือไม่
    if(mysqli_num_rows($result_order) > 0) {
        // สร้าง header ของตาราง
        $header_order = array('OrderNo', 'Date', 'ProductQty', 'ProductCode', 'ProductName' , 'PricePerUnit','sum');

        // Create a new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Report');
        $pdf->SetSubject('Report PDF');

        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Set font
        $fontname = TCPDF_FONTS::addTTFfont("D:/xampp/htdocs/full/project/customer/TCPDF/fonts/TH Sarabun PSK V-1/THSarabun.ttf", 'TrueTypeUnicode', '', 96);
        $pdf->SetFont($fontname, '', 14);

        // Add a page
        $pdf->AddPage();

       


        // HTML content for the PDF
        $htmlContent = '<style>
                    h1, h3 {
                        text-align: center;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 20px;
                    }
                    th, td {
                        //border: 1px solid #dddddd;
                        text-align: left;
                        padding: 8px;
                    }
                    th {
                        background-color: #f2f2f2;
                    }
                    p {
                        margin-top: 10px;
                    }
                </style>';
        $htmlContent .= '<h1 style="text-align: center;">รายงานยอดขาย</h1>';
        $htmlContent .= '<h3 style="text-align: center;">ประจำวันที่ ' . $startDate . ' ถึง ' . $endDate . '</h3>';

        date_default_timezone_set('Asia/Bangkok');
        $htmlContent .= '<table border="0">';
        $htmlContent .= '<tr><td>บริษัท โอทอปออนไลน์ จำกัด</td></tr>';
        $htmlContent .= '<tr><td>พิมพ์เมื่อวันที่ ' . date("d/m/Y") . '</td></tr>';

        // Add order details table
        $htmlContent .= '<tr><td>รายละเอียด</td></tr>';
        $htmlContent .= '</table>';


        $htmlContent .= '<table border="1">
                            <tr style="background-color:rgb(192, 192, 246)">
                                <th>ลำดับที่คำสั่งซื้อ</th>
                                <th>รายการสินค้า</th>
                                <th>จำนวน</th>
                                <th>ราคาต่อหน่วย</th>
                                <th>รวมเงิน</th>
                            </tr>';

        // Fetch and display order details
        $total = 0;
        $itemCount = 0;
        while ($row = mysqli_fetch_assoc($result_order)) {
            $itemCount++;
            $htmlContent .= '<tr>
                                <td>' . $row['OrderNo']. '</td>
                                <td>' . $row['ProductName'] . '</td>
                                <td>' . $row['ProductQty'] . '</td>
                                <td>' . $row['PricePerUnit'] . '</td>';
            $sum = $row['ProductQty'] * $row['PricePerUnit'];
            $htmlContent .= '<td>' . number_format($sum, 2) . '</td>
                            </tr>';
            $total += $sum;
        }
        $htmlContent .= '</table>';

        // Display total
        $htmlContent .= '<p><b>รวมยอดขายทั้งสิ้น:</b> ' . number_format($total, 2) . ' บาท</p>';


        $htmlContent .= '<p><b>สรุปรายการขาย:</b></p>';
        $sql_product = "SELECT p.`ProductCode`, p.`ProductName`, SUM(o.`ProductQty`) AS `TotalProductQty`
        FROM `product` AS p INNER JOIN `order` AS o ON p.`ProductCode` = o.`ProductCode`
        GROUP BY p.`ProductCode`;";


        $htmlContent .= '<table border="1">
        <tr style="background-color:rgb(192, 192, 246)">
            <th style="text-align: center;">รหัสสินค้า</th>
            <th style="text-align: center;">ชื่อสินค้า</th>
            <th style="text-align: center;">จำนวนที่ขายได้</th>
        </tr>';
        $result_product = mysqli_query($cx, $sql_product);
        while ($row = mysqli_fetch_assoc($result_product)){
        $htmlContent .= '<tr>
            <td>' . $row['ProductCode']. '</td>
            <td>' . $row['ProductName'] . '</td>
            <td>' . $row['TotalProductQty'] . '</td>
        </tr>';
        }
        $htmlContent .= '</table>';

        // Write HTML content to PDF
        $pdf->writeHTML($htmlContent, true, false, true, false, '');

        // Close and output PDF document
        $pdf->Output('report'.$startDate.'_'.$endDate.'.pdf', 'I');

        // Close MySQL connection
        mysqli_close($cx);
    } else {
        echo "ไม่พบข้อมูลรายการสินค้าในช่วงเวลาดังที่เลือก";
    }
} else {
    echo "คุณยังไม่ได้เลือกวันที่";
}
?>
