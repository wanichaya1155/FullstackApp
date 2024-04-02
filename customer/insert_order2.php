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

$customersql = "SELECT * FROM customer WHERE CustNo = '$custno'";
$result_customer = mysqli_query($cx, $customersql);
$rowCustomer = mysqli_fetch_assoc($result_customer);
$address = $rowCustomer['Address'];

date_default_timezone_set("Asia/Bangkok");
//Insert purchase
$sqlOH = mysqli_prepare($cx, 
"INSERT INTO `purchase` (`CustNo`, `Date`, `Status`, `Total`, `Address`) 
VALUES (?, CURRENT_TIMESTAMP, 'Pending', 0, ?);");
mysqli_stmt_bind_param($sqlOH, "is",$custno, $address);
if (mysqli_stmt_execute($sqlOH)) {
    echo "Insert purchase successful!";
} else {
    echo "Error: " . mysqli_error($cx);
}


//find Lastest PurchaseNo for insert orders and Invoice
$sqlPhResult = mysqli_query($cx, "SELECT MAX(PurchaseNo) AS LastPurchaseNo FROM purchase");
$rowPurchaseNo = mysqli_fetch_assoc($sqlPhResult);
$lastPh = $rowPurchaseNo['LastPurchaseNo'];

//Insert  Invoice
$sqlIv = mysqli_prepare($cx,
"INSERT INTO invoice (PurchaseNo, CustNo, Date, Status, Total)
VALUES (?, ?, CURRENT_TIMESTAMP, 'Pending', 0);");
mysqli_stmt_bind_param($sqlIv, "ii",$lastPh,$custno);
if (mysqli_stmt_execute($sqlIv)) {
    echo "Insert Invoice successful!";
} else {
    echo "Error: " . mysqli_error($cx);
}
//find Lastest Invoice for insert Invoice_detail
$sqlIvResult = mysqli_query($cx, "SELECT MAX(InvoiceNo) AS LastInvoiceNo FROM invoice");
$rowInvoice = mysqli_fetch_assoc($sqlIvResult);
$lastIv = $rowInvoice['LastInvoiceNo'];

//Insert orders & invoice_detail
for ($i = 0; $i < count($selectedProductNo); $i++) {
    $productNo = $selectedProductNo[$i];
    $productQty = $qty_array[$i];
    //insert order
    $stmtOr = mysqli_prepare($cx, 
            "INSERT into `orders` (PurchaseNo, ProductQty, ProductNo, CustNo) 
            VALUES (?,?,?,?);");
    mysqli_stmt_bind_param($stmtOr, 'iiii', $lastPh, $productQty, $productNo, $custno);
    if(!mysqli_execute($stmtOr)){
        echo "Error";
    } else {
        echo "การสั่งซื้อเสร็จสิ้น";
    }

    //insert invoice_detail
    $stmtInD = mysqli_prepare($cx, 
            "INSERT INTO invoice_detail (InvoiceNo, ProductNo, ProductQty)
            VALUES (?,?,?);
            ");
    mysqli_stmt_bind_param($stmtInD, 'iii', $lastIv, $productNo, $productQty);
    if(!mysqli_execute($stmtInD)){
        echo "Error";
    } else {
        echo "invoice_detail successfully";
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
                $queryOrder = "SELECT * FROM `orders` WHERE PurchaseNo = '$lastPh' ORDER BY ProductNo";
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
                //update purchase
                $sqlOH = "UPDATE purchase SET Total = $sum WHERE PurchaseNo = $lastPh";
                if (mysqli_query($cx, $sqlOH)) {
                    echo "Update purchase successful!";
                } else {
                    echo "Error updating purchase: " . mysqli_error($cx);
                }
                //update invoice
                $sqlIvUp = "UPDATE invoice SET Total = $sum WHERE InvoiceNo = $lastIv";
                if (mysqli_query($cx, $sqlIvUp)) {
                    echo "Update invoice successful!";
                } else {
                    echo "Error updating invoice: " . mysqli_error($cx);
                }


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
        <form action="pdftest9.php" method="get">
            <h5>พิมพ์ใบสั่งซื้อ</h5>
        <input type="hidden" name="custno" value="<?php echo $custno ?>">
        <input type="hidden" name="purchaseNo" value="<?php echo $lastPh ?>">
        <input type="submit" value="พิมพ์ใบสั่งซื้อ">
    </form>
</body>
</html>    
