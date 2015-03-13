<?php

	//Manage_Vehicles_Add_New
	//@Author: Alex McCormick
	//@Date: 22.04.2013
	
?>

<form method="post" action="<?php fetchdir($php); ?>manage_vehicles_vehicle_insert.php">
	<div class="row collapse">
		<div class="large-2 columns">
			<label class="inline">Asset Group</label>
		</div>
		<div class="large-10 columns">
			<select name="AssetGroup">
				<?php
					dbconnect();
				
					//Fetch Asset Groups from Database 
					$stmt = $conn->prepare("SELECT * FROM Asset_Group WHERE ImageFile IS NOT NULL AND Active = 1 GROUP BY Manufacturer,Definition,EngineCapacity,Category,Transmission");
					$stmt->execute();
							
					$transmission = ($row['Manufacturer'] == '0') ? 'Automatic' : 'Manual';
							
					foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
					{
						echo "<option value=".$row['AssetGroupNo'].">".$row['Manufacturer'].' '.$row['Definition'].' '.$row['EngineCapacity'].' '.$row['Category']." (".$transmission.")</option>";	
					}
				?>
			</select>
		</div>
	</div>
	<div class="row collapse">
		<div class="large-2 columns">
			<label class="inline">Registration No.</label>
		</div>
		<div class="large-10 columns">
			<input name="RegistrationNo" type="text" value="" placeholder="" />
		</div>
	</div>
	<div class="row collapse">
		<div class="large-2 columns">
			<label class="inline">MOT Expiry</label>
		</div>
		<div class="large-10 columns">
			<input name="MOTExpiry" type="text" value="" placeholder="" />
		</div>
	</div>
	<div class="row collapse">
		<div class="large-2 columns">
			<label class="inline">Tax Expiry</label>
		</div>
		<div class="large-10 columns">
			<input name="TaxExpiry" type="text" value="" placeholder="" />
		</div>
	</div>
	<div class="row collapse">
		<div class="large-2 columns">
			<label class="inline">Mileage</label>
		</div>	
		<div class="large-10 columns">
			<input name="Mileage" type="text" value="" placeholder="" />
		</div>
	</div>
	<div class="row collapse">
		<div class="large-2 columns">
			<label class="inline">Location</label>
		</div>
		<div class="large-10 columns">
			<select name="Location" style="margin-bottom:1em;">
				<?php
					dbconnect();
											
					//Fetch Locations from Database 
					$stmt = $conn->prepare("SELECT * FROM Location WHERE Active = 1 ORDER BY LocationID");
					$stmt->execute();
								
					foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
					{
						echo "<option value=".$row['LocationID'].">".$row['Add1']."</option>";	
					}
				?>
			</select>
		</div>
	</div>
	<button class="radius button" name="add_vehicle" type="submit">Add</button>
</form>		