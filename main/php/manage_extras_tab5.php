<?php

	//Manage_Vehicles_Add_New
	//@Author: Alex McCormick
	//@Date: 22.04.2013
	
?>

<form method="post" action="<?php fetchdir($php); ?>manage_extras_extra_insert.php">
	<div class="row collapse">
		<div class="large-2 columns">
			<label class="inline">Asset Group</label>
		</div>
		<div class="large-10 columns">
			<select name="AssetGroup">
				<?php
					dbconnect();
				
					//Fetch Asset Groups from Database 
					$stmt = $conn->prepare("SELECT * FROM Asset_Group WHERE ImageFile IS NULL AND Active = 1");
					$stmt->execute();
							
					foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
					{
						echo "<option value='".$row['AssetGroupNo']."'>".$row['Definition']."</option>";	
					}
				?>
			</select>
		</div>
	</div>
	<div class="row collapse">
		<div class="large-2 columns">
			<label class="inline">Serial No.</label>
		</div>
		<div class="large-10 columns">
			<input name="SerialNo" type="text" value="" placeholder="" />
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
	<button class="radius button" name="add_extra" type="submit">Add</button>
</form>		