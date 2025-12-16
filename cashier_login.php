<?php
$username = $_POST['username'];
$password = $_POST['password'];

$correctUser = "captainuser";
$correctPass = "cashier0329";

if ($username == $correctUser && $password == $correctPass) {
    header("Location: cashier_page.php");
    exit();
} else {
    header("Location: cashier_login_error.html");
    exit();
}
?>