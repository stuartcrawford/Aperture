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
  			<div class="large-12 column"><h2>Authentication Error</h2></div>		
  		</div>
		<div class="row">
  			<div class="large-12 column"><p>You are no longer authenticated as the same user when you visited this page. This could be because you have logged out or logged into a different account since then, or you have been a victim of a CSRF attack.</p></div>		
  		</div>
		<div class="row">
			<div class="large-12 column">
				<?php
					if ($_SESSION['User']['AccessLvl'] == null)
					{
						echo '<a href="',fetchdir($gpages),'login.php">Go to Login</a>';
					}
					else
					{
						echo '<a href="',fetchdir($apages),'my_account.php">Go to My Account</a>';
					}
				?>
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