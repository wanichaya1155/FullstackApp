<?php
    $cx = mysqli_connect("localhost","root","","app");

    $query = "SELECT * FROM product  ORDER BY ProductNo";
    $result = mysqli_query($cx, $query);

    
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

        form {
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
        <div class="search">
            <p>ค้นหา  </p>
            <form method="post" action="search_resultP.php">
                <input type="text" name="search">
                <button type="submit">
                    <img src="img/search.png" alt="ค้นหา">
                </button>
            </form>
        </div>
        
    </div>

    <table>
        <tr>
            <!-- <th>
            <div id="checkAll">
                <input type="checkbox" id="selectAll" onchange="toggleCheckboxes()">
                <label for="selectAll">Select All</label>
            </div>
            </th> -->
            <th>ProductNo</th>
            <th>ProductName</th>
            <th>PricePerUnit</th>
            <th>CostPrice</th>
            <th>StockQty</th>
            <th>Category</th>
            <th>Update</th>
            <th>Delete</th>
        </tr>
        <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['ProductNo']}</td>";
                    echo "<td>{$row['ProductName']}</td>";
                    echo "<td>{$row['PricePerUnit']}</td>";
                    echo "<td>{$row['Cost']}</td>";
                    echo "<td>{$row['Qty']}</td>";
                    echo "<td>{$row['Category']}</td>";
                    $id = $row['ProductNo'];
                    echo "<td><a href='product/update.php?data=$id'><img class='edit_img' src='img/edit.png'></a></td>";
                    echo "<td><a href='product/delete.php?data=$id'><img src='img/delete.png'></a></td>";
                    echo "</tr>";
                }
                //$lastProductCode = $id;

        ?>
    </table>
    <center><!-- <a href="product/insert_form.html"> -->
        
    <a href="product/insert_form.php">
            <div class="insert_button">
                <img src="img/insert.jpg">
                <p>Insert Product</p>
            </div>
        </a></center>
    <?php
        mysqli_close($cx);
    ?>

</body>


</style>        
</html>