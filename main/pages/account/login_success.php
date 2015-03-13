<?php include realpath(dirname(__FILE__)."/../../php/global.php"); ?>
<?php
	if (!$_SESSION['Form']['Referrer'] == true)
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
    				window.location.replace("<?php echo $_SESSION['Form']['Referrer']; ?>");
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
				
		 <div id="first_row" class="row">
  			<img id="spacecore" src="<?php fetchdir($img); ?>space_core_download_by_hanaminasho-d3g6a8s.png.jpg" alt="spacecore" title="Spaceeeeeeeeeeeeeeeeeeeeeee" />
  		</div>
  		<div class="row">	
  			<div class="large-12 columns "><h2>Login Succesful</h2></div>	
		</div>  						
  		<div class="row">
  			<div class="large-12 columns"><p>You will be redirected in <span id="countdown">5</span> seconds please click <a href="<?php echo $_SESSION['Form']['Referrer']; ?>">here</a> if it takes longer</p></div>	
  		</div>
		
  		<!-- End Main Content -->
 
 		<!-- Footer -->
 		<?php include realpath(dirname(__FILE__)."/../../php/footer.php"); ?>	
 		<!-- End Footer -->	
	</body>
</html>