<?php
    $cx = mysqli_connect("localhost","root","","app");

    if(isset($_POST['custno'])){
        $custno = $_POST['custno'];
        //echo "Customer Number: $custno <br>";
    }
    if (isset($_POST['productNo'])) {
        $selectedProductNo= $_POST['productNo'];
    } else {
        echo "No product selected.";
    }
    $qty_array = $_POST['qty'];
    $cartId = $_POST['cartId'];


?>
<link rel="stylesheet" href="navbar.css">
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
<?php 
//ใช้สำหรับแปลงค่าข้อมูลให้อยู่ในประเภทข้อมูลที่ต้องการ
// $sql = "SELECT MAX(OrderNo) AS lastOrderNo FROM `orders`";
// $result = mysqli_query($cx, $sql);
// $row = mysqli_fetch_assoc($result);
// //insert
// $lastOrderNo = $row['lastOrderNo'];

// // ตรวจสอบว่ามีการสั่งซื้อก่อนหน้าหรือไม่
// if ($lastOrderNo !== null) {
//     // หากมีใบสั่งซื้อก่อนหน้าแล้ว
//     $orderNo = $lastOrderNo + 1;
// } else {
//     // หากยังไม่มีใบสั่งซื้อในฐานข้อมูล
//     $orderNo = 1; // เริ่มต้นที่หมายเลขใบสั่งซื้อ 1
// }

date_default_timezone_set("Asia/Bangkok");

// ต่อไปคือส่วนที่เหมือนเดิม
for ($i = 0; $i < count($selectedProductNo); $i++) {
    $productNo = $selectedProductNo[$i];
    $productQty = $qty_array[$i];
    //echo "order = <font color=red> $orderNo,'$custno','$productCode',$productQty </font> .<br>";
    $stmt = mysqli_prepare($cx, 
            "INSERT into `orders` (ProductQty, ProductNo, CustNo) 
            VALUES ('$productQty', '$productNo', '$custno');");

    //mysqli_stmt_bind_param($stmt, 'ssss', $orderNo, $productQty, $productCode, $custno);

    
    if(!mysqli_execute($stmt)){
        echo "Error";
    } else {
        // echo "Insert order = <font color=red> $orderNo,'$custno','$productCode',$productQty </font> is successful.<br>";
        echo "การสั่งซื้อเสร็จสิ้น";
    }
    

}

//$sql = "DELETE FROM shoppingcart WHERE CartId = '$cartId'";
//$result = mysqli_query($cx, $sql);
for($i = 0; $i<count($cartId); $i++){
    $theCartId = $cartId[$i];
    $query = "DELETE FROM shoppingcart WHERE CartId = '$theCartId'";
    $result = mysqli_query($cx, $query);
}
?>



<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comfirm Order</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    

 <?php
    
$customersql = "SELECT * FROM customer WHERE CustNo = '$custno'";
$result_customer = mysqli_query($cx, $customersql);

if ($result_customer) {
    if (mysqli_num_rows($result_customer) > 0) {
        
        //echo "ORDER $orderNo - CUSTOMER IS: ";
        //$customer = mysqli_fetch_assoc($result);
        //echo "ชื่อ ".$customer["CustName"]." <br>เพศ ".$customer["Sex"]." <br>ที่อยู่ ".$customer["Address"]." <br>เบอร์โทร ".$customer["Tel"]." <br>";
        // ใช้ print_r เพื่อแสดงโครงสร้างของข้อมูลลูกค้า
        
        //print_r($customer);
    } else {
        echo "Customer not found.";
    }
} else {
    echo "Error executing query: " . mysqli_error($cx);
}

$sql = "SELECT MAX(OrderNo) AS lastOrderNo FROM `orders`";
$result = mysqli_query($cx, $sql);
$row = mysqli_fetch_assoc($result);
// //insert
$lastOrderNo = $row['lastOrderNo'];
?>
    <table>
        <h1>การสั่งซื้อสินค้า</h1>
            <tr>
                <th>รหัสสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th>ราคาต่อหน่วย</th>
                <th>จำนวน</th>
                <th>รวม</th>
            </tr>
            <?php
                $sum=0;
                $queryOrder = "SELECT * FROM `orders` WHERE OrderNo = '$lastOrderNo' ORDER BY ProductNo";
                $resultOrder = mysqli_query($cx, $queryOrder);
                while ($row = mysqli_fetch_assoc($resultOrder)){
                    $productNo = $row['ProductNo'];
                    $qty = $row['ProductQty'];
                    $query = "SELECT * FROM product WHERE ProductNO='$productNo' ORDER BY ProductNo";
                    $result = mysqli_query($cx, $query);
                    $rowProduct = mysqli_fetch_assoc($result);
                    
                    $price = $rowProduct['PricePerUnit'];
                    $result = $qty * $price;
                    echo "<tr>";
                    echo "<td>$productNo</td>";
                    echo "<td>{$rowProduct['ProductName']}</td>";
                    echo "<td>{$price}</td>";
                    echo "<td>$qty</td>";
                    echo "<td>$result</td>";
                    echo "</td>";
                    $sum += $result;

                    echo "<input type='hidden' name='productNo[]' value='$productNo'>";
                    echo "<input type='hidden' name='qty[]' value='$qty'>";
                }
                $rowCustomer = mysqli_fetch_assoc($result_customer);
                $address = $rowCustomer['Address'];
                $sqlOH = mysqli_prepare($cx, 
                "INSERT INTO `purchase` (`OrderNo`,`CustNo`, `Date`, `Total`, `Address`, `Status`,`AdminNo`) 
                VALUES (?,?, CURRENT_TIMESTAMP, ?, ?, 'Pending',?);");
                $defaultAdmin = 1;
                mysqli_stmt_bind_param($sqlOH, "iidsi", $lastOrderNo, $custno, $sum, $address,$defaultAdmin);

                if (mysqli_stmt_execute($sqlOH)) {
                    echo "Insert successful!";
                } else {
                    echo "Error: " . mysqli_error($cx);
                }

                mysqli_stmt_close($sqlOH);

                mysqli_close($cx);
                
            ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><?php echo $sum;?></td>
            </tr>
        </table>
        <form action="pdftest8.php" method="get">
            <h5>พิมพ์ใบสั่งซื้อ</h5>
        <input type="hidden" name="custno" value="<?php echo $custno ?>">
        <input type="hidden" name="orderNo" value="<?php echo $orderNo ?>">
        <input type="submit" value="พิมพ์ใบสั่งซื้อ">
    </form>
</body>
</html>    
