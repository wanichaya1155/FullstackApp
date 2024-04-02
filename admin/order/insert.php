<?php
    
    $cx = mysqli_connect("localhost","root","","shopping");

    $stmt = mysqli_prepare($cx, 
    "insert into stock(OrderNo,Date,ProductQty,ProductCode,CustNo) 
    values('$_POST[a1]','$_POST[a2]','$_POST[a3]','$_POST[a4]')");

    if(!mysqli_execute($stmt)){
        echo "Error";
    }else{
        echo "Insert Product = <font color=red> '$_POST[a1]' </font> is successful.";
    }

    mysqli_close($cx);
?>