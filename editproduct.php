<?php
	include('../connect.php');
	$id=$_GET['id'];
	$result = $db->prepare("SELECT * FROM products WHERE id= :userid");
	$result->bindParam(':userid', $id);
	$result->execute();
	for($i=0; $row = $result->fetch(); $i++){
?>
<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
<form action="saveeditproduct.php" method="post">
<div id="ac">
<input type="hidden" name="memi" value="<?php echo $id; ?>" />
<span>Code : </span><br><input type="text" name="code" value="<?php echo $row['code']; ?>" id="lform" /><br>
<span>Model : </span><br><input type="text" name="name" value="<?php echo $row['name']; ?>" id="lform" /><br>
<span>Description : </span><br><textarea name="description" id="lform"><?php echo $row['description']; ?></textarea><br>
<span>Unit : </span><br><input type="text" name="unit" id="lform" value="<?php echo $row['Unit']; ?>" /><br>
<span>Cost : </span><br><input type="text" name="cost" value="<?php echo $row['cost']; ?>" id="lform" /><br>
<span>Price : </span><br><input type="text" name="price" value="<?php echo $row['price']; ?>" id="lform" /><br>
<span>Qty : </span><br><input type="text" name="qty" value="<?php echo $row['qty']; ?>" id="lform" /><br>
<br><input id="btn" type="submit" value="save" />
</div>
</form>
<?php
}
?>