<?php

	include("global.php");
	dbconnect();
	
	//Booking Header ID 
	$id = $_POST['BookingHeadID'];
	
	//Booking Header ID 
	$id_detail = $_POST['BookingDetailID'];

	if(isset($_POST['update_booking'])) {
		
		//Missed Booking
		if($_POST['missed'] == 'on') {
			$stmt = $conn->prepare("UPDATE Booking_Header SET Status = 3 WHERE BookingHeadID = :id");	
			$stmt->execute(array('id' => $id));
		}
		
		//UpdateBooking
		$PickupLocation = $_POST['PickUpLocation'];
		$ActualPickUpDate = $_POST['ActualPickUpDate'];
		$StartMileage = $_POST['StartMileage'];
		$ProposedReturnDate = $_POST['ProposedReturnDate'];
		$ProposedReturnLocation = $_POST['ProposedReturnLocation'];
		$ActualReturnDate = $_POST['ActualReturnDate'];
		$ActualReturnLocation = $_POST['ActualReturnLocation'];
		$EndMileage = $_POST['EndMileage'];
		
		$stmt = $conn->prepare("	UPDATE Booking_Header SET PickupLocation = :PickupLocation, ActualPickUpDate, = :ActualPickUpDate, 
									StartMileage = :StartMileage, ProposedReturnDate = :ProposedReturnDate, ProposedReturnLocation = :ProposedReturnLocation, 
									ActualReturnDate = :ActualReturnDate, ActualReturnLocation = :ActualReturnLocation, EndMileage = :EndMileage 
									WHERE BookingHeadID = :id																						");
		$stmt->execute(array(		'id' => $id, 'PickupLocation' => $PickupLocation, 'ActualPickUpDate' => $ActualPickUpDate, 
									'StartMileage' => $StartMileage, 'ProposedReturnDate' => $ProposedReturnDate, 
									'ProposedReturnLocation' => $ProposedReturnLocation, 'ActualReturnDate' => $ActualReturnDate, 
									'ActualReturnLocation' => $ActualReturnLocation, 'EndMileage' => $EndMileage									));
	
		Header('Location: '.fetchinline($apages).'manage_bookings.php');
		
	}

	else if(isset($_POST['cancel_booking'])) {
		
		$stmt = $conn->prepare("UPDATE Booking_Header SET Status = 0 WHERE BookingHeadID = :id");	
		$stmt->execute(array('id' => $id));
		
		Header('Location: '.fetchinline($apages).'manage_bookings.php');
		
	}
	
	else if(isset($_POST['cancel_booking_detail'])) {
				
		$stmt = $conn->prepare("DELETE FROM Booking_Detail WHERE BookingDetailID= :id");	
		$stmt->execute(array('id' => $id_detail));
		
		Header('Location: '.fetchinline($apages).'manage_bookings.php');
	}
?>