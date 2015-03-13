<?php include realpath(dirname(__FILE__)."/../../php/global.php"); ?>
<?php
	setpermissions('admin','staff','customer');
	if (!is_null($_SESSION['Form']['Success']))
	{
		// Clear form inputs stored in the session to avoid autofilling on success (because the success page is the same page)
		$success = $_SESSION['Form']['Success'];
		unset($_SESSION['Form']);
		$_SESSION['Form']['Success'] = $success;
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
  			<div class="large-12 column"><h2>Feedback</h2></div>		
  		</div>
		<div class="row">
  			<div class="large-6 column">
				<p>Please enter your feedback in the form below<p>
				<form action="<?php fetchdir($php); ?>submit_feedback.php" method="post" id="submit_feedback" name="submit_feedback" class="custom">
					<textarea name="msg" id="msg"><?php fillTextarea('msg'); ?></textarea>
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
					<input type="submit" class="radius button" name="submitfeedback" value="Send"/>
				</form>
			</div>		
  		</div>
		
  		<!-- End Main Content -->
 
 		<!-- Footer -->
 		<?php include realpath(dirname(__FILE__)."/../../php/footer.php"); ?>	
 		<!-- End Footer -->	
	</body>
</html>