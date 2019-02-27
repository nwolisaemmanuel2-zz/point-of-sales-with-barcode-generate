<?php
	include('../connect.php');
	$id=$_GET['id'];
	$c=$_GET['invoice'];
	$qty=$_GET['qty'];
	$wapak=$_GET['code'];
	//edit qty
	$sql = "UPDATE products 
			SET qty=qty+?
			WHERE name=?";
	$q = $db->prepare($sql);
	$q->execute(array($qty,$wapak));

	$result = $db->prepare("DELETE FROM sales_order WHERE id= :memid");
	$result->bindParam(':memid', $id);
	$result->execute();
	header("location: index.php?transaction=$c");
?>