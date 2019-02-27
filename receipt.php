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
	$id=$_GET['invoice'];
	$resultas = $db->prepare("SELECT sum(amount) FROM sales_order WHERE invoice= :a");
	$resultas->bindParam(':a', $id);
	$resultas->execute();
	for($i=0; $rowas = $resultas->fetch(); $i++){
	$fgfg=$rowas['sum(amount)'];
	}
?>
<html>
	<head>
		<title>Receipt</title>
		<style>
			body {
				font-family: arial;
				font-size: 12px;
				
			}
			
			.clearfix {
				clear: both;
			}
			table.gridtable th {
				padding: 5px;
			}
			table.gridtable td {
				padding: 5px;
			}
		</style>
		<script language="javascript">
		function Clickheretoprint()
		{ 
		  var disp_setting="toolbar=yes,location=no,directories=yes,menubar=yes,"; 
			  disp_setting+="scrollbars=yes,width=1000, height=500, left=100, top=25"; 
		  var content_vlue = document.getElementById("content").innerHTML; 
		  
		  var docprint=window.open("","",disp_setting); 
		   docprint.document.open(); 
		   docprint.document.write('</head><body onLoad="self.print()" style="width: 1000px; font-size:11px; font-family:arial; font-weight:normal;">');          
		   docprint.document.write(content_vlue); 
		   docprint.document.close(); 
		   docprint.focus(); 
		}
		</script>
	</head>
	<body>
	<a id="addd" href="javascript:Clickheretoprint()">Print</a> | <a id="addd" href="index.php">Back</a>
		<div class="content" id="content" style="width: 850px; margin: 20px auto;">
			<div style="text-align: center;">
			<strong style="font-size: 20px; margin-top: 46px; display: inline-block;">RCYCLE MARKETING</strong><br>
			<STRONG># 4 AN MERCEDEZ BLDG., GALO ST., BACOLOD CITY</STRONG><br>
			TEL. NO. : (034) 708-0843<br><br><br><br>
			</div>
			<div style="float: left;width: 520px;margin-right: 30px;">
				<span style="display: inline-block;width: 150px;text-align: right;padding-right: 20px;margin-bottom: 10px;font-weight: bold;width: 75px;">Sold To :</span> &nbsp;&nbsp;&nbsp;<?php echo $_GET['name']; ?><br>
				<span style="display: inline-block;width: 150px;text-align: right;padding-right: 20px;margin-bottom: 10px;font-weight: bold;width: 75px;">Address :</span> &nbsp;&nbsp;&nbsp;<?php echo $_GET['addrs']; ?><br>
			</div>
			<div style="float: right;width: 300px; margin-bottom: 20px;">
				<span style="font-weight: bold; text-align: left; margin-bottom: 10px; display: inline-block;">Delivery Receipt # :</span><span style="float: right;"><?php echo $_GET['invoice']; ?></span><br>
				<span style="font-weight: bold; text-align: left; margin-bottom: 10px; display: inline-block;">Date :</span><span style="float: right;"><?php echo date("l F d, Y"); ?></span><br>
				<span style="font-weight: bold; text-align: left; margin-bottom: 10px; display: inline-block;">Agent :</span><span style="float: right;"><?php echo $_SESSION['SESS_FIRST_NAME'] ?></span><br>
				<span style="font-weight: bold; text-align: left; margin-bottom: 10px; display: inline-block;">Terms :</span><span style="float: right;"></span><br>
			</div>
			<div class="clearfix"></div>
			<table class="gridtable" style="font-family: verdana,arial,sans-serif; font-size:11px; border-color: #666666; border-collapse: collapse; width: 100%; margin-top: 20px;">
			<tr>
				<th style="border-bottom: 1px solid #000000; width: 80px;">QTY</th><th style="border-bottom: 1px solid #000000; width: 80px;">UNIT</th><th style="border-bottom: 1px solid #000000; width: 80px;">MODEL</th><th style="border-bottom: 1px solid #000000;">DESCRIPTION</th><th style="border-bottom: 1px solid #000000; width: 81px;">UNIT PRICE</th><th style="border-bottom: 1px solid #000000;">DISC</th><th style="border-bottom: 1px solid #000000; width: 85px;">TOTAL</th>
			</tr>
			<?php
				$result = $db->prepare("SELECT * FROM sales_order WHERE invoice= :userid");
				$result->bindParam(':userid', $id);
				$result->execute();
				for($i=0; $row = $result->fetch(); $i++){
			?>
			<tr>
				<td style="text-align: center;"><?php echo $row['qty']; ?></td>
				<td style="text-align: center;"><?php echo $row['unit']; ?></td>
				<td style="text-align: center;"><?php echo $row['name']; ?></td>
				<td><?php echo $row['description']; ?></td>
				<td style="text-align: right;"><?php
				$p=$row['price'];
				echo formatMoney($p, true);
				?></td>
				<td style="text-align: center;"><?php echo $row['discount']; ?></td>
				<td style="text-align: right;"><?php
				$a=$row['amount'];
				echo formatMoney($a, true);
				?></td>
			</tr>
			<?php
			}
			?>
			<tr>
				<th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th colspan="2" style="border-top: 1px solid #000000; text-align: left;">TOTAL</th><th style="border-top: 1px solid #000000; text-align: right;">
				<?php
				$sds=$fgfg;
				echo formatMoney($sds, true);
				?>
				</th>
			</tr>
			<tr>
				<th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th colspan="2" style="border-top: 1px solid #000000; text-align: left;">CASH TENDERED</th><th style="border-top: 1px solid #000000; text-align: right;">
				<?php
				$sds=$_GET['cash'];
				echo formatMoney($sds, true);
				?>
				</th>
			</tr>
			<tr>
				<th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th colspan="2" style="border-top: 1px solid #000000; text-align: left;">CHANGE</th><th style="border-top: 1px solid #000000; text-align: right;">
				<?php
				$sds=$_GET['change'];
				echo formatMoney($sds, true);
				?>
				</th>
			</tr>
			</table>
		</div>
	</body>
</html>