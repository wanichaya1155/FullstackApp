<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Data</title>
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

    <h2>Product Data</h2>

    <?php
        session_start();
        $cx = mysqli_connect("localhost","root","","shopping");
        $custId = $_SESSION['custId'];
        $query = "SELECT shoppingcart.CartId AS CartId, shoppingcart.ProductCode AS ProductCode, product.ProductName AS ProductName, product.PricePerUnit AS PricePerUnit, shoppingcart.ProductQty AS ProductQty
        FROM shoppingcart 
        INNER JOIN product ON shoppingcart.ProductCode = product.ProductCode 
        WHERE shoppingcart.CustNo = '$custId'
        ORDER BY ProductCode";
        $result = mysqli_query($cx, $query);
    ?>

    <form id="productForm" action="test_make.php" method="GET">
        <table>
            <tr>
                <th>Select</th> 
                <th>Qty</th> 
                <th>ProductCode</th>
                <th>ProductName</th>
                <th>PricePerUnit</th>
                <th>CartId</th>
            </tr>
            <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row['ProductCode'];
                    echo "<tr>";
                    echo "<td><input type='checkbox' name='productCode[]' value='$id' ></td>";
                    echo "<input type='hidden' name='cartId[]' value='{$row['CartId']}'>";
                    echo "<input type='hidden' class='quantity-hidden' name='qty[]' value='{$row['ProductQty']}'>";
                    echo "<td><div class='product_qty'>
                            <button type='button' class='quantity-btn minus-btn'>-</button>
                            <input type='text' class='quantity-input'  value='{$row['ProductQty']}' readonly>
                            <button type='button' class='quantity-btn plus-btn'>+</button></div></td>";
                    echo "<td>{$row['ProductCode']}</td>";
                    echo "<td>{$row['ProductName']}</td>";
                    echo "<td>{$row['PricePerUnit']}</td>";
                    echo "<td class='CartId'>{$row['CartId']}</td>";
                    echo "</tr>";
                }
            ?>
        </table>
        <center>
            <input type="submit" value="ซื้อสินค้า">
            <input type="hidden" name="custno" value="<?php echo $custId;?>">
        </center>
    </form>

    <?php
        mysqli_close($cx);
    ?>

    <script>
        document.querySelectorAll('.plus-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                var input = button.parentElement.querySelector('.quantity-input');
                var value = parseInt(input.value, 10);
                input.value = isNaN(value) ? 1 : value + 1;
            });
        });

        document.querySelectorAll('.minus-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                var input = button.parentElement.querySelector('.quantity-input');
                var value = parseInt(input.value, 10);
                if (value > 1) {
                    input.value = value - 1;
                }
            });
        });

        // ป้องกันการส่ง form เมื่อกดปุ่ม + หรือ -
        document.getElementById('productForm').addEventListener('submit', function(event) {
            event.preventDefault();
            alert('โปรดใช้ปุ่ม "ซื้อสินค้า" เพื่อส่งฟอร์ม');
        });

        function updateQuantity(productCode, quantity) {
            var formData = new FormData();
            formData.append('ProductCode', productCode);
            formData.append('ProductQty', quantity);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_quantity.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert('อัปเดตจำนวนสินค้าเรียบร้อยแล้ว');
                } else {
                    alert('เกิดข้อผิดพลาดในการอัปเดตจำนวนสินค้า');
                }
            };
            xhr.send(formData);
        }
    </script>
</body>
</html>
