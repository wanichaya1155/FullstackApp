<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Order</title>
    <link rel="stylesheet" href="navbar.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        .top {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            background-color: #ddd;
            width: 100%;
            box-sizing: border-box;
        }

        .date {
            font-size: 14px;
            color: #333;
        }

        .table-container {
            width: 80%;
            margin-top: 20px;
            box-sizing: border-box;
            overflow-x: auto;
        }

        table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            margin-top: 20px;
            box-sizing: border-box;
            border-radius: 8px;
            overflow: hidden;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 12px;
            width: 20%; /* ระบุความกว้างของคอลัมน์ที่ต้องการ */
        }


        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<div class="menu">
        <ul>
            <li>
                <a href="index.php">หน้าหลัก</a>
            </li>
            <li>
                <a href="shopping_cart.php">ตะกร้าสินค้า</a>
            </li>
            <li>
                <a href="my_order2.php">การซื้อของฉัน</a>
            </li>
        </ul>
    </div>

    <h2>การซื้อของฉัน</h2>
    
    <div class="top">
        <div class="date">
            <?php
                date_default_timezone_set("Asia/Bangkok");
            ?>
            <p>date : <?php echo date("d-M-Y H:i:s"); ?></p>
        </div>
    </div>

    <?php
    session_start();
    $cx = mysqli_connect("localhost","root","","app");

    $custId = $_SESSION['custId'];
    $query = "SELECT `orders`.OrderNo AS OrderNo, `orders`.ProductNo AS ProductNo, product.ProductName AS ProductName, product.PricePerUnit AS PricePerUnit, `orders`.ProductQty AS ProductQty
    FROM `orders`
    INNER JOIN product ON `orders`.ProductNo = product.ProductNo
    WHERE `orders`.CustNo = '$custId'
    ORDER BY CAST(`orders`.OrderNo AS UNSIGNED), `order`.ProductNo";


    $result = mysqli_query($cx, $query);
    $orderNo = "";
    $sum = 0;
    echo '<div class="table-container">';
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['OrderNo'] != $orderNo) {
                // ถ้า OrderNo ใหม่ ให้ประกาศค่าใหม่
                if ($orderNo != "") { // ตรวจสอบว่าไม่ใช่ OrderNo แรกที่ยังไม่มีข้อมูล
                    // แสดงค่ารวมของ Order ก่อนปิดตาราง
                    echo "<tr>";
                    echo "<td colspan='5'>รวมทั้งสิ้น</td>";
                    echo "<td>$sum</td>";
                    echo "</tr>";
                    echo "</table>";
                }
                // ประกาศค่าใหม่
                $orderNo = $row['OrderNo'];
                $sum = 0;
                //echo '<div class="table-container">';
                echo "<table>";
                echo "<tr>";
                echo "<th>รหัส Order</th>";
                echo "<th>รหัสสินค้า</th>";
                echo "<th>ชื่อสินค้า</th>";
                echo "<th>ราคาต่อหน่วย</th>";
                echo "<th>จำนวน</th>";
                echo "<th>รวม</th>";
                echo "</tr>";
            }
        
            $productNo = $row['ProductNo'];
            $productName = $row['ProductName'];
            $pricePerUnit = $row['PricePerUnit'];
            $productQty = $row['ProductQty'];
            $totalPrice = $pricePerUnit * $productQty;
            $sum += $totalPrice;
        
            echo "<tr>";
            echo "<td>$orderNo</td>";
            echo "<td>$productNo</td>";
            echo "<td>$productName</td>";
            echo "<td>$pricePerUnit</td>";
            echo "<td>$productQty</td>";
            echo "<td>$totalPrice</td>";
            echo "</tr>";
        }
        
        // แสดงค่ารวมของ Order ล่าสุดก่อนปิดตาราง
        if ($orderNo != "") { // ตรวจสอบว่ามีข้อมูลอยู่หรือไม่
            echo "<tr>";
            echo "<td colspan='5'>รวมทั้งสิ้น</td>";
            echo "<td>$sum</td>";
            echo "</tr>";
            echo "</table><br>";
            echo "</div>";
            
        }
       
    ?>
</body>
</html>
