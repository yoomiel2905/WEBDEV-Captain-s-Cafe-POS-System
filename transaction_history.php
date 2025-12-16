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

// Get all orders
$sql="SELECT * FROM ORDERS ORDER BY ORDER_DATE DESC";
$result=sqlsrv_query($conn,$sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaction History - Captain's Cafe</title>
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

        .content-box {
            background: white;
            border: 3px solid #3d8060;
            border-radius: 10px;
            padding: 30px;
            margin-top: 20px;
        }

        .transaction-table {
            margin-top: 20px;
        }

        .btn-view {
            background-color: #3d8060;
            color: white;
        }

        .badge-senior {
            background-color: #daa847;
            color: black;
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="admin_stats.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_page.php">Take Order</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="menu_management.php">Menu Management</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="transaction_history.php">Transaction History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="login_page.html">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="content-box">
            <h2>Transaction History</h2>
            <p>View all orders and transactions</p>

            <table class="table table-striped transaction-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date & Time</th>
                        <th>Subtotal</th>
                        <th>Discount</th>
                        <th>Total</th>
                        <th>Payment Method</th>
                        <th>Discount Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while($order=sqlsrv_fetch_array($result)){
                        echo '<tr>';
                        echo '<td>#'.$order['ORDERID'].'</td>';
                        echo '<td>'.$order['ORDER_DATE']->format('Y-m-d H:i:s').'</td>';
                        echo '<td>P'.number_format($order['SUBTOTAL'],2).'</td>';
                        echo '<td>P'.number_format($order['DISCOUNT_AMOUNT'],2).'</td>';
                        echo '<td><b>P'.number_format($order['TOTAL_AMOUNT'],2).'</b></td>';
                        echo '<td>'.$order['PAYMENT_METHOD'].'</td>';
                        echo '<td>';
                        if($order['IS_SENIOR'] == 1){
                            echo '<span class="badge badge-senior">Senior Citizen</span>';
                        }else{
                            echo '<span class="badge bg-secondary">No Discount</span>';
                        }
                        echo '</td>';
                        echo '<td>';
                        echo '<a href="view_order.php?id='.$order['ORDERID'].'" class="btn btn-sm btn-view">View Details</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
sqlsrv_close($conn);
?>