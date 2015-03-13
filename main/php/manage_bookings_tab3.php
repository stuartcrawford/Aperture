<?php

//Manage_Vehicles_Active_Booking
//@Author: Alex McCormick
//@Date: 22.04.2013
	
include('global.php');
dbconnect();
	
if ($_POST['action'] == 'bookingdetail') {
	
	//Admin & Staff View
	if ($_SESSION['User']['AccessLvl'] == 1 || $_SESSION['User']['AccessLvl'] == 2) {
	
		//Select Query
		$id = $_POST['record_id'];
		$stmt = $conn->prepare("SELECT * FROM Booking_Detail WHERE BookingHeadID = :id");
		$stmt->execute(array('id' => $id));
		
		$rslt = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		if ($rslt) {
		
			//Start Table, Start Head		
			$html_table = '<table id="Booking_Detail" data-filter="#filter" class="footable">' . "\n"  . '<thead>';
	
			//New Row	
    		$html_table .= '<tr>' . "\n";
	
			//Header
				$html_table .= '<th data-class="expand">ID</th>';
				$html_table .= '<th data-hide="">Cost</th>';
				$html_table .= '<th data-hide="">AssetNo</th>';
				$html_table .= '<th data-sort-ignore="true">Select</th>';

			//End Head	
    		$html_table .= '</tr>' . "\n" . '</thead>';
	
			//Start Body		
			$html_table .= '<tbody>';
		
			//Fetch Data
			foreach( $rslt as $row ) {	
			
				$html_table .= '<tr>';
				$html_table .= '<td class="BookingDetailID" data-class="expand" data-hide="">'.$row['BookingDetailID'].'</td>';
				$html_table .= '<td class="OptionalExtraCost" data-hide="">'.$row['OptionalExtraCost'].'</td>';
				$html_table .= '<td class="AssetNo" data-hide="phone">'.$row['AssetNo'].'</td>';
				$html_table .= '<td data-sort-ignore="true"><div id="select_record'.$row['BookingDetailID'].'" class="select_record"><a href="javascript:selectsubrecord(\''.$row['BookingDetailID'].'\');">Select</a></div></td>';
				$html_table .= '</tr>';	
		
			}
	
			//End Body, End Table
			$html_table .= '</tbody>' . '</table>';
	
			//Display Table
			echo $html_table;
			
		}
		else {
			echo '<h3>No Results</h3>';
			echo '<p>There are no optionial extras hired against the selected booking</p>';
		}
	
	}
	
}
?>