<?php include realpath(dirname(__FILE__)."/../../php/global.php"); ?>
<?php
	if (!is_null($_SESSION['User']['AccessLvl']))
	{
		header('Location: '.fetchinline($apages).'my_account.php');
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
  			<div class="large-12 column"><h2>Login</h2></div>		
  		</div>
		<div class="row">
  			<div class="large-6 column">
				<form action="<?php fetchdir($php); ?>login.php" method="post" id="login" name="login" class="custom">
					<label for="email">Email</label><input<?php fillInput('Email'); ?> type="text" name="email" id="email"/>
					<label for="password">Password</label><input type="password" name="password" id="password"/>
					<?php
						if (!is_null($_SESSION['Form']['Error']))
						{
							echo '<p id="fb" style="color:red">'.$_SESSION['Form']['Error'].'</p>';
						}
					?>
					<input type="submit" class="radius button" name="submit" value="Login"/>
				</form>
			</div>		
  		</div>
  		<div class="row">
  			<div class="large-12 column">
				<p>I haven't got an account yet, <a href="<?php fetchdir($gpages); ?>registration.php">take me to registration!</a>
				<br>Forgotten password? <a href="<?php fetchdir($gpages); ?>forgotten_password.php">Reset my password</a></p>
			</div>	
  		</div>
		
  		<!-- End Main Content -->
 
 		<!-- Footer -->
 		<?php include realpath(dirname(__FILE__)."/../../php/footer.php"); ?>	
 		<!-- End Footer -->	
	</body>
</html>