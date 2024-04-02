<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHOPPING</title>
    <link rel="stylesheet" href="navbar.css">
    <style>
        body{
            margin: 0rem auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #e3e3e3;
        }
        a{
            text-decoration: none;
            color: black;
        }
        .container_top{
            width: 100%;
            height: 10rem;
            background: #ff8ad4;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .ul{
            margin: 0px auto;
        }
        .item{
            max-width: 1040px;
            display:grid;
            grid-template-columns: repeat(5,1fr);
        }
        .item_box{
            margin: 0.25rem;
            max-width: 220px;
            height: 280px;
            box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.25);
            border-radius: 10px;
            border: 2px solid transparent;
        }
        .item_box img{
            width: 100%;
            height: 197px;
            border-radius: 10px 10px 0px 0px;
            object-fit: cover; /* ไม่ต้องบีบรูปภาพแต่จะทำให้เต็ม container */
            object-position: center center;
        }
        .item_box:hover {
            border: 2px solid #ff8ad4; /* เปลี่ยนสีขอบเมื่อ hover */
        }
        .info {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .info p{
            margin: 10px;
            font-weight: bold;
            font-size: 18px;
        }
        /* .item_box img {
            display: block;
            margin: 0 auto; 
            border: 5px ;
        } */

        
        
    </style>
</head>
<body>
<?php
    session_start();
    $cx = mysqli_connect("localhost","root","","app");

    $query = "SELECT * FROM product ORDER BY ProductNo";
    $result = mysqli_query($cx, $query);
?>
<?php
    
    //$userName = $_POST["userName"];
    //$password = $_POST["password"];
    // if(isset($_POST["password"])){
    //     $_SESSION['custId'] = $_POST["password"];
    // }else{
    //     $custId = $_SESSION['custId'];
    // }
    $custId = $_SESSION['custId'];
    
?>
    <div class="container_top">
        <h1>SHOPPING</h1>
    </div>
    
    <div class="menu">
        <ul>
            <li>
                <a href="">หน้าหลัก</a>
            </li>
            <li>
                <a href="shopping_cart.php">ตะกร้าสินค้า</a>
            </li>
            <li>
                <a href="my_order2.php">การซื้อของฉัน</a>
            </li>
        </ul>
    </div>
    <div class="item">
    
        <?php?> 
            <!-- <a href="" >
                <div class="item_box">
                    <img src="img/p001.png">
                    <div class="info">
                        <p class="name_porduct">ปลาส้ม</p>
                        <div class="price">
                            <p>200 บาท</p>
                        </div>
                    </div>
                </div>
                <div class="item_box">
                    <img src="img/p002.png">
                    <div class="info">
                        <p class="name_porduct">ปลาส้ม</p>
                        <div class="price">
                            <p>200 บาท</p>
                        </div>
                    </div>
                </div>
            </a> -->
            <?php 
                $sql = "SELECT ProductName, PricePerUnit,ProductNo,Category FROM product";
                $result = $cx->query($sql);
                
                if ($result->num_rows > 0) {
                    // แสดงข้อมูลที่ดึงมาจากฐานข้อมูล
                    while($row = $result->fetch_assoc()) {
                        echo "<a href='product_item.php?ProductNo={$row['ProductNo']}'>";
                        echo '<div class="item_box">';
                        //echo '<img src="img/' . $row["ProductNo"] . '">';
                        echo '<img src="img/'.$row["ProductNo"].'.jpg">';
                        echo '<div class="info">';
                        echo '<p class="name_product">' . $row["ProductName"] . '</p>';
                        echo  '<div class = "category">';
                        echo  '<p >'.$row["Category"] . '</p> </div>';
                        echo '<div class="price">';
                        echo '<p>' . $row["PricePerUnit"] . ' บาท</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo "</a>";
                    }
                } else {
                    echo "0 ผลลัพธ์";
                }
                $cx->close();
        ?>
            
          
        

    </div>
</body>

</html>
