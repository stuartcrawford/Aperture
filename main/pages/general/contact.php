<?php include realpath(dirname(__FILE__)."/../../php/global.php"); ?>
<?php
	if (!is_null($_SESSION['Form']['Success']))
	{
		// Clear form inputs stored in the session to avoid autofilling on success
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
  			<div class="large-12 column"><h2>Contact Us</h2></div>		
  		</div>
		<div class="row">
  			<div class="large-12 column">
  				<!--<p style="font-size:90%;">
			We realise our customers and staff are the corner stone to the continuing success of Aperture Car Hire and we consider the two equally important.<br>
			All employees at Aperture Car Hire work hard, with purpose and enthusiasm to give every customer who walks through the doors the best possible experience while providing a great service. 
			It goes without saying how important the customers are. However we do not take this for granted.<br>
			We are committed to finding out exactly what customers want and expect when they visit one of our branches.  It is this vision of consistency, quality, attention to detail and excellent customer service which has driven Aperture Car Hire over the last two months and continues to do so.<br>
			Aperture Car Hire aims to provide its customers with service of a high standard, an excellent overall experience and value for money. In our journey, we have found staying true to these goals is the way to enable to company to continue to grow, deliver consistently on its promises and meet the expectations of our customers.</p>-->
			
			<p class="message" style="font-size:90%; margin-bottom:2em;">
			Your comments are important to us. Please use the form below to provide feedback on our services, 
			let us know about website issues, and ask questions about cars, our services or optional extras. If you'd prefer to call or send a 
			letter, then please do so with the following details:</p>
			</div>
		</div>
		<div class="row">
  			<div class="large-6 column">
				<p class="message">Please enter your enquiry in the form below<p>
				<form action="<?php fetchdir($php); ?>submit_enquiry.php" method="post" id="submit_enquiry" name="submit_enquiry" class="custom">
					<label for="email">Email</label><input type="text" name="email" id="email"<?php fillInput('Email'); ?>/>
					<label for="name">Name</label><input type="text" name="name" id="name"<?php fillInput('Name'); ?>/>
					<label for="name">Message</label>
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
					<input type="submit" class="radius button" name="submit" value="Send"/>
				</form>
			</div>		
			<div class="large-2 column"></div>
  			<div class="large-4 column">
							<p>
				<b>Address:</b><br>
				Sheepscar Street South<br>
				Leeds<br>
				LS7 1AD<br>
				Tel: 0113 2458111<br>
				Fax: 0113 2455029<br>
			</p>
			<br>
			<p><b>Opening Hours:</b></p>
				<table border="0">
					<tr><td>Monday</td> <td>08:30 &#8212; 17:30</td></tr>
					<tr><td>Tuesday</td> <td>08:30 &#8212; 17:30</td></tr>
					<tr><td>Wednesday</td> <td>08:30 &#8212; 17:30</td></tr>
					<tr><td>Thursday</td> <td>08:30 &#8212; 17:30</td></tr>
					<tr><td>Friday</td> <td>08:30 &#8212; 17:30</td></tr>
					<tr><td>Saturday</td> <td>08:30 &#8212; 17:30</td></tr>
					<tr><td>Sunday</td> <td>08:30 &#8212; 17:30</td></tr>
				</table>
			
			</p>
				
			</div>		
  		</div>
		
  		<!-- End Main Content -->
 
 		<!-- Footer -->
 		<?php include realpath(dirname(__FILE__)."/../../php/footer.php"); ?>	
 		<!-- End Footer -->	
	</body>
</html>