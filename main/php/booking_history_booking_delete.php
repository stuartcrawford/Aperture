<?php

	if(isset($_POST['action'])) {
	
		include ('global.php');
		dbconnect();
	
		$stmt = $conn->prepare("SELECT BookingHeadID FROM Booking_Header WHERE BookingHeadID = :id AND TIME_TO_SEC(TIMEDIFF(ProposedPickUpDate,now())) >= :min");
		$stmt->bindParam(':id',$_POST['record_id']);
		$stmt->bindParam(':min',$minAdvancedBooking);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		if ($row)
		{
			$id = $_POST['record_id'];
			$stmt = $conn->prepare("UPDATE Booking_Header SET Status = 0 WHERE BookingHeadID = :id");
			$stmt->execute(Array('id' => $id));
			
			$_SESSION['Form']['Success'] = 'You have succesfully cancelled the booking';
			echo 'Success';
		}
		else
		{
			$_SESSION['Form']['Error'] = 'You can only cancel a booking a minimum of 24 hours before pickup';
			echo 'You can only cancel a booking a minimum of 24 hours before pickup';
		}
		
	}

?>