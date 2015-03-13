<?php include realpath(dirname(__FILE__)."/../../php/global.php"); ?>
<?php if (!$referrer) Header('Location: '.fetchinline($gpages).'index.php') ?>
<?php setpermissions('admin','staff','customer'); ?>
<?php $_SESSION['Booking']['Step'] = 0; ?>
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
			<img src="<?php fetchdir($img); ?>expired.jpg" alt="Page Expired" title="Please reload the page!" />
  		</div>
		<br>
		<div class="row">
  			<div class="large-12 column"><a href="<?php fetchdir($gpages); ?>index.php">Return to homepage</a></div>		
  		</div>
		
  		<!-- End Main Content -->
 
 		<!-- Footer -->
 		<?php include realpath(dirname(__FILE__)."/../../php/footer.php"); ?>	
 		<!-- End Footer -->	
	</body>
</html>