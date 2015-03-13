<?php include realpath(dirname(__FILE__)."/../../php/global.php"); ?>
<?php
	if (!$_SESSION['Form']['Submit'] == true)
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
  			<div class="large-12 column"><h2>You have been logged out</h2></div>		
  		</div>
		<div class="row">
  			<div class="large-12 column"><p>You have automatically been logged out because you have been inactive for over an hour. In order to continue with what you were doing, you will need to log in again.</p></div>		
  		</div>
		<div class="row">
			<div class="large-12 column">
				<a href="<?php fetchdir($gpages); ?>login.php">Go to Login</a>
				<br><br>
				<a href="<?php fetchdir($gpages); ?>index.php">Return to homepage</a>
			</div>		
  		</div>
		
  		<!-- End Main Content -->
 
 		<!-- Footer -->
 		<?php include realpath(dirname(__FILE__)."/../../php/footer.php"); ?>	
 		<!-- End Footer -->	
	</body>
</html>