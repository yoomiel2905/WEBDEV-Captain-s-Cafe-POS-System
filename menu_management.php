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

$sql="SELECT * FROM MENU_ITEMS ORDER BY CATEGORY, ITEM_NAME";
$result=sqlsrv_query($conn,$sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Menu Management - Captain's Cafe</title>
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

        .menu-table img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }

        .btn-add {
            background-color: #3d8060;
            color: white;
        }

        .btn-edit {
            background-color: #daa847;
            color: black;
        }

        .btn-delete {
            background-color: #d93545;
            color: white;
        }

        .btn-toggle {
            background-color: #6c757d;
            color: white;
            font-size: 12px;
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
                        <a class="nav-link active" href="menu_management.php">Menu Management</a>
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

    <div class="container">
        <div class="content-box">
            <h2>Menu Management</h2>
            <a href="add_menu_item.html" class="btn btn-add mb-3">Add New Item</a>

            <table class="table table-striped menu-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while($row=sqlsrv_fetch_array($result)){
                        echo '<tr>';
                        echo '<td><img src="'.$row['IMAGE_PATH'].'" alt="'.$row['ITEM_NAME'].'"></td>';
                        echo '<td>'.$row['ITEM_NAME'].'</td>';
                        echo '<td>'.$row['CATEGORY'].'</td>';
                        echo '<td>P'.number_format($row['PRICE'],2).'</td>';
                        echo '<td>';
                        if($row['IS_AVAILABLE']==1){
                            echo '<span class="badge bg-success">Available</span>';
                        }else{
                            echo '<span class="badge bg-danger">Not Available</span>';
                        }
                        echo '</td>';
                        echo '<td>';
                        
                        echo '<form action="toggle_availability.php" method="POST" style="display:inline;">';
                        echo '<input type="hidden" name="itemid" value="'.$row['ITEMID'].'">';
                        if($row['IS_AVAILABLE']==1){
                            echo '<input type="hidden" name="status" value="0">';
                            echo '<button type="submit" class="btn btn-sm btn-toggle">Mark Unavailable</button> ';
                        }else{
                            echo '<input type="hidden" name="status" value="1">';
                            echo '<button type="submit" class="btn btn-sm btn-toggle">Mark Available</button> ';
                        }
                        echo '</form>';
                        
                        echo '<form action="edit_menu_item.php" method="POST" style="display:inline;">';
                        echo '<input type="hidden" name="id" value="'.$row['ITEMID'].'">';
                        echo '<button type="submit" class="btn btn-sm btn-edit">Edit</button> ';
                        echo '</form>';
                        
                        echo '<form action="delete_menu_item.php" method="POST" style="display:inline;" onsubmit="return confirm(\'Delete this item?\')">';
                        echo '<input type="hidden" name="id" value="'.$row['ITEMID'].'">';
                        echo '<button type="submit" class="btn btn-sm btn-delete">Delete</button>';
                        echo '</form>';
                        
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