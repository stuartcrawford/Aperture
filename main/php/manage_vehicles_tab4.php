<?php

	//Manage_Vehicles_Edit_Vehicle
	//@Author: Alex McCormick
	//@Date: 14.04.2013
	
	include('global.php');
	dbconnect();
	
	if ($_POST['action'] == 'editvehicle')
	{
		//Select Query
		$stmt = $conn->prepare("SELECT * FROM Asset_Group, Asset, Vehicle WHERE Asset.AssetNo = Vehicle.AssetNo AND Asset.AssetGroupNo = Asset_Group.AssetGroupNo AND Vehicle.AssetNo = :id"); 
		$stmt->execute(array('id' => $_POST['record_id']));
								
		//Fetch Data
		foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
		{ 
?>		

<form class="custom" method="post" action="<?php fetchdir($php); ?>manage_vehicles_vehicle_update_or_delete.php">
	<input type="hidden" name="Vehicle" value="<?php echo $_POST['record_id']; ?>" />
	<div class="row collapse">
		<div class="large-2 columns">
			<label class="inline">Asset Group</label>
		</div>
		<div class="large-10 columns">
			<select name="AssetGroup">
				<?php $transmission = ($row['Manufacturer'] == '0') ? '(Automatic)' : '(Manual)'; ?>
				<option value="<?php echo $row['AssetGroupNo']; ?>" selected><?php echo $row['Manufacturer'].' '.$row['Definition'].' '.$row['EngineCapacity'].' '.$row['Category'].' '.$transmission; ?></option>;	
			</select>
		</div>
	</div>
	<div class="row collapse">
		<div class="large-2 columns">
			<label class="inline">Registration No.</label>
		</div>
		<div class="large-10 columns">
			<input name="RegistrationNo" type="text" value="<?php echo $row['RegistrationNo']; ?>" placeholder="" />
		</div>
	</div>
	<div class="row collapse">
		<div class="large-2 columns">
			<label class="inline">MOT Expiry</label>
		</div>
		<div class="large-10 columns">
			<input name="MOTExpiry" type="text" value="<?php echo $row['MOTExpiry']; ?>" placeholder="" />
		</div>
	</div>
	<div class="row collapse">
		<div class="large-2 columns">
			<label class="inline">Tax Expiry</label>
		</div>
		<div class="large-10 columns">
			<input name="TaxExpiry" type="text" value="<?php echo $row['TaxExpiry']; ?>" placeholder="" />
		</div>
	</div>
	<div class="row collapse">
		<div class="large-2 columns">
			<label class="inline">Mileage</label>
		</div>	
		<div class="large-10 columns">
			<input name="Mileage" type="text" value="<?php echo $row['Mileage']; ?>" placeholder="" />
		</div>
	</div>
	<div class="row collapse">
		<div class="large-2 columns">
			<label class="inline">Location</label>
		</div>
<?php $location = $row['Location'];	}	} ?>	
		<div class="large-10 columns">
			<select name="LocationID" style="margin-bottom:1em;">
				<?php
					//Fetch Locations from Database 
					$stmt = $conn->prepare("SELECT * FROM Location WHERE Active = 1 ORDER BY LocationID");
					$stmt->execute();
							
					foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
					{
						if ($location == $row['LocationID']) {
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
	<button class="radius button" name="update_vehicle" type="submit">Update</button>
	<?php 
		//Check Enabled or Disabled Status
		$stmt = $conn->prepare("SELECT Active FROM Asset WHERE AssetNo = :id");
		$stmt->execute(array('id' => $_POST['record_id']));
		
		foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
		{ 
			if ($row['Active'] == 0) { ?><button class="radius button" name="enable_vehicle" type="submit" style="background-color:#589E1F; border-color:#589E1F;">Enable</button><?php } 
	 		else if ($row['Active'] == 1) { ?><button class="radius button" name="delete_vehicle" type="submit" style="background-color:#CC1417; border-color:#CC1417;">Disable</button><?php }
	 	} 
	 ?>
</form>	