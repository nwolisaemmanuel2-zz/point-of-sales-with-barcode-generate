<?php
session_start();
$errmsg_arr = array();
$errflag = false;
include('../connect.php');
$a = $_POST['invoice'];
if ($a!='0') {
$transaction_code=$_POST['invoice'];
}
if ($a=='0') {
function createRandomPassword() {
	$chars = "003232303232023232023456789";
	srand((double)microtime()*1000000);
	$i = 0;
	$pass = '' ;
	while ($i <= 7) {

		$num = rand() % 33;

		$tmp = substr($chars, $num, 1);

		$pass = $pass . $tmp;

		$i++;

	}
	return $pass;
}
$transaction_code='RS-'.createRandomPassword();
}
echo $transaction_code;
echo $a;
$b = $_POST['barcode'];
$c = $_POST['qty'];
$ddddddd = $_POST['disc'];
$result = $db->prepare("SELECT * FROM products WHERE code= :userid");
$result->bindParam(':userid', $b);
$result->execute();
for($i=0; $row = $result->fetch(); $i++){
$asasa=$row['price'];
$name=$row['name'];
$qtylft=$row['qty'];
$ddd=$row['description'];
$uuu=$row['Unit'];
}
if ($qtylft<$c) {
$errmsg_arr[] = 'No Available Stock';
$errflag = true;
}
if($errflag) {
	$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
	session_write_close();
	header("location: index.php?transaction=$transaction_code");
	exit();
}
//edit qty
$sql = "UPDATE products 
        SET qty=qty-?, sold=sold+?
		WHERE code=?";
$q = $db->prepare($sql);
$q->execute(array($c,$c,$b));
$ooooo=$asasa-$ddddddd;
$d=$ooooo*$c;
// query
$sql = "INSERT INTO sales_order (invoice,qty,amount,name,price,description,discount,unit) VALUES (:a,:c,:d,:e,:f,:g,:h,:i)";
$q = $db->prepare($sql);
$q->execute(array(':a'=>$transaction_code,':c'=>$c,':d'=>$d,':e'=>$name,':f'=>$ooooo,':g'=>$ddd,':h'=>$ddddddd,':i'=>$uuu));
header("location: index.php?transaction=$transaction_code");


?>