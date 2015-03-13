<?php

	//Manage_Locations_Edit
	//@Author: Alex McCormick
	//@Date: 22.04.2013
	
	include('global.php');
	dbconnect();
	
	if ($_POST['action'] == 'editbooking')
	{
		//Select Query
		$stmt = $conn->prepare("SELECT * FROM Booking_Header WHERE BookingHeadID = :id");
		$stmt->execute();
		$stmt->execute(array('id' => $_POST['b_id']));
								
		//Fetch Data
		foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
		{ 
?>		
							
							
							<form>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Booking ID</label>
									</div>
									<div class="large-10 columns">
										<input id="" type="text" value="<?php echo $row['BookingHeadID']; ?>" placeholder="" readonly/>
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Status</label>
									</div>
									<div class="large-10 columns">
										<input id="" type="text" value="<?php echo $row['Status']; ?>" placeholder="" readonly/>
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
										<label class="inline">ActualPickUpDate</label>
									</div>
									<div class="large-10 columns">
										<input id="" type="text" value="<?php echo $row['ActualPickUpDate']; ?>" placeholder=""/>
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">PickupLocation</label>
									</div>
									<div class="large-10 columns">
										<input id="" type="text" value="<?php echo $row['PickupLocation']; ?>" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">StartMileage</label>
									</div>
									<div class="large-10 columns">
										<input id="" type="text" value="<?php echo $row['StartMileage']; ?>" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">ProposedReturnDate</label>
									</div>
									<div class="large-10 columns">
										<input id="" type="text" value="<?php echo $row['ProposedReturnDate']; ?>" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">ProposedReturnLocation</label>
									</div>
									<div class="large-10 columns">
										<input id="" type="text" value="<?php echo $row['ProposedReturnLocation']; ?>" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">ActualReturnDate</label>
									</div>
									<div class="large-10 columns">
										<input id="" type="text" value="<?php echo $row['ActualReturnDate']; ?>" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">ActualReturnLocation</label>
									</div>
									<div class="large-10 columns">
										<input id="" type="text" value="<?php echo $row['ActualReturnLocation']; ?>" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">EndMileage</label>
									</div>
									<div class="large-10 columns">
										<input id="" type="text" value="<?php echo $row['EndMileage']; ?>" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Vehicle Cost</label>
									</div>
									<div class="large-10 columns">
										<input id="" type="text" value="<?php echo $row['VehicleCost']; ?>" placeholder="" />
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
								
								
								<button class="radius button" type="submit">Submit</button>
							</form>
							
<?php	}	}	 ?>				