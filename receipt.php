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

$orderid=$_GET['orderid'];
$usertype=$_GET['usertype']; // NEW

$sql="SELECT * FROM ORDERS WHERE ORDERID='$orderid'";
$result=sqlsrv_query($conn,$sql);
$order=sqlsrv_fetch_array($result);

$sql2="SELECT * FROM ORDER_ITEMS WHERE ORDERID='$orderid'";
$result2=sqlsrv_query($conn,$sql2);

// Determine which page to redirect to
if($usertype == 'cashier'){
    $backPage = 'cashier_page.php';
}else{
    $backPage = 'admin_page.php';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Receipt - Captain's Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('images/bg.jpg') no-repeat center center fixed;
            background-size: cover;
            padding: 20px;
        }
        .receipt-box {
            background: white;
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 10px;
            border: 3px solid #3d8060;
        }
        .btn-print {
            background-color: #3d8060;
            color: white;
        }
    </style>
</head>
<body>
    <div class="receipt-box" id="receiptContent">
        <h2 align="center">Captain's Cafe</h2>
        <p align="center">Thank you for your order!</p>
        <p align="center">Order #<?php echo $orderid; ?></p>
        <hr>
        
        <table class="table">
            <tr>
                <th>Item</th>
                <th>Price</th>
            </tr>
            <?php
            while($item=sqlsrv_fetch_array($result2)){
                echo '<tr>';
                echo '<td>'.$item['PRODUCT_NAME'].'</td>';
                echo '<td>P'.number_format($item['PRICE'],2).'</td>';
                echo '</tr>';
            }
            ?>
        </table>
        
        <hr>
        <p><b>Payment Method:</b> <?php echo $order['PAYMENT_METHOD']; ?></p>
        <p><b>Subtotal:</b> P<?php echo number_format($order['SUBTOTAL'],2); ?></p>
        <p><b>Discount:</b> -P<?php echo number_format($order['DISCOUNT_AMOUNT'],2); ?></p>
        <h4><b>TOTAL:</b> P<?php echo number_format($order['TOTAL_AMOUNT'],2); ?></h4>
        
        <button onclick="printReceipt()" class="btn btn-print w-100 mt-3">Print Receipt</button>
        <a href="<?php echo $backPage; ?>" class="btn btn-success w-100 mt-2">New Order</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function printReceipt() {
            var content = document.getElementById('receiptContent').innerHTML;
            var printWindow = window.open('', '', 'width=400,height=600');
            
            printWindow.document.write('<html><head><title>Print Receipt</title>');
            printWindow.document.write('<style>');
            printWindow.document.write('body { font-family: Arial, sans-serif; padding: 20px; }');
            printWindow.document.write('table { width: 100%; border-collapse: collapse; }');
            printWindow.document.write('th, td { padding: 5px; text-align: left; border-bottom: 1px solid #ddd; }');
            printWindow.document.write('hr { border: 1px solid #333; }');
            printWindow.document.write('button { display: none; }');
            printWindow.document.write('a { display: none; }');
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(content);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>
</body>
</html>