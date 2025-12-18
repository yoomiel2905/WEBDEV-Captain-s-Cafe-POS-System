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
$itemname=$_POST['itemname'];
$category=$_POST['category'];
$price=$_POST['price'];
$availability=$_POST['availability'];
$oldimage=$_POST['oldimage'];

if($_FILES['itemimage']['name'] != ''){
    $destination="uploads/";
    $filename=basename($_FILES['itemimage']['name']);
    $finalpath=$destination.$filename;
    
    $allowtypes=array('jpg','jpeg','png');
    $filetype=pathinfo($finalpath, PATHINFO_EXTENSION);
    
    if(in_array(strtolower($filetype), $allowtypes)){
        $upload=move_uploaded_file($_FILES['itemimage']['tmp_name'], $finalpath);
        
        if($upload){
            $imagepath=$finalpath;
        }else{
            $imagepath=$oldimage;
        }
    }else{
        $imagepath=$oldimage;
    }
}else{
    $imagepath=$oldimage;
}

$sql="UPDATE MENU_ITEMS SET ITEM_NAME='$itemname', CATEGORY='$category', PRICE='$price', IMAGE_PATH='$imagepath', IS_AVAILABLE='$availability' WHERE ITEMID='$itemid'";
$result=sqlsrv_query($conn,$sql);

if($result){
    header("Location: menu_management.php");
    exit();
}else{
    die(print_r(sqlsrv_errors(),true));
}

sqlsrv_close($conn);
?>