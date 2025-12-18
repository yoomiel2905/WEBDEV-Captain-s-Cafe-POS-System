<?php
$serverName="LAPTOP-3OO9AVHG\SQLEXPRESS";
$connectionOptions=[
    "Database"=>"CaptainsCafe",
    "Uid"=>"",
    "PWD"=>""
];
$conn=sqlsrv_connect($serverName, $connectionOptions);

if($conn==false)
    die(print_r(sqlsrv_errors(),true));

$subtotal=$_POST['subtotalValue'];
$discount=$_POST['discountValue'];
$total=$_POST['totalValue'];
$itemCount=$_POST['itemCount'];
$payment=$_POST['payment'];
$usertype=$_POST['usertype']; 

$discountType="No Discount";
$isSenior=0;
if($_POST['discount']=="20"){
    $discountType="Senior Citizen";
    $isSenior=1;
}

$sql="INSERT INTO ORDERS (SUBTOTAL, DISCOUNT_AMOUNT, TOTAL_AMOUNT, DISCOUNT_TYPE, IS_SENIOR, PAYMENT_METHOD) 
      VALUES ('$subtotal', '$discount', '$total', '$discountType', '$isSenior', '$payment')";
$result=sqlsrv_query($conn,$sql);

if($result){
    $sql2="SELECT MAX(ORDERID) AS ORDERID FROM ORDERS";
    $result2=sqlsrv_query($conn,$sql2);
    $row=sqlsrv_fetch_array($result2);
    $orderid=$row['ORDERID'];
    
    for($i=0; $i<$itemCount; $i++){
        $itemName=$_POST['itemName'.$i];
        $itemPrice=$_POST['itemPrice'.$i];
        $itemQty=$_POST['itemQty'.$i];
        
        $sql3="INSERT INTO ORDER_ITEMS (ORDERID, PRODUCT_NAME, QUANTITY, PRICE) 
               VALUES ('$orderid', '$itemName', '$itemQty', '$itemPrice')";
        sqlsrv_query($conn,$sql3);
    }
    
    sqlsrv_close($conn);
    ?>
    <html>
    <body>
        <form id="redirectForm" action="receipt.php" method="POST">
            <input type="hidden" name="orderid" value="<?php echo $orderid; ?>">
            <input type="hidden" name="usertype" value="<?php echo $usertype; ?>">
        </form>
        <script>
            document.getElementById('redirectForm').submit();
        </script>
    </body>
    </html>
    <?php
}
?>