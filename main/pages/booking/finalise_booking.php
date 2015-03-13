<?php include realpath(dirname(__FILE__)."/../../php/global.php"); ?>
<?php
	setpermissions('admin','staff','customer');

	reservation_timeout();
	
	if ($_SESSION['Booking']['Step'] < 3)
	{
		if(isset($referrer))
		{
			Header ('Location: '.fetchinline($bpages).'page_expired.php');
			exit;
		}
		else
		{
			Header ('Location: '.fetchinline($gpages).'index.php');
			exit;
		}
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
		<div class="large-12 columns">
			<h2>Booking Details</h2>
				<label><strong>Progress:</strong></label>
						<div class="progress large-12 success round" style="30px"><span class="meter" style="width: 75%"></span></div>			
		</div>
	</div>
	<div class="row">
		<div class="large-7 columns">
			<div class="">
				<?php
					//Fetch Vehicle from Database
					$id = $_SESSION['Booking']['Vehicle']['ID'];
					$stmt = $conn->prepare("SELECT * FROM Asset, Asset_Group, Vehicle WHERE Asset.AssetGroupNo = Asset_Group.AssetGroupNo AND Vehicle.AssetNo = Asset.AssetNo AND Asset.AssetNo = :id");
					$stmt->execute(array('id' => $id));
					
					foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row ) { 
					
					?>	
						<img src="<?php fetchdir($php); ?>thumbnail.php?file=<?php echo $row['ImageFile']; ?>&amp;width=544&amp;height=366&amp;dir=vehicle" alt="<?php echo $row['Manufacturer'].' '.$row['Definition'].' '.$row['EngineCapacity'].' '.$row['Category']; ?>" title="<?php echo $row['Manufacturer'].' '.$row['Definition'].' '.$row['EngineCapacity'].' '.$row['Category']; ?>"/>
					<?php
						echo '<div class="row collapse">';
						echo '<h4>Vehicle</h4>';
						echo '<div class="large-6 small-12 columns">';
						echo '<p><strong>Manufacturer:</strong> '.$row['Manufacturer'].'<br/>';
						echo '<strong>Model:</strong> '.$row['Definition'].'<br/>';
						echo '<strong>Litre:</strong> '.$row['EngineCapacity'].'</p>';
						
						echo '</div>';
						echo '<div class="large-6 small-12 columns">';
						
						echo '<p><strong>Category:</strong> '.$row['Category'].'<br/>';
						echo '<strong>Doors:</strong> '.$row['Doors'].'<br/>';
						echo '<strong>Seats:</strong> '.$row['Seats'].'</p>';
						
						echo '</div>';
						echo '</div>';
					}	
				?>
				<hr class="hide-for-medium-up"></hr>
				<div class="row collapse">
				<div class="large-6 small-12 columns">
				<h4 style="">Pick Up</h4>
				<?php	
					
					//Fetch Locations from Database 
					dbconnect();
					$id = $_SESSION['Booking']['Head']['Pickup']['Location'];
					$stmt = $conn->prepare("SELECT * FROM Location WHERE LocationID = :id");
					$stmt->execute(array('id' => $id));
									
					foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
					{
						echo '<p><strong>Location:</strong> '.$row['Add1'].'<br/>';
						echo '<strong>Date:</strong> '.$_SESSION['Booking']['Head']['Pickup']['Date'].'<br/>';
						echo '<strong>Time:</strong> '.$_SESSION['Booking']['Head']['Pickup']['Time'].'</p>';
					}
				?>
				</div>
				
				<div class="large-6 small-12 columns">
				<h4 style="">Drop Off</h4>
				<?php
					$id = $_SESSION['Booking']['Head']['Dropoff']['Location'];
					$stmt = $conn->prepare("SELECT * FROM Location WHERE LocationID = :id");
					$stmt->execute(array('id' => $id));
									
					foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
					{
						
						echo '<p><strong>Location:</strong> '.$row['Add1'].'<br/>';
						echo '<strong>Date:</strong> '.$_SESSION['Booking']['Head']['Dropoff']['Date'].'<br/>';
						echo '<strong>Time:</strong> '.$_SESSION['Booking']['Head']['Dropoff']['Time'].'</p>';
					
					}
				?>
				</div>
				</div>
				<hr class="hide-for-medium-up"></hr>
			</div>
		</div>
		<div class="large-1 columns"></div>
		<div class="large-4 columns">
			<div class="row collapse">
			<div class="large-12 small-12 columns">
				<h4 style="">Customer</h4>
				<?php
								
					//Fetch User Information from Database 
					dbconnect();
					$id = $_SESSION['User']['ID'];
					$stmt = $conn->prepare("SELECT * FROM User WHERE UserID = :id");
					$stmt->execute(array('id' => $id));
						
					foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
					{
						echo'<p><strong>First Name:</strong> '.$row['FirstName'].'<br/>';
						echo'<strong>Last Name:</strong> '.$row['LastName'].'<br/>';
						echo'<strong>Add1:</strong> '.$row['Add1'].'<br/>';
						echo'<strong>Add2:</strong> '.$row['Add2'].'<br/>';
						echo'<strong>Add3:</strong> '.$row['Add3'].'<br/>';
						echo'<strong>Postcode:</strong> '.$row['Postcode'].'<br/>';
						echo'<strong>Telephone:</strong> '.$row['Telephone'].'<br/>';
						echo'<strong>Mobile:</strong> '.$row['Mobile'].'<br/>';
						if(isset($row['LicenseNo'])) {echo'<strong>LicenseNo:</strong> '.$row['LicenseNo'].'<br/></p>';}
						
					}
					
				?>
			</div>
			<hr class="hide-for-medium-up"></hr>
			<div class="large-12 small-12 columns" style="">
				<h4 style="">Payment</h4>
				<p>
					<?php
						//Fetch Values from Create Booking
						$pickup = date("Y-m-d H:i:s", strtotime($_SESSION['Booking']['Head']['Pickup']['Date'].' '.$_SESSION['Booking']['Head']['Pickup']['Time']));
						$dropoff = date("Y-m-d H:i:s", strtotime($_SESSION['Booking']['Head']['Dropoff']['Date'].' '.$_SESSION['Booking']['Head']['Dropoff']['Time']));
					
						// Calculate the total cost
						$diffsecs = strtotime($dropoff) - strtotime($pickup);
						$diff = ceil($diffsecs / 60 / 60 / 24);
						if ($diffsecs <= 60 * 60 * 12)
						{
							$diff = $diff / 2;
							$total = ceil($diff*$_SESSION['Booking']['Vehicle']['Cost']);
						}
						else
						{
							$total = $diff*$_SESSION['Booking']['Vehicle']['Cost'];
						}
									
					?>
					
					Car Hire: &pound;<?php echo number_format($total, 2, '.', ''); ?><br/>
					
					<?php 
					
						// Calculate no of days booked rounded up
						$diff = strtotime($dropoff) - strtotime($pickup);
						$diff = ceil($diff / 60 / 60 / 24);
					
						//Optional Extra Prices
						if($_SESSION['Booking']['Detail']['GPS']['Qty'] > 0) { echo 'GPS: &pound;'.number_format($diff*$_SESSION['Booking']['Detail']['GPS']['Cost']*$_SESSION['Booking']['Detail']['GPS']['Qty'], 2, '.', '').'<br/>'; } 
						if($_SESSION['Booking']['Detail']['ChildSeat']['Qty'] > 0) { echo 'Child Seat: &pound;'.number_format($diff*$_SESSION['Booking']['Detail']['ChildSeat']['Cost']*$_SESSION['Booking']['Detail']['ChildSeat']['Qty'], 2, '.', '').'<br/>'; } 
					 	if($_SESSION['Booking']['Detail']['InfantSeat']['Qty'] > 0) { echo 'Infant Seat: &pound;'.number_format($diff*$_SESSION['Booking']['Detail']['InfantSeat']['Cost']*$_SESSION['Booking']['Detail']['InfantSeat']['Qty'], 2, '.', '').'<br/>'; } 
					 	if($_SESSION['Booking']['Detail']['BoosterSeat']['Qty'] > 0) { echo 'Booster Seat: &pound;'.number_format($diff*$_SESSION['Booking']['Detail']['BoosterSeat']['Cost']*$_SESSION['Booking']['Detail']['BoosterSeat']['Qty'], 2, '.', '').'<br/>'; } 
						if($_SESSION['Booking']['Detail']['SnowTires']['Qty'] > 0) { echo 'Snow Tires: &pound;'.number_format($diff*$_SESSION['Booking']['Detail']['SnowTires']['Cost']*$_SESSION['Booking']['Detail']['SnowTires']['Qty'], 2, '.', '').'<br/>'; } 
					?>
					<strong>Total: </strong>&pound;<?php echo number_format($_SESSION['TotalPrice']/100, 2, '.', ''); ?>
				</p>
			</div>
			</div>
			<form action="<?php fetchdir($php); ?>create_booking.php" method="POST">
				<?php if (isset($lblError)) { ?>
    				<div id="error">
	        			<label style="color:red"><?php echo $lblError ?></label>
    				</div>
				<?php } ?>
        		<button class="button" type="submit" id="submit" name="booking4" style="width=100%">Proceed to Payment</button>
            	<button class="button" name="cancel" type="submit" style="width=100%">Cancel</button>
        	</form>	
		</div>
	</div>
    <!-- End Main Content -->
    
    <!-- Footer -->
 	<?php include realpath(dirname(__FILE__)."/../../php/footer.php"); ?>	
 	<!-- End Footer -->	
</body>
</html>