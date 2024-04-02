<?php
    if(isset($_POST['a1'])) {
        $a1 = $_POST['a1'];
    }else {
        echo "No parameters received";
    }
    $conn = mysqli_connect("localhost","root","","app");

    $stmt = mysqli_prepare($conn, 
    "UPDATE product set ProductName='$_POST[a2]', PricePerUnit='$_POST[a3]', Cost='$_POST[a4]', Qty='$_POST[a5]',Category='$_POST[a6]' where ProductNo='$a1' ");

    if(!mysqli_execute($stmt)){
        echo "Error";
    }else{
        echo "Update Product = <font color=red> '$a1' </font> is successful.";
    }

    mysqli_close($conn);
?>