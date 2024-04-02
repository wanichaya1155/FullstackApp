<?php
    $conn = mysqli_connect("localhost","root","","app");

    $stmt = mysqli_prepare($conn, "delete from product where ProductNo='$_GET[data]' ");

    if(!mysqli_execute($stmt)){
        echo "Error";
    }else{
        echo "Delete product = <font color=red> '$_GET[data]' </font> is successful.";
    }

    mysqli_close($conn);
?>