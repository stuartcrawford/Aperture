<?php

	//Manage_Locations_Edit
	//@Author: Alex McCormick
	//@Date: 22.04.2013
	
	include('global.php');
	dbconnect();
	
	if ($_POST['action'] == 'editassetgroup')
	{
		//Select Query
		$stmt = $conn->prepare("SELECT * FROM Asset_Group WHERE AssetGroupNo = :id");
		$stmt->execute(array('id' => $_POST['record_id']));
								
		//Fetch Data
		foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
		{
			
		//Vehicle Group
		if ($row['Manufacturer'] != null) { 
?>		
							
							<form id="asset_group_update_or_delete" method="post" action="<?php fetchdir($php); ?>manage_asset_groups_update_or_delete.php" enctype="multipart/form-data">
								<input type="hidden" name="AssetGroup" value="<?php echo $_POST['record_id']; ?>" />
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Manufacturer</label>
									</div>
									<div class="large-10 columns">
										<input name="Manufacturer" type="text" value="<?php echo $row['Manufacturer']; ?>" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Model</label>
									</div>
									<div class="large-10 columns">
										<input name="Definition" type="text" value="<?php echo $row['Definition']; ?>" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Litre</label>
									</div>
									<div class="large-10 columns">
										<input name="EngineCapacity" type="text" value="<?php echo $row['EngineCapacity']; ?>" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Type</label>
									</div>
									<div class="large-10 columns">
										<input name="Category" type="text" value="<?php echo $row['Category']; ?>" placeholder=""  />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Hire Cost</label>
									</div>
									<div class="large-10 columns">
										<input name="HireCost" type="text" value="<?php echo $row['HireCost']; ?>" placeholder="" />
									</div>
								</div>
								
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Image Filename</label>
									</div>
									<div class="large-10 columns">
										<input name="ImageFile" type="text" value="<?php echo $row['ImageFile']; ?>" placeholder="" readonly/>
									</div>
								</div>
								
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Update Image</label>
									</div>
									<div class="large-10 columns">
										<input name="file2" type="file" value="" placeholder="" />
									</div>
								</div>
								
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Transmission</label>
									</div>
									<div class="large-10 columns">
										<select name="Transmission">
											<?php if ($row['Transmission'] == '0') { ?><option value="0" selected>Automatic</option><?php } else { ?><option value="0">Automatic</option><?php } ?>
											<?php if ($row['Transmission'] == '1') { ?><option value="1" selected>Manual</option><?php } else { ?><option value="1"><Manual</option><?php } ?>
										</select>
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Doors</label>
									</div>
									<div class="large-10 columns">
										<input name="Doors" type="text" value="<?php echo $row['Doors']; ?>" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Seats</label>
									</div>
									<div class="large-10 columns">
										<input name="Seats" type="text" value="<?php echo $row['Seats']; ?>" placeholder="" />
									</div>
								</div>
								<button class="radius button" name="update_vehicle_group" type="submit">Update</button>
								<?php 
									//Check Enabled or Disabled Status
									$stmt = $conn->prepare("SELECT Active FROM Asset_Group WHERE AssetGroupNo = :id");
									$stmt->execute(array('id' => $_POST['record_id']));
		
									foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
									{ 
										if ($row['Active'] == 0) { ?><button class="radius button" name="enable_asset_group" type="submit" style="background-color:#589E1F; border-color:#589E1F;">Enable</button><?php } 
	 									else if ($row['Active'] == 1) { ?><button class="radius button" name="delete_asset_group" type="submit" style="background-color:#CC1417; border-color:#CC1417;">Disable</button><?php }
	 								} 
	 							?>
							</form>
							
<?php	
				}	
		
		//Optional Extra Group
		else if ($row['Manufacturer'] == null) { ?>
		
								<form id="asset_group_update_or_delete" method="post" action="<?php fetchdir($php); ?>manage_asset_groups_update_or_delete.php">
								<input type="hidden" name="AssetGroup" value="<?php echo $_POST['record_id']; ?>" />
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Description</label>
									</div>
									<div class="large-10 columns">
										<input name="Definition" type="text" value="<?php echo $row['Definition']; ?>" placeholder="" readonly/>
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Hire Cost</label>
									</div>
									<div class="large-10 columns">
										<input name="HireCost" type="text" value="<?php echo $row['HireCost']; ?>" placeholder="" />
									</div>
								</div>
								<button class="radius button" name="update_extra_group" type="submit">Update</button>
								<?php /**
									//Check Enabled or Disabled Status
									$stmt = $conn->prepare("SELECT Active FROM Asset_Group WHERE AssetGroupNo = :id");
									$stmt->execute(array('id' => $_POST['record_id']));
		
									foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
									{ 
										if ($row['Active'] == 0) { ?><button class="radius button" name="enable_asset_group" type="submit" style="background-color:#589E1F; border-color:#589E1F;">Enable</button><?php } 
	 									else if ($row['Active'] == 1) { ?><button class="radius button" name="delete_asset_group" type="submit" style="background-color:#CC1417; border-color:#CC1417;">Disable</button><?php }
	 								} **/
	 							?>
							</form>
						
<?php	}	}  } ?>				