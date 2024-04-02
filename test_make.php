<?php
    $cx = mysqli_connect("localhost","root","","app");

    if(isset($_GET['custno'])){
        $custno = $_GET['custno'];
        echo "Customer Number: $custno <br>";
    }
    if (isset($_GET['productNo'])) {
        $selectedProductNo = $_GET['productNo'];
    } else {
        echo "No product selected.";
    }
    $qty_array = $_GET['qty'];
    //$cartId = $_GET['$cartId']

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comfirm Order</title>
    <link rel="stylesheet" href="navbar.css">
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
    <center>
        <h1>Comfirm Order</h1>
    </center>
    <form method=post  action=insert_order2.php>
        <table>
            <tr>
                <th>รหัสสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th>ราคาต่อหน่วย</th>
                <th>จำนวน</th>
                <th>รวม</th>
            </tr>
            <?php
                $sum=0;

                
                for ( $i=0; $i < count($selectedProductNo); $i++) {
                    $productNo = $selectedProductNo[$i];
                    
                    
                    //$query = "SELECT * FROM product WHERE ProductCode='$productCode' ORDER BY ProductCode";
                    // $query = "SELECT shoppingcart.CartId AS CartId, shoppingcart.ProductNo AS ProductNo, product.ProductName AS ProductName, product.PricePerUnit AS PricePerUnit, shoppingcart.ProductQty AS ProductQty
                    // FROM shoppingcart 
                    // INNER JOIN product ON shoppingcart.ProductNo = product.ProductNo 
                    // WHERE shoppingcart.CustNo = '$custno' AND shoppingcart.ProductNo = '$productNo'
                    // ORDER BY ProductNo";

                    $query = "SELECT shoppingcart.CartId AS CartId, shoppingcart.ProductNo AS ProductNo, product.ProductName AS ProductName, product.PricePerUnit AS PricePerUnit, shoppingcart.ProductQty AS ProductQty
                    FROM shoppingcart 
                    INNER JOIN product ON shoppingcart.ProductNo = product.ProductNo 
                    WHERE shoppingcart.CustNo = '$custno' AND shoppingcart.ProductNo = '$productNo'
                    ORDER BY ProductNo";
                    
                    // $index[$i] = intval(substr($productNo, 1));

                    $result = mysqli_query($cx, $query);
                    $row = mysqli_fetch_assoc($result);
                    // if (isset($qty_array[$index[$i]-1])) {
                    //     $qty = $qty_array[$index[$i]-1];
                    // } else {
                    //     // ทำงานเมื่อไม่มีดัชนีที่ต้องการ
                    //     $qty = 0; // หรือให้ค่าเริ่มต้นอื่น ๆ ที่ต้องการ
                    //     echo "คำเตือน: ไม่พบดัชนี {$index[$i]} ใน qty_array";
                    // }
                    $qty = $row['ProductQty'];
                    $price = $row['PricePerUnit'];
                    $result = $qty * $price;
                    echo "<tr>";
                    echo "<td>$productNo</td>";
                    echo "<td>{$row['ProductName']}</td>";
                    echo "<td>{$price}</td>";
                    echo "<td>$qty</td>";
                    echo "<td>$result</td>";
                    echo "</td>";
                    $sum += $result;
                    echo "<input type='hidden' name='cartId[]' value='{$row['CartId']}'>";
                    echo "<input type='hidden' name='productNo[]' value='$productNo'>";
                    echo "<input type='hidden' name='qty[]' value='$qty'>";
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
        <input type="hidden" name="custno" value="<?php echo $custno;?>">
        <input type="submit" value="ยืนยันการสั่งซื้อ">
    </form>
</body>
</html>