<?php include realpath(dirname(__FILE__)."/../../php/global.php"); ?>
<?php
	if (!isset($_SESSION['Form']['Email']))
	{
		header('Location: '.fetchinline($gpages).'index.php');
	}
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
  			<div class="large-12 column"><h2>Password Reset</h2></div>		
  		</div>
		<div class="row">
  			<div class="large-12 column"><p>Your password has been reset. An email has been sent to <strong><?php echo htmlentities($_SESSION['Form']['Email']); ?></strong> which contains a randomly generated password. Be sure to change your password to something more memorable after logging in, using the password change facility on the My Account page.</p></div>		
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