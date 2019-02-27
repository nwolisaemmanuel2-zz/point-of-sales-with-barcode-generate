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
	if (isset($_GET["transaction"])) { $tr  = $_GET["transaction"]; } else { $tr=0; }; 
	$resultas = $db->prepare("SELECT sum(amount) FROM sales_order WHERE invoice= :a");
	$resultas->bindParam(':a', $tr);
	$resultas->execute();
	for($i=0; $rowas = $resultas->fetch(); $i++){
	$fgfg=$rowas['sum(amount)'];
	}
	
?>
<html>
<head>
<title>
POS
</title>
<link href="../style.css" media="screen" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js"></script>
<script type="text/javascript" language="Javascript">
	var sum=0;
	price = document.frmOne.total.value;
	document.frmOne.change.value = price;
    function OnChange(value){
		
		payable = document.frmOne.total.value;
		cash = document.frmOne.cash.value;
        sum = cash - payable;
		
		document.frmOne.change.value = sum;
    }
</script>
<script>
window.onload = function() {
  document.getElementById("country").focus();
};
</script>
<SCRIPT language=Javascript>
      <!--
      function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

         return true;
      }
      //-->
   </SCRIPT>

<script>
function suggest(inputString){
		if(inputString.length == 0) {
			$('#suggestions').fadeOut();
		} else {
		$('#country').addClass('load');
			$.post("autosuggest.php", {queryString: ""+inputString+""}, function(data){
				if(data.length >0) {
					$('#suggestions').fadeIn();
					$('#suggestionsList').html(data);
					$('#country').removeClass('load');
				}
			});
		}
	}

	function fill(thisValue) {
		$('#country').val(thisValue);
		setTimeout("$('#suggestions').fadeOut();", 600);
	}

</script>
<style>
#result {
	height:20px;
	font-size:16px;
	font-family:Arial, Helvetica, sans-serif;
	color:#333;
	padding:5px;
	margin-bottom:10px;
	background-color:#FFFF99;
}

.suggestionsBox {
	position: absolute;
	left: 10px;
	margin: 0;
	width: 268px;
	top: 40px;
	padding:0px;
	background-color: #000;
	color: #fff;
}
.suggestionList {
	margin: 0px;
	padding: 0px;
}
.suggestionList ul li {
	list-style:none;
	margin: 0px;
	padding: 6px;
	border-bottom:1px dotted #666;
	cursor: pointer;
}
.suggestionList ul li:hover {
	background-color: #FC3;
	color:#000;
}
ul {
	font-family:Arial, Helvetica, sans-serif;
	font-size:11px;
	color:#FFF;
	padding:0;
	margin:0;
}

.load{
background-image:url(loader.gif);
background-position:right;
background-repeat:no-repeat;
}

#suggest {
	position:relative;
}
.combopopup{
	padding:3px;
	width:268px;
	border:1px #CCC solid;
}

</style>
</head>
<body onLoad="document.getElementById('country').focus();">
	<div id="mainwrapper">
		<div id="topmenu">
			<ul>
				<?php
				$sdsdsd=$_SESSION['SESS_FIRST_NAME'];
				if($sdsdsd=='admin') {
				?>
				<li>
					<a class="active" href="index.php">
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
					<a href="salesreport.php">
						<img alt="Statistics" src="img/salesreport.png">
						<span>Sales Report</span>
					</a>
				</li>
				<?php
				}
				if($sdsdsd=='cashier') {
				?>
				<li>
					<a class="active" href="index.php">
						<img alt="Statistics" src="img/pos.png">
						<span>POS</span>
					</a>
				</li>
				<?php
				}
				?>
			</ul>
		</div>
		<div id="contentmain">
			<div id="main">
				<div id="salesreg">
					<span id="title">Sales register</span>
					<div id="dt" style="position: relative;">
							<?php
							if( isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) >0 ) {
								foreach($_SESSION['ERRMSG_ARR'] as $msg) {
									echo '<span style="color:red;">',$msg,'</span>'; 
								}
								unset($_SESSION['ERRMSG_ARR']);
							}
							?>
						<form action="enter.php" method="post">
						<input type="hidden" name="invoice" id="lform" value="<?php echo $tr ?>" />
						<input type="text" name="barcode" id="country" onkeyup="suggest(this.value);" onblur="fill();" class="" autocomplete="off" placeholder="Enter Model or Barcode Here" tabindex="1" />
						<div class="suggestionsBox" id="suggestions" style="display: none;">
							<div class="suggestionList" id="suggestionsList"> &nbsp; </div>
						</div>
						<input type="text" name="qty" tabindex="2" id="lform" style="width: 80px;" placeholder="Quantity" onkeypress="return isNumberKey(event)" />
						<input type="text" name="disc" tabindex="2" id="lform" style="width: 80px;" placeholder="Discount" onkeypress="return isNumberKey(event)" />
						<input id="btn" type="submit" tabindex="3" value="Enter" id="btn" />
						</form>
						<div id="tablecon">
						<table class="gridtable">
						<tr>
							<th>QTY</th><th>UNIT</th><th>MODEL</th><th>DESCRIPTION</th><th>UNIT PRICE</th><th>DISC</th><th>TOTAL</th><th>&nbsp;</th>
						</tr>
						<?php
							$result = $db->prepare("SELECT * FROM sales_order WHERE invoice= :userid");
							$result->bindParam(':userid', $tr);
							$result->execute();
							for($i=0; $row = $result->fetch(); $i++){
						?>
						<tr>
							<td><?php echo $row['qty']; ?></td>
							<td><?php echo $row['unit']; ?></td>
							<td><?php echo $row['name']; ?></td>
							<td><?php echo $row['description']; ?></td>
							<td><?php
							$p=$row['price'];
							echo formatMoney($p, true);
							?></td>
							<td><?php echo $row['discount']; ?></td>
							<td><?php
							$a=$row['amount'];
							echo formatMoney($a, true);
							?></td>
							<td style="text-align: center;">
							<a href="delete.php?id=<?php echo $row['id']; ?>&invoice=<?php echo $tr; ?>&qty=<?php echo $row['qty'];?>&code=<?php echo $row['name'];?>"> <img src="delete.png" /> </a>
							</td>
						</tr>
						<?php
						}
						?>
						</table>
						</div>

					</div>
				</div>
				<div id="salessummary">
					
					<form NAME = "frmOne" action="preview.php" method="post">
					<input type="hidden" name="ininin" id="lform" value="<?php echo $tr ?>" />
					<span id="title">Sales sumary</span>
					<div id="total">
					<?php
					if( isset($_SESSION['CASH']) && is_array($_SESSION['CASH']) && count($_SESSION['CASH']) >0 ) {
						foreach($_SESSION['CASH'] as $msg) {
							echo '<span style="color:red;">',$msg,'</span>'; 
						}
						unset($_SESSION['CASH']);
					}
					?>
						<div id="customer">
							Customer Details
						</div>
						<input type="text" name="cname" id="lform" style="width: 208px;" placeholder="Customer Name"  required="required"/><br><br>
						<input type="text" name="address" id="lform" style="width: 208px;" placeholder="Address"  required="required"/><br>
					</div>
					
					<div id="total">
						<div id="customer">
							Sales Details
						</div>
						<div style="padding-top: 5px; padding-bottom: 5px;"><span style="float: left; font-size: 14px; font-weight: bold;">12 % VAT :</span><span style="float: right; font-size: 14px; font-weight: bold;">Php
						<?php
						$as=$fgfg;
						$vat=$as*.12;
						echo formatMoney($vat, true);
						?>
						</span><div class="clearfix"></div></div>
						<div style="padding-top: 5px; padding-bottom: 5px;"><span style="float: left; font-size: 14px; font-weight: bold;">Vatable :</span><span style="float: right; font-size: 14px; font-weight: bold;">Php
						<?php
						$as=$fgfg;
						$vt=$as-$vat;
						echo formatMoney($vt, true);
						?>
						</span><div class="clearfix"></div></div>
						<div style="padding-top: 5px; padding-bottom: 5px;"><span style="float: left; font-size: 14px; font-weight: bold;">Total :</span><span style="float: right; font-size: 14px; font-weight: bold;">Php
						<?php
						$sds=$fgfg;
						echo formatMoney($sds, true);
						?>
						</span><div class="clearfix"></div></div>
					</div>
					
					<div id="total">
						<div id="customer">
							Payment Details
						</div>
						<input type="hidden" name="total" id="lform" style="width: 208px;" placeholder="Cash" value="<?php echo $fgfg ?>" />
						<input type="text" name="cash" id="lform" style="width: 208px;" placeholder="Cash" onkeyup='OnChange(this.value)' onkeypress="return isNumberKey(event)" autocomplete="off"  required="required"/><br><br>
						<input type="text" name="change" id="lform" style="width: 208px;" placeholder="Change" readonly/><br><br>
						<input id="btn" type="submit" value="Print" id="btn" /><br><br>
					</div>
					</form>
				</div>
				<div class="clearfix"></div>
			</div>
			<div id="footer">
				<span style="display: inline-block; padding-top: 5px; padding-left: 11px;">Welcome&nbsp;&nbsp;<strong><?php echo $_SESSION['SESS_FIRST_NAME'] ?></strong>&nbsp;|&nbsp;<a href="../index.php">Logout</a></span>
				<div style="width: auto; float: right;">				
				<span style="display: inline-block; padding-top: 5px; padding-right: 11px;">&nbsp;&nbsp;<strong>
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
</body>
</html>