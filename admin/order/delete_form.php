<?php
    $conn = mysqli_connect("localhost","root","","app");

    $stmt = mysqli_prepare($conn, "delete from orders where OrderNo='$_GET[data]' ");

    if(!mysqli_execute($stmt)){
        echo "Error";
    }else{
        echo "Delete product = <font color=red> '$_GET[data]' </font> is successful.";
    }

    mysqli_close($conn);
?>