<?php
    $cx = mysqli_connect("localhost","root","","app");

    $query = "SELECT * FROM customer ORDER BY CustNo";
    $result = mysqli_query($cx, $query);

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Data</title>
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
    </style>
</head>
<body>

    <h2>Customer Data</h2>
    <div class="top">
        <div class="date">
            <?php
                date_default_timezone_set("Asia/Bangkok");
            ?>
            <p>date : <?php echo date("d-M-Y H:i:s"); ?></p>
        </div>
        <a href="customer/insert_form.php">
            <div class="insert_button">
                <img src="img/insert.jpg">
                <p>Insert Customer</p>
            </div>
        </a>
    </div>

    <table>
        <tr>
            <th>CustNo</th>
            <th>CustName</th>
            <th>UserName</th>
            <th>Sex</th>
            <th>Address</th>
            <th>Tel</th>
            <th>Update</th>
            <th>Delete</th>
        </tr>
        <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['CustNo']}</td>";
                    echo "<td>{$row['CustName']}</td>";
                    echo "<td>{$row['UserName']}</td>";
                    echo "<td>{$row['Sex']}</td>";
                    echo "<td>{$row['Address']}</td>";
                    echo "<td>{$row['Tel']}</td>";
                    $id = $row['CustNo'];
                    echo "<td><a href='customer/update.php?data=$id'><img class='edit_img' src='img/edit.png'></a></td>";
                    echo "<td><a href='customer/delete.php?data=$id'><img src='img/delete.png'></a></td>";
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