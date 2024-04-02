<?php
session_start();
$cx = mysqli_connect("localhost", "root", "", "shopping");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ProductCode']) && isset($_POST['ProductQty'])) {
    $productCode = $_POST['ProductCode'];
    $quantity = $_POST['ProductQty'];

    // อัปเดตจำนวนสินค้าใน shoppingcart ในฐานข้อมูล
    $updateQuery = "UPDATE shoppingcart SET ProductQty = '$quantity' WHERE ProductCode = '$productCode'";
    $updateResult = mysqli_query($cx, $updateQuery);

    if ($updateResult) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "invalid request";
}

mysqli_close($cx);
?>
