<?php
    if(isset($_POST['data'])) {
        $a1 = $_POST['data'];
    }else {
        echo "No parameters received";
    }
    $conn = mysqli_connect("localhost","root","","shopping");

    $stmt = mysqli_prepare($conn, 
    "update stock set ProdoctName='$_POST[a2]', PricePerUnit='$_POST[a3]', StockQty='$_POST[a4]' where ProductCode='$a1' ");

    if(!mysqli_execute($stmt)){
        echo "Error";
    }else{
        echo "Update Product = <font color=red> '$a1' </font> is successful.";
    }

    mysqli_close($conn);
?>