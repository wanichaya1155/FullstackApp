<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample form</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #cceeff;
            color: #000099;
            margin: 0;
            padding: 0;
        }

        .container {
            display: grid;
            place-items: center;
            height: 100vh;
        }

        .form-container {
            width: 70%;
            max-width: 600px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form {
            display: grid;
            gap: 10px;
        }

        label {
            font-size: 1.2rem;
            color: #000033;
        }

        input {
            padding: 10px;
            box-sizing: border-box;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"],
        input[type="reset"] {
            background-color: #4caf50;
            color: white;
            cursor: pointer;
            padding: 10px;
            border: none;
            border-radius: 5px;
        }

        input[type="submit"]:hover,
        input[type="reset"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>ใส่ข้อมูลของลูกค้าที่ต้องการ</h1>
            <hr>
            <?php
                if(isset($_GET['data'])){
                    $lastId = $_GET['data'];
                    echo 'รหัสสินค้าล่าสุดคือ: ' . $lastId;
                } else {
                    echo 'No data parameter found.';
                }
            ?>
            <br>
            <form method="post" action="insert.php">
                <label for="a1">รหัสสินค้า</label>
                <input type="text" name="a1" id="a1" size="4" maxlength="4">

                <label for="a2">ชื่อสินค้า</label>
                <input type="text" name="a2" id="a2" size="50" maxlength="20">

                <label for="a3">ราคาสินค้าต่อหน่วย</label>
                <input type="text" name="a3" id="a3" size="10" maxlength="10">

                <label for="a4">จำนวนสินค้า</label>
                <input type="text" name="a4" id="a4" size="50" maxlength="80">

                <input type="submit" value="ยืนยัน">
                <input type="reset" value="ยกเลิก">
            </form>
        </div>
    </div>
</body>
</html>
