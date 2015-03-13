<?php
	
	//View_Manage_Extras
	//@Author: Alex McCormick
	//@Date: 03.05.2013
	
	dbconnect();
	
	//Admin and Staff View
	if ($_SESSION['User']['AccessLvl'] == 1 || $_SESSION['User']['AccessLvl'] == 2) {
	
		//Select Query
		$stmt = $conn->prepare("SELECT * FROM Optional_Extra, Asset, Asset_Group, Location WHERE Optional_Extra.AssetNo = Asset.AssetNo AND Asset.AssetGroupNo = Asset_Group.AssetGroupNo AND Asset.Location = Location.LocationID AND Asset.Active = 1");
		$stmt->execute();
		
		$rslt = $stmt->fetchAll(PDO::FETCH_ASSOC);	
		
		if ($rslt)
		{
		
		//Start Table, Start Head		
		$html_table = '<table id="Optional_Extra" data-filter="#filter" class="footable">' . "\n"  . '<thead>';
	
		//New Row	
    	$html_table .= '<tr>' . "\n";
	
		//Header
		$html_table .= '<th data-class="expand" data-type="numeric" data-sort-initial="true">ID</th>';
		$html_table .= '<th data-hide="phone">Description</th>';
		$html_table .= '<th data-hide="phone">Serial Num</th>';	
		$html_table .= '<th data-hide="phone,tablet">Hire Cost</th>';		
		$html_table .= '<th data-hide="phone,tablet" data-type="numeric">Asset Group</th>';
		$html_table .= '<th data-hide="phone,tablet">Location</th>';
		$html_table .= '<th data-sort-ignore="true">Select</th>';

		//End Head	
    	$html_table .= '</tr>' . "\n" . '</thead>';
	
		//Start Body		
		$html_table .= '<tbody>';
		
		//Fetch Data
		foreach( $rslt as $row ) {	
			
			$html_table .= '<tr>';
			$html_table .= '<td class="AssetNo"  		data-class="expand">'.$row['AssetNo'].'</td>';
			$html_table .= '<td class="Definition"						>'.$row['Definition'].'</td>';
			$html_table .= '<td class="SerialNumber" 	data-hide="phone">'.$row['SerialNumber'].'</td>';	
			$html_table .= '<td class="HireCost" 		data-hide="phone,tablet">'.$row['HireCost'].'</td>';		
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
			echo '<p>There are no active Optional Extras at this time</p>';
		}	
	}
?>