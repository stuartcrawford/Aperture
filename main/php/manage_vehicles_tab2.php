<?php
	
	//View_Manage_Inactive_Vehicles
	//@Author: Alex McCormick
	//@Date: 26.04.2013
	
	dbconnect();
	
	//Admin & Staff View
	if ($_SESSION['User']['AccessLvl'] == 1 || $_SESSION['User']['AccessLvl'] == 2) {
	
		//Select Query
		$stmt = $conn->prepare("SELECT * FROM Vehicle, Asset, Asset_Group, Location WHERE Vehicle.AssetNo = Asset.AssetNo AND Asset.AssetGroupNo = Asset_Group.AssetGroupNo AND Asset.Location = Location.LocationID AND Asset.Active = 0");
		$stmt->execute();
		$rslt = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if($rslt) {
		
		//Start Table, Start Head		
		$html_table = '<table id="Vehicle-inactive" data-filter="#filter" class="footable">' . "\n"  . '<thead>';
	
		//New Row	
    	$html_table .= '<tr>' . "\n";
	
		//Header
		$html_table .= '<th data-hide="phone,tablet">Avail.</th>';	
		$html_table .= '<th data-hide="phone" data-type="numeric" data-sort-initial="true">ID</th>';
		$html_table .= '<th data-hide="phone,tablet,desktop">Updated</th>';
		$html_table .= '<th data-hide="phone">Manufacturer</th>';
		$html_table .= '<th data-hide="phone">Model</th>';
		$html_table .= '<th data-class="expand">Registration</th>';
		$html_table .= '<th data-hide="phone">Litre</th>';	
		$html_table .= '<th data-hide="phone,tablet">Category</th>';		
		$html_table .= '<th data-hide="phone,tablet">Seats</th>';
		$html_table .= '<th data-hide="phone,tablet">Doors</th>';
		$html_table .= '<th data-hide="phone,tablet">MOT Expiry</th>';
		$html_table .= '<th data-hide="phone,tablet">Tax Expiry</th>';
		$html_table .= '<th data-hide="phone,tablet" data-type="numeric">Mileage</th>'; 
		$html_table .= '<th data-hide="phone,tablet">Drive</th>';
		$html_table .= '<th data-hide="phone,tablet" data-type="numeric">Asset Group</th>';
		$html_table .= '<th data-hide="phone,tablet">Location</th>';
		$html_table .= '<th data-sort-ignore="true">Select</th>';

		//End Head	
    	$html_table .= '</tr>' . "\n" . '</thead>';
	
		//Start Body		
		$html_table .= '<tbody>';
		
		//Fetch Data
		foreach( $rslt as $row ) {	
			
			$drive = ($row['Transmission'] == 0) ? 'A' : 'M';
			
			$html_table .= '<tr>';
			if($row['Available'] == 0) {
				$html_table .= '<td class="Available" data-value="disabled" data-hide="phone,tablet"><img title="disabled" src="'.$protocol.$host.$branch.$imgfootable.'disabled.png" /></td>';
			}
			else {
				$html_table .= '<td class="Available" data-value="active" data-hide="phone,tablet"><img title="active" src="'.$protocol.$host.$branch.$imgfootable.'active.png" /></td>';
			}

			$html_table .= '<td class="AssetNo"  		data-hide="phone,tablet,desktop">'.$row['AssetNo'].'</td>';
			$html_table .= '<td class="LastUpdated"  	data-hide="phone,tablet,desktop">'.$row['LastUpdated'].'</td>';
			$html_table .= '<td class="Manufacturer" 	data-hide="phone">'.$row['Manufacturer'].'</td>';
			$html_table .= '<td class="Definition" 		data-class="expand">'.$row['Definition'].'</td>';
			$html_table .= '<td class="RegistrationNo" 	data-hide="phone">'.$row['RegistrationNo'].'</td>';
			$html_table .= '<td class="EngineCapacity" 	data-hide="phone">'.$row['EngineCapacity'].'</td>';	
			$html_table .= '<td class="Category" 		data-hide="phone,tablet">'.$row['Category'].'</td>';		
			$html_table .= '<td class="Seats" 			data-hide="phone,tablet">'.$row['Seats'].'</td>';
			$html_table .= '<td class="Doors" 			data-hide="phone,tablet">'.$row['Doors'].'</td>';
			$html_table .= '<td class="MOTExpiry" 		data-hide="phone,tablet">'.$row['MOTExpiry'].'</td>';
			$html_table .= '<td class="TaxExpiry" 		data-hide="phone,tablet">'.$row['TaxExpiry'].'</td>';
			$html_table .= '<td class="Mileage" 		data-hide="phone,tablet" data-type="numeric">'.$row['Mileage'].'</td>';	
			$html_table .= '<td class="Transmission" 	data-hide="phone,tablet">'.$drive.'</td>';
			$html_table .= '<td class="AssetGroupNo" 	data-hide="phone,tablet">'.$row['AssetGroupNo'].'</td>';
			$html_table .= '<td class="Location" 		data-hide="phone,tablet">'.$row['Add1'].'</td>';
			
			$html_table .= '<td data-sort-ignore="true"><div id="select_record'.$row['AssetNo'].'" class="select_record"><a href="javascript:selectrecord(\''.$row['AssetNo'].'\');">Select</a></div></td>';
			$html_table .= '</tr>';	
		
		}
	
		//End Body, End Table
		$html_table .= '</tbody>' . '</table>';
	
		//Display Table
		echo $html_table;
		
		}
		else {
			echo '<h3>No Results</h3>';
			echo '<p>There are no inactive Vehicles at this time</p>';
		}
	}
?>