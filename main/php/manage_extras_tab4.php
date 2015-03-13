<?php

	//Manage_Extras_Edit_Vehicle
	//@Author: Alex McCormick
	//@Date: 03.06.2013
	
	include('global.php');
	dbconnect();
	
	if ($_POST['action'] == 'editextra')
	{
		//Select Query
		$stmt = $conn->prepare("SELECT * FROM Asset_Group, Asset, Optional_Extra WHERE Asset_Group.AssetGroupNo = Asset.AssetGroupNo AND Asset_Group.ImageFile IS NULL AND Asset_Group.Active = 1 AND Optional_Extra.AssetNo = Asset.AssetNo AND Asset.AssetNo = :id");
		$stmt->execute(array('id' => $_POST['record_id']));
								
		//Fetch Data
		foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
		{ 
?>		

<form method="post" action="<?php fetchdir($php); ?>manage_extras_extra_update_or_delete.php">
	<input type="hidden" name="Extra" value="<?php echo $_POST['record_id']; ?>" />
	<div class="row collapse">
		<div class="large-2 columns">
			<label class="inline">Asset Group</label>
		</div>
		<div class="large-10 columns">
			<select name="AssetGroup">
				<option value="<?php echo $row['Definition']; ?>" selected><?php echo $row['Definition']; ?></option>	
			</select>
		</div>
	</div>
	<div class="row collapse">
		<div class="large-2 columns">
			<label class="inline">Serial No.</label>
		</div>
		<div class="large-10 columns">
			<input name="SerialNumber" type="text" value="<?php echo $row['SerialNumber']; ?>" placeholder="" />
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
	<button class="radius button" name="update_extra" type="submit">Update</button>
	<?php 
		//Check Enabled or Disabled Status
		$stmt = $conn->prepare("SELECT Active FROM Asset WHERE AssetNo = :id");
		$stmt->execute(array('id' => $_POST['record_id']));
		
		foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
		{ 
			if ($row['Active'] == 0) { ?><button class="radius button" name="enable_extra" type="submit" style="background-color:#589E1F; border-color:#589E1F;">Enable</button><?php } 
	 		else if ($row['Active'] == 1) { ?><button class="radius button" name="delete_extra" type="submit" style="background-color:#CC1417; border-color:#CC1417;">Disable</button><?php }
	 	} 
	 ?>
</form>	