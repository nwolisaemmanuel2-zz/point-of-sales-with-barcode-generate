<?php
	require_once('auth.php');
	function formatMoney($number, $fractional=false) {
		if ($fractional) {
			$number = sprintf('%.2f', $number);
		}
		while (true) {
			$replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number);
			if ($replaced != $number) {
				$number = $replaced;
			} else {
				break;
			}
		}
		return $number;
	}
	include('../connect.php');
?>
<html>
<head>
<title>
POS
</title>
<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
<script src="argiepolicarpio.js" type="text/javascript" charset="utf-8"></script>
<script src="js/application.js" type="text/javascript" charset="utf-8"></script>
<link href="src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
<script src="lib/jquery.js" type="text/javascript"></script>
<script src="src/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
  jQuery(document).ready(function($) {
    $('a[rel*=facebox]').facebox({
      loadingImage : 'src/loading.gif',
      closeImage   : 'src/closelabel.png'
    })
  })
</script>
</head>
<body>
	<div id="mainwrapper">
		<div id="topmenu">
			<ul>
				<li>
					<a href="index.php">
						<img alt="Statistics" src="img/pos.png">
						<span>POS</span>
					</a>
				</li>
				<li>
					<a href="inventory.php">
						<img alt="Statistics" src="img/inventory.png">
						<span>Inventory</span>
					</a>
				</li>
				<li>
					<a class="active" href="products.php">
						<img alt="Statistics" src="img/products.png">
						<span>Products</span>
					</a>
				</li>
				<li>
					<a href="salesreport.php">
						<img alt="Statistics" src="img/salesreport.png">
						<span>Sales Report</span>
					</a>
				</li>
			</ul>
		</div>
		<div id="contentmain">
			<div id="main">
				<div id="salesreg" style="width: 769px;">
					<span id="title">Products</span>
					<div id="dt">
						<input type="text" name="barcode" style="width: 360px;" placeholder="Search...." id="filter" tabindex="1" /><a rel="facebox" id="addd" href="addproduct.php">Add Product</a><br><br>
						<div id="tablecon">
						<table class="gridtable" style="width: 723px;" id="resultTable" data-responsive="table">
						<thead>
						<tr>
							<th>Code</th><th>Model</th><th>Description</th><th>Qty</th><th>Unit</th><th>Cost</th><th>Price</th><th style="width: 50px;">&nbsp;</th>
						</tr>
						</thead>
						<tbody>
						<?php
							$result = $db->prepare("SELECT * FROM products");
							$result->execute();
							for($i=0; $row = $result->fetch(); $i++){
						?>
						<tr class="record">
							<td><?php echo $row['code']; ?></td>
							<td><?php echo $row['name']; ?></td>
							<td><?php echo $row['description']; ?></td>
							<td><?php echo $row['qty']; ?></td>
							<td><?php echo $row['Unit']; ?></td>
							<td><?php
							$p=$row['cost'];
							echo formatMoney($p, true);
							?></td>
							<td><?php
							$a=$row['price'];
							echo formatMoney($a, true);
							?></td>
							<td style="text-align: center;">
							<a rel="facebox" title="Click To Edit" href="editproduct.php?id=<?php echo $row['id']; ?>"> <img src="edit.png" /> </a><a href="#" id="<?php echo $row['id']; ?>" class="delbutton" title="Click To Delete"><img src="delete.png" /><br>
							<a href="barcode/html/BCGcode39.php?id=<?php echo $row['code']; ?>">Generate Barcode</a>
							</td>
						</tr>
						<?php
						}
						?>
						<tbody>
						</table>
						</div>

					</div>
				</div>
			</div>
			<div id="footer">
				<span style="display: inline-block; padding-top: 7px; padding-left: 11px;">Welcome&nbsp;&nbsp;<strong><?php echo $_SESSION['SESS_FIRST_NAME'] ?></strong>&nbsp;|&nbsp;<a href="../index.php">Logout</a></span>
				<div style="width: auto; float: right;">				
				<span style="display: inline-block; padding-top: 7px; padding-right: 11px;">&nbsp;&nbsp;<strong>
				<span id=tick2>
				</span>
				<script>
				function show2(){
				if (!document.all&&!document.getElementById)
				return
				thelement=document.getElementById? document.getElementById("tick2"): document.all.tick2
				var Digital=new Date()
				var hours=Digital.getHours()
				var minutes=Digital.getMinutes()
				var seconds=Digital.getSeconds()
				var dn="PM"
				if (hours<12)
				dn="AM"
				if (hours>12)
				hours=hours-12
				if (hours==0)
				hours=12
				if (minutes<=9)
				minutes="0"+minutes
				if (seconds<=9)
				seconds="0"+seconds
				var ctime=hours+":"+minutes+":"+seconds+" "+dn
				thelement.innerHTML=ctime
				setTimeout("show2()",1000)
				}
				window.onload=show2
				//-->
				</script>
				<?php //echo date("g:i a"); ?>&nbsp;|&nbsp;<?php echo date("l F d, Y"); ?></strong></span>
				</div>
			</div>
		</div>
	</div>
	<script src="js/jquery.js"></script>
  <script type="text/javascript">
$(function() {


$(".delbutton").click(function(){

//Save the link in a variable called element
var element = $(this);

//Find the id of the link that was clicked
var del_id = element.attr("id");

//Built a url to send
var info = 'id=' + del_id;
 if(confirm("Sure you want to delete this update? There is NO undo!"))
		  {

 $.ajax({
   type: "GET",
   url: "deleteproduct.php",
   data: info,
   success: function(){
   
   }
 });
         $(this).parents(".record").animate({ backgroundColor: "#fbc7c7" }, "fast")
		.animate({ opacity: "hide" }, "slow");

 }

return false;

});

});
</script>
</body>
</html>