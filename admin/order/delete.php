<?php
    $id =  $_GET["data"];
    $query = "SELECT * FROM order WHERE OrderNo = '$id'";
    $cx = mysqli_connect("localhost","root","","shopping");
    $result = mysqli_query($cx, $query);
    $row = mysqli_fetch_assoc($result);
    echo "ต้องการลบสินค้า รหัสสินค้า : ". $row['OrderNo'] ."<br>วันที่สั่งซื้อ : ". $row['Date'] ."<br> จำนวนที่ของ : ". $row['ProductQty']."<br>รหัสสินค้า: ". $row['ProductCode']."<br>รหัสลูกค้า : ". $row['CustNo'];
    mysqli_close($cx);
?>                             
<form method="get" action="delete_form.php">
    <input type="hidden" name="data" value="<?php echo $row['OrderNo']; ?>">
    <input type=submit value=ยืนยัน>
</form>

<!-- 
19/01
พน หยุด
ตรวจงานชุด
    งาน
    1.ในการรับorder เมนูแรกทำดรื่องจัดการกับลูกค้าและสินค้า
    2.กดรายการลุกค้า แล้วอัปเดต ดีลีตได้ อินเสริทอยู่ด้านบน เมื่อกรอกเสร็จลิ้งค์มาหน้าตาราง
        ++มี check box เผื่อลบลูกค้าหลายรายพร้อมกัน ส่งเป็น array หรือยิงไปลบทีละตัวก็ได้
    3. สินค้า มีหน้าบริหารจัดการสินค้า เป็นตารางเหมือนกัน 
    4.อย่างยุ่งกับ primary key ถ้าต้องกรอก primary key ต้องมีการเช็คด้วย
    5.ค้นหาลูกค้า จาก รหัส ชื่อ ที่อยู่ ได้ %
    6.รับ order มีหน้า order ออกแบบได้อย่างอืสระ 
        ลูกค้ารับ order --กรอกตารางsupply
        หน้ารับ order มี customer ชนกับ stock คิดเองว่าให้ลูกค้าเลือกสินค้ายังไง
        ตอนสั่งซื้อลูกค้าต้องรู้ว่าราคาเท่าไหร่ และสินค้าในสต็อกมีเท่าไหร่ สั่งกี่เล่ม
    มี summary จะวนลูป+ หรือ select count* ก็ได้ หรือ num row(นับจำนวนแถว)
    เพิ่ม date ใน supply 
    รายงานทั้งหลาย แม้แต่ทรานสคริป จะมีการแบ่งข้อมูลออกเป็น 2 ชุด คือ header detail โดยทั้งสองอันต้องแยกตารางกัน
        header = 1 พวก summary ภาษี แท็ค 
            1ใครสั่ง 2ส่งไปที่ใคร 3ใครจ่ายเงิน ต้องมีรายละเอียดของลูกคา้ทั้งสามคนด้วย
            คอนแทคต้องมี  primary secondary
            ส่วนลดทั้งหมด
        detail = many เป็น 1 to many มีid กำหนดด้วย   เป็น2คอมพาว key
            ส่วนลดราย item

    ต้องการใบ order  ที่จะส่งของ
    ****ทำหน้าจอ      -frontend ของลูกค้า และ 
                -back end ของ software admin
            ดังนั้นต้อง manage ทั้ง order และ stock
    การเขียนก็ตามสะดวก
    ไม่ต้องมีรีวอด หรือดาว หรือการสะสมแต้ม
    ยิง sql ไป กัลบ หลาย ๆ รอบได้
    ในการที่ cgi จะ connect
                    prepare sql
                    fatch

    เจอกันหลังอาทิตย์สอบ
-->