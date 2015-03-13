<?php

	//Manage_Locations_Edit
	//@Author: Alex McCormick
	//@Date: 22.04.2013
	
	include('global.php');
	dbconnect();
	
	if ($_POST['action'] == 'editbookingheader')
	{
		//Select Query
		$stmt = $conn->prepare("SELECT * FROM Booking_Header WHERE BookingHeadID = :id");
		$stmt->execute(array('id' => $_POST['record_id']));
								
		//Fetch Data
		foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
		{ 
?>							
							<form action="<?php fetchdir($php); ?>manage_bookings_update_or_delete.php" method="POST">
								<div class="row collapse">
									<div class="large-12 columns">
								<h5>Booking Header</h5>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Booking ID</label>
									</div>
									<div class="large-10 columns">
										<input name="BookingHeadID" type="text" value="<?php echo $row['BookingHeadID']; ?>" placeholder="" readonly/>
									</div>
								</div>
								<div class="row collapse">	
									<div class="large-2 columns">
										<label class="inline">Missed</label>
									</div>
									<div class="large-10 columns">
										<input name="missed" type="checkbox"<?php if ($row['Status'] == 3) echo ' checked'; ?>>
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Status</label>
									</div>
									<div class="large-10 columns">
										<?php if($row['Status'] == 1) { $status = 'Active'; } else if($row['Status'] == 2) { $status = 'Complete'; } else if($row['Status'] == 3) { $status = 'Missed'; } else if($row['Status'] == 4) { $status = 'Reservation'; } ?>
										<input id="" type="text" value="<?php echo $status ?>" placeholder="" readonly/>
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">UserID</label>
									</div>
									<div class="large-10 columns">
										<input id="" type="text" value="<?php echo $row['UserID']; ?>" placeholder="" readonly/>
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">AssetNo</label>
									</div>
									<div class="large-10 columns">
										<input id="" type="text" value="<?php echo $row['AssetNo']; ?>" placeholder="" readonly/>
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">CreationDate</label>
									</div>
									<div class="large-10 columns">
										<input id="" type="text" value="<?php echo $row['CreationDate']; ?>" placeholder="" readonly/>
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">ProposedPickUpDate</label>
									</div>
									<div class="large-10 columns">
										<input id="" type="text" value="<?php echo $row['ProposedPickUpDate']; ?>" placeholder="" readonly />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">PickupLocation</label>
									</div>
									<div class="large-10 columns">
										<select name="PickUpLocation">
											<?php
																						
												//Fetch Locations from Database 
												$stmt = $conn->prepare("SELECT * FROM Location WHERE Active = 1 ORDER BY LocationID");
												$stmt->execute();
												
												foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row1 )
												{
													if ( $row1['LocationID'] ==  $row['PickupLocation']) {												
														echo "<option value=".$row1['LocationID']." selected>".$row1['Add1']."</option>";
													}
													else {
														echo "<option value=".$row1['LocationID'].">".$row1['Add1']."</option>";
													}	
												}
											?>
										</select>
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">ActualPickUpDate</label>
									</div>
									<div class="large-10 columns">
										<input name="ActualPickUpDate" type="text" value="<?php echo $row['ActualPickUpDate']; ?>" placeholder=""/>
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">StartMileage</label>
									</div>
									<div class="large-10 columns">
										<input name="StartMileage" type="text" value="<?php echo $row['StartMileage']; ?>" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">ProposedReturnDate</label>
									</div>
									<div class="large-10 columns">
										<input name="ProposedReturnDate" type="text" value="<?php echo $row['ProposedReturnDate']; ?>" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">ProposedReturnLocation</label>
									</div>
									<div class="large-10 columns">
										<select name="ProposedReturnLocation">
											<?php
																						
												//Fetch Locations from Database 
												$stmt = $conn->prepare("SELECT * FROM Location WHERE Active = 1 ORDER BY LocationID");
												$stmt->execute();
								
												foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row1 )
												{
													
													if ( $row1['LocationID'] ==  $row['ProposedReturnLocation']) {												
														echo "<option value=".$row1['LocationID']." selected>".$row1['Add1']."</option>";
													}
													else {
														echo "<option value=".$row1['LocationID'].">".$row1['Add1']."</option>";
													}	
												}
											?>
										</select>
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">ActualReturnDate</label>
									</div>
									<div class="large-10 columns">
										<input name="ActualReturnDate" type="text" value="<?php echo $row['ActualReturnDate']; ?>" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">ActualReturnLocation</label>
									</div>
									<div class="large-10 columns">
										<select name="ActualReturnLocation">
											<?php
																						
												//Fetch Locations from Database 
												$stmt = $conn->prepare("SELECT * FROM Location WHERE Active = 1 ORDER BY LocationID");
												$stmt->execute();
												
												if ($row['ActualReturnLocation'] == null) {
													echo "<option value='' selected>N/A</option>";
												}
								
												foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row1 )
												{
													if ( $row1['LocationID'] ==  $row['ActualReturnLocation']) {												
														echo "<option value=".$row1['LocationID']." selected>".$row1['Add1']."</option>";
													}
													else {
														echo "<option value=".$row1['LocationID'].">".$row1['Add1']."</option>";
													}	
												}
											?>
										</select>
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">EndMileage</label>
									</div>
									<div class="large-10 columns">
										<input name="EndMileage" type="text" value="<?php echo $row['EndMileage']; ?>" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Vehicle Cost</label>
									</div>
									<div class="large-10 columns">
										<input id="" type="text" value="<?php echo $row['VehicleCost']; ?>" placeholder="" readonly/>
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">PaymentID</label>
									</div>
									<div class="large-10 columns">
										<input id="" type="text" value="<?php echo $row['PaymentID']; ?>" placeholder="" readonly/>
									</div>
								</div>
		<?php if ($row['Status'] != 4) { ?><button class="radius button" name="update_booking" type="submit">Update</button><?php } ?>
	 	<?php 
		//Check Enabled or Disabled Status
		$stmt = $conn->prepare("SELECT Status FROM Booking_Header WHERE BookingHeadID = :id");
		$stmt->execute(array('id' => $_POST['record_id']));
		
		foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
		{ 
			if ($row['Status'] == 1 || $row['Status'] == 4) { ?><button class="radius button" name="cancel_booking" type="submit" style="background-color:#CC1417; border-color:#CC1417;">Cancel</button><?php }
	 	} 
	 ?>
							</form>
							
<?php	
			}	
		} 
		else if ($_POST['action'] == 'editbookingdetail') {
			 
		//Select Query
		$stmt = $conn->prepare("SELECT * FROM Booking_Detail WHERE BookingDetailID = :id");
		$stmt->execute(array('id' => $_POST['record_id']));
								
		//Fetch Data
		foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
		{ 
			
			?>
								
								<form method="POST" action="<?php fetchdir($php); ?>manage_bookings_update_or_delete.php">
									<h5>Booking Detail</h5>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Booking Detail ID</label>
									</div>
									<div class="large-10 columns">
										<input name="BookingDetailID" type="text" value="<?php echo $row['BookingDetailID']; ?>" placeholder="" readonly/>
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Booking Header ID</label>
									</div>
									<div class="large-10 columns">
										<input id="" type="text" value="<?php echo $row['BookingHeadID']; ?>" placeholder="" readonly/>
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Hire Cost</label>
									</div>
									<div class="large-10 columns">
										<input id="" type="text" value="<?php echo $row['OptionalExtraCost']; ?>" placeholder="" readonly/>
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Asset No</label>
									</div>
									<div class="large-10 columns">
										<input id="" type="text" value="<?php echo $row['AssetNo']; ?>" placeholder="" readonly/>
									</div>
								</div>
							<button class="radius button" name="cancel_booking_detail" type="submit" style="background-color:#CC1417; border-color:#CC1417;">Remove</button>
							</form>	
<?php }		}	 ?>				