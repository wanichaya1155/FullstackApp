<?php
    $cx = mysqli_connect("localhost","root","","test");

    $custno = $_POST["custno"];
    $supplyNo = $_POST["supplyNo"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill</title>
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
    
    $customersql = "SELECT * FROM customer WHERE custno = '$custno'";
$result = mysqli_query($cx, $customersql);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        $customer = mysqli_fetch_assoc($result);
        echo "ชื่อ ".$customer["custname"]." <br>เพศ ".$customer["sex"]." <br>ที่อยู่ ".$customer["addrsee"]." <br>เบอร์โทร ".$customer["tel"]." <br>";
        // ใช้ print_r เพื่อแสดงโครงสร้างของข้อมูลลูกค้า
        echo "ORDER $supplyNo - CUSTOMER IS: ";
        print_r($customer);
    } else {
        echo "Customer not found.";
    }
} else {
    echo "Error executing query: " . mysqli_error($cx);
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
                $total = 0;
                $sql = "SELECT * FROM supply WHERE supplyNo = $supplyNo ORDER BY ProductCode";
                $result = mysqli_query($cx, $sql);
                while($row = mysqli_fetch_assoc($result)){
                    $productCode = $row["ProductCode"];
                    $query = "SELECT * FROM stock WHERE ProductCode='$productCode' ORDER BY ProductCode";
                    $resultP = mysqli_query($cx, $query);
                    $product = mysqli_fetch_assoc($resultP);
                    $productName = $product['ProdoctName'];
                    $price = $product['PricePerUnit'];
                    $qty = $row["Qty"];
                    $sum = $qty * $price;
                    echo "<tr>";
                    echo "<td>$productCode</td>";
                    echo "<td>$productName </td>";
                    echo "<td>$price</td>";
                    echo "<td>$qty</td>";
                    echo "<td>$sum</td>";
                    echo "</td>";
                    $total += $sum;
                }
                mysqli_close($cx);
            ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><?php echo $total;?></td>
            </tr>
        </table>
</body>
</html>    

