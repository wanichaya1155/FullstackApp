<?php
    $cx = mysqli_connect("localhost","root","","app");

    if(isset($_GET['purchaseNo'])){
        $purchaseNo = $_GET['purchaseNo'];
    }else {
        echo "No get paramitor.";
    }
    $custNo = $_GET['custno'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Order</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .header_invoice th, .header_invoice td {
            border: none;
            background-color: transparent;
        }

        .header_invoice table {
            width: 100%;
        }

        .header_invoice .left-col {
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }

        .header_invoice .right-col {
            width: 50%;
            vertical-align: top;
            padding-left: 20px;
        }

        .header_invoice .address {
            font-size: 14px;
            line-height: 1.6;
        }

        .total-row td:last-child {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php

    $sql_customer = "SELECT CustNo , CustName , Sex,`Address`,Tel FROM customer WHERE CustNo = '$custNo'"; 

    $invoice_sql = "SELECT 
        inv.InvoiceNo AS InvoiceNo,
        inv.CustNo AS CustNo,
        inv.Date AS `Date`,
        inv.Status AS `Status`,
        inv.Total AS Total,
        pd.ProductNo AS ProductNo,
        pd.ProductName AS ProductName,
        pd.PricePerUnit AS PricePerUnit,
        invd.ProductQty AS ProductQty
        FROM 
            invoice inv
        JOIN 
            invoice_detail invd ON inv.InvoiceNo = invd.InvoiceNo
        JOIN 
            product pd ON invd.ProductNo = pd.ProductNo
        WHERE 
            inv.PurchaseNo = $purchaseNo;";


    $result_invoice = mysqli_query($cx, $invoice_sql);
    $result_customer = mysqli_query($cx, $sql_customer);

    // if ($result_customer) {
    //     $row = mysqli_fetch_assoc($result_customer);
    //     $custName = $row['CustName'];
    //     if (mysqli_num_rows($result_customer) > 0) {
    //         echo "purchaseNo : $purchaseNo - CUSTOMER IS: $custName ";
    //         $customer = mysqli_fetch_assoc($result_customer);
    //         // echo "ชื่อ ".$customer["CustName"]." <br>เพศ ".$customer["Sex"]." <br>ที่อยู่ ".$customer["Address"]." <br>เบอร์โทร ".$customer["Tel"]." <br>";
    //         // ใช้ print_r เพื่อแสดงโครงสร้างของข้อมูลลูกค้า
            
    //         //print_r($customer);
    //     } else {
    //         echo "Customer not found.";
    //     }
    // } else {
    //     echo "Error executing query: " . mysqli_error($cx);
    // }
?>
    <div class="container">
        <h1>ใบแจ้งหนี้</h1>
        <?php
            // Fetch and display customer details
            if ($result_customer && mysqli_num_rows($result_customer) > 0) {
                $row = mysqli_fetch_assoc($result_customer);
        ?>
        <div class="header_invoice">
            <table>
                <tr>
                    <td class="left-col">
                        <h2>ร้านค้าผู้ให้บริการ</h2>
                        <div class="address">
                            <p>บริษัท โอทอปออนไลน์ จำกัด</p>
                            <p>1 ซอย ฉลองกรุง 1 แขวงลาดกระบัง เขตลาดกระบัง กรุงเทพมหานคร 10520</p>
                            <p>เลขประจำตัวผู้เสียภาษีอากร 1212312121</p>
                            <p>ติดต่อ 0922577784</p>
                        </div>
                    </td>
                    <td class="right-col">
                        <h2>รายละเอียดลูกค้า</h2>
                        <div class="address">
                            <p>ชื่อลูกค้า: <?php echo $row['CustName']; ?></p>
                            <p>หมายเลขโทรศัพท์: <?php echo $row['Tel']; ?></p>
                            <p>ที่อยู่: <?php echo $row['Address']; ?></p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <?php
            } else {
                echo "<p>Customer not found.</p>";
            }
        ?>
        <table>
            <tr>
                <th>รหัสสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th>ราคาต่อหน่วย</th>
                <th>จำนวน</th>
                <th>รวม</th>
            </tr>
            <?php
                $sum = 0;
                while ($row = mysqli_fetch_assoc($result_invoice)) {
                    $productNo = $row['ProductNo'];
                    $qty = $row['ProductQty'];
                    $price = $row['PricePerUnit'];
                    $result = $qty * $price;
                    $sum += $result;
            ?>
            <tr>
                <td><?php echo $productNo; ?></td>
                <td><?php echo $row['ProductName']; ?></td>
                <td><?php echo $price; ?></td>
                <td><?php echo $qty; ?></td>
                <td><?php echo $result; ?></td>
            </tr>
            <?php
                }
                mysqli_close($cx);
            ?>
            <tr class="total-row">
                <td colspan="4">รวมทั้งสิ้น</td>
                <td><?php echo $sum; ?></td>
            </tr>
        </table>
    </div>

    <div class="button_con">
            <button  >
    </div>
</body>
</html>
