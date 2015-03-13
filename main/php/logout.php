<?php
	include('global.php');
	dbconnect();
	
	if ($_SESSION['User']['AccessLvl'] == null)
	{
		// Redirect to homepage if user is not logged in
		header('Location: '.fetchinline($gpages).'index.php');
	}
	else if (isset($_POST['submit']))
	{
		//Clear Booking @Added by AM: 29.04.2013
		$stmt = $conn->prepare("SELECT * FROM Booking_Header WHERE UserID = :UserID AND STATUS = 4;");
		$stmt->execute(array('UserID' => $_SESSION['User']['ID']));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		if($row) {
			$stmt = $conn->prepare("DELETE FROM Booking_Header WHERE BookingHeadID = :BookingHeadID");	
			$stmt->execute(array('BookingHeadID' => $row['BookingHeadID']));
			$stmt = $conn->prepare("DELETE FROM Booking_Detail WHERE BookingHeadID = :BookingHeadID");	
			$stmt->execute(array('BookingHeadID' => $row['BookingHeadID']));
		}	
		
		/**unset(  $_SESSION['Reservation'], $_SESSION['Booking']['Head'], $_SESSION['Booking']['Detail'], $_SESSION['Booking']['Extra'], 
				$_SESSION['Booking']['Vehicle'], $_SESSION['Asset'], $_SESSION['Invoice'], $_SESSION['TotalPrice'],$_SESSION['TotalAmount'],
				$_SESSION['Response'],$_SESSION['User']['APIToken'], $_SESSION['Booking']['Step']);
		
		unset($_SESSION['User']); // Unauthenticate the user**/
		session_regenerate_id(); // Prevents session fixation
		session_unset(); // Empty the session, unauthenticating the user
		csrfguard_regenerate_token(); // Get new CSRF token
		
		$_SESSION['Form']['Submit'] = true;
		header('Location: '.fetchinline($apages).'logout_success.php');
	}
?>