<?php
    session_start();
    $cx = mysqli_connect("localhost","root","","shopping");

    $custId = $_SESSION['custId'];
    // $query = "SELECT shopping.ProductCode, ProductName,PricePerUnit,ProductQty 
    // WHERE shopping.ProductCode = product.ProductCode AND CustNo = '$custno'
    // FROM product,shoppingcart 
    // ORDER BY ProductCode";

    $query = "SELECT order.OrderNo AS OrderNo, order.ProductCode AS ProductCode, product.ProductName AS ProductName, product.PricePerUnit AS PricePerUnit, order.ProductQty AS ProductQty
    FROM order
    INNER JOIN product ON order.ProductCode = product.ProductCode 
    WHERE order.CustNo = $custId
    ORDER BY order.OrderNo, order.ProductCode";

    
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

    <h2>Product Data</h2>
    <div class="top">
        <div class="date">
            <?php
                date_default_timezone_set("Asia/Bangkok");
            ?>
            <p>date : <?php echo date("d-M-Y H:i:s"); ?></p>
        </div>
 
        
    </div>

    <table>
            <tr>
                <th>OrderNo</th>
                <th>ProductCode</th>
                <th>ProductName</th>
                <th>PricePerUnit</th>
                <th>Qty</th> 
            </tr>
            <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        $id = $row['ProductCode'];
                        echo "<tr>";
                        echo "<input type='hidden' class='quantity-hidden' name='qty[]' value='{$row['ProductQty']}'>";
                        //echo "<td><input type='text' name='qty[]' value='1' maxlength='6'></td>";
                        echo "<td>{$row['OrderNo']}</td>";
                        echo "<td>{$row['ProductCode']}</td>";
                        echo "<td>{$row['ProductName']}</td>";
                        echo "<td>{$row['PricePerUnit']}</td>";
                        echo "<td>{$row['ProductQty']}</td>";
                        echo "</tr>";
                    }
            ?>
    </table>
    <?php
        mysqli_close($cx);
    ?>

</body>


</style>        
</html>
