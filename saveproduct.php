<?php
session_start();
include('../connect.php');
$a = $_POST['code'];
$b = $_POST['name'];
$c = $_POST['cost'];
$d = $_POST['price'];
$f = $_POST['qty'];
$g = $_POST['description'];
$h = $_POST['unit'];
// query
$sql = "INSERT INTO products (code,name,cost,price,qty,description,Unit) VALUES (:a,:b,:c,:d,:f,:g,:h)";
$q = $db->prepare($sql);
$q->execute(array(':a'=>$a,':b'=>$b,':c'=>$c,':d'=>$d,':f'=>$f,':g'=>$g,':h'=>$h));
header("location: products.php");


?>