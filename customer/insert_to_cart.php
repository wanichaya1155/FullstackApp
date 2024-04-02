<?php
session_start();

$cx = mysqli_connect("localhost","root","","app");

$productNo = $_POST['productNo'];
$quantity = $_POST['quantity'];
$custId = $_SESSION['custId'];

// ตรวจสอบว่าสินค้ามีอยู่ในตะกร้าแล้วหรือไม่
$query = "SELECT * FROM shoppingcart WHERE CustNo = '$custId' AND ProductNo = '$productNo'";
$result = mysqli_query($cx, $query);

if(mysqli_num_rows($result) > 0) {
    // ถ้ามีอยู่แล้ว ให้อัปเดตจำนวนสินค้าในตะกร้า
    $row = mysqli_fetch_assoc($result);
    $cartId = $row['CartId'];
    $currentQuantity = $row['ProductQty'];
    $newQuantity = $currentQuantity + $quantity;

    $updateQuery = "UPDATE shoppingcart SET ProductQty = '$newQuantity' WHERE CartId = '$cartId'";
    $updateResult = mysqli_query($cx, $updateQuery);

    if($updateResult) {
        echo "สินค้าถูกอัปเดตในตะกร้าแล้ว";
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดตสินค้าในตะกร้า: " . mysqli_error($cx);
    }
} else {
    // ถ้ายังไม่มีให้เพิ่มสินค้าเข้าไปใหม่
    $query_maxCartId = "SELECT MAX(CartId) AS maxCartId FROM shoppingcart";
    $result_maxCartId  = mysqli_query($cx, $query_maxCartId);
    $row = mysqli_fetch_assoc($result_maxCartId);

    // นำค่า maxCartId มาเก็บในตัวแปร
    $maxCartId = $row['maxCartId'];
    $newcartId = intval($maxCartId) + 1;

    $insertQuery = "INSERT INTO shoppingcart (CartId, CustNo, ProductNo, ProductQty) 
    VALUES ('$newcartId', '$custId', '$productNo', '$quantity');";

    $insertResult = mysqli_query($cx, $insertQuery);

    if($insertResult) {
        echo "สินค้าถูกเพิ่มลงในตะกร้าแล้ว";
    } else {
        echo "เกิดข้อผิดพลาดในการเพิ่มสินค้าในตะกร้า: " . mysqli_error($cx);
    }
}

mysqli_close($cx);
?>
