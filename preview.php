<?php
session_start();
$errmsg_arr = array();
$errflag = false;
include('../connect.php');
$id= $_POST['ininin'];
$a = $_POST['cname'];
$b = $_POST['address'];
$c = $_POST['total'];
$d = $_POST['cash'];
$e = $_POST['change'];
$f = date("m/d/Y");
if ($c>$d) {
$errmsg_arr[] = 'Pls. input Proper Cash';
$errflag = true;
}
if($errflag) {
	$_SESSION['CASH'] = $errmsg_arr;
	session_write_close();
	header("location: index.php?transaction=$id");
	exit();
}
// query
$sql = "INSERT INTO sales (invoice,date,name,address,amount) VALUES (:a,:b,:c,:d,:e)";
$q = $db->prepare($sql);
$q->execute(array(':a'=>$id,':b'=>$f,':c'=>$a,':d'=>$b,':e'=>$c));
header("location: receipt.php?change=$e&cash=$d&invoice=$id&name=$a&addrs=$b");


?>