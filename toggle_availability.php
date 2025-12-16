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

$itemid=$_POST['itemid'];
$status=$_POST['status'];

$sql="UPDATE MENU_ITEMS SET IS_AVAILABLE='$status' WHERE ITEMID='$itemid'";
$result=sqlsrv_query($conn,$sql);

if($result){
    header("Location: menu_management.php");
    exit();
}else{
    die(print_r(sqlsrv_errors(),true));
}

sqlsrv_close($conn);
?>