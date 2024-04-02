<?php
    
    $cx = mysqli_connect("localhost","root","","app");

    $stmt = mysqli_prepare($cx, 
    "insert into product(ProductName,PricePerUnit,Cost,Qty,Category) 
    values('$_POST[a1]','$_POST[a2]','$_POST[a3]','$_POST[a4]','$_POST[a5]')");

    if(!mysqli_execute($stmt)){
        echo "Error";
    }else{
        echo "Insert Product = <font color=red> '$_POST[a1]' </font> is successful.";
    }

    mysqli_close($cx);
?>