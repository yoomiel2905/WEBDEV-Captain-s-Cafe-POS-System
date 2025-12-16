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

// Get drinks (ONLY AVAILABLE)
$sqlDrinks="SELECT * FROM MENU_ITEMS WHERE CATEGORY='DRINKS' AND IS_AVAILABLE=1 ORDER BY ITEM_NAME";
$resultDrinks=sqlsrv_query($conn,$sqlDrinks);

// Get food (ONLY AVAILABLE)
$sqlFood="SELECT * FROM MENU_ITEMS WHERE CATEGORY='FOOD' AND IS_AVAILABLE=1 ORDER BY ITEM_NAME";
$resultFood=sqlsrv_query($conn,$sqlFood);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Captain's Cafe - Cashier POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('images/bg.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .header-bar {
            background-color: #3d8060;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .header-bar img {
            width: 50px;
            margin-right: 10px;
            vertical-align: middle;
        }
        .header-bar h2 {
            display: inline;
            vertical-align: middle;
            margin: 0;
        }
        .logout-btn {
            position: absolute;
            right: 20px;
            top: 15px;
        }
        .category-header {
            background-color: #daa847;
            color: black;
            padding: 10px;
            border-radius: 8px;
            margin-top: 20px;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .menu-item {
            background: white;
            border: 2px solid #daa847;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
        }
        .menu-item:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .menu-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .menu-item h5 {
            color: #3d8060;
            font-size: 16px;
            margin: 5px 0;
        }
        .menu-item .price {
            color: #d93545;
            font-weight: bold;
            font-size: 18px;
        }
        .cart-section {
            background: white;
            border: 3px solid #3d8060;
            border-radius: 10px;
            padding: 20px;
            position: sticky;
            top: 20px;
        }
        .cart-header {
            background-color: #3d8060;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 15px;
        }
        .cart-item {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .discount-section {
            background-color: #fff3cd;
            padding: 10px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .total-section {
            background-color: #daa847;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        .total-section h4 {
            margin: 5px 0;
            color: black;
        }
        .btn-checkout {
            background-color: #3d8060;
            color: white;
            width: 100%;
            padding: 12px;
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header-bar">
        <img src="images/logo.png" alt="Logo">
        <h2>Captain's Cafe - Cashier</h2>
        <a href="login_page.html" class="btn btn-warning btn-sm logout-btn">Logout</a>
    </div>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-8">
                <h2 style="color: white;">Order Menu</h2>

                <div class="category-header">DRINKS</div>
                <div class="row">
                    <?php
                    while($drink=sqlsrv_fetch_array($resultDrinks)){
                        echo '<div class="col-md-4">';
                        echo '<div class="menu-item" onclick="addItem(\''.$drink['ITEM_NAME'].'\', '.$drink['PRICE'].')">';
                        echo '<img src="'.$drink['IMAGE_PATH'].'" alt="'.$drink['ITEM_NAME'].'">';
                        echo '<h5>'.$drink['ITEM_NAME'].'</h5>';
                        echo '<div class="price">P'.number_format($drink['PRICE'],2).'</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>

                <div class="category-header">FOOD</div>
                <div class="row">
                    <?php
                    while($food=sqlsrv_fetch_array($resultFood)){
                        echo '<div class="col-md-4">';
                        echo '<div class="menu-item" onclick="addItem(\''.$food['ITEM_NAME'].'\', '.$food['PRICE'].')">';
                        echo '<img src="'.$food['IMAGE_PATH'].'" alt="'.$food['ITEM_NAME'].'">';
                        echo '<h5>'.$food['ITEM_NAME'].'</h5>';
                        echo '<div class="price">P'.number_format($food['PRICE'],2).'</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>

            <div class="col-md-4">
                <form action="save_order.php" method="POST" id="orderForm">
    <input type="hidden" name="usertype" value="cashier">
    <div class="cart-section">
                        <div class="cart-header">
                            <h4>Current Order</h4>
                        </div>

                        <div id="cartItems"></div>

<div class="discount-section">
    <label><b>Discount:</b></label><br>
    <input type="radio" name="discount" value="0" checked onchange="calcTotal()">
    <label>No Discount</label><br>
    <input type="radio" name="discount" value="20" onchange="calcTotal()">
    <label>Senior Citizen (20% OFF)</label>
</div>

<div class="discount-section">
    <label><b>Payment Method:</b></label><br>
    <input type="radio" name="payment" value="Cash" checked required>
    <label>Cash</label><br>
    <input type="radio" name="payment" value="GCash" required>
    <label>GCash</label><br>
    <input type="radio" name="payment" value="Card" required>
    <label>Card</label>
</div>

                        <div class="total-section">
                            <h4>Subtotal: P<span id="sub">0.00</span></h4>
                            <h4>Discount: -P<span id="disc">0.00</span></h4>
                            <h4><b>TOTAL: P<span id="tot">0.00</span></b></h4>
                        </div>

                        <input type="hidden" name="subtotalValue" id="subVal">
                        <input type="hidden" name="discountValue" id="discVal">
                        <input type="hidden" name="totalValue" id="totVal">
                        <input type="hidden" name="itemCount" id="count" value="0">

                        <button type="submit" class="btn btn-checkout">Checkout</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var idx = 0;

        function addItem(n, p) {
            var cart = document.getElementById('cartItems');
            var div = document.createElement('div');
            div.className = 'cart-item';
            div.id = 'item' + idx;
            div.innerHTML = '<b>' + n + '</b> - P' + p + 
                '<input type="hidden" name="itemName' + idx + '" value="' + n + '">' +
                '<input type="hidden" name="itemPrice' + idx + '" value="' + p + '">' +
                '<input type="hidden" name="itemQty' + idx + '" value="1">' +
                '<br><button type="button" class="btn btn-sm btn-danger" onclick="removeItem(' + idx + ')">Remove</button>';
            cart.appendChild(div);
            idx++;
            document.getElementById('count').value = idx;
            calcTotal();
        }

        function removeItem(id) {
            document.getElementById('item' + id).remove();
            calcTotal();
        }

        function calcTotal() {
            var total = 0;
            var inputs = document.querySelectorAll('input[name^="itemPrice"]');
            for(var i = 0; i < inputs.length; i++) {
                total += parseFloat(inputs[i].value);
            }
            
            var disc = 0;
            var radios = document.getElementsByName('discount');
            for(var i = 0; i < radios.length; i++) {
                if(radios[i].checked) {
                    disc = parseFloat(radios[i].value);
                }
            }
            
            var discAmt = total * disc / 100;
            var final = total - discAmt;
            
            document.getElementById('sub').textContent = total.toFixed(2);
            document.getElementById('disc').textContent = discAmt.toFixed(2);
            document.getElementById('tot').textContent = final.toFixed(2);
            
            document.getElementById('subVal').value = total.toFixed(2);
            document.getElementById('discVal').value = discAmt.toFixed(2);
            document.getElementById('totVal').value = final.toFixed(2);
        }
    </script>
</body>
</html>

<?php
sqlsrv_close($conn);
?>