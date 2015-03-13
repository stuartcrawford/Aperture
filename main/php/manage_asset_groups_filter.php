 <?php
 	if ($_POST['vehiclegroup'] == "hide" && $_POST['extragroup'] == "hide") {
?>

  				<div class="offset-1 large-5 medium-12 small-12 columns">
					<form class="custom">
						<label for="customDropdown">Column</label>
						<select id="customDropdown">
							<option value="All">All</option>
						</select>
					</form>
				</div> 

<?php
	}
	else if ($_POST['vehiclegroup'] == "show" && $_POST['extragroup'] == "hide") {
 ?>
						
	<div class="offset-1 large-5 medium-12 small-12 columns">
		<form class="custom">
			<label for="customDropdown">Column</label>
			<select id="customDropdown">
				<option value="All">All</option>
				<option value="AssetGroupNo">ID</option>
				<option value="Manufacturer">Manufacturer</option>
				<option value="Definition">Model</option>
  				<option value="EngineCapacity">Litre</option>
  				<option value="Category">Category</option>
  				<option value="HireCost">Hire Cost</option>
  				<option value="ImageFile">Image Filename</option>
  				<option value="Doors">Doors</option>
  				<option value="Seats">Seats</option>
  				<option value="Transmission">Drive</option>
			</select>
		</form> 
	</div>
	
<?php
	}
	else if ($_POST['vehiclegroup'] == "hide" && $_POST['extragroup'] == "show") {
 ?>
	
	<div class="offset-1 large-5 medium-12 small-12 columns">
		<form class="custom">
			<label for="customDropdown">Column</label>
			<select id="customDropdown">
				<option value="All">All</option>
				<option value="AssetGroupNo">ID</option>
				<option value="Definition">Description</option>
				<option value="HireCost">Hire Cost</option>
			</select>
		</form> 
	</div>
																		
<?php } ?>
<script>
	$(document).foundation('off');
	$(document).foundation();
</script>