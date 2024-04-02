<?php
    $cx = mysqli_connect("localhost","root","","app");

    $query = "SELECT * FROM `purchase` ORDER BY PurchaseNo DESC";
    $result = mysqli_query($cx, $query);

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Data</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        body{
            display: flex;
            flex-direction: column;
            align-items: center;
        }
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
        .container{
            width: 80%;
        }
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
        .top > * {
            padding: 10px; /* เปลี่ยนค่า padding ตามที่คุณต้องการ */
        }
        .report{
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .report p{
            font-size: 15px;
            color: #2c2c2c;
        }
        button[type="button"] {
            margin: auto;
            padding: 10px 15px;
            background-color: #00004d;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
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
            margin: auto;
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
    <h2>Order Data</h2>
    <div class="top">
        <div class="date">
            <?php
                date_default_timezone_set("Asia/Bangkok");
            ?>
            <p>date : <?php echo date("d-M-Y H:i:s"); ?></p>
        </div>
        <div class="search">
            <p>ค้นหา  </p>
            <form method="post" action="search_resultO.php">
                <input type="text" name="search">
                <button type="submit">
                    <img src="img/search.png" alt="ค้นหา">
                </button>
            </form>
        </div>
        
    </div>
    <div class="report">
        <div class="date1">
            <input type="text" id="datepicker1" placeholder="Select date">
            <p id="selectedDate1">จากวันที่ : -</p>
        </div>
        <div class="date2">
            <input type="text" id="datepicker2" placeholder="Select date">
            <p id="selectedDate2">ถึง : -</p>
        </div>
        <div>
            <form id="reportForm" method="get" action="report.php">
                <!-- Input hidden สำหรับเก็บค่า dateStr -->
                <input type="hidden" name="startDate" id="startDateInput" value="">
                <input type="hidden" name="endDate" id="endDateInput" value="">
                <button type="button" onclick="makeReport()">Make Report</button>
            </form>
            <br>
        </div>

        <script>
            function makeReport() {
                // ดึงค่า dateStr จาก #datepicker1 และ #datepicker2
                var startDate = document.getElementById("datepicker1").value;
                var endDate = document.getElementById("datepicker2").value;

                // กำหนดค่าให้กับ input hidden
                document.getElementById("startDateInput").value = startDate;
                document.getElementById("endDateInput").value = endDate;

                // submit ฟอร์ม
                document.getElementById("reportForm").submit();
            }

            flatpickr("#datepicker1", {
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr, instance) {
                    document.getElementById("selectedDate1").textContent = "จากวันที่ : " + dateStr;
                },
            });

            flatpickr("#datepicker2", {
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr, instance) {
                    document.getElementById("selectedDate2").textContent = "ถึง : " + dateStr;
                },
            });
        </script>
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
            <th>PurchaseNo</th>
            <th>CustomerId</th>
            <th>Date</th>
            <th>Total</th>
            <th>Status</th>
            <th>Purchase</th>
            <th>Invoice</th>
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
                    $purchaseNo = $row['PurchaseNo'];
                    echo "<td>
                            <a href='order/purchasepdf.php?purchaseNo=$purchaseNo&custno={$row['CustNo']}'>
                                <img src='img/print.jpg'>  
                            </a>
                        </td>";
                    echo"<td>
                            <a href='order/invoicepdf.php?purchaseNo=$purchaseNo&custno={$row['CustNo']}'>
                                <img src='img/print.jpg'>  
                            </a>
                        </td>";
                
                    // echo "<td><a href='order/delete.php?data=$id'><img src='img/delete.png'></a></td>";
                    echo "</tr>";
                }
                $lastOrderNo = $purchaseNo;

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