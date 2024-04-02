<?php
    // $cx = mysqli_connect("localhost","root","","shopping");

    // $cartId = $_POST['cartId'];
    // $sql = "DELETE FROM shoppingcart WHERE CardId = 'cartId'";
    // $result = mysqli_prepare($cx, $sql);
?>

<?php
    session_start();
    $cx = mysqli_connect("localhost", "root", "", "app");

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cartId'])) {
        $cartId = $_POST['cartId'];
        
        // สร้างคำสั่ง SQL เพื่อลบรายการสินค้าจากตาราง shoppingcart โดยใช้ cartId
        $query = "DELETE FROM shoppingcart WHERE CartId = '$cartId'";
        
        // ส่งคำสั่ง SQL ไปทำงาน
        if (mysqli_query($cx, $query)) {
            // ลบรายการสินค้าสำเร็จ
            echo "รายการสินค้าถูกลบออกจากตะกร้าแล้ว";
        } else {
            // เกิดข้อผิดพลาดในการลบรายการสินค้า
            echo "เกิดข้อผิดพลาดในการลบรายการสินค้า: " . mysqli_error($cx);
        }
    } else {
        // ไม่มีการส่งข้อมูล cartId หรือไม่ใช่เมธอด POST
        echo "ไม่พบข้อมูลหรือเมธอดไม่ถูกต้อง";
    }

    mysqli_close($cx);
?>
