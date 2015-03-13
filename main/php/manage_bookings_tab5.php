<?php
	
	//View_Despatches_And_returns
	//@Author: Unyime Ekerette
	//@Date: 26.04.2013
	dbconnect();
	
	//Admin & Staff View
	if ($_SESSION['User']['AccessLvl'] == 1 || $_SESSION['User']['AccessLvl'] == 2) {
	
		//Select Query
		$stmt = $conn->prepare("
						SELECT  bh.ActualReturnDate AS Date, l.Add1 AS Location,
								bh.EndMileage AS Mileage, ag.Manufacturer, ag.Definition,
								ag.EngineCapacity, ag.Category, v.RegistrationNo, bh.BookingHeadID
							FROM Booking_Header bh
							LEFT JOIN Asset a
							ON bh.AssetNo = a.AssetNo
							LEFT JOIN Asset_Group ag
							ON a.AssetGroupNo = ag.AssetGroupNo
							LEFT JOIN Vehicle v
							ON bh.AssetNo = v.AssetNo
							LEFT JOIN Location l
							ON bh.ActualReturnLocation = l.LocationID
							WHERE bh.ActualReturnDate IS NOT NULL
							ORDER BY Date DESC
							");
		$stmt->execute();
	
		//Start Table, Start Head
		
		/* @Edit: Added <thead> html tag to match CSS styling rules provided by UE
  	 	 * @Author: Alex McCormick 
		 * @Date: 27.03.2013 			*/
		
		$html_table = '<table id="Location-R" data-filter="#filter" class="footable">' . "\n"  . '<thead>';
		
		//New Row
    	$html_table .= '<tr>' . "\n";
	
		//Header
		//$html_table .= '<th data-class="expand">Type</th>';
		$html_table .= '<th>Date</th>';
		$html_table .= '<th data-hide="phone">Location</th>';
		$html_table .= '<th data-hide="phone">Mileage</th>';
		$html_table .= '<th data-init="hide" data-hide="phone" data-type="numeric" data-sort-initial="true">Manufacturer</th>';
		$html_table .= '<th data-init="hide" data-hide="phone,tablet" data-type="numeric" data-sort-initial="true">Model</th>';
		$html_table .= '<th data-init="hide" data-hide="phone,tablet" data-type="numeric" data-sort-initial="true">Litre</th>';
		$html_table .= '<th data-init="hide" data-hide="phone,tablet" data-type="numeric" data-sort-initial="true">Category</th>';
		$html_table .= '<th data-class="expand">RegistrationNo</th>';
		
	
		//End Head
		
		/* @Edit: Added </thead> html tag to match CSS styling rules provided by UE
  	 	 * @Author: Alex McCormick 
		 * @Date: 27.03.2013 			*/
		
    	$html_table .= '</tr>' . "\n" . '</thead>';
	
		//Start Body
		
		/* @Edit: Added <tbody> html tag to match CSS styling rules provided by UE
  	 	 * @Author: Alex McCormick 
		 * @Date: 27.03.2013 			*/
		
		$html_table .= '<tbody>';
		
		//Fetch Data
		foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row ) {	
	
			//...by Column Name	
			$html_table .= '<tr>';
			//$html_table .='<td class="Type">'.$row['Type'].'</td>';
			$html_table .= '<td class="Date">'.$row['Date'].'</td>';
			$html_table .= '<td class="Location" data-hide="phone">'.$row['Location'].'</td>';
			$html_table .= '<td class="Mileage" data-hide="phone">'.$row['Mileage'].'</td>';
			$html_table .= '<td class="Manufacturer" data-hide="phone">'.$row['Manufacturer'].'</td>';
			$html_table .= '<td class="Definition" data-hide="phone,tablet">'.$row['Definition'].'</td>';
			$html_table .= '<td class="EngineCapacity" data-hide="phone,tablet">'.$row['EngineCapacity'].'</td>';
			$html_table .= '<td class="Category" data-hide="phone,tablet">'.$row['Category'].'</td>';
			$html_table .= '<td data-class="expand">'.$row['RegistrationNo'].'</td>';
			$html_table .= '</tr>';

		
		}
	
		//End Body, End Table
		
		/* @Edit: Added </tbody> html tag to match CSS styling rules provided by UE
  	 	 * @Author: Alex McCormick 
		 * @Date: 27.03.2013 			*/
		
		$html_table .= '</tbody>' . '</table>';
	
		//Display Table
		echo $html_table;
	
	}
?>