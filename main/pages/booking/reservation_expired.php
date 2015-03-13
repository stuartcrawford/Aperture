<?php include realpath(dirname(__FILE__)."/../../php/global.php"); ?>
<?php 
	setpermissions('admin','staff','customer');
	
	if ($_SESSION['Booking']['Aborted'] == false) {
		
		if(isset($referrer))
		{
			Header ('Location: '.fetchinline($bpages).'page_expired.php');
		}
		else
		{
			Header ('Location: '.fetchinline($gpages).'index.php');
		}
	}
	
	$_SESSION['Booking']['Aborted'] = false;
?>
<!DOCTYPE html>
<?php include realpath(dirname(__FILE__)."/../../php/ie_js_fix.php"); ?>
	<head>
		<?php include realpath(dirname(__FILE__)."/../../php/head.php"); ?>	
	</head>
	<body>
		<!-- Header -->
		<?php include realpath(dirname(__FILE__)."/../../php/header.php"); ?>
		<!-- End Header -->
			
		<!-- Main Content -->  
				
  		<div id="first_row" class="row">
  			<div class="large-12 column"><h2>Reservation Expired</h2></div>		
  		</div>
		<div class="row">
  			<div class="large-12 column"><p>Your reservation has expired. If you wish to continue with your booking you must start the booking process from the beginning.</p></div>		
  		</div>
		<div class="row">
  			<div class="large-12 column"><a href="<?php fetchdir($gpages); ?>index.php">Return to homepage</a></div>		
  		</div>
		
  		<!-- End Main Content -->
 
 		<!-- Footer -->
 		<?php include realpath(dirname(__FILE__)."/../../php/footer.php"); ?>	
 		<!-- End Footer -->	
	</body>
</html>