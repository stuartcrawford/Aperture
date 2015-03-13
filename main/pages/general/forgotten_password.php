<?php include realpath(dirname(__FILE__)."/../../php/global.php"); ?>
<?php
	if (!is_null($_SESSION['User']['AccessLvl']))
	{
		header('Location: '.fetchinline($gpages).'index.php');
	}
?>
<!DOCTYPE html>
<?php include realpath(dirname(__FILE__)."/../../php/ie_js_fix.php"); ?>
	<head>
		<?php include realpath(dirname(__FILE__)."/../../php/head.php"); ?>	
		<script>
			function OnloadFunction()
			{
				$('#submitemail').click(function (e)
				{
					e.preventDefault();
					var email = $('#accountemail').val();
					$.ajax({
						url: "<?php echo fetchdir($php).'forgotten_password.php'; ?>",
						data: "submitemail=&ajax=&accountemail="+email,
						type: "POST",
						async: false,
						success: function(data) {
							if (data == 'Redirect')
							{
								window.location = '<?php fetchdir($gpages); ?>authentication_error.php';
							}
							else
							{
								$("#security").html(data);
								OnloadFunction();
							}
						}
					});
				});
			}
			// Set onready functions again after ajax calls by calling OnloadFunction() in final callback
			$(document).ready(function(){OnloadFunction();});
		</script>
	</head>
	<body>
		<!-- Header -->
		<?php include realpath(dirname(__FILE__)."/../../php/header.php"); ?>
		<!-- End Header -->
			
		<!-- Main Content -->  
		
		<div id="security">
			<div id="first_row" class="row">
				<div class="large-12 column"><h2>Forgotten Password</h2></div>		
			</div>
			<div class="row">
				<div class="large-12 column"><p id="message">To reset your password, you must enter the email address associated with your account and answer the 2 security questions you set up when registering. After successfully completing this form you will receive an email providing a new password, which you will then be able to log in with.</p></div>		
			</div>
			<div class="row">
				<div class="large-6 column">
					<form action="<?php echo fetchdir($php).$currentFile; ?>" method="post" id="enter_email" name="enter_email" class="nocsrf">
						<label for="accountemail">Email address</label><input type="text" name="accountemail" id="accountemail"<?php fillInput('Email'); ?>/>
						<?php
							if (!is_null($_SESSION['Form']['Error']))
							{
								echo '<p id="fb" style="color:red">'.$_SESSION['Form']['Error'].'</p>';
							}
							if (isset($_SESSION['Form']['SetEmail']))
							{
								echo '<input type="submit" class="radius button small" name="submitemail" id="submitemail" value="Update"/>';
							}
							else
							{
								echo '<input type="submit" class="radius button small" name="submitemail" id="submitemail" value="Continue"/>';
							}
							
							// Second part of form appears through ajax
							if (isset($_SESSION['Form']['SetEmail']))
							{
								dbconnect();
								
								$stmt = $conn->prepare("SET @email=:email");
								$stmt->bindParam(':email',$_SESSION['Form']['SetEmail']);
								$stmt->execute();
								$stmt = $conn->prepare("SELECT sq FROM Security_Question WHERE sqid IN ((SELECT sq1 FROM User WHERE email=@email),(SELECT sq2 FROM User WHERE email=@email))");
								$stmt->execute();
								$i = 0;
								foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
								{
									$sq[$i] = $row['sq'];
									$i++;
								}
								
								echo '
									<h5>Security Questions</h5>
									<label for="q1">'.$sq[0].'</label><input type="text" name="q1" id="q1"',fillInput('Q1'),'/>
									<label for="q2">'.$sq[1].'</label><input type="text" name="q2" id="q2"',fillInput('Q2'),'/>
									<input type="hidden" name="setemail" id="setemail" value="'.$_SESSION['Form']['SetEmail'].'"/>
								';
								if (isset($_SESSION['Form']['Error2']))
								{
									echo '<p id="fb" style="color:red">'.$_SESSION['Form']['Error2'].'</p>';
								}
								echo '
									<input type="submit" class="radius button small success" name="submit" id="submit" value="Reset Password"/>
								';
							}
						?>
					</form>
				</div>		
			</div>
		</div>
		
  		<!-- End Main Content -->
 
 		<!-- Footer -->
 		<?php include realpath(dirname(__FILE__)."/../../php/footer.php"); ?>	
 		<!-- End Footer -->	
	</body>
</html>