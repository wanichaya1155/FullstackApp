<?php
    $cx = mysqli_connect("localhost","root","","shopping");

    if(isset($_GET['custno'])){
        $custno = $_GET['custno'];
        echo "Customer Number: $custno <br>";
    }
    if (isset($_GET['productCode'])) {
        $selectedProductCodes = $_GET['productCode'];
    } else {
        echo "No product selected.";
    }
    $qty_array = $_GET['qty'];

?>
<!DOCTYPE html>
<html lang="en">
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
    <center>
        <h1>Comfirm Order</h1>
    </center>
    <form method="get" action="insert_order2.php">
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
                foreach ($selectedProductCodes as $productCode) {
                    $query = "SELECT shoppingcart.CartId AS CartId, shoppingcart.ProductCode AS ProductCode, product.ProductName AS ProductName, product.PricePerUnit AS PricePerUnit, shoppingcart.ProductQty AS ProductQty
                    FROM shoppingcart 
                    INNER JOIN product ON shoppingcart.ProductCode = product.ProductCode 
                    WHERE shoppingcart.CustNo = '$custno' AND shoppingcart.ProductCode = '$productCode'
                    ORDER BY ProductCode";
                    $result = mysqli_query($cx, $query);
                    $row = mysqli_fetch_assoc($result);
                    $qty = $qty_array[$productCode] ?? 0; // ตรวจสอบว่ามีค่าในอาร์เรย์หรือไม่
                    $price = $row['PricePerUnit'];
                    $total = $qty * $price;
                    echo "<tr>";
                    echo "<td>$productCode</td>";
                    echo "<td>{$row['ProductName']}</td>";
                    echo "<td>{$price}</td>";
                    echo "<td>$qty</td>";
                    echo "<td>$total</td>";
                    echo "</tr>";
                    $sum += $total;
                    echo "<input type='hidden' name='productCode[]' value='$productCode'>";
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
