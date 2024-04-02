<?php
// Include TCPDF library
require_once('TCPDF/tcpdf.php');

// ตัวอย่างการเชื่อมต่อ MySQL
$cx = mysqli_connect("localhost", "root", "", "app");

// ตรวจสอบว่ามีการส่ง orderNo และ custno ผ่าน URL หรือไม่
if(isset($_GET["purchaseNo"]) && isset($_GET["custno"])) {
    $purchaseNo = $_GET["purchaseNo"];
    $custNo = $_GET["custno"];

    // ตัวอย่าง SQL query เพื่อดึงข้อมูลจาก MySQL
    $sql_customer = "SELECT CustNo , CustName , `Address`,Tel FROM customer WHERE CustNo = '$custNo'"; 
    $sql_order = "SELECT
    `o`.`PurchaseNo`,
    `o`.`OrderNo`,
    `o`.`ProductQty`,
    `o`.`ProductNo`,
    `p`.`ProductName`,
    `p`.`PricePerUnit`
    FROM `orders` AS `o`
    INNER JOIN `product` AS `p` ON `o`.`ProductNo` = `p`.`ProductNo`
    WHERE `o`.`PurchaseNo` = '$purchaseNo'";

   

    // ส่งคำสั่ง SQL ไปยัง MySQL
    $result_customer = mysqli_query($cx, $sql_customer);
    $result_order = mysqli_query($cx, $sql_order);



    //echo 

    // ตรวจสอบว่ามีข้อมูลลูกค้าและรายการสินค้าหรือไม่
    if($result_customer && $result_order && mysqli_num_rows($result_customer) > 0 && mysqli_num_rows($result_order) > 0) {
        // สร้าง header ของตาราง
        $header_customer = array('CustNo', 'CustName','Address','Tel');
        $header_order = array('PurchaseNo','OrderNo', 'ProductQty', 'ProductNo', 'ProductName' , 'PricePerUnit','sum');

        // Create a new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Order');
        $pdf->SetSubject('Order PDF');

        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Set font อย
        $fontname = TCPDF_FONTS::addTTFfont("D:/years3/term2/xamp/htdocs/Fullstack/application/app/customer/TCPDF/fonts/TH Sarabun PSK V-1/THSarabun.ttf", 'TrueTypeUnicode', '', 96);
        $pdf->SetFont($fontname, '', 14);

        // Add a page
        $pdf->AddPage();

        // HTML content for the PDF
        $htmlContent = '<h1 style="text-align: center;">ใบสั่งซื้อสินค้า</h1>';

        // Fetch and display customer details
        while ($row = mysqli_fetch_assoc($result_customer)) {
            $htmlContent .= ' <table border="0">
                                <tr>
                                    <td style="vertical-align: top;">
                                        <table border="0">
                                            <tr>
                                                <th style="font-size: 17px;">ร้านค้าผู้ให้บริการ</th>
                                            </tr>
                                            <tr>
                                                <td>บริษัท โอทอปออนไลน์ จำกัด</td>
                                            </tr>
                                            <tr>
                                                <td>1 ซอย ฉลองกรุง 1 แขวงลาดกระบัง เขตลาดกระบัง กรุงเทพมหานคร 10520</td>
                                            </tr>
                                            <tr>
                                                <td>เลขประจำตัวผู้เสียภาษีอากร 1212312121</td>
                                            </tr>
                                            <tr>
                                                <td>ติดต่อ 0922577784</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="padding-left: 60px; vertical-align: top;">
                                        <table border="0">
                                            <tr >
                                                <th style="font-size: 17px;">รายละเอียดลูกค้า</th>
                                            </tr>
                                            <tr>
                                                <th style=" padding-left: 50px">ชื่อลูกค้า  '. $row['CustName'] .'</th>
                                            </tr>
                                            <tr>
                                            <th  style=" padding-left: 50px">หมายเลขโทรศัพท์  ' . $row['Tel'] . '</th>
                                            </tr>
                                            <tr>
                                            <th style=" padding-left: 50px">ที่อยู่  ' . $row['Address'] . '</th>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>';
        }

        // Add order details table
        $htmlContent .= '<p><b>รายละเอียดคำสั่งซื้อ</b></p>';
        $htmlContent .= '<table border="1">
                            <tr style="background-color:rgb(192, 192, 246)">
                                <th style="text-align: center; ">ลำดับที่</th>
                                <th style="text-align: center;">รายการสินค้า</th>
                                <th style="text-align: center;">จำนวน</th>
                                <th style="text-align: center;">ราคาต่อหน่วย</th>
                                <th style="text-align: center;">รวมเงิน</th>
                            </tr>';

        // Fetch and display order details
        $total = 0;
        $itemCount = 0;
        while ($row = mysqli_fetch_assoc($result_order)) {
            $itemCount++;
            $htmlContent .= '<tr>
                                <td style="text-align: center;">' . $itemCount . '</td>
                                <td> ' . $row['ProductName'] . '</td>
                                <td style="text-align: center;">' . $row['ProductQty'] . '</td>
                                <td style="text-align: center;">' . $row['PricePerUnit'] . '</td>';
            $sum = $row['ProductQty'] * $row['PricePerUnit'];
            $htmlContent .= '<td style="text-align: right;">' . number_format($sum, 2) . '</td>
                            </tr>';
            $total += $sum;
        }
        $htmlContent .= '</table>';

        // Display total
        $htmlContent .= '<p style="text-align: right;"><b>รวมทั้งสิ้น:</b> ' . number_format($total, 2) . ' บาท</p>';

        // Write HTML content to PDF
        $pdf->writeHTML($htmlContent, true, false, true, false, '');

        // Close and output PDF document
        $pdf->Output('order.pdf', 'I');

        // Close MySQL connection
        mysqli_close($cx);
    } else {
        echo "ไม่พบข้อมูลลูกค้าหรือรายการสินค้า1";
    }
} else {
    echo "ไม่พบข้อมูลใบสั่งซื้อหรือข้อมูลลูกค้า2";
}
?>
