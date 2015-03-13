<?php include realpath(dirname(__FILE__)."/../../php/global.php"); ?>
<?php
	dbconnect();

	if ($_SESSION['User']['ID'] == null || $_SESSION['User']['AccessLvl'] != null)
	{
		// Redirect without a valid session
		header('Location: '.fetchinline($gpages).'index.php');
		exit;
	}
	
	// Get next login time
	$stmt = $conn->prepare("SELECT NextLogin FROM User WHERE UserID=:id");
	$stmt->bindParam(':id',$_SESSION['User']['ID']);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	
	$timeout = '';
	if (strtotime($row['NextLogin']) > time())
	{
		// Get time remaining in minutes
		$timeout = strtotime($row['NextLogin']) - time();
		$timeout = ceil($timeout / 60);
		if ($timeout == 1)
		{
			$timeout .= ' minute';
		}
		else
		{
			$timeout .= ' minutes';
		}
	}
	else
	{
		// Revoke access to max out login page and redirect to login form
		$_SESSION['User']['ID'] = null;
		header('Location: '.fetchinline($gpages).'login.php');
		exit;
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
  			<div class="large-12 column"><h2>Max Login Attempts Reached</h2></div>		
  		</div>
		<div class="row">
  			<div class="large-12 column"><p>You have attempted to login with incorrect details too many times. You must wait <?php echo $timeout; ?> before you will be able to login again. If you are still experiencing problems then, please reset your password on the forgotten password page.</p></div>		
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