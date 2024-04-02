<?php
session_start();

    $cx = mysqli_connect("localhost","root","","app");

    $productNo = $_GET['ProductNo'];

    $query = "SELECT * FROM product WHERE ProductNo=  '$productNo'";
    $result = mysqli_query($cx, $query);
    
    // if(isset($_SESSION['custId'])) {
    //     $username = $_SESSION['custId'];
    //     // ต่อไปนี้คุณสามารถใช้ $username ได้
    // }
    $custId = $_SESSION['custId'];
    $_SESSION['productId'] = $productNo;
?>
<link rel="stylesheet" href="navbar.css">
<style>
    
    body{
        display: flex;
        flex-direction: column;
        align-items: center;
        height: 100vh; /* ให้ body มีความสูงเท่ากับ viewport height เพื่อให้เนื้อหาอยู่ตรงกลาง */
        margin: 0; /* ลบ margin ที่อาจมีอยู่ */
    }

    .container_product{
        margin-top: 20px;
        width: 80%;
        display: flex;
    }
    .container_product img{
        margin-right: 50px;
        width: 623px;
        height: 442px;
        object-fit: cover; /* ไม่ต้องบีบรูปภาพแต่จะทำให้เต็ม container */
        object-position: center center;
    }
    .Item_name{
        font-size: 36px;
        font-weight: bold;
    }
    .price{
        font-size: 30px;
    }
    button[class="buy_now"]{
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0px 0px 10px;
            background: rgb(32, 135, 32);
            color: white;
            font-weight: bold;
        }
    button[class="add_to_cart"]{
        padding:  10px 20px 10px 20px;
        border-radius: 4px;
        margin: 10px 0px 0px 10px;
        background: rgb(255,127,80);
        color: white;
        font-weight: bold;
    }
    
    .quantity {
    display: flex;
    align-items: center;
    }

    .quantity-btn {
    background-color: #e0e0e0;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    }

    .quantity-input {
    width: 40px;
    text-align: center;
    }
        
</style>
<body>
<div class="menu">
        <ul>
            <li>
                <a href="index.php">หน้าหลัก</a>
            </li>
            <li>
                <a href="shopping_cart.php">ตะกร้าสินค้า</a>
            </li>
            <li>
                <a href="my_order2.php">การซื้อของฉัน</a>
            </li>
        </ul>
    </div>
    <?php
     
        $row = $result->fetch_assoc();
    
    ?>
    <div class=container_product>
        <div class="product_img" >
            <img src="img/<?php echo $productNo;?>.jpg">
        </div>
        <div class="inform">
            <p class="Item_name" ><?php echo $row["ProductName"]  ?></p>
            <p class="price" ><?php echo $row["PricePerUnit"]  ?> บาท</p>
            <p class="Item_description"></p>
            <div class="product_qty">
                <button class="quantity-btn minus-btn">-</button>
                <input type="text" class="quantity-input" value="1">
                <button class="quantity-btn plus-btn">+</button>
                
            </div>
            <button class="buy_now" onclick="buyNow()">ซื้อสินค้า</button>
            <button class="add_to_cart" onclick="addToCart('<?php echo $row['ProductNo']; ?>')">เพิ่มลงตะกร้า</button>

            
            
</div>
        </div>
    </div>
    <script>
        fetch('item_desc.json')
        .then(response => response.json())
        .then(data => {
            // ดึงข้อมูลสินค้าจาก JSON
            const products = data.products;

            // หาสินค้าที่มี code ตรงกับที่รับมาจาก URL
            const product = products.find(item => item.code === "<?php echo $productNo; ?>");

            // แสดงข้อมูลสินค้าใน HTML
            if (product) {
                document.querySelector(".Item_description").textContent = product.description;
            }
        })
        .catch(error => console.error('เกิดข้อผิดพลาด:', error));
    </script>
    <script>
        // ปุ่มเพิ่มจำนวนสินค้า
        document.querySelector('.plus-btn').addEventListener('click', function() {
        var input = document.querySelector('.quantity-input');
        var value = parseInt(input.value, 10);
        input.value = isNaN(value) ? 1 : value + 1;
        });

        // ปุ่มลดจำนวนสินค้า
        document.querySelector('.minus-btn').addEventListener('click', function() {
        var input = document.querySelector('.quantity-input');
        var value = parseInt(input.value, 10);
        //input.value = isNaN(value) ? 1 : value - 1;
        if (value >= 1) {
        input.value = value - 1;
        } else {
            // ถ้าค่าเป็น 1 แล้วกดลด ให้ค่าเป็น 1 ไม่เปลี่ยนแปลง
            input.value = 1;
        }
        });
    </script>
    <script>
        function buyNow() {
            window.location.href = 'shopping_cart.php'; 
        }
        function addToCart(productNo) {
            // สร้าง object FormData เพื่อส่งข้อมูลไปยังฐานข้อมูล
            var formData = new FormData();
            formData.append('productNo', productNo);

            // เรียกใช้งาน AJAX เพื่อส่งข้อมูลไปยังไฟล์ PHP เพื่อ insert ข้อมูลลงในฐานข้อมูล
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'insert_to_cart.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                alert('สินค้าถูกเพิ่มลงในตะกร้าแล้ว');
                } else {
                alert('เกิดข้อผิดพลาดในการเพิ่มสินค้าลงในตะกร้า');
                }
            };
            var qtyElement = document.getElementsByClassName('quantity-input')[0];
            var qty = qtyElement.value;
            formData.append('quantity', qty);
            
        
            xhr.send(formData);

        
        }
    </script>
    
    
</body>