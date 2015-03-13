<?php
	
	//View_Manage_Booking_Head
	//@Author: Unyime Ekerrete
	//@Date: 27.04.2013
	
	dbconnect();
	
	//Admin & Staff View
	if ($_SESSION['User']['AccessLvl'] == 1 || $_SESSION['User']['AccessLvl'] == 2) {
	
		//Select Query
		$stmt = $conn->prepare("SELECT * FROM Booking_Header, Location WHERE Status IN(0) AND Booking_Header.PickupLocation = Location.LocationID");
		$stmt->execute();
		$rslt = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		if($rslt) {
		
		//Start Table, Start Head		
		$html_table = '<table id="Booking_Head-inactive" data-filter="#filter" class="footable">' . "\n"  . '<thead>';
	
		//New Row	
    	$html_table .= '<tr>' . "\n";
	
		//Header	
		$html_table .= '<th data-class="expand" data-type="numeric" data-sort-initial="true">ID</th>';
        $html_table .= '<th  data-hide="phone,tablet" data-type="numeric" data-sort-initial="true">Status</th>';
        $html_table .= '<th  data-hide="phone,tablet" >User ID</th>';
        $html_table .= '<th  data-hide="phone,tablet" >Vehicle ID</th>';
        $html_table .= '<th  data-hide="phone,tablet" >CreationDate</th>';
        $html_table .= '<th  data-hide="phone" >Pickup Date</th>';
        $html_table .= '<th data-hide="phone,tablet,desktop">ActualPickUpDate</th>';
        $html_table .= '<th data-hide="phone">Pickup Location</th>';
        $html_table .= '<th  data-hide="phone,tablet,desktop" >StartMileage</th>';
        $html_table .= '<th  data-hide="phone" >Plan Return Date</th>';
        $html_table .= '<th data-hide="phone">Plan Return Location</th>';
        $html_table .= '<th data-hide="phone,tablet,desktop">Return Date</th>';
        $html_table .= '<th data-hide="phone,tablet,desktop">Return Location</th>';
        $html_table .= '<th  data-hide="phone,tablet,desktop" >EndMileage</th>';
        $html_table .= '<th  data-hide="phone,tablet" >VehicleCost</th>';
        $html_table .= '<th  data-hide="phone,tablet" >PaymentID</th>';

		//End Head	
    	$html_table .= '</tr>' . "\n" . '</thead>';
	
		//Start Body		
		$html_table .= '<tbody>';
		
		//Fetch Data
		foreach( $rslt as $row ) {	
			
			if($row['Status'] == 0) { $status = 'Inactive'; } 
			
			$stmt = $conn->prepare("SELECT Add1 AS ReturnLocation FROM Location WHERE LocationID = :location");
			$stmt->execute(array('location' => $row['ProposedReturnLocation']));
			$rslt = $stmt->fetch(PDO::FETCH_ASSOC);
			
			$html_table .= '<tr>';
			$html_table .='<td class="BookingHeadID" data-class="expand">'.$row['BookingHeadID'].'</td>';
			$html_table .= '<td class="Status" data-hide="phone,tablet">'.$status.'</td>';
			$html_table .= '<td class="UserID" data-hide="phone,tablet">'.$row['UserID'].'</td>';
			$html_table .= '<td class="AssetNo" data-hide="phone,tablet">'.$row['AssetNo'].'</td>';
			$html_table .= '<td class="CreationDate" data-hide="phone,tablet">'.$row['CreationDate'].'</td>';
			$html_table .= '<td class="ProposedPickUpDate" data-hide="phone">'.$row['ProposedPickUpDate'].'</td>';
			$html_table .= '<td class="ActualPickUpDate" data-hide="phone,tablet,desktop">'.$row['ActualPickUpDate'].'</td>';
			$html_table .= '<td class="PickupLocation" data-hide="phone">'.$row['Add1'].'</td>';
			$html_table .= '<td class="StartMileage" data-hide="phone,tablet,desktop">'.$row['StartMileage'].'</td>';
			$html_table .= '<td class="ProposedReturnDate" data-hide="phone">'.$row['ProposedReturnDate'].'</td>';
			$html_table .= '<td class="ProposedReturnLocation" data-hide="phone,tablet">'.$rslt['ReturnLocation'].'</td>';
			$html_table .= '<td class="ActualReturnDate" data-hide="phone,tablet,desktop">'.$row['ActualReturnDate'].'</td>';
			$html_table .= '<td class="ActualReturnLocation" data-hide="phone,tablet,desktop">'.$row['ActualReturnLocation'].'</td>';
			$html_table .= '<td class="EndMileage" data-hide="phone,tablet,desktop">'.$row['EndMileage'].'</td>';
			$html_table .= '<td class="VehicleCost" data-hide="phone,tablet">'.$row['VehicleCost'].'</td>';
			$html_table .= '<td class="PaymentID" data-hide="phone,tablet">'.$row['PaymentID'].'</td>';
			$html_table .= '</tr>';	
		
		}
	
		//End Body, End Table
		$html_table .= '</tbody>' . '</table>';
	
		//Display Table
		echo $html_table;
		
		}
		else {
			echo '<h3>No Results</h3>';
			echo '<p>There are no inactive Bookings at this time</p>';
		}
	}
    	
?>