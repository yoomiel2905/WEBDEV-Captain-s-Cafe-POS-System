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

$itemid=$_POST['id'];

$sql="SELECT * FROM MENU_ITEMS WHERE ITEMID='$itemid'";
$result=sqlsrv_query($conn,$sql);
$item=sqlsrv_fetch_array($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Menu Item - Captain's Cafe</title>
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

        .form-box {
            background: white;
            border: 3px solid #3d8060;
            border-radius: 10px;
            padding: 30px;
            margin-top: 20px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-submit {
            background-color: #3d8060;
            color: white;
        }

        .btn-cancel {
            background-color: #d93545;
            color: white;
        }

        .current-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            margin-top: 10px;
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
        <div class="form-box">
            <h2>Edit Menu Item</h2>

            <form action="process_edit_item.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="itemid" value="<?php echo $item['ITEMID']; ?>">
                <input type="hidden" name="oldimage" value="<?php echo $item['IMAGE_PATH']; ?>">

                <div class="mb-3">
                    <label class="form-label">Item Name</label>
                    <input type="text" name="itemname" class="form-control" value="<?php echo $item['ITEM_NAME']; ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label><br>
                    <input type="radio" name="category" value="DRINKS" <?php if($item['CATEGORY']=='DRINKS') echo 'checked'; ?> required> Drinks
                    <input type="radio" name="category" value="FOOD" <?php if($item['CATEGORY']=='FOOD') echo 'checked'; ?> required> Food
                </div>

                <div class="mb-3">
                    <label class="form-label">Price</label>
                    <input type="number" name="price" class="form-control" step="0.01" value="<?php echo $item['PRICE']; ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Availability</label><br>
                    <input type="radio" name="availability" value="1" <?php if($item['IS_AVAILABLE']==1) echo 'checked'; ?> required> Available
                    <input type="radio" name="availability" value="0" <?php if($item['IS_AVAILABLE']==0) echo 'checked'; ?> required> Not Available
                </div>

                <div class="mb-3">
                    <label class="form-label">Current Image</label><br>
                    <img src="<?php echo $item['IMAGE_PATH']; ?>" class="current-image" alt="Current Image">
                </div>

                <div class="mb-3">
                    <label class="form-label">Change Image (Leave blank to keep current image)</label>
                    <input type="file" name="itemimage" class="form-control" accept=".jpg,.jpeg,.png">
                </div>

                <button type="submit" class="btn btn-submit">Update Item</button>
                <a href="menu_management.php" class="btn btn-cancel">Cancel</a>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
sqlsrv_close($conn);
?>