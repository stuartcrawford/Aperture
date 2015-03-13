<?php include realpath(dirname(__FILE__)."/../../php/global.php"); ?>
<?php
	if (isset($_SESSION['Form']['Email']))
	{
		$heading = 'Registration Complete';
		$text = 'Your account has been activated and <strong>'.$_SESSION['Form']['Email'].'</strong> is now registered on the system. You will now be able to make bookings on the site and view your full booking history through the My Account page.';
	}
	else if ($_SESSION['Form']['Error'] == true)
	{
		$heading = 'Invalid Activation Link';
		$text = 'The activation link is invalid or your account has already been activated';
	}
	else
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
  			<div class="large-12 column"><h2><?php echo $heading; ?></h2></div>		
  		</div>
		<div class="row">
  			<div class="large-12 column"><p><?php echo $text; ?></p></div>		
  		</div>
		<div class="row">
			<div class="large-12 column">
				<?php
					if (isset($_SESSION['Form']['Email']))
					{
						if ($_SESSION['User']['AccessLvl'] == null)
						{
							echo '<a href="',fetchdir($gpages),'login.php">Go to Login</a>';
							echo '<br><br>';
						}
						else if (isset($_SESSION['Form']['Auth']))
						{
							echo '<a href="',fetchdir($apages),'my_account.php">Go to My Account</a>';
							echo '<br><br>';
						}
					}
				?>
				<a href="<?php fetchdir($gpages); ?>index.php">Return to homepage</a>
			</div>		
  		</div>

  		<!-- End Main Content -->
 
 		<!-- Footer -->
 		<?php include realpath(dirname(__FILE__)."/../../php/footer.php"); ?>	
 		<!-- End Footer -];
	</body>
</html>