<?php
    session_start();
    $cx = mysqli_connect("localhost","root","","app");

    $custId = $_SESSION['custId'];
    // $query = "SELECT shopping.ProductNo, ProductName,PricePerUnit,ProductQty 
    // WHERE shopping.ProductNo = product.ProductNo AND CustNo = '$custno'
    // FROM product,shoppingcart 
    // ORDER BY ProductNo";

        $query = "SELECT shoppingcart.CartId AS CartId, shoppingcart.ProductNo AS ProductNo, product.ProductName AS ProductName, product.PricePerUnit AS PricePerUnit, shoppingcart.ProductQty AS ProductQty
        FROM shoppingcart 
        INNER JOIN product ON shoppingcart.ProductNo = product.ProductNo 
        WHERE shoppingcart.CustNo = '$custId'
        ORDER BY ProductNo";
    
    $result = mysqli_query($cx, $query);
?>
<?php
    //$userName = $_POST["userName"];
    //$password = $_POST["password"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Data</title>
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
    <style>
        img {
            max-width: 20px;
            height: 20px;
        }
        .edit_img{
            max-width: 15px;
            height: auto;
        }
        .top{
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .insert_button{
            display: flex;
        }
        .insert_button img{
            margin: 16px;
        }
        .search{
            display: flex;
            align-items: center;
            text-align: center;
        }

        .search p {
            font-size: 18px;
            margin: 0;
            color: #333333;
            font-weight: bold;
        }

        .form_search {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
        }

        input[type="text"] {
            padding: 10px;
            width: 200px;
            border: 1px solid #dddddd;
            border-radius: 4px;
            margin-right: 5px;
        }

        button[type="submit"] {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
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
                <a href="shopping_cart.php" class='underlined-text'>ตะกร้าสินค้า</a>
            </li>
            <li>
                <a href="my_order2.php">การซื้อของฉัน</a>
            </li>
        </ul>
    </div>
    <h2>Product Data</h2>
    <div class="top">
        <div class="date">
            <?php
                date_default_timezone_set("Asia/Bangkok");
            ?>
            <p>date : <?php echo date("d-M-Y H:i:s"); ?></p>
        </div>
        <!-- <div class="search">
            <p>ค้นหา  </p>
            <form class="form_search" method="post" action="search_resultP.php">
                <input type="text" name="search">
                <button type="submit">
                    <img src="img/search.png" alt="ค้นหา">
                </button>
            </form>
        </div> -->
        
    </div>

    <form action="test_make.php" method="GET">
        <table>
            <tr>
                <th>Select</th> 
                <th>Qty</th> 
                <th>ProductNo</th>
                <th>ProductName</th>
                <th>PricePerUnit</th>
                <th>CartId</th>
            </tr>
            <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        $id = $row['ProductNo'];
                        echo "<tr>";
                        echo "<td><input type='checkbox' name='productNo[]' value='$id' ></td>";
                        echo "<input type='hidden' name='cartId[]' value='{$row['CartId']}'>";
                        echo "<input type='hidden' class='quantity-hidden' name='qty[]' value='{$row['ProductQty']}'>";
                        //echo "<td><input type='text' name='qty[]' value='1' maxlength='6'></td>";
                        echo "<td><div class='product_qty'>
                        <button class='quantity-btn minus-btn'>-</button>
                        <input type='text' class='quantity-input'  value='{$row['ProductQty']}'>
                        <button class='quantity-btn plus-btn'>+</button></div></td>";
                        echo "<td>{$row['ProductNo']}</td>";
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

</body>

<script>
    document.querySelectorAll('.plus-btn').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // หยุดการเกิดเหตุการณ์คลิกธรรมดา
            var input = button.parentElement.querySelector('.quantity-input');
            var value = parseInt(input.value, 10);
            input.value = isNaN(value) ? 1 : value + 1;
        });
    });

    document.querySelectorAll('.minus-btn').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // หยุดการเกิดเหตุการณ์คลิกธรรมดา
            var input = button.parentElement.querySelector('.quantity-input');
            var value = parseInt(input.value, 10);
            if (value > 1) {
                input.value = value - 1;
            } else if (value === 1) {
                var cartId = button.parentElement.parentElement.querySelector('.CartId').textContent;
                var formData = new FormData();
                formData.append('cartId', cartId);

                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_cart.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        window.location.reload();
                    } else {
                        alert('เกิดข้อผิดพลาดในการลบรายการสินค้า');
                    }
                };
                xhr.send('cartId=' + encodeURIComponent(cartId));
            }
        });
    });
</script>

</style>        
</html>
