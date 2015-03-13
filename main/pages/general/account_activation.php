<?php include realpath(dirname(__FILE__)."/../../php/global.php"); ?>
<?php
	if ($_SESSION['User']['Activated'] == 'false')
	{
		dbconnect();
									
		$stmt = $conn->prepare("SELECT Email FROM User WHERE UserID = :id");
		$stmt->bindParam(':id',$_SESSION['User']['ID']);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$email = htmlentities($row['Email']);
		
		$message = "Before you can use your account it will need to be activated. You should have received an email containing an activation link. Please open this email and click the activation link to activate your account. Be sure to check the junk folder if you cannot find it in your inbox. If you haven't received the email, please click the resend button to send another email to <strong>$email</strong>.";
		if (isset($referrer) && isset($_GET['action']))
		{
			if ($_GET['action'] == '1')
			{
				$message = "Your account has been created successfully. Before you can use your account it will need to be activated. You will shortly receive an email containing an activation link. Please open this email and click the activation link to activate your account. Be sure to check the junk folder if you cannot find it in your inbox. If you haven't received the email within a few minutes, please click the resend button to send another email to <strong>$email</strong>.";
			}
			else if ($_GET['action'] == '2')
			{
				$message = "You have logged in successfully. Before you can use your account it will need to be activated. You should have received an email containing an activation link. Please open this email and click the activation link to activate your account. Be sure to check the junk folder if you cannot find it in your inbox. If you haven't received the email, please click the resend button to send another email to <strong>$email</strong>.";
			}
		}
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
  			<div class="large-12 column"><h2>Account Activation</h2></div>		
  		</div>
		<div class="row">
  			<div class="large-12 column"><p><?php echo $message; ?></p></div>		
  		</div>
		<div class="row">
			<div class="large-6 column">
				<form action="<?php echo fetchdir($php).'resend_activation.php'; ?>" method="post" id="resend_email" name="resend_email" class="custom">
					<?php
						if (!is_null($_SESSION['Form']['Success']))
						{
							echo '<p id="fb" style="color:green">'.$_SESSION['Form']['Success'].'</p>';
						}
						else if (!is_null($_SESSION['Form']['Error']))
						{
							echo '<p id="fb" style="color:red">'.$_SESSION['Form']['Error'].'</p>';
						}
					?>
					<input type="submit" class="radius button small" name="submit" value="Resend"/>
				</form>
			</div>
  		</div>
		
  		<!-- End Main Content -->
 
 		<!-- Footer -->
 		<?php include realpath(dirname(__FILE__)."/../../php/footer.php"); ?>	
 		<!-- End Footer -->	
	</body>
</html>