<?php
    $cx = mysqli_connect("localhost","root","","app");
    $search_input = $_POST['search'];
    $query = "SELECT * FROM `purchase` WHERE LOWER(PurchaseNo) LIKE LOWER('%$search_input%') 
    OR LOWER(CustNo) LIKE LOWER('%$search_input%') 
    OR LOWER(`Status`) LIKE LOWER('%$search_input%') ORDER BY PurchaseNo";

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
            <p>ค้นหา</p>
            <form method="post" action="search_result.php">
                <input type="text" name="search">
                <input type="submit" value="ค้นหา">
            </form>
        </div>
        
    </div>

    <div class='container'>
    <table>
        <tr>
            <!-- <th>
            <div id="checkAll">
                <input type="checkbox" id="selectAll" onchange="toggleCheckboxes()">
                <label for="selectAll">Select All</label>
            </div>
            </th> -->
            <th>OrderNo</th>
            <th>CustomerId</th>
            <th>Date</th>
            <th>Total</th>
            <th>Status</th>
            <th>Print</th>
            <th>Update</th>
            <!-- <th>Delete</th> -->
        </tr>
        <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['PurchaseNo']}</td>";
                    echo "<td>{$row['CustNo']}</td>";
                    echo "<td>{$row['Date']}</td>";
                    echo "<td>{$row['Total']}</td>";
                    echo "<td>{$row['Status']}</td>";
                    $id = $row['OHId'];
                    echo "<td>
                            <a href='order/print_order.php?data=$id&custno={$row['CustNo']}'>
                                <img src='img/print.jpg'>  
                            </a>
                        </td>";
                    echo "<td><a href='order/update.php?data=$id'><img class='edit_img' src='img/edit.png'></a></td>";
                    // echo "<td><a href='order/delete.php?data=$id'><img src='img/delete.png'></a></td>";
                    echo "</tr>";
                }
                $lastOrderNo = $id;

        ?>
    </table>
    </div>
    <center><!-- <a href="product/insert_form.html"> -->  
        <a href="order/insert_form.php?data=<?php echo $lastOrderNo; ?>">
                <div class="insert_button">
                    <img src="img/insert.jpg">
                    <p>Insert Order</p>
                </div>
            </a>
    </center>
    <?php
        mysqli_close($cx);
    ?>

</body>


</style>        
</html>