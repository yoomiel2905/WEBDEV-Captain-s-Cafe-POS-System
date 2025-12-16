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

$orderid=$_GET['id'];

// Get order details
$sql="SELECT * FROM ORDERS WHERE ORDERID='$orderid'";
$result=sqlsrv_query($conn,$sql);
$order=sqlsrv_fetch_array($result);

// Get order items
$sql2="SELECT * FROM ORDER_ITEMS WHERE ORDERID='$orderid'";
$result2=sqlsrv_query($conn,$sql2);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Details - Captain's Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('images/bg.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .navbar {
            background-color: #3d8060;
        }

        .navbar-brand img {
            width: 50px;
            margin-right: 10px;
        }

        .order-box {
            background: white;
            border: 3px solid #3d8060;
            border-radius: 10px;
            padding: 30px;
            margin-top: 20px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .order-header {
            background-color: #3d8060;
            color: white;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .btn-back {
            background-color: #d93545;
            color: white;
        }

        .btn-print {
            background-color: #3d8060;
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin_stats.php">
                <img src="images/logo.png" alt="Logo">
                Captain's Cafe - Admin
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="order-box" id="orderDetails">
            <div class="order-header">
                <h3>Order Details - #<?php echo $order['ORDERID']; ?></h3>
                <p><?php echo $order['ORDER_DATE']->format('F d, Y - h:i A'); ?></p>
            </div>

            <h5>Order Items:</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while($item=sqlsrv_fetch_array($result2)){
                        $itemTotal = $item['PRICE'] * $item['QUANTITY'];
                        echo '<tr>';
                        echo '<td>'.$item['PRODUCT_NAME'].'</td>';
                        echo '<td>'.$item['QUANTITY'].'</td>';
                        echo '<td>P'.number_format($item['PRICE'],2).'</td>';
                        echo '<td>P'.number_format($itemTotal,2).'</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <p><b>Payment Method:</b> <?php echo $order['PAYMENT_METHOD']; ?></p>
                    <p><b>Discount Type:</b> <?php echo $order['DISCOUNT_TYPE']; ?></p>
                    <?php
                    if($order['IS_SENIOR'] == 1){
                        echo '<p><b>Senior Citizen Discount Applied</b></p>';
                    }
                    ?>
                </div>
                <div class="col-md-6 text-end">
                    <p><b>Subtotal:</b> P<?php echo number_format($order['SUBTOTAL'],2); ?></p>
                    <p><b>Discount:</b> -P<?php echo number_format($order['DISCOUNT_AMOUNT'],2); ?></p>
                    <h4><b>TOTAL:</b> P<?php echo number_format($order['TOTAL_AMOUNT'],2); ?></h4>
                </div>
            </div>

            <hr>

            <a href="transaction_history.php" class="btn btn-back">Back to History</a>
            <button onclick="printReceipt()" class="btn btn-print">Print Receipt</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function printReceipt() {
            var content = document.getElementById('orderDetails').innerHTML;
            var printWindow = window.open('', '', 'width=400,height=600');
            
            printWindow.document.write('<html><head><title>Print Receipt</title>');
            printWindow.document.write('<style>');
            printWindow.document.write('body { font-family: Arial, sans-serif; padding: 20px; }');
            printWindow.document.write('table { width: 100%; border-collapse: collapse; }');
            printWindow.document.write('th, td { padding: 5px; text-align: left; border-bottom: 1px solid #ddd; }');
            printWindow.document.write('hr { border: 1px solid #333; }');
            printWindow.document.write('button { display: none; }');
            printWindow.document.write('a { display: none; }');
            printWindow.document.write('.order-header { background-color: #3d8060; color: white; padding: 15px; text-align: center; margin-bottom: 20px; }');
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

<?php
sqlsrv_close($conn);
?>