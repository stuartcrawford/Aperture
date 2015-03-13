<?php include realpath(dirname(__FILE__)."/../../php/global.php"); ?>
<?php
	setpermissions('admin','staff','customer');

	if ($_SESSION['Booking']['Aborted'] == false) {
		
		if(isset($referrer))
		{
			Header ('Location: '.fetchinline($bpages).'page_expired.php');
			exit;
		}
		else
		{
			Header ('Location: '.fetchinline($gpages).'index.php');
			exit;
		}
	}
	$_SESSION['Booking']['Step'] = 0;
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
  			<div class="large-12 column"><h2>Booking Aborted</h2></div>				
  		</div>
		<div class="row">
  			<div class="large-12 column"><p>Your booking was Aborted. 
			Please return to the home page to make a new reservation!</p></div>		
  		</div>
		<div class="row">
  			<div class="large-12 column" id="bookingabortedsign"><img src="<?php fetchdir($img); ?>baborted.jpg" alt="booking aborted" title="booking aborted"/></div>		
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