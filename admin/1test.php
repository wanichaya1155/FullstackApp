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

        img {
            max-width: 20px;
            height: 20px;
        }

        .edit_img {
            max-width: 15px;
            height: auto;
        }

        .top {
            display: flex;
            justify-content: space-between;
        }

        .insert_button {
            display: flex;
        }

        .insert_button img {
            margin: 16px;
        }

        .search-container {
            margin-top: 20px;
            text-align: right;
        }

        .search-input {
            padding: 8px;
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

    <div class="search-container">
        <label for="search">Search:</label>
        <input type="text" id="search" class="search-input" onkeyup="filterTable()" placeholder="Type to search...">
    </div>

    <table id="productTable">
        <tr>
            <th>ProductCode</th>
            <th>ProductName</th>
            <th>PricePerUnit</th>
            <th>StockQty</th>
            <th>Update</th>
            <th>Delete</th>
        </tr>
        <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$row['ProductCode']}</td>";
                echo "<td>{$row['ProductName']}</td>";
                echo "<td>{$row['PricePerUnit']}</td>";
                echo "<td>{$row['Qty']}</td>";
                $id = $row['ProductCode'];
                echo "<td><a href='product/update.php?data=$id'><img class='edit_img' src='img/edit.png'></a></td>";
                echo "<td><a href='product/delete.php?data=$id'><img src='img/delete.png'></a></td>";
                echo "</tr>";
            }
            $lastProductCode = $id;
        ?>
    </table>

    <center>
        <a href="product/insert_form.php?data=<?php echo $lastProductCode; ?>">
            <div class="insert_button">
                <img src="img/insert.jpg">
                <p>Insert Product</p>
            </div>
        </a>
    </center>

    <?php
        mysqli_close($cx);
    ?>

    <script>
        function filterTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("search");
            filter = input.value.toUpperCase();
            table = document.getElementById("productTable");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0]; // Assuming ProductCode is the first column
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>

</body>
</html>
