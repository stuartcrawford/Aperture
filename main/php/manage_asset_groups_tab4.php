<?php

	//View_Manage_Assset_Groups_Add_New
	//@Author: Alex McCormick
	//@Date: 02.05.2013
	if(isset($_POST['vehiclegroup']) || isset($_POST['extragroup'])) { include 'global.php'; }
	
	if($_POST['vehiclegroup'] == 'hide' && $_POST['extragroup'] == 'hide' || !isset($_POST['vehiclegroup']) && !isset($_POST['extragroup']) ) {
		echo '<h3>Please Filter on an Asset Group Type</h3>';
		echo '<p>You will need to have filtered on an Asset Group Type from the top of the page to see data in this section</p>';
		exit;
	}	
	
	//Vehicle Asset Group Add
	else if ($_POST['vehiclegroup'] == 'show' && $_POST['extragroup'] == 'hide') { ?>

							<form id="asset_group_insert" method="post" action="<?php fetchdir($php); ?>manage_asset_groups_insert.php" enctype="multipart/form-data">
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Manufacturer</label>
									</div>
									<div class="large-10 columns">
										<input name="Manufacturer" type="text" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Model</label>
									</div>
									<div class="large-10 columns">
										<input name="Definition" type="text" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Litre</label>
									</div>
									<div class="large-10 columns">
										<input name="EngineCapacity" type="text" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Type</label>
									</div>
									<div class="large-10 columns">
										<input name="Category" type="text" placeholder=""  />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Hire Cost</label>
									</div>
									<div class="large-10 columns">
										<input name="HireCost" type="text" placeholder="" />
									</div>
								</div>
								
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Upload Image</label>
									</div>
									<div class="large-10 columns">
										<input name="file" type="file" value="" placeholder="" />
									</div>
								</div>
								
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Transmission</label>
									</div>
									<div class="large-10 columns">
										<select name="Transmission">
											<option value="0">Automatic</option>
											<option value="1" selected>Manual</option>
										</select>
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Doors</label>
									</div>
									<div class="large-10 columns">
										<input name="Doors" type="text" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Seats</label>
									</div>
									<div class="large-10 columns">
										<input name="Seats" type="text" placeholder="" />
									</div>
								</div>
								<button class="radius button" name="add_vehicle_group" type="submit">Add</button>
							</form>		

<?php	}
	
	//Optional Extra Asset Group Add
	else if ($_POST['vehiclegroup'] == 'hide' && $_POST['extragroup'] == 'show') { ?>
		
		
								<form id="asset_group_insert" method="post" action="<?php fetchdir($php); ?>manage_asset_groups_insert.php">
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Description</label>
									</div>
									<div class="large-10 columns">
										<input name="Definition" type="text" value="" placeholder="" />
									</div>
								</div>
								<div class="row collapse">
									<div class="large-2 columns">
										<label class="inline">Hire Cost</label>
									</div>
									<div class="large-10 columns">
										<input name="HireCost" type="text" value="" placeholder="" />
									</div>
								</div>
								<button class="radius button" name="add_extra_group" type="submit">Add</button>
							</form>	
		
<?php	} ?>

