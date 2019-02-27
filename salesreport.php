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
	if (isset($_GET["d1"])) { $do  = $_GET["d1"]; } else { $do=0; }; 
	if (isset($_GET["d2"])) { $dt  = $_GET["d2"]; } else { $dt=0; }; 
?>
<html>
<head>
<title>
POS
</title>
<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
<script language="javascript">
function Clickheretoprint()
{ 
  var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,"; 
	  disp_setting+="scrollbars=yes,width=1000, height=500, left=100, top=25"; 
  var content_vlue = document.getElementById("tablecon").innerHTML; 
  
  var docprint=window.open("","",disp_setting); 
   docprint.document.open(); 
   docprint.document.write('</head><body onLoad="self.print()" style="width: 1000px; font-size:11px; font-family:arial; font-weight:normal;">');          
   docprint.document.write(content_vlue); 
   docprint.document.close(); 
   docprint.focus(); 
}
</script>
<link rel="stylesheet" type="text/css" href="tcal.css" />
<script type="text/javascript" src="tcal.js"></script>
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
					<a href="products.php">
						<img alt="Statistics" src="img/products.png">
						<span>Products</span>
					</a>
				</li>
				<li>
					<a class="active" href="salesreport.php">
						<img alt="Statistics" src="img/salesreport.png">
						<span>Sales Report</span>
					</a>
				</li>
			</ul>
		</div>
		<div id="contentmain">
			<div id="main">
				<div id="salesreg" style="width: 769px;">
					<span id="title">Sales Report</span><a id="addd" href="javascript:Clickheretoprint()">Print</a>
					<div id="dt">
						<form action="salesreport.php" method="get">
							From : <input type="text" name="d1" class="tcal" value="" /> To: <input type="text" name="d2" class="tcal" value="" /> <input id="btn" type="submit" value="Search">
						</form>
						<div id="tablecon">
						<div style="text-align: center; font-weight: bold; margin: 5px 0;"> Sales Report From : <?php echo $do ?> To : <?php echo $dt ?>
						</div>
						<table class="gridtable" style="width: 723px; text-align: left;" id="resultTable" data-responsive="table">
						<thead>
						<tr>
							<th>Date</th><th>Invoice</th><th>Name</th><th>Amount</th>
						</tr>
						</thead>
						<tbody>
						<?php
							$result = $db->prepare("SELECT * FROM sales WHERE date BETWEEN :a AND :b");
							$result->bindParam(':a', $do);
							$result->bindParam(':b', $dt);
							$result->execute();
							for($i=0; $row = $result->fetch(); $i++){
						?>
						<tr class="record">
							<td><?php echo $row['date']; ?></td>
							<td><?php echo $row['invoice']; ?></td>
							<td><?php echo $row['name']; ?></td>
							<td><?php
							$p=$row['amount'];
							echo formatMoney($p, true);
							?></td>
						</tr>
						<?php
						}
						?>
						<tbody>
						<thead>
						<tr>
							<th colspan="3">Total</th>
							<th>
							<?php
							$results = $db->prepare("SELECT sum(amount) FROM sales WHERE date BETWEEN :c AND :d");
							$results->bindParam(':c', $do);
							$results->bindParam(':d', $dt);
							$results->execute();
							for($i=0; $rowas = $results->fetch(); $i++){
							$s=$rowas['sum(amount)'];
							echo formatMoney($s, true);
							}
							?>
							</th>
						</tr>
						</thead>
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
				&nbsp;|&nbsp;<?php echo date("l F d, Y"); ?></strong></span>
				</div>
			</div>
		</div>
	</div>
</body>
</html>