<?php
// configuration
include('../connect.php');

// new data
$id = $_POST['memi'];
$a = $_POST['code'];
$b = $_POST['name'];
$c = $_POST['cost'];
$d = $_POST['price'];
$f = $_POST['qty'];
$g = $_POST['description'];
$h = $_POST['unit'];
// query
$sql = "UPDATE products 
        SET code=?, name=?, cost=?, price=?, qty=?, description=?, Unit=?
		WHERE id=?";
$q = $db->prepare($sql);
$q->execute(array($a,$b,$c,$d,$f,$g,$h,$id));
header("location: products.php");

?>