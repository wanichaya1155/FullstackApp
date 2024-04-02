<?php
$cx = mysqli_connect("localhost", "root", "", "app");

if(isset($_POST["userName"]) && isset($_POST["password"])){
    $username = $_POST["userName"];
    $input_password = $_POST["password"];

    

    
    // ใช้ parameterized query เพื่อป้องกัน SQL Injection
    $sql = "SELECT AdminNo,AdminPassword,AdminUserName,`Role`  FROM `admin` WHERE AdminUserName=?";
    $query = mysqli_prepare($cx, $sql);
    mysqli_stmt_bind_param($query, "s", $username);
    mysqli_stmt_execute($query);
    $result = mysqli_stmt_get_result($query);
    $row = mysqli_fetch_assoc($result);
    
    // ถ้ามีผู้ใช้งานที่ตรงตามเงื่อนไข
    if($row) {
        // ใช้ password_verify เพื่อเปรียบเทียบรหัสผ่านที่ถูกเข้ารหัสกับรหัสผ่านที่ป้อนเข้ามา
        if($row['AdminPassword']== $input_password) {
            // เช็ค Role ของผู้ใช้
            if($row['Role'] == 'H'){
                session_start();
                $_SESSION['adminno'] = $row['AdminNo'];
                header("Location: indexH.html");
                exit;
            } else {
                session_start();
                $_SESSION['adminno'] = $row['AdminNo'];
                header("Location: indexL.html");
                exit;
            }   
        } else {
            // ถ้ารหัสผ่านไม่ถูกต้อง
            echo "<script>alert('ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง  ');</script>";
            echo "<script>window.location='login.html';</script>";
        }
    } else {
        // ถ้าไม่พบชื่อผู้ใช้ในฐานข้อมูล
        echo "<script>alert('ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง');</script>";
        echo "<script>window.location='login.html';</script>";
    }
}
?>
