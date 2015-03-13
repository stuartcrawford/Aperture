<?php
	
	//View_Manage_Asset_Group
	//@Author: Stuart Crawford
	//@Date: 01.05.2013
	if(isset($_POST['vehiclegroup']) || isset($_POST['extragroup'])) { include 'global.php'; }
	dbconnect();
	
	//Admin View
	if ($_SESSION['User']['AccessLvl'] == 1) {
		
			if($_POST['vehiclegroup'] == 'hide' && $_POST['extragroup'] == 'hide' || !isset($_POST['vehiclegroup']) && !isset($_POST['extragroup']) ) {
				echo '<h3>Please Filter on an Asset Group Type</h3>';
				echo '<p>You will need to have filtered on an Asset Group Type from the top of the page to see data in this section</p>';
				exit;
			}	
			else if ($_POST['vehiclegroup'] == 'show' && $_POST['extragroup'] == 'hide') {
				//Select Query
				$stmt = $conn->prepare("SELECT * FROM Asset_Group WHERE Active = 1 AND Manufacturer IS NOT NULL");
				$stmt->execute();
			}
			else if ($_POST['vehiclegroup'] == 'hide' && $_POST['extragroup'] == 'show') {
				//Select Query
				$stmt = $conn->prepare("SELECT * FROM Asset_Group WHERE Active = 1 AND Manufacturer IS NULL");
				$stmt->execute();
			}
			
		$rslt = $stmt->fetchAll(PDO::FETCH_ASSOC);	
		
		if ($rslt)
		{
		
		//Start Table, Start Head		
		$html_table = '<table id="Asset_Group" data-filter="#filter" class="footable">' . "\n"  . '<thead>';
	
		//New Row	
    	$html_table .= '<tr>' . "\n";

		//Header
		$html_table .= '<th data-class="expand" data-type="numeric" data-sort-initial="true">ID</th>';
		if ($_POST['vehiclegroup'] == 'show') { $html_table .= '<th data-hide="phone">Manufacturer</th>'; }
        if ($_POST['vehiclegroup'] == 'show') { $html_table .= '<th  data-hide="phone,tablet">Model</th>'; }
		else if ($_POST['extragroup'] == 'show') { $html_table .= '<th>Description</th>'; }
		if ($_POST['vehiclegroup'] == 'show') { $html_table .= '<th data-hide="phone">Litre</th>'; }
        if ($_POST['vehiclegroup'] == 'show') { $html_table .= '<th  data-hide="phone,tablet" data-type="numeric" data-sort-initial="true">Category</th>'; }
        if ($_POST['vehiclegroup'] == 'show') { $html_table .= '<th  data-hide="phone,tablet" data-type="numeric" data-sort-initial="true">Hire Cost</th>'; }
		else if ($_POST['extragroup'] == 'show') { $html_table .= '<th data-hide="phone" data-type="numeric" data-sort-initial="true">Hire Cost</th>'; }
        if ($_POST['vehiclegroup'] == 'show') { $html_table .= '<th  data-hide="phone,tablet" data-type="numeric" data-sort-initial="true">Image Filename</th>'; }
        if ($_POST['vehiclegroup'] == 'show') { $html_table .= '<th  data-hide="phone,tablet,desktop" data-type="numeric" data-sort-initial="true">Transmission</th>'; }
        if ($_POST['vehiclegroup'] == 'show') { $html_table .= '<th data-hide="phone">Doors</th>'; }
        if ($_POST['vehiclegroup'] == 'show') { $html_table .= '<th data-hide="phone">Seats</th>'; }
		$html_table .= '<th data-sort-ignore="true">Select</th>';

		//End Head	
    	$html_table .= '</tr>' . "\n" . '</thead>';
	
		//Start Body		
		$html_table .= '<tbody>';
		
		//Fetch Data
		foreach( $rslt as $row ) {	

			$drive = ($row['Transmission'] == 0) ? 'Automatic' : 'Manual';

			$html_table .= '<tr>';	
			$html_table .= '<td class="AssetGroupNo" data-type="numeric" data-class="expand">'.$row['AssetGroupNo'].'</td>';
			if ($_POST['vehiclegroup'] == 'show') { $html_table .= '<td class="Manufacturer" data-hide="phone">'.$row['Manufacturer'].'</td>'; }		
			if ($_POST['vehiclegroup'] == 'show') { $html_table .= '<td class="Definition" data-hide="phone,tablet">'.$row['Definition'].'</td>'; }
			else if ($_POST['extragroup'] == 'show') { $html_table .= '<td class="Definition">'.$row['Definition'].'</td>'; }
			if ($_POST['vehiclegroup'] == 'show') { $html_table .= '<td class="EngineCapacity" data-hide="phone">'.$row['EngineCapacity'].'</td>'; }
			if ($_POST['vehiclegroup'] == 'show') { $html_table .= '<td class="Category" data-hide="phone,tablet">'.$row['Category'].'</td>'; }
			if ($_POST['vehiclegroup'] == 'show') { $html_table .= '<td class="HireCost" data-hide="phone,tablet">'.$row['HireCost'].'</td>'; }
			else if ($_POST['extragroup'] == 'show') { $html_table .= '<td class="HireCost" data-hide="phone">'.$row['HireCost'].'</td>'; }
			if ($_POST['vehiclegroup'] == 'show') { $html_table .= '<td class="ImageFile" data-hide="phone,tablet">'.$row['ImageFile'].'</td>'; }
			if ($_POST['vehiclegroup'] == 'show') { $html_table .= '<td class="Transmission" data-hide="phone,tablet">'.$drive.'</td>'; }
			if ($_POST['vehiclegroup'] == 'show') { $html_table .= '<td class="Doors" data-hide="phone,tablet">'.$row['Doors'].'</td>'; }
			if ($_POST['vehiclegroup'] == 'show') { $html_table .= '<td class="Seats" data-hide="phone,tablet">'.$row['Seats'].'</td>'; }
			
			$html_table .= '<td data-sort-ignore="true"><div id="select_record'.$row['AssetGroupNo'].'" class="select_record"><a href="javascript:selectrecord(\''.$row['AssetGroupNo'].'\');">Select</a></div></td>';
			$html_table .= '</tr>';	
		
		}
	
		//End Body, End Table
		$html_table .= '</tbody>' . '</table>';
	
		//Display Table
		echo $html_table;
		
		}
		else {
			echo '<h3>No Results</h3>';
			echo '<p>There are no active Asset Groups at this time</p>';
		}
	}

?>