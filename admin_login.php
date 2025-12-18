<?php
$username = $_POST['username'];
$password = $_POST['password'];

$correctUser = "captainadmin";
$correctPass = "admin0329";

if ($username == $correctUser && $password == $correctPass) {
    header("Location: admin_stats.php");
    exit();
} else {
    header("Location: admin_login_error.html");
    exit();
}
?>
