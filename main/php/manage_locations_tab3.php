<?php

	//View_Manage_Locations_Edit
	//@Author: Stuart Crawford
	//@Date: 26.04.2013
	
	include('global.php');
	dbconnect();
	
	if ($_POST['action'] == 'editlocation')
	{
		//Select Query
		$stmt = $conn->prepare("SELECT * FROM Location WHERE LocationID = :id");
		$stmt->execute(array('id' => $_POST['record_id']));
								
		//Fetch Data
		foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
		{ 
?>		

<form class="custom" method="post" action="<?php fetchdir($php); ?>manage_locations_location_update_or_delete.php">
	<input type="hidden" name="LocationID" value="<?php echo $_POST['record_id']; ?>" />
	<div class="row collapse">
		<div class="large-2 columns">
			<label class="inline">Address 1</label>
		</div>
		<div class="large-10 columns">
			<input name="Add1" type="text" value="<?php echo $row['Add1']; ?>" placeholder="" />
		</div>
	</div>
	<div class="row collapse">
		<div class="large-2 columns">
			<label class="inline">Address 2</label>
		</div>	
		<div class="large-10 columns">
			<input name="Add2" type="text" value="<?php echo $row['Add2']; ?>" placeholder="" />
		</div>
	</div>
	<div class="row collapse">
		<div class="large-2 columns">
			<label class="inline">Address 3</label>
		</div>	
		<div class="large-10 columns">
			<input name="Add3" type="text" value="<?php echo $row['Add3']; ?>" placeholder="" />
		</div>
	</div>
	<div class="row collapse">
		<div class="large-2 columns">
			<label class="inline">Postcode</label>
		</div>	
		<div class="large-10 columns">
			<input name="Postcode" type="text" value="<?php echo $row['Postcode']; ?>" placeholder="" />
		</div>
	</div>
	<button class="radius button" name="update_location" type="submit" >Update</button>
	<?php 
		//Check Enabled or Disabled Status
		$stmt = $conn->prepare("SELECT Active FROM Location WHERE LocationID = :id");
		$stmt->execute(array('id' => $_POST['record_id']));
	
		foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
		{ 
			if ($row['Active'] == 0) { ?><button class="radius button" name="enable_location" type="submit" style="background-color:#589E1F; border-color:#589E1F;">Enable</button><?php } 
			else if ($row['Active'] == 1) { ?><button class="radius button" name="delete_location" type="submit" style="background-color:#CC1417; border-color:#CC1417;">Disable</button><?php }
		} 
	?>
</form>
<?php	}	}	?>