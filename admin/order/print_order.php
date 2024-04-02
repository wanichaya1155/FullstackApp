<?php
    $cx = mysqli_connect("localhost","root","","app");

    if(isset($_GET['data'])){
        $orderNo = $_GET['data'];
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
    
    $customersql = "SELECT * FROM customer WHERE CustNo = '$custNo'";
    $result = mysqli_query($cx, $customersql);
    
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            echo "ORDER $orderNo - CUSTOMER IS: ";
            $customer = mysqli_fetch_assoc($result);
            echo "ชื่อ ".$customer["CustName"]." <br>เพศ ".$customer["Sex"]." <br>ที่อยู่ ".$customer["Address"]." <br>เบอร์โทร ".$customer["Tel"]." <br>";
            // ใช้ print_r เพื่อแสดงโครงสร้างของข้อมูลลูกค้า
            
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
                $sum=0;
                $queryOrder = "SELECT * FROM `order` WHERE OrderNo = '$orderNo' ORDER BY ProductCode";
                $resultOrder = mysqli_query($cx, $queryOrder);
                while ($row = mysqli_fetch_assoc($resultOrder)){
                    $productCode = $row['ProductCode'];
                    $qty = $row['ProductQty'];
                    $query = "SELECT * FROM product WHERE ProductCode='$productCode' ORDER BY ProductCode";
                    $result = mysqli_query($cx, $query);
                    $rowProduct = mysqli_fetch_assoc($result);
                    
                    $price = $rowProduct['PricePerUnit'];
                    $result = $qty * $price;
                    echo "<tr>";
                    echo "<td>$productCode</td>";
                    echo "<td>{$rowProduct['ProductName']}</td>";
                    echo "<td>{$price}</td>";
                    echo "<td>$qty</td>";
                    echo "<td>$result</td>";
                    echo "</td>";
                    $sum += $result;

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
</body>
</html>    

