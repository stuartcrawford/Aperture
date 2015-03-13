<?php
	
	//View_Account_Details
	//@Author: Alex McCormick
	//@Amendments by: Unyime Ekerette
	if($_POST['action'] == 'refreshbooking') include('global.php');
	dbconnect();
/*
old query
	SELECT h.BookingHeadID, h.CreationDate, h.ProposedPickUpDate,
				h.PickupLocation, h.ProposedReturnDate, h.ProposedReturnLocation,
				(SELECT COUNT(a.AssetNo)
					FROM Booking_Detail d, Asset a, Asset_Group g
					WHERE d.AssetNo=a.AssetNo
						AND a.AssetGroupNo=g.AssetGroupNo
						AND d.BookingHeadID=h.BookingHeadID
						AND g.Definition='GPS') 'GPS',
				(SELECT COUNT(a.AssetNo)
					FROM Booking_Detail d, Asset a, Asset_Group g
					WHERE d.AssetNo=a.AssetNo
						AND a.AssetGroupNo=g.AssetGroupNo
						AND d.BookingHeadID=h.BookingHeadID
						AND g.Definition='Child Seat') 'ChildSeat',
				(SELECT COUNT(a.AssetNo)
					FROM Booking_Detail d, Asset a, Asset_Group g
					WHERE d.AssetNo=a.AssetNo
						AND a.AssetGroupNo=g.AssetGroupNo
						AND d.BookingHeadID=h.BookingHeadID
						AND g.Definition='Infant Seat') 'InfantSeat',
				(SELECT COUNT(a.AssetNo)
					FROM Booking_Detail d, Asset a, Asset_Group g
					WHERE d.AssetNo=a.AssetNo
						AND a.AssetGroupNo=g.AssetGroupNo
						AND d.BookingHeadID=h.BookingHeadID
						AND g.Definition='Booster Seat') 'BoosterSeat',
				(SELECT COUNT(a.AssetNo)
					FROM Booking_Detail d, Asset a, Asset_Group g
					WHERE d.AssetNo=a.AssetNo
						AND a.AssetGroupNo=g.AssetGroupNo
						AND d.BookingHeadID=h.BookingHeadID
						AND g.Definition='Snow Tires') 'SnowTires',
				h.Status
			FROM Booking_Header h
			WHERE h.UserId=:id
*/
	
		// Query to display booking history for the logged in user
		// old query
		$id = $_SESSION['User']['ID'];
		$stmt = $conn->prepare("
		SELECT h.BookingHeadID, h.CreationDate, h.ProposedPickUpDate AS PickupDate,
		l.Add1 AS PickupLocation, h.ProposedReturnDate AS ReturnDate, l2.Add1 AS ReturnLocation,
		(SELECT COUNT(a.AssetNo)
			FROM Booking_Detail d, Asset a, Asset_Group g
			WHERE d.AssetNo=a.AssetNo
				AND a.AssetGroupNo=g.AssetGroupNo
				AND d.BookingHeadID=h.BookingHeadID
				AND g.Definition='GPS') 'GPS',
		(SELECT COUNT(a.AssetNo)
			FROM Booking_Detail d, Asset a, Asset_Group g
			WHERE d.AssetNo=a.AssetNo
				AND a.AssetGroupNo=g.AssetGroupNo
				AND d.BookingHeadID=h.BookingHeadID
				AND g.Definition='Child Seat') 'ChildSeat',
		(SELECT COUNT(a.AssetNo)
			FROM Booking_Detail d, Asset a, Asset_Group g
			WHERE d.AssetNo=a.AssetNo
				AND a.AssetGroupNo=g.AssetGroupNo
				AND d.BookingHeadID=h.BookingHeadID
				AND g.Definition='Infant Seat') 'InfantSeat',
		(SELECT COUNT(a.AssetNo)
			FROM Booking_Detail d, Asset a, Asset_Group g
			WHERE d.AssetNo=a.AssetNo
				AND a.AssetGroupNo=g.AssetGroupNo
				AND d.BookingHeadID=h.BookingHeadID
				AND g.Definition='Booster Seat') 'BoosterSeat',
		(SELECT COUNT(a.AssetNo)
			FROM Booking_Detail d, Asset a, Asset_Group g
			WHERE d.AssetNo=a.AssetNo
				AND a.AssetGroupNo=g.AssetGroupNo
				AND d.BookingHeadID=h.BookingHeadID
				AND g.Definition='Snow Tires') 'SnowTires',
		h.Status, h.VehicleCost
	FROM Booking_Header h, Location l, Location l2
	WHERE h.PickupLocation = l.LocationID
		AND h.ProposedReturnLocation = l2.LocationID
		AND h.UserId=:id
		AND h.Status NOT IN (0, 4)
		");
		$stmt->execute(Array('id' => $id));
		
		$rslt = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		if ($rslt) {
	
		//Start Table, Start Head
		
		/* @Edit: Added <thead> html tag to match CSS styling rules provided by UE
  	 	 * @Author: Alex McCormick 
		 * @Date: 27.03.2013 			*/
		
		if (!is_null($_SESSION['Form']['Success']))
		{
			$fb = '<p id="fb" style="color:green">'.$_SESSION['Form']['Success'].'</p>';
		}
		else if (!is_null($_SESSION['Form']['Error']))
		{
			$fb = '<p id="fb" style="color:red">'.$_SESSION['Form']['Error'].'</p>';
		}
		
		$html_table = '<div class="fb">'.$fb.'</div>';
		$html_table .= '<table id="Booking" data-filter="#filter" class="footable">' . "\n"  . '<thead>';
		
		//New Row
    	$html_table .= '<tr>' . "\n";
	
		$html_table .= '<th data-class="expand" data-type="numeric" data-sort-initial="true">Booking ID</th>';
		$html_table .= '<th data-hide="phone,tablet" data-type="numeric" data-sort-initial="true">Status</th>';
		$html_table .= '<th data-hide="phone">Pickup Location</th>';
		$html_table .= '<th data-hide="phone">PickUp Date</th>';
		$html_table .= '<th data-hide="phone">ReturnDate</th>';
		$html_table .= '<th data-hide="phone,tablet,desktop" >CreationDate</th>';
		$html_table .= '<th data-hide="phone,tablet,desktop" data-type="numeric" data-sort-initial="true">GPS</th>';
		$html_table .= '<th data-hide="phone,tablet,desktop" data-type="numeric" data-sort-initial="true">Child Seat</th>';
		$html_table .= '<th data-hide="phone,tablet,desktop" data-type="numeric" data-sort-initial="true">Infant Seat</th>';
		$html_table .= '<th data-hide="phone,tablet,desktop" data-type="numeric" data-sort-initial="true">Booster Seat</th>';
		$html_table .= '<th data-hide="phone,tablet,desktop" data-type="numeric" data-sort-initial="true">Snow Tires</th>';
		$html_table .= '<th data-hide="phone">Return Location</th>';
		$html_table .= '<th data-hide="phone">Total Cost</th>';
		$html_table .= '<th data-sort-ignore="true">Action</th>';
	
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
		foreach( $rslt as $row ) {
			
			// Calculate the total cost of vehicle
			$pickup = $row['ProposedPickupDate'];
			$dropoff = $row['ProposedReturnDate'];
			$diffsecs = strtotime($dropoff) - strtotime($pickup);
			$diff = ceil($diffsecs / 60 / 60 / 24);
			if ($diffsecs <= 60 * 60 * 12)
			{
				$diff = $diff / 2;
				$BookingVehicleCost = ceil($diff*$row['VehicleCost']);
			}
			else
			{
				$BookingVehicleCost = $diff*$row['VehicleCost'];
			}
			$BookingVehicleCost = number_format($BookingVehicleCost, 2, '.', '');
			
			// Calculate the total cost of extras
			$diff = strtotime($dropoff) - strtotime($pickup);
			$diff = ceil($diff / 60 / 60 / 24);
			$stmt2 = $conn->prepare("SELECT * FROM Booking_Detail WHERE BookingDetailID = :id");
			$stmt2->bindParam(':id',$row['BookingHeadID']);
			$stmt2->execute();
			$BookingExtrasCost = 0;
			foreach( $stmt2->fetchAll(PDO::FETCH_ASSOC) as $row2 )
			{
				$BookingExtrasCost += $row2['OptionalExtraCost'];
			}
			$BookingExtrasCost = number_format($BookingExtrasCost, 2, '.', '');
			
			// Calculate the price paid
			$total = ($BookingVehicleCost + $BookingExtrasCost)*100;
		
			if($row['Status'] == 1) { $status = 'Active'; } else if($row['Status'] == 2) { $status = 'Complete'; } else if($row['Status'] == 3) { $status = 'Missed'; } 
			if($row['Status'] == 1) { $cancel = '<a href="javascript:cancelrecord(\''.$row['BookingHeadID'].'\');">Cancel</a>'; } else { $cancel = ''; }
				
			//...by Column Name	
			$html_table .= '<tr>';
			$html_table .='<td class="BookingHeadID" data-type="numeric" data-class="expand" data-sort-initial="true">'.$row['BookingHeadID'].'</td>';
			$html_table .= '<td class="Status" data-hide="phone,tablet">'.$status.'</td>'; 
			$html_table .= '<td class="PickupLocation" data-hide="phone">'.$row['PickupLocation'].'</td>'; 
			$html_table .= '<td class="PickupDate" data-hide="phone">'.$row['PickupDate'].'</td>';
			$html_table .= '<td class="ReturnDate" data-hide="phone">'.$row['ReturnDate'].'</td>';
			$html_table .= '<td class="CreationDate" data-hide="phone,tablet,desktop">'.$row['CreationDate'].'</td>';
			$html_table .= '<td class="GPS" data-hide="phone,tablet,desktop">'.$row['GPS'].'</td>';
			$html_table .= '<td class="ChildSeat" data-hide="phone,tablet,desktop">'.$row['ChildSeat'].'</td>';
			$html_table .= '<td class="InfantSeat" data-hide="phone,tablet,desktop">'.$row['InfantSeat'].'</td>';
			$html_table .= '<td class="BoosterSeat" data-hide="phone,tablet,desktop">'.$row['BoosterSeat'].'</td>';
			$html_table .= '<td class="SnowTires" data-hide="phone,tablet,desktop">'.$row['SnowTires'].'</td>';
			$html_table .= '<td class="ReturnLocation" data-hide="phone">'.$row['ReturnLocation'].'</td>';
			$html_table .= '<td class="TotalCost" data-hide="phone">36.00</td>';
			$html_table .= '<td data-sort-ignore="true"><div id="select_record'.$row['BookingHeadID'].'" class="select_record">'.$cancel.'</div></td>';			
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
		else {
			echo '<h3>No Bookings</h3>';
			echo '<p>You have made no bookings so far</p>';	
		}
	
?>