<?php
	
	//@Title: Header
	//@Author: AM + JC + SC
	
?>
<!-- Aperture Logo -->
<nav class="top-bar">
	<!-- Title Area -->
	<ul class="title-area">
		<li class="name">
			<img src="<?php fetchdir($img); ?>ApertureScienceLogo_transparent-invert.png" alt="Aperture Logo" />
		</li>
		<li class="toggle-topbar menu-icon">
			<a href="#"><span>menu</span></a>
		</li>
	</ul>
	<!-- End Title Area -->
	<!-- Right Nav Section -->
	<section class="top-bar-section">
		<ul class="right">
			<li class="divider"></li>
			
			<!-- Home -->
			<li><a href="<?php fetchdir($gpages); ?>index.php">Home</a></li>
			<li class="divider"></li>
				
			<!-- About -->
			<li><a href="<?php fetchdir($gpages); ?>about.php">About Us</a></li>
			<li class="divider"></li>
				
			<!-- Contact -->
			<li><a href="<?php fetchdir($gpages); ?>contact.php">Contact Us</a></li>
			<li class="divider"></li>
			
			<!-- Staff Portal -->
			<?php if ($_SESSION['User']['AccessLvl'] == 1) {	?>
			<li class="has-dropdown">
				<a href="<?php fetchdir($apages); ?>manage_accounts.php">Admin</a>
				<ul class="dropdown">
					<li><a href="<?php fetchdir($apages); ?>manage_accounts.php">User</a></li>
					<li><a href="<?php fetchdir($apages); ?>manage_asset_groups.php">Asset Group</a></li>
					<li><a href="<?php fetchdir($apages); ?>manage_vehicles.php">Vehicle</a></li>
					<li><a href="<?php fetchdir($apages); ?>manage_optional_extras.php">Optional Extra</a></li>
					<li><a href="<?php fetchdir($apages); ?>manage_bookings.php">Booking</a></li>
					<li><a href="<?php fetchdir($apages); ?>manage_locations.php">Location</a></li>
				</ul>
			</li>
			<li class="divider"></li>
			<?php } else if ($_SESSION['User']['AccessLvl'] == 2) {		?>
			<li class="has-dropdown">
				<a href="<?php fetchdir($apages); ?>manage_accounts.php">Staff</a>
				<ul class="dropdown">
					<li><a href="<?php fetchdir($apages); ?>manage_accounts.php">Customer</a></li>
					<li><a href="<?php fetchdir($apages); ?>manage_vehicles.php">Vehicle</a></li>
					<li><a href="<?php fetchdir($apages); ?>manage_optional_extras.php">Optional Extra</a></li>
					<li><a href="<?php fetchdir($apages); ?>manage_bookings.php">Booking</a></li>
				</ul>
			</li>
			<li class="divider"></li>
			<?php }		?>

			<!-- Universal Dropdown -->
			<?php if ($_SESSION['User']['AccessLvl'] == null) {	?>
				<!-- Login Dropdown -->
				<li class="has-dropdown">
					<a href="<?php fetchdir($gpages); ?>login.php">Login</a>
					<ul class="dropdown">
						<li>
							<form action="<?php fetchdir($php); ?>login.php" method="post" name="dropsubmit" id="dropsubmit">
								<li style="width:250px"><label>Email</label></li>
								<li class="hacked" style="padding-left:10px; padding-right:10px; padding-bottom:10px;">
									<input type="text" name="email" id="email">
								</li>
								<li><label>Password</label></li>
								<li class="hacked" style="padding-left:10px; padding-right:10px; padding-bottom:10px;">
									<input type="password" name="password" id="password">
								</li>
								<div id="errors" style="display:none">
									<li id="error1"><label style="color:red">The email address or password you<br>entered is incorrect</label></li>
									<li id="error2"><label style="color:red">The email address or password you entered is incorrect</label></li>
								</div>
								<li class="hacked" style="padding-left:10px; padding-top:8px; padding-bottom:18px">
									<input type="submit" class="radius button" name="submit" id="submit" value="Login"/>
								</li>
							</form>
						</li>
					</ul>
				</li>
			<?php } else{	?>
			
			<!-- Account Dropdown -->
			<li class="has-dropdown">
				<a href="<?php fetchdir($apages); ?>my_account.php"><?php echo $_SESSION['User']['Forename']; ?></a>
				<ul class="dropdown">
					<li>
						<a href="<?php fetchdir($apages); ?>my_account.php">My Account</a>
					</li>
					<li>
						<a href="<?php fetchdir($apages); ?>booking_history.php">Booking History</a>
					</li>
					<li class="hacked" style="padding-left:15px; padding-top:5px; padding-bottom:22px">
						<form action="<?php fetchdir($php); ?>logout.php" method="post" class="nocsrf">
							<input type="submit" class="radius button" name="submit" id="submit" value="Logout"/>
						</form>
					</li>
				</ul>
			</li>
			<?php }		?>
			
			<!-- Extra links for tablets and mobiles -->
			<?php if ($_SESSION['User']['AccessLvl'] == null) {	?>
			<li class="divider mobileagent"></li>
			<li class="has-dropdown mobileagent">
				<a href="">More</a>
				<ul class="dropdown">
					<li><a href="<?php fetchdir($gpages); ?>registration.php">Registration</a></li>
					<li><a href="<?php fetchdir($gpages); ?>forgotten_password.php">Forgotten Password</a></li>
				</ul>
			</li>
			<?php }		?>
			
			<!-- Social Media -->
			<li class="divider"></li>
			<a class="social" href="https://www.facebook.com/ApertureRentals">
				<img class="social" src="<?php fetchdir($img); ?>facebook-icon.png" alt="facebook"/>
			</a>
			<a class="social" href="https://twitter.com/ApertureRentals">
				<img class="social" src="<?php fetchdir($img); ?>twitter-icon-white.png" alt="twitter"/>
			</a>
			<a class="social" href="https://plus.google.com/">
				<img class="social" src="<?php fetchdir($img); ?>icon-google-40.png" alt="google+"/>
			</a>
		</ul>
	</section>
	<!-- End Right Nav Section -->
</nav>

<!-- Feedback panel -->
<?php if ($_SESSION['User']['AccessLvl'] != null) {		?>
<div class="panel_container">
	<div class="feedbackpanel">
		<div class="inner">
			<form style="margin:0" name="submitfeedback" id="submitfeedback" class="custom" action="<?php fetchdir($php); ?>submit_feedback.php" method="post">
				<h5>Any comments or questions would be appreciated.</h5>
				<textarea placeholder="Enter feedback here..." name="msg" style="height:150px; resize:none;"></textarea>
				<div class="fb" style="margin-top:-10px; margin-bottom:-12px;"></div>
				<div style="direction:rtl;">
					<input style="margin-top:16px; margin-bottom:18px;" class="radius button" type="submit" value="Send" />
				</div>
			</form>
		</div>
		<div class="trigger">
			<div class="rotate">
				<p><strong>Feedback</strong></p>
			</div>
		</div>
	</div>
</div>
<?php }		?>

<!-- Javascript disabled notice -->
<script>
	document.body.className += ' js-enabled';
</script>
<nav id="noscript" class="top-bar" style="top:45px; background-color:#00A3CD; z-index:2000000000; height:auto;">
	<ul>
		<li style="margin-top:3px; margin-left:6px; margin-right:6px;"><label><strong>JavaScript is currently disabled. Please enable JavaScript to ensure full functionality of the website</strong></label></li>
	</ul>
</nav>