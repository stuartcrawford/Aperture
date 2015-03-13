<?php

	//View_Manage_Locations_Add_New
	//@Author: Stuart Crawford
	//@Date: 26.04.2013
	
?>

<form class="custom" method="post" action="<?php fetchdir($php); ?>manage_locations_location_insert.php">
	<div class="row collapse">
		<div class="large-2 columns">
			<label class="inline">Address 1</label>
		</div>
		<div class="large-10 columns">
			<input name="Add1" type="text" value="" placeholder="" />
		</div>
	</div>
	<div class="row collapse">
		<div class="large-2 columns">
			<label class="inline">Address 2</label>
		</div>	
		<div class="large-10 columns">
			<input name="Add2" type="text" value="" placeholder="" />
		</div>
	</div>
	<div class="row collapse">
		<div class="large-2 columns">
			<label class="inline">Address 3</label>
		</div>	
		<div class="large-10 columns">
			<input name="Add3" type="text" value="" placeholder="" />
		</div>
	</div>
	<div class="row collapse">
		<div class="large-2 columns">
			<label class="inline">Postcode</label>
		</div>	
		<div class="large-10 columns">
			<input name="Postcode" type="text" value="" placeholder="" />
		</div>
	</div>
	<button class="radius button" name="add_location" type="submit">Add</button>
</form>