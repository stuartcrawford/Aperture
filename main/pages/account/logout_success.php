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
		
		<!-- Redirect Timer -->
		<script>
			var time_left = 5;
			var update_interval;

			function redirect_timer(){
  				time_left--;
  				document.getElementById('countdown').innerHTML = time_left;
  				
  				if(time_left == 0){
    				clearInterval(update_interval);
    				window.location.replace("<?php fetchdir($gpages)?>index.php");
  				}
			}

			update_interval = setInterval('redirect_timer()', 1000);
		</script>	
	</head>
	<body>
		<!-- Header -->
		<?php include realpath(dirname(__FILE__)."/../../php/header.php"); ?>
		<!-- End Header -->
			
		<!-- Main Content -->  
				
  		<div class="row">
  			<img id="poorwheatley" src="<?php fetchdir($img); ?>Wheatley-portal-2-28647605-1024-672.jpg" alt="poorwheatley" title="The end" />
  		</div>
  		<div class="row">	
  			<div class="large-12 columns "><h2>Logout Succesful</h2></div>	
		</div>  						
  		<div class="row">
  			<div class="large-12 columns"><p>You will be redirected in <span id="countdown">5</span> seconds please click <a href="<?php fetchdir($gpages); ?>index.php">here</a> if it takes longer</p></div>	
  		</div>
		
  		<!-- End Main Content -->
 
 		<!-- Footer -->
 		<?php include realpath(dirname(__FILE__)."/../../php/footer.php"); ?>	
 		<!-- End Footer -->	
	</body>
</html>