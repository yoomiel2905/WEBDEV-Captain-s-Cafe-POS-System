<?php
$serverName="LAPTOP-3OO9AVHG\SQLEXPRESS";
$connectionOptions=[
    "Database"=>"CAPTAINSCAFE",
    "Uid"=>"",
    "PWD"=>""
];
$conn=sqlsrv_connect($serverName, $connectionOptions);

if($conn==false)
    die(print_r(sqlsrv_errors(),true));

$sql1="SELECT SUM(TOTAL_AMOUNT) AS TOTAL FROM ORDERS WHERE CONVERT(DATE, ORDER_DATE) = CONVERT(DATE, GETDATE())";
$result1=sqlsrv_query($conn,$sql1);
$row1=sqlsrv_fetch_array($result1);
$totalSales = $row1['TOTAL'] ? number_format($row1['TOTAL'], 2) : '0.00';

$sql2="SELECT COUNT(ORDERID) AS TOTAL FROM ORDERS WHERE CONVERT(DATE, ORDER_DATE) = CONVERT(DATE, GETDATE())";
$result2=sqlsrv_query($conn,$sql2);
$row2=sqlsrv_fetch_array($result2);
$totalOrders = $row2['TOTAL'] ? $row2['TOTAL'] : 0;

$sql3="SELECT COUNT(ORDERID) AS TOTAL FROM ORDERS WHERE CONVERT(DATE, ORDER_DATE) = CONVERT(DATE, GETDATE()) AND IS_SENIOR = 1";
$result3=sqlsrv_query($conn,$sql3);
$row3=sqlsrv_fetch_array($result3);
$seniorDiscounts = $row3['TOTAL'] ? $row3['TOTAL'] : 0;

$sql4="SELECT SUM(TOTAL_AMOUNT) AS TOTAL FROM ORDERS WHERE MONTH(ORDER_DATE) = MONTH(GETDATE()) AND YEAR(ORDER_DATE) = YEAR(GETDATE())";
$result4=sqlsrv_query($conn,$sql4);
$row4=sqlsrv_fetch_array($result4);
$monthlySales = $row4['TOTAL'] ? number_format($row4['TOTAL'], 2) : '0.00';

$sql5="SELECT COUNT(ORDERID) AS TOTAL FROM ORDERS WHERE MONTH(ORDER_DATE) = MONTH(GETDATE()) AND YEAR(ORDER_DATE) = YEAR(GETDATE())";
$result5=sqlsrv_query($conn,$sql5);
$row5=sqlsrv_fetch_array($result5);
$monthlyOrders = $row5['TOTAL'] ? $row5['TOTAL'] : 0;

$sql6="SELECT COUNT(ORDERID) AS TOTAL FROM ORDERS WHERE MONTH(ORDER_DATE) = MONTH(GETDATE()) AND YEAR(ORDER_DATE) = YEAR(GETDATE()) AND IS_SENIOR = 1";
$result6=sqlsrv_query($conn,$sql6);
$row6=sqlsrv_fetch_array($result6);
$monthlySeniorDiscounts = $row6['TOTAL'] ? $row6['TOTAL'] : 0;

$currentMonth = date('F Y');

sqlsrv_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Captain's Cafe</title>
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

        .stat-box {
            background: white;
            border: 3px solid #daa847;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 20px;
            text-align: center;
        }

        .stat-box .icon {
            font-size: 50px;
            margin-bottom: 15px;
        }

        .stat-box h2 {
            color: #3d8060;
            font-size: 48px;
            margin: 15px 0;
            font-weight: bold;
        }

        .stat-box p {
            color: #666;
            font-size: 18px;
            margin: 0;
        }

        .dashboard-title {
            color: white;
            margin-bottom: 30px;
        }

        .section-divider {
            border-top: 3px solid #daa847;
            margin: 40px 0;
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
                        <a class="nav-link active" href="admin_stats.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_page.php">Take Order</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="menu_management.php">Menu Management</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="transaction_history.php">Transaction History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="login_page.html">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="dashboard-title">Today's Summary</h1>

        <div class="row">
            <div class="col-md-4">
                <div class="stat-box">
                    <div class="icon">💰</div>
                    <h2>P<?php echo $totalSales; ?></h2>
                    <p>Today's Sales</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-box">
                    <div class="icon">📋</div>
                    <h2><?php echo $totalOrders; ?></h2>
                    <p>Today's Orders</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-box">
                    <div class="icon">👴</div>
                    <h2><?php echo $seniorDiscounts; ?></h2>
                    <p>Senior Discounts Given</p>
                </div>
            </div>
        </div>

        <div class="section-divider"></div>

        <h1 class="dashboard-title">Monthly Summary (<?php echo $currentMonth; ?>)</h1>

        <div class="row">
            <div class="col-md-4">
                <div class="stat-box">
                    <div class="icon">💵</div>
                    <h2>P<?php echo $monthlySales; ?></h2>
                    <p>This Month's Sales</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-box">
                    <div class="icon">📊</div>
                    <h2><?php echo $monthlyOrders; ?></h2>
                    <p>This Month's Orders</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stat-box">
                    <div class="icon">🎫</div>
                    <h2><?php echo $monthlySeniorDiscounts; ?></h2>
                    <p>Monthly Senior Discounts</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>