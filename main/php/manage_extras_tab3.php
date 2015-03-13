<?php

//Manage_Vehicles_Active_Booking
//@Author: Alex McCormick
//@Date: 22.04.2013
	
include('global.php');
dbconnect();
	
if ($_POST['action'] == 'activebookings') {
	
	//Admin & Staff
	if ($_SESSION['User']['AccessLvl'] == 1 || $_SESSION['User']['AccessLvl'] == 2) {
	
		//Select Query
		$selectedextra = $_POST['record_id'];
		$stmt = $conn->prepare("SELECT * FROM Location, Booking_Header, Booking_Detail WHERE Booking_Header.BookingHeadID = Booking_Detail.BookingHeadID AND PickupLocation = LocationID AND Booking_Detail.AssetNo = :selectedextra AND Booking_Header.Status = 1");
		$stmt->execute(array('selectedextra' => $selectedextra));
		
		$rslt = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		if ($rslt) {
		
			//Start Table, Start Head		
			$html_table = '<table id="Booking" data-filter="#filter" class="footable">' . "\n"  . '<thead>';
	
			//New Row	
    		$html_table .= '<tr>' . "\n";
	
			//Header
			$html_table .= '<th data-class="expand" data-type="numeric">Booking ID</th>';
			$html_table .= '<th data-type="numeric">Optional Extra ID</th>';
			$html_table .= '<th data-hide="phone">User</th>';
			$html_table .= '<th data-hide="phone">Created</th>';
			$html_table .= '<th data-hide="phone">Pickup Date</th>';			
			$html_table .= '<th data-hide="phone,tablet">Pickup Location</th>';
			$html_table .= '<th data-hide="phone,tablet">Start Mileage</th>';
			$html_table .= '<th data-hide="phone,tablet">Cost</th>';
			$html_table .= '<th data-hide="phone,tablet">Payment ID</th>';

			//End Head	
    		$html_table .= '</tr>' . "\n" . '</thead>';
	
			//Start Body		
			$html_table .= '<tbody>';
		
			//Fetch Data
			foreach( $rslt as $row ) {
				
				$pickupdate = (isset($row['ActualPickUpDate'])) ? $row['ActualPickUpDate'] : $row['ProposedPickUpDate'];
				$startmileage = (isset($row['StartMileage'])) ? $row['StartMileage'] : 'Not Despatched';
			
				$html_table .= '<tr>';
				$html_table .= '<td class="BookingHeadID" data-class="expand" data-type="numeric">'.$row['BookingHeadID'].'</td>';
				$html_table .= '<td class="AssetNo" data-type="numeric">'.$row['AssetNo'].'</td>';
				$html_table .= '<td class="UserID" data-hide="phone">'.$row['UserID'].'</td>';
				$html_table .= '<td class="CreationDate" data-hide="phone">'.$row['CreationDate'].'</td>';
				$html_table .= '<td class="ActualPickUpDate" data-hide="phone,tablet">'.$pickupdate.'</td>';
				$html_table .= '<td class="PickupLocation" data-hide="phone,tablet">'.$row['Add1'].'</td>';
				$html_table .= '<td class="StartMileage" data-hide="phone,tablet">'.$startmileage.'</td>';
				$html_table .= '<td class="Location" data-hide="phone,tablet">'.$row['OptionalExtraCost'].'</td>';
				$html_table .= '<td class="Location" data-hide="phone,tablet">'.$row['PaymentID'].'</td>';
				$html_table .= '</tr>';	
		
			}
	
			//End Body, End Table
			$html_table .= '</tbody>' . '</table>';
	
			//Display Table
			echo $html_table;
			
		}
		else {
			echo '<h3>No Results</h3>';
			echo '<p>There are no current bookings on the selected vehicle</p>';
		}
	
	}
}
?>