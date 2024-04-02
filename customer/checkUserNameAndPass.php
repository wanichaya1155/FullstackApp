<?php
    $cx = mysqli_connect("localhost", "root", "", "app");
    
    if(isset($_POST["userName"]) && isset($_POST["password"])){
        $username = $_POST["userName"];
        $input_password = $_POST["password"];
        
        // ใช้ parameterized query เพื่อป้องกัน SQL Injection
        $sql = "SELECT * FROM customer WHERE UserName=?";
        $query = mysqli_prepare($cx, $sql);
        mysqli_stmt_bind_param($query, "s", $username);
        mysqli_stmt_execute($query);
        $result = mysqli_stmt_get_result($query);
        $row = mysqli_fetch_assoc($result);
        if($row['Password']== $input_password) {
            // ถ้า userName และ Password ถูกต้อง
            session_start();
            $_SESSION['custId'] = $row['CustNo'];
            header("Location: index.php");
            exit;
        } else {
            // ถ้า userName หรือ Password ไม่ถูกต้อง
            echo "<script>alert('ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง');</script>";
            echo "<script>window.location='login.html';</script>";

        }
    }
?>
