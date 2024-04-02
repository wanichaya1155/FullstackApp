<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <style>
        form{
            margin: auto;
        }
        .new{
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body bgcolor="#cceeff" text=#000099>
    <center>
        <font size="7" color="#000033">
            ใส่ข้อมูลของออเดอร์ที่ต้องการเปลี่ยนแปลง
        </font>
        <hr color="blue">
        <?php 
            $id =  $_GET["data"];
            $query = "SELECT * FROM order WHERE OrderNo = '$id'";
            $cx = mysqli_connect("localhost","root","","shopping");
            $result = mysqli_query($cx, $query);
        ?>
        <form method="post" action="update_form.php">
            <input type="hidden" name="data" value="<?php echo $id; ?>">

            <p>รหัสออเดอร์ <?php echo $id;?><br>
            
            <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    $custno = $row['custno'];
                    $productCode = $row['ProductCode'];
                    $qty = $row['Qty'];
                    ?>
                    <div class="new">
                        ID ลูกค้า<input type="text" name="a2" size="4" maxlength="20" value=<?php echo $custno;?>>
                        ID สินค้า<input type="text" name="a3" size="4" maxlength="20" value="<?php echo $productCode?>">
                        จำนวนสินค้า<input type="text" name="a4" size="6" maxlength="20" value="<?php echo $productCode?>">
                    </div>
                    <br>
                    <?php
                }
            ?>
                <input type="submit" value="ยืนยัน">
                <input type="reset" value="ยกเลิก">
            </p>
        </form>
    </center>
</body>
</html>