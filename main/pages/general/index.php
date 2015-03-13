<?php include realpath(dirname(__FILE__)."/../../php/global.php"); ?>
<?php reservation_timeout(); ?>
<!DOCTYPE html>
<?php include realpath(dirname(__FILE__)."/../../php/ie_js_fix.php"); ?>
	<head>
		<?php include realpath(dirname(__FILE__)."/../../php/head.php"); ?>
				
		<!-- jQuery CrossSlide Plugin -->
		<script src="<?php fetchdir($js); ?>jquery.cross-slide.js"></script>
		<script>
			$(function(){
				$('#crossslide').crossSlide({
					sleep: 2,
					fade: 1
				}, [
					{ src: '<?php fetchdir($img)?>slideshow1.jpg' },
					{ src: '<?php fetchdir($img)?>slideshow2.jpg' },
					{ src: '<?php fetchdir($img)?>slideshow3.jpg' }		
				]);
			});
		</script>
		
		<!-- Mobile Detect -->
		 <?php 
			detectmobile();
			
			if ($mobileAgent == true) {
				echo '<style>';
				echo '#crossslide {';
				echo 'display:none;';
				echo '}';
				echo '</style>';
			} 
		?>
		
		<!-- Orientation Change Script Removed by @Author: Alex McCormick 31.03.2013
		<script>
			// Listen for orientation changes
			window.addEventListener("orientationchange", function() {
			
				$('#crossslide').hide();
				
			}, false);
		</script>	-->
		
		<!-- Datepicker added by @Author: Stuart Crawford 18.04.2013 -->
		<?php
			if ($mobileAgent == false) {
		?>
		<link rel="stylesheet" href="<? fetchdir($css); ?>redmond/jquery-ui-1.10.2.custom.css" />
		<script src="<? fetchdir($js); ?>jquery-ui-1.10.2.custom.min.js"></script>
		<link rel="stylesheet" href="<? fetchdir ($css); ?>redmond/style.css" />
		<style>
			div.ui-datepicker
			{
				font-size:15px;
				margin-top:-48px;
			}
		</style>
		<script>
			$(function() {
				$( "#PickupDate" ).datepicker({
					minDate: 0,
					maxDate: "<?php echo $maxAdvancedBooking; ?>",
					beforeShow: function() {
						setTimeout(function(){
							$('.ui-datepicker').css('z-index', 1999999999);
						}, 0);
					}
				});
				$( "#DropoffDate" ).datepicker({
					minDate: 0,
					maxDate: "<?php echo $maxAdvancedBooking; ?>",
					beforeShow: function() {
						setTimeout(function(){
							$('.ui-datepicker').css('z-index', 1999999999);
						}, 0);
					}
				});
			});
		</script>
		<?php }		?>
	</head>
	<body>
		
		<!-- Header -->
		<?php include realpath(dirname(__FILE__)."/../../php/header.php"); ?>		
		<!-- End Header -->
		
		<!-- Main Content -->  	
		<div id="first_row" class="row">
			<div id="bookingform1" class="large-3 columns">
				<h3>Hire a Car</h3>
				
				<?php 
					if (isset($_SESSION['Form']['Error'])) { ?><div class="row"><div class="large-12 column"><?php echo "<p style='color:red; position:relative; z-index:1; font-size:75%;'>".$_SESSION['Form']['Error']."</p>" ?></div></div>
					<style scoped>
						#bookingform1 {
							height:450px;
						}
					</style>
				<?php } ?>
					
				<!-- Car Hire Booking Form -->
				<form class="custom" action="<?php fetchdir($php); ?>create_booking.php" method="post">
              		<div class="row collapse">
                		<div class="large-11 columns">
							<label><strong>Pick-Up</strong></label>               		
							<select name="PickupLocation" id="PickupLocation">
								<?php
								
									//Fetch Locations from Database 
									dbconnect();
									$stmt = $conn->prepare("SELECT * FROM Location WHERE Active = 1 ORDER BY LocationID");
									$stmt->execute();
									
									if ($_SESSION['Booking']['Head']['Pickup']['Location'] == null)
									{
										echo '<option DISABLED value="" selected>Select Location</option>';
									}
									else
									{
										echo '<option DISABLED value="">Select Location</option>';
									}
									
									foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
									{
										if ($row['LocationID'] == $_SESSION['Booking']['Head']['Pickup']['Location']) {
											echo "<option value=".$row['LocationID']." selected>".$row['Add1']."</option>";
										}
										else {
											echo "<option value=".$row['LocationID'].">".$row['Add1']."</option>";
										}
									}
								?>
							</select>
						</div>
					</div>
					<div class="row collapse">                 		
						<div class="large-11 columns">	                		
							<div id="datewrap" class="small-6 columns">							
								<label>Date:</label> <input placeholder="mm/dd/yyyy" type="text" id="PickupDate" name="PickupDate" value="<?php if (isset($_SESSION['Booking']['Head']['Pickup']['Date'])) echo $_SESSION['Booking']['Head']['Pickup']['Date']; ?>" />
            	     		</div>            				
							<div class="small-5 columns">	                  		
								<label>Time</label>    	              			
								<select name="PickupTime">
									<?php
										for($hours=9; $hours<18; $hours++)
										if (str_pad($hours,2,'0',STR_PAD_LEFT).':00' == $_SESSION['Booking']['Head']['Pickup']['Time']) {
        									echo '<option value ="'.str_pad($hours,2,'0',STR_PAD_LEFT).':00" selected>'.str_pad($hours,2,'0',STR_PAD_LEFT).':00</option>';
										}
										else {
											echo '<option value ="'.str_pad($hours,2,'0',STR_PAD_LEFT).':00">'.str_pad($hours,2,'0',STR_PAD_LEFT).':00</option>';
										}
                       				?>
                       			</select> 
        	        		</div>	            
						</div>	       			
					</div>    	        	
					<div class="row collapse">        	    		
						<div class="large-11 columns">            	    		
							<label><strong>Drop-Off</strong></label>                	  		
							<select name="DropoffLocation">
								<?php
								
									//Fetch Locations from Database 
									dbconnect();
									$stmt = $conn->prepare("SELECT * FROM Location WHERE Active = 1 ORDER BY LocationID");
									$stmt->execute();
									
									if ($_SESSION['Booking']['Head']['Pickup']['Location'] == null)
									{
										echo '<option value="default" selected>Select Location</option>';
									}
									else
									{
										echo '<option value="default">Select Location</option>';
									}
									
									foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
									{
										if ($row['LocationID'] == $_SESSION['Booking']['Head']['Dropoff']['Location']) {
											echo "<option value=".$row['LocationID']." selected>".$row['Add1']."</option>";
										}
										else {
											echo "<option value=".$row['LocationID'].">".$row['Add1']."</option>";
										}
									}
								?>       	
							</select>
						</div>    	    		
					</div>        	 		
					<div class="row collapse">             			
						<div class="large-11 columns">                		
							<div id="datewrap" class="small-6 columns"> 
								<label>Date:</label> <input placeholder="mm/dd/yyyy" type="text" id="DropoffDate" name="DropoffDate" value="<?php if (isset($_SESSION['Booking']['Head']['Dropoff']['Date'])) echo $_SESSION['Booking']['Head']['Dropoff']['Date']; ?>" />
	                 		</div>    	        
							<div class="small-5 columns">        	          			
								<label>Time</label>
								<select name="DropoffTime">
									<?php
										for($hours=9; $hours<18; $hours++)
										if (str_pad($hours,2,'0',STR_PAD_LEFT).':00' == $_SESSION['Booking']['Head']['Dropoff']['Time']) {
        									echo '<option value ="'.str_pad($hours,2,'0',STR_PAD_LEFT).':00" selected>'.str_pad($hours,2,'0',STR_PAD_LEFT).':00</option>';
										}
										else {
											echo '<option value ="'.str_pad($hours,2,'0',STR_PAD_LEFT).':00">'.str_pad($hours,2,'0',STR_PAD_LEFT).':00</option>';
										}
                       				?>
                       			</select>            	      			
                	 		</div>	               		
						</div>    	     		
					</div>        	    		
					<button type="submit" name="booking1" class="radius button">Submit</button        			
				</form>
				<!-- End Car Hire Booking Form -->
				   			
			</div>   			
			<div class="large-12 columns">
				<div id="crossslide"></div>   			
			</div>   
		</div> 
		<div class="row">
			<div class="large-4 columns info_icon_container">
				<div class="icon-picture">
					<img class="info_icon" src="<?php fetchdir($img); ?>info_icon_speech.png" alt="Icon: Speech Bubble" />
				</div>
				<div class="icon-text">
					<p><strong>Book a time</strong></p>
					<p>Select the time you want to pick up and return your car</p>
				</div>
			</div>
			<div class="large-4 columns info_icon_container">
				<div class="icon-picture">
					<img class="info_icon" src="<?php fetchdir($img); ?>info_icon_magnifying_glass.png" alt="Icon: Magnifying Glass" />
				</div>
				<div class="icon-text">
					<p><strong>See what you could drive</strong></p>
					<p>Find the perfect car for your trip. Search by location, make and model</p>
				</div>
			</div>
			<div class="large-4 columns info_icon_container">
				<div class="icon-picture">
					<img class="info_icon" src="<?php fetchdir($img); ?>info_icon_car.png" alt="Icon: Car" />
				</div>
				<div class="icon-text">
					<p><strong>Pick up your car</strong></p>
					<p>Collect the keys in person from your pick-up location. Fully insured with breakdown included</p>
				</div>
			</div>
		</div>
		<div class="row">
			<div id="aperturebar" class="large-12 columns">
				<div id="aperturebarlogo" class="large-3 small-4 columns">
					<img src="<?php fetchdir($img); ?>nobg_aperture-logo.png" alt="Aperture Banner Logo"/>
				</div>
			</div>
		</div>
		<div class="row">
			<div id="sitemap">
				<div class="large-4 small-6 columns">
					<ul class="nobullets">
						<li><label><strong>Home</strong></label></li>
						<?php if($_SESSION['User']['AccessLvl'] == null) { ?><li><a href="<?php fetchdir($gpages); ?>login.php">Login</a></li><?php } ?>
						<?php if($_SESSION['User']['AccessLvl'] == null) { ?><li><a href="<?php fetchdir($gpages); ?>registration.php">Register</a></li><?php } ?>
						<?php if($_SESSION['User']['AccessLvl'] != null) { ?><li><a href="<?php fetchdir($apages); ?>my_account.php">My Account</a></li><?php } ?>
						<?php if($_SESSION['User']['AccessLvl'] != null) { ?><li><a href="<?php fetchdir($apages); ?>feedback.php">Feedback</a></li><?php } ?>
					</ul>
				</div>
				<div class="large-4 small-6 columns">
					<ul class="nobullets">
						<li><label><strong>Aperture</strong></label></li>
						<li><a href="<?php fetchdir($gpages); ?>about.php">About</a></li>
						<li><a href="<?php fetchdir($gpages); ?>contact.php">Contact Us</a></li>
					</ul>
				</div>
				<div class="large-4 small-6 columns">
					<ul class="nobullets">
						<li><label><strong>Bookings</strong></label></li>
						<li><a href="<?php fetchdir($gpages); ?>terms_and_conditions.php">Terms and Conditions</a></li>
					</ul>
				</div>
			</div>
		</div>
			  		
		<!-- End Main Content -->
		
		<!-- Footer --> 		
		<?php include realpath(dirname(__FILE__)."/../../php/footer.php"); ?>	 		
		<!-- End Footer -->
		
		<!--	Credits go to...
				Ethan Marcotte for his groundbreaking research in and supporting book of the same title: Responsive Web Design - http://www.abookapart.com/products/responsive-web-design
				Foundation.Zurb for their Open Source Responsive Framework - http://foundation.zurb.com/
				eWAY for their Rapid 3.0 Payment API Sandbox for Developers - http://www.eway.co.uk/
				
				Scott Jehl (MIT):	for his GPL2 Licensed Respond.js jQuery polyfill for CSS3 Media Queries - https://github.com/scottjehl/Respond
									for his GPL2 Licensed Picturefill.js jQuery for CSS3 Media Queries - https://github.com/scottjehl/picturefill 
				
				Bradvin on GitHub for FooTable responsive tables - https://github.com/bradvin/FooTable
					- Copyright 2012 Steven Usher & Brad Vincent
					- Released under the MIT license
					- You are free to use FooTable in commercial projects as long as this copyright header is left intact.
				
				Thank you all!
				
																															-->		
	</body>
</html>