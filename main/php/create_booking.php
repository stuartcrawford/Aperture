<?php

	// Create Booking Function
	// @Author: Alex McCormick						
	
	include('global.php');
	dbconnect();
	
	//Booking Completed or Aborted
	//Also Disallow Typed URL Navigation
	if (!isset($referrer)){
		Header('Location: '.fetchinline($gpages).'index.php');
		exit;
	}

	//Abort Booking
	if (isset($_POST['cancel'])) {
		$_SESSION['Booking']['Aborted'] = true;
		$stmt = $GLOBALS['conn'] ->prepare("DELETE FROM Booking_Header WHERE BookingHeadID = :BookingHeadID");	
		$stmt->execute(array('BookingHeadID' => $_SESSION['Booking']['Head']['ID']));
		$stmt = $GLOBALS['conn'] ->prepare("DELETE FROM Booking_Detail WHERE BookingHeadID = :BookingHeadID");	
		$stmt->execute(array('BookingHeadID' => $_SESSION['Booking']['Head']['ID']));
		unset(	$_SESSION['Booking']['Head'], $_SESSION['Booking']['Detail'], $_SESSION['Booking']['Extra'], 
				$_SESSION['Asset'], $_SESSION['Invoice'], $_SESSION['TotalPrice'],$_SESSION['TotalAmount'],
				$_SESSION['Response'],$_SESSION['User']['APIToken'], $_SESSION['ewayAPI']);
		$_SESSION['Booking']['Step'] = 0;
	  	Header('Location: '.fetchinline($bpages).'booking_aborted.php');
		exit;
	}

	// #####################################################################################################################################################
	
	//Index -> Select Vehicle
	//@Author: Alex McCormick		
	else if (isset($_POST['booking1'])) {
		
		//Check For Reservation Timeout
		reservation_timeout();
		
		//Process Post Data			
		$_SESSION['Booking']['Head']['Pickup']['Location'] = $_POST['PickupLocation'];
		$_SESSION['Booking']['Head']['Pickup']['Date'] = $_POST['PickupDate'];
		$_SESSION['Booking']['Head']['Pickup']['Time'] =  $_POST['PickupTime'];
		
		//Bull Dropoff Location default to Pickup Location 
		$_SESSION['Booking']['Head']['Dropoff']['Location'] = ($_POST['DropoffLocation'] == 'default') ? $_POST['PickupLocation'] : $_POST['DropoffLocation'];
		
		$_SESSION['Booking']['Head']['Dropoff']['Date'] = $_POST['DropoffDate'];
		$_SESSION['Booking']['Head']['Dropoff']['Time'] = $_POST['DropoffTime'];
		
		//Check Booking is at least 24Hrs in the Future
		$pickup = date(strtotime($_SESSION['Booking']['Head']['Pickup']['Date'].' '.$_SESSION['Booking']['Head']['Pickup']['Time']));
		$bookingnotice = time() + $minAdvancedBooking;
		
		if ($pickup < $bookingnotice ) {
			$_SESSION['Form']['Error'] = 'You must make a booking at least 24 hours in the future';
			Header('Location: '.fetchinline($gpages).'index.php');
			exit;
		}

		//Check Booking Duration is at least one hour
		$pickup = $pickup + (60*60*2);
		$dropoff = date(strtotime($_SESSION['Booking']['Head']['Dropoff']['Date'].' '.$_SESSION['Booking']['Head']['Dropoff']['Time']));
		
		if ($pickup > $dropoff) {
			$_SESSION['Form']['Error'] = 'Your booking duration must be longer than 1 hour';
			Header('Location: '.fetchinline($gpages).'index.php');
			exit;
		}
		
		//Advance a Step	
		$_SESSION['Booking']['Step'] = 1;
		Header('Location: '.fetchinline($bpages).'select_vehicle.php');
			
	}
	// #####################################################################################################################################################
	
	//Select Vehicle -> Optional Extras
	//@Author: Alex McCormick
	//@Edit by: Unyime Ekerette
	//@Edit by: Josh Chan
	else if (isset($_POST['booking2'])) {
		
		if($_SESSION['Booking']['Step'] < 1) {
			$_SESSION['Booking']['Step'] = 0;
			Header('Location: '.fetchinline($bpages).'page_expired.php');
			exit;
		}
		
		//Check For Reservation Timeout
		reservation_timeout();
		
		//Grab Post Data Variables	
		$_SESSION['Booking']['Vehicle']['Cost'] = $_POST['HireCost'];
		$_SESSION['Booking']['AssetGroup']['ID'] = $_POST['VehicleID'];
		
		//Variables to send to Database
		$AssetGroupNo = $_SESSION['Booking']['AssetGroup']['ID'];
		$Status = 4;
		$UserID = $_SESSION['User']['ID'];
		$CreationDate = date("Y-m-d H:i:s");
		$ProposedPickUpDate = date("Y-m-d H:i:s", strtotime($_SESSION['Booking']['Head']['Pickup']['Date'].' '.$_SESSION['Booking']['Head']['Pickup']['Time']));
		$ProposedReturnDate = date("Y-m-d H:i:s", strtotime($_SESSION['Booking']['Head']['Dropoff']['Date'].' '.$_SESSION['Booking']['Head']['Dropoff']['Time']));							
		$PickupLocation = $_SESSION['Booking']['Head']['Pickup']['Location'];
		$ProposedReturnLocation = $_SESSION['Booking']['Head']['Dropoff']['Location'];
		$VehicleCost = $_SESSION['Booking']['Vehicle']['Cost'];
			
		//Selct Query to check at least one vehicle of the selected asset group is still available and Select First Instance @Author: Josh Chan
		$stmt = $conn->prepare("SET @pickup=:pickup,@dropoff=:dropoff,@groupno=:groupno,@location=:location,@timeout=:timeout,@userid=:userid;");
		$stmt->bindParam(':pickup',$ProposedPickUpDate);
		$stmt->bindParam(':dropoff',$ProposedReturnDate);
		$stmt->bindParam(':groupno',$AssetGroupNo);
		$stmt->bindParam(':location',$PickupLocation);
		$stmt->bindParam(':timeout',$reservationTimeout);
		$stmt->bindParam(':userid',$userID);
		$stmt->execute();
		
		$stmt = $conn->prepare("
			SELECT a.AssetNo,b.BookingHeadID,
				(SELECT COUNT(AssetNo)
					FROM Booking_Header
					WHERE (AssetNo=a.AssetNo
							AND Status=1)
						OR (AssetNo=a.AssetNo
							AND Status=4
							AND UserID NOT IN(@userid)
							AND TIME_TO_SEC(TIMEDIFF(now(),CreationDate))<@timeout)) AS Count
				FROM Asset a
				LEFT JOIN Booking_Header b
				ON (a.AssetNo=b.AssetNo
						AND b.Status=1
						AND (@dropoff <= b.ProposedPickUpDate OR b.ProposedReturnDate <= @pickup))
					OR (a.AssetNo=b.AssetNo
						AND b.Status=4
						AND (@dropoff <= b.ProposedPickUpDate OR b.ProposedReturnDate <= @pickup)
						AND TIME_TO_SEC(TIMEDIFF(now(),b.CreationDate))<@timeout)
				WHERE a.Active=1
					AND a.AssetGroupNo=@groupno
					AND a.Location=@location
				GROUP BY a.AssetNo
				HAVING (COUNT(a.AssetNo)=Count AND b.BookingHeadID IS NOT NULL)
					OR Count=0;
		");
		$stmt->execute();
					
		// Fetch Vehicle ID
		// @Added by JC
		$row = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch first row only
		if ($row)
		{
			$_SESSION['Booking']['Vehicle']['ID'] = $row['AssetNo'];
		}
		else
		{
			// Reject booking if vehicle is no longer available to book
			$_SESSION['Form']['Error'] = 'The vehicle you have selected is no longer available to book';
			Header('Location: '.fetchinline($bpages).'select_vehicle.php');
			exit;
			
		}
		
		// Populate booking head session with booking id of the user's reservation from database
		$stmt = $conn->prepare("SELECT BookingHeadID FROM Booking_Header WHERE UserID=:id AND Status=4");
		$stmt->bindParam(':id',$_SESSION['User']['ID']);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		//Update or New Booking Header Record
		if ($row) {
			
			//Store the user's booking head id
			$_SESSION['Booking']['Head']['ID'] = $row['BookingHeadID'];	
			//Update Record with this value as head id
			$stmt = $conn->prepare("UPDATE Booking_Header SET AssetNo = :AssetNo, CreationDate = :CreationDate, ProposedPickUpDate = :ProposedPickUpDate, 
									PickupLocation = :PickupLocation, ProposedReturnDate = :ProposedReturnDate, 
									ProposedReturnLocation = :ProposedReturnLocation, VehicleCost = :VehicleCost WHERE BookingHeadID = :BookingHeadID");
			$stmt->execute(array(	'BookingHeadID' => $_SESSION['Booking']['Head']['ID'], 'AssetNo' => $_SESSION['Booking']['Vehicle']['ID'], 'CreationDate' => $CreationDate, 
									'ProposedPickUpDate' => $ProposedPickUpDate, 'ProposedReturnDate' => $ProposedReturnDate, 
									'ProposedReturnLocation' => $ProposedReturnLocation, 'PickupLocation' => $PickupLocation, 'VehicleCost' => $VehicleCost));
			
		}
		else {
				
			//Insert New record into booking header table
			$stmt = $conn->prepare("INSERT INTO Booking_Header(AssetNo, Status, UserID, CreationDate, ProposedPickUpDate, ProposedReturnDate, ProposedReturnLocation, VehicleCost, PickupLocation) 
									VALUES (:AssetNo, :Status, :UserID, :CreationDate, :ProposedPickUpDate, :ProposedReturnDate, :ProposedReturnLocation, :VehicleCost, 
									:PickupLocation)");
			$stmt->execute(array(	'AssetNo' => $_SESSION['Booking']['Vehicle']['ID'], 'Status' => $Status, 'UserID' => $UserID, 'CreationDate' => $CreationDate, 'ProposedPickUpDate' => $ProposedPickUpDate, 
									'ProposedReturnDate' => $ProposedReturnDate, 'ProposedReturnLocation' => $ProposedReturnLocation, 
									'PickupLocation' => $PickupLocation, 'VehicleCost' => $VehicleCost));
			
			//Store this booking head id
			$_SESSION['Booking']['Head']['ID'] = $conn->lastInsertId();
		}
			
		//Set Reservation Start Time
		$_SESSION['Reservation']['Start'] = time();
			
		//Advance a Step
		$_SESSION['Booking']['Step'] = 2;
		Header('Location: '.fetchinline($bpages).'optional_extras.php'); 
	
	}

	// #####################################################################################################################################################
	
	//Optional Extras -> Finalise Booking
	//@Author: Alex McCormick
	//@Edit by: Unyime Ekerette
	else if (isset($_POST['booking3'])) {
		
		if($_SESSION['Booking']['Step'] < 2) {
			$_SESSION['Booking']['Step'] = 0;
			Header('Location: '.fetchinline($bpages).'page_expired.php');
			exit;
		}
		
		//Check For Reservation Timeout
		reservation_timeout();

		//Check Optional Extras Availability
		//@Added by JC 29.04.2013
		$ProposedPickUpDate = date("Y-m-d H:i:s", strtotime($_SESSION['Booking']['Head']['Pickup']['Date'].' '.$_SESSION['Booking']['Head']['Pickup']['Time']));
		$ProposedReturnDate = date("Y-m-d H:i:s", strtotime($_SESSION['Booking']['Head']['Dropoff']['Date'].' '.$_SESSION['Booking']['Head']['Dropoff']['Time']));							
		$PickupLocation = $_SESSION['Booking']['Head']['Pickup']['Location'];
		((isset($_POST['GPS'])) ? $GPS = 1 : $GPS = 0);
		$ChildSeat = $_POST['ChildSeat'];
		$InfantSeat = $_POST['InfantSeat'];
		$BoosterSeat = $_POST['BoosterSeat'];
		((isset($_POST['SnowTires'])) ? $SnowTires = 1 : $SnowTires = 0);
		$userID = $_SESSION['User']['ID']; //@Added by AM 29.04.2013

		//Query to find all available assets of each type of optional extra within the specified time range at the specified location, returning the ids of the optional extras
		$stmt = $conn->prepare("SET @pickup=:pickup,@dropoff=:dropoff,@location=:location,@timeout=:timeout,@userid=:userid;");
		$stmt->bindParam(':pickup',$ProposedPickUpDate);
		$stmt->bindParam(':dropoff',$ProposedReturnDate);
		$stmt->bindParam(':location',$PickupLocation);
		$stmt->bindParam(':timeout',$reservationTimeout);
		$stmt->bindParam(':userid',$userID);
		$stmt->execute();

		$stmt = $conn->prepare("
			SELECT g.Definition,a.AssetNo,b.BookingHeadID,
				(SELECT COUNT(bd.AssetNo)
					FROM Booking_Header bh, Booking_Detail bd
					WHERE (bh.BookingHeadID=bd.BookingHeadID
							AND bd.AssetNo=a.AssetNo
							AND bh.Status=1)
						OR (bh.BookingHeadID=bd.BookingHeadID
							AND bd.AssetNo=a.AssetNo
							AND bh.Status=4
							AND bh.UserID NOT IN(@userid)	
							AND TIME_TO_SEC(TIMEDIFF(now(),bh.CreationDate))<@timeout)) AS Count
				FROM Asset a
				LEFT JOIN Booking_Detail d
				ON a.AssetNo=d.AssetNo
				LEFT JOIN Booking_Header b
				ON (b.BookingHeadID=d.BookingHeadID
						AND b.Status=1
						AND (@dropoff <= b.ProposedPickUpDate OR b.ProposedReturnDate <= @pickup))
					OR (b.BookingHeadID=d.BookingHeadID
						AND b.Status=4
						AND (@dropoff <= b.ProposedPickUpDate OR b.ProposedReturnDate <= @pickup)
						AND TIME_TO_SEC(TIMEDIFF(now(),b.CreationDate))<@timeout)
				LEFT JOIN Asset_Group g
				ON a.AssetGroupNo=g.AssetGroupNo
				WHERE a.Active=1
					AND a.AssetGroupNo IN(
						SELECT AssetGroupNo
							FROM Asset_Group
							WHERE Definition='GPS')
					AND a.Location=@location
				GROUP BY a.AssetNo
				HAVING (COUNT(a.AssetNo)=Count AND b.BookingHeadID IS NOT NULL)
					OR Count=0
				LIMIT :GPS
				UNION ALL
			SELECT g.Definition,a.AssetNo,b.BookingHeadID,
				(SELECT COUNT(bd.AssetNo)
					FROM Booking_Header bh, Booking_Detail bd
					WHERE (bh.BookingHeadID=bd.BookingHeadID
							AND bd.AssetNo=a.AssetNo
							AND bh.Status=1)
						OR (bh.BookingHeadID=bd.BookingHeadID
							AND bd.AssetNo=a.AssetNo
							AND bh.Status=4
							AND bh.UserID NOT IN(@userid)
							AND TIME_TO_SEC(TIMEDIFF(now(),bh.CreationDate))<@timeout)) AS Count
				FROM Asset a
				LEFT JOIN Booking_Detail d
				ON a.AssetNo=d.AssetNo
				LEFT JOIN Booking_Header b
				ON (b.BookingHeadID=d.BookingHeadID
						AND b.Status=1
						AND (@dropoff <= b.ProposedPickUpDate OR b.ProposedReturnDate <= @pickup))
					OR (b.BookingHeadID=d.BookingHeadID
						AND b.Status=4
						AND (@dropoff <= b.ProposedPickUpDate OR b.ProposedReturnDate <= @pickup)
						AND TIME_TO_SEC(TIMEDIFF(now(),b.CreationDate))<@timeout)
				LEFT JOIN Asset_Group g
				ON a.AssetGroupNo=g.AssetGroupNo
				WHERE a.Active=1
					AND a.AssetGroupNo IN(
						SELECT AssetGroupNo
							FROM Asset_Group
							WHERE Definition='Child Seat')
					AND a.Location=@location
				GROUP BY a.AssetNo
				HAVING (COUNT(a.AssetNo)=Count AND b.BookingHeadID IS NOT NULL)
					OR Count=0
				LIMIT :ChildSeat
				UNION ALL
			SELECT g.Definition,a.AssetNo,b.BookingHeadID,
				(SELECT COUNT(bd.AssetNo)
					FROM Booking_Header bh, Booking_Detail bd
					WHERE (bh.BookingHeadID=bd.BookingHeadID
							AND bd.AssetNo=a.AssetNo
							AND bh.Status=1)
						OR (bh.BookingHeadID=bd.BookingHeadID
							AND bd.AssetNo=a.AssetNo
							AND bh.Status=4
							AND bh.UserID NOT IN(@userid)
							AND TIME_TO_SEC(TIMEDIFF(now(),bh.CreationDate))<@timeout)) AS Count
				FROM Asset a
				LEFT JOIN Booking_Detail d
				ON a.AssetNo=d.AssetNo
				LEFT JOIN Booking_Header b
				ON (b.BookingHeadID=d.BookingHeadID
						AND b.Status=1
						AND (@dropoff <= b.ProposedPickUpDate OR b.ProposedReturnDate <= @pickup))
					OR (b.BookingHeadID=d.BookingHeadID
						AND b.Status=4
						AND (@dropoff <= b.ProposedPickUpDate OR b.ProposedReturnDate <= @pickup)
						AND TIME_TO_SEC(TIMEDIFF(now(),b.CreationDate))<@timeout)
				LEFT JOIN Asset_Group g
				ON a.AssetGroupNo=g.AssetGroupNo
				WHERE a.Active=1
					AND a.AssetGroupNo IN(
						SELECT AssetGroupNo
							FROM Asset_Group
							WHERE Definition='Infant Seat')
					AND a.Location=@location
				GROUP BY a.AssetNo
				HAVING (COUNT(a.AssetNo)=Count AND b.BookingHeadID IS NOT NULL)
					OR Count=0
				LIMIT :InfantSeat
				UNION ALL
			SELECT g.Definition,a.AssetNo,b.BookingHeadID,
				(SELECT COUNT(bd.AssetNo)
					FROM Booking_Header bh, Booking_Detail bd
					WHERE (bh.BookingHeadID=bd.BookingHeadID
							AND bd.AssetNo=a.AssetNo
							AND bh.Status=1)
						OR (bh.BookingHeadID=bd.BookingHeadID
							AND bd.AssetNo=a.AssetNo
							AND bh.Status=4
							AND bh.UserID NOT IN(@userid)
							AND TIME_TO_SEC(TIMEDIFF(now(),bh.CreationDate))<@timeout)) AS Count
				FROM Asset a
				LEFT JOIN Booking_Detail d
				ON a.AssetNo=d.AssetNo
				LEFT JOIN Booking_Header b
				ON (b.BookingHeadID=d.BookingHeadID
						AND b.Status=1
						AND (@dropoff <= b.ProposedPickUpDate OR b.ProposedReturnDate <= @pickup))
					OR (b.BookingHeadID=d.BookingHeadID
						AND b.Status=4
						AND (@dropoff <= b.ProposedPickUpDate OR b.ProposedReturnDate <= @pickup)
						AND TIME_TO_SEC(TIMEDIFF(now(),b.CreationDate))<@timeout)
				LEFT JOIN Asset_Group g
				ON a.AssetGroupNo=g.AssetGroupNo
				WHERE a.Active=1
					AND a.AssetGroupNo IN(
						SELECT AssetGroupNo
							FROM Asset_Group
							WHERE Definition='Booster Seat')
					AND a.Location=@location
				GROUP BY a.AssetNo
				HAVING (COUNT(a.AssetNo)=Count AND b.BookingHeadID IS NOT NULL)
					OR Count=0
				LIMIT :BoosterSeat
				UNION ALL
			(SELECT g.Definition,a.AssetNo,b.BookingHeadID,
				(SELECT COUNT(bd.AssetNo)
					FROM Booking_Header bh, Booking_Detail bd
					WHERE (bh.BookingHeadID=bd.BookingHeadID
							AND bd.AssetNo=a.AssetNo
							AND bh.Status=1)
						OR (bh.BookingHeadID=bd.BookingHeadID
							AND bd.AssetNo=a.AssetNo
							AND bh.Status=4
							AND bh.UserID NOT IN(@userid)
							AND TIME_TO_SEC(TIMEDIFF(now(),bh.CreationDate))<@timeout)) AS Count
				FROM Asset a
				LEFT JOIN Booking_Detail d
				ON a.AssetNo=d.AssetNo
				LEFT JOIN Booking_Header b
				ON (b.BookingHeadID=d.BookingHeadID
						AND b.Status=1
						AND (@dropoff <= b.ProposedPickUpDate OR b.ProposedReturnDate <= @pickup))
					OR (b.BookingHeadID=d.BookingHeadID
						AND b.Status=4
						AND b.UserID NOT IN(@userid)
						AND (@dropoff <= b.ProposedPickUpDate OR b.ProposedReturnDate <= @pickup)
						AND TIME_TO_SEC(TIMEDIFF(now(),b.CreationDate))<@timeout)
				LEFT JOIN Asset_Group g
				ON a.AssetGroupNo=g.AssetGroupNo
				WHERE a.Active=1
					AND a.AssetGroupNo IN(
						SELECT AssetGroupNo
							FROM Asset_Group
							WHERE Definition='Snow Tires')
					AND a.Location=@location
				GROUP BY a.AssetNo
				HAVING (COUNT(a.AssetNo)=Count AND b.BookingHeadID IS NOT NULL)
					OR Count=0
				LIMIT :SnowTires);
		");
		$stmt->bindParam(':GPS',$GPS);
		$stmt->bindParam(':ChildSeat',$ChildSeat);
		$stmt->bindParam(':InfantSeat',$InfantSeat);
		$stmt->bindParam(':BoosterSeat',$BoosterSeat);
		$stmt->bindParam(':SnowTires',$SnowTires);
		$stmt->execute();

		$count1 = 0;
		$count2 = 0;
		$count3 = 0;
		$count4 = 0;
		$count5 = 0;
		$data = array();
		foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
		{
			// Assign costs to assets in a new array and count the occurances of each optional extra type
			if ($row['Definition'] == 'GPS')
			{
				$count1++;
				$count = count($data);
				$data[$count]['asset'] = $row['AssetNo'];
				$data[$count]['cost'] = number_format($_POST['GPSCost'],2); 
			}
			else if ($row['Definition'] == 'Child Seat')
			{
				$count2++;
				$count = count($data);
				$data[$count]['asset'] = $row['AssetNo'];
				$data[$count]['cost'] = number_format($_POST['ChildSeatCost'],2);
			}
			else if ($row['Definition'] == 'Infant Seat')
			{
				$count3++;
				$count = count($data);
				$data[$count]['asset'] = $row['AssetNo'];
				$data[$count]['cost'] = number_format($_POST['InfantSeatCost'],2); 
			}
			else if ($row['Definition'] == 'Booster Seat')
			{
				$count4++;
				$count = count($data);
				$data[$count]['asset'] = $row['AssetNo'];
				$data[$count]['cost'] = number_format($_POST['BoosterSeatCost'],2);
			}
			else if ($row['Definition'] == 'Snow Tires')
			{
				$count5++;
				$count = count($data);
				$data[$count]['asset'] = $row['AssetNo'];
				$data[$count]['cost'] = number_format($_POST['SnowTiresCost'],2);
			}
		}

		// Reject booking if any of the optional extras are not available to book
		if ($count1 < $GPS)
		{
			$_SESSION['Form']['Error'] = 'A GPS is not available to reserve at the time of booking';
			Header('Location: '.fetchinline($bpages).'optional_extras.php');
			exit;
		}
		else if ($count2 < $ChildSeat)
		{
			if ($count2 == 0)
			{
				$_SESSION['Form']['Error'] = 'There are no Child Seats available to reserve at the time of booking';
			}
			else if ($count2 == 1)
			{
				$_SESSION['Form']['Error'] = 'We only have '.$count2.' Child Seat available to reserve at the time of booking';
			}
			else
			{
				$_SESSION['Form']['Error'] = 'We only have '.$count2.' Child Seats available to reserve at the time of booking';
			}
			Header('Location: '.fetchinline($bpages).'optional_extras.php');
			exit;
		}
		else if ($count3 < $InfantSeat)
		{
			if ($count3 == 0)
			{
				$_SESSION['Form']['Error'] = 'There are no Infant Seats available to reserve at the time of booking';
			}
			else if ($count3 == 1)
			{
				$_SESSION['Form']['Error'] = 'We only have '.$count3.' Infant Seat available to reserve at the time of booking';
			}
			else
			{
				$_SESSION['Form']['Error'] = 'We only have '.$count3.' Infant Seats available to reserve at the time of booking';
			}
			Header('Location: '.fetchinline($bpages).'optional_extras.php');
			exit;
		}
		else if ($count4 < $BoosterSeat)
		{
			if ($count4 == 0)
			{
				$_SESSION['Form']['Error'] = 'There are no Booster Seats available to reserve at the time of booking';
			}
			else if ($count4 == 1)
			{
				$_SESSION['Form']['Error'] = 'We only have '.$count4.' Booster Seat available to reserve at the time of booking';
			}
			else
			{
				$_SESSION['Form']['Error'] = 'We only have '.$count4.' Booster Seats available to reserve at the time of booking';
			}
			Header('Location: '.fetchinline($bpages).'optional_extras.php');
			exit;
		}
		else if ($count5 < $SnowTires)
		{
			$_SESSION['Form']['Error'] = 'Snow Tires are not available to reserve at the time of booking';
			Header('Location: '.fetchinline($bpages).'optional_extras.php');
			exit;
		}
		else {

			//Success
			$_SESSION['Booking']['Detail']['GPS']['Qty'] = $count1;
			$_SESSION['Booking']['Detail']['ChildSeat']['Qty'] = $count2;
			$_SESSION['Booking']['Detail']['InfantSeat']['Qty'] = $count3;
			$_SESSION['Booking']['Detail']['BoosterSeat']['Qty'] = $count4;
			$_SESSION['Booking']['Detail']['SnowTires']['Qty'] = $count5;
			$_SESSION['Booking']['Detail']['GPS']['Cost'] = number_format($_POST['GPSCost'],2);
			$_SESSION['Booking']['Detail']['ChildSeat']['Cost'] = number_format($_POST['ChildSeatCost'],2);
			$_SESSION['Booking']['Detail']['InfantSeat']['Cost'] = number_format($_POST['InfantSeatCost'],2);
			$_SESSION['Booking']['Detail']['BoosterSeat']['Cost'] = number_format($_POST['BoosterSeatCost'],2);
			$_SESSION['Booking']['Detail']['SnowTires']['Cost'] = number_format($_POST['SnowTiresCost'],2); 
			
			
			if (sizeof($_SESSION['Booking']['Extra']) > sizeof($data)) {
					
				for ($i = sizeof($data); $i < sizeof($_SESSION['Booking']['Extra'])+1; $i++)
				{
				
					$stmt = $conn->prepare("DELETE FROM Booking_Detail WHERE BookingDetailID = :bookingdetail;");
					$stmt->execute(array('bookingdetail' => $_SESSION['Booking']['Extra'][$i]['ID']));
					
					unset($_SESSION['Booking']['Extra'][$i]);
					
				}
				
			}
				
			for ($i = 0; $i < sizeof($_SESSION['Booking']['Extra']); $i++) {
				
				$data[$i]['ID'] = $_SESSION['Booking']['Extra'][$i]['ID'];
			}

		}


		//Insert or update booking detail records
		for ($i = 0; $i < sizeof($data); $i++)
		{
			if (!isset($_SESSION['Booking']['Extra'][$i]['ID'])) {
				$stmt = $conn->prepare("INSERT INTO Booking_Detail VALUES (null,:hid,:cost,:asset);");
				$stmt->execute(array('hid' => $_SESSION['Booking']['Head']['ID'], 'cost' => $data[$i]['cost'], 'asset' => $data[$i]['asset']));
				$data[$i]['ID'] = $conn->lastInsertId();
			}
			else {
				$stmt = $conn->prepare("UPDATE Booking_Detail SET BookingHeadID = :hid, OptionalExtraCost = :cost, AssetNo = :asset WHERE BookingDetailID = :id");
				$stmt->execute(array('id'=> $data[$i]['ID'],'hid' => $_SESSION['Booking']['Head']['ID'], 'cost' => $data[$i]['cost'], 'asset' => $data[$i]['asset']));
			}

			$_SESSION['Booking']['Extra'][$i]['ID'] = $data[$i]['ID']; 
		}
		
		
		//Calculate Total Price for Vehicle Hire
		$pickup = date("Y-m-d H:i:s", strtotime($_SESSION['Booking']['Head']['Pickup']['Date'].' '.$_SESSION['Booking']['Head']['Pickup']['Time']));
		$dropoff = date("Y-m-d H:i:s", strtotime($_SESSION['Booking']['Head']['Dropoff']['Date'].' '.$_SESSION['Booking']['Head']['Dropoff']['Time']));
		
									
		// Calculate the total cost of vehicle
		$diffsecs = strtotime($dropoff) - strtotime($pickup);
		$diff = ceil($diffsecs / 60 / 60 / 24);
		if ($diffsecs <= 60 * 60 * 12)
		{
			$diff = $diff / 2;
			$BookingVehicleCost = ceil($diff*$_SESSION['Booking']['Vehicle']['Cost']);
		}
		else
		{
			$BookingVehicleCost = $diff*$_SESSION['Booking']['Vehicle']['Cost'];
		}
		
		//Calculate Extras
		$diff = strtotime($dropoff) - strtotime($pickup);
		$diff = ceil($diff / 60 / 60 / 24);
		
		
		$_SESSION['Booking']['Vehicle']['TotalCost'] = number_format($BookingVehicleCost, 2, '.', '');
		$_SESSION['Booking']['Detail']['TotalCost'] = 	number_format($diff*$_SESSION['Booking']['Detail']['GPS']['Cost']*$_SESSION['Booking']['Detail']['GPS']['Qty'], 2, '.', '') +
														number_format($diff*$_SESSION['Booking']['Detail']['ChildSeat']['Cost']*$_SESSION['Booking']['Detail']['ChildSeat']['Qty'], 2, '.', '') +
														number_format($diff*$_SESSION['Booking']['Detail']['InfantSeat']['Cost']*$_SESSION['Booking']['Detail']['InfantSeat']['Qty'], 2, '.', '') +
														number_format($diff*$_SESSION['Booking']['Detail']['BoosterSeat']['Cost']*$_SESSION['Booking']['Detail']['BoosterSeat']['Qty'], 2, '.', '') +
														number_format($diff*$_SESSION['Booking']['Detail']['SnowTires']['Cost']*$_SESSION['Booking']['Detail']['SnowTires']['Qty'], 2, '.', '');
		
		$_SESSION['TotalPrice'] = 	($_SESSION['Booking']['Vehicle']['TotalCost'] +  $_SESSION['Booking']['Detail']['TotalCost'])*100;
		
		//Advance a Step
		$_SESSION['Booking']['Step'] = 3;
		Header('Location: '.fetchinline($bpages).'finalise_booking.php'); 
			
	}

	// #####################################################################################################################################################
	
	//Finalise Booking -> Payment
	//@Author: Alex McCormick
	else if (isset($_POST['booking4'])) {
		
		if($_SESSION['Booking']['Step'] < 3) {
			$_SESSION['Booking']['Step'] = 0;
			Header('Location: '.fetchinline($bpages).'page_expired.php');
			exit;
		}
		
		//Check For Reservation Timeout
		reservation_timeout();
			
		//Payment API
		//@Author: Alex McCormicK

		//Invoice
		$_SESSION['Invoice']['Number'] = "INVOICE";
		$_SESSION['Invoice']['Description'] = "Standard Invoice";

		//Access User Database Record
		$id = $_SESSION['User']['ID']; 
		$stmt = $conn->prepare("SELECT * FROM User WHERE UserID = :id");
		$stmt->execute(array('id' => $id));
		$rslt = $stmt->fetchAll(PDO::FETCH_ASSOC);	

		//Include RapidAPI Library
		require(realpath(dirname(__FILE__)."/../api/Rapid3.0.php"));

		//Create RapidAPI Service
		$service = new RapidAPI();

		//Redirect to Self Url after Payment
		$self_url = 'http';
		if (!empty($_SERVER['HTTPS'])) {$self_url .= "s";}
		$self_url .= "://" . $_SERVER["SERVER_NAME"];
		if ($_SERVER["SERVER_PORT"] != "80") {
    		$self_url .= ":".$_SERVER["SERVER_PORT"];
		}
		$self_url .= $_SERVER["REQUEST_URI"];
		$redirect_url = $self_url;	
    
    	//Create AccessCode Request Object
    	$request = new CreateAccessCodeRequest();

		//Fetch User Details from Database
		foreach ($rslt as $row) {

	    	//Populate values for Customer Object
    		//Note: TokenCustomerID is Required Field When Update an exsiting TokenCustomer
    		if(isset($row['APIToken']))
    		$request->Customer->TokenCustomerID = $row['APIToken'];
				
			$_SESSION['User']['APIToken'] = $row['APIToken'];
		   	$request->Customer->Reference = $row['UserID'];
    		//Note: Title is Required Field When Create/Update a TokenCustomer
    		$request->Customer->Title = $row['Title'];
    		//Note: FirstName is Required Field When Create/Update a TokenCustomer
    		$request->Customer->FirstName = $row['FirstName'];
    		//Note: LastName is Required Field When Create/Update a TokenCustomer
    		$request->Customer->LastName = $row['LastName'];
    		$request->Customer->CompanyName = '';
		   	$request->Customer->JobDescription = '';
    		$request->Customer->Street1 = $row['HouseNo'] . $row['Add1'];
    		$request->Customer->City = $row['Add2'];
    		$request->Customer->State = $row['Add3'];
    		$request->Customer->PostalCode = $row['Postcode'];
    		//Note: Country is Required Field When Create/Update a TokenCustomer
    		$request->Customer->Country = "UK";
    		$request->Customer->Email = $row['Email'];
    		$request->Customer->Phone = $row['Telephone'];
    		$request->Customer->Mobile = $row['Mobile'];
    		$request->Customer->Comments = '';
    		$request->Customer->Fax = '';
    		$request->Customer->Url = '';
    
		   	//Populate values for ShippingAddress Object. 
		   	//This values can be taken from a Form POST as well. Now is just some dummy data.
		   	$request->ShippingAddress->FirstName = $row['FirstName'];
    		$request->ShippingAddress->LastName = $row['LastName'];
    		$request->ShippingAddress->Street1 = $row['HouseNo'] . $row['Add1'];
    		$request->ShippingAddress->Street2 = '';
    		$request->ShippingAddress->City = $row['Add2'];
    		$request->ShippingAddress->State = $row['Add3'];;
    		$request->ShippingAddress->Country = "UK";
    		$request->ShippingAddress->PostalCode = $row['Postcode'];
    		$request->ShippingAddress->Email = $row['Email'];
    		$request->ShippingAddress->Phone = isset($row['Telephone']) ? $row['Telephone'] : $row['Mobile'];
    		//ShippingMethod, e.g. "LowCost", "International", "Military". Check the spec for available values.
    		$request->ShippingAddress->ShippingMethod = "LowCost";
		
			//Populate values for LineItems
    		$item1 = new LineItem();   
    		$item1->SKU = "SKU1";
    		$item1->Description = "";
    		$item2 = new LineItem();
    		$item2->SKU = "SKU2";
    		$item2->Description = "";
    		$request->Items->LineItem[0] = $item1;
    		$request->Items->LineItem[1] = $item2;
    
		   	//Populate values for Options
    		$opt1 = new Option();
    		$opt1->Value = "";
    		$opt2 = new Option();
    		$opt2->Value = "";
    		$opt3 = new Option();
    		$opt3->Value = "";
    		$request->Options->Option[0]= $opt1;
    		$request->Options->Option[1]= $opt2;
    		$request->Options->Option[2]= $opt3;
    
		   	//Populate values for Payment Object
		   	//Note: TotalAmount is a Required Field When Process a Payment, TotalAmount should set to "0" or leave EMPTY when Create/Update A TokenCustomer
		   	$request->Payment->TotalAmount = $_SESSION['TotalPrice'];
		   	$request->Payment->InvoiceNumber = $_SESSION['Invoice']['Number'];
		   	$request->Payment->InvoiceDescription = $_SESSION['Invoice']['Description'];
		   	$request->Payment->InvoiceReference = $_SESSION['Invoice']['Number'];
		   	$request->Payment->CurrencyCode = "GBP";
    
	    	//Url to the page for getting the result with an AccessCode
	    	//Note: RedirectUrl is a Required Field For all cases
	    	$request->RedirectUrl = $redirect_url;
  
  	    	//Method for this request. e.g. ProcessPayment, Create TokenCustomer, Update TokenCustomer & TokenPayment
   			$request->Method = "TokenPayment";

   			//Call RapidAPI
   			$result = $service->CreateAccessCode($request);

   			//Save result into Session. payment.php and results.php will retrieve this result from Session
   			$_SESSION['TotalAmount'] = (int) $_SESSION['TotalPrice'];
   			$_SESSION['InvoiceReference'] = $_SESSION['Invoice']['Number'];
   			$_SESSION['Response'] = $result;
	
		}
        
	    //Check if any error returns
   		if(isset($result->Errors))
   		{	
       		//Get Error Messages from Error Code. Error Code Mappings are in the Config.php file
       		$ErrorArray = explode(",", $result->Errors);
        
       		$lblError = "";
       
       		foreach ( $ErrorArray as $error )
       		{
           		if(isset($service->APIConfig[$error]))
           		$lblError .= $error." ".$service->APIConfig[$error]."<br>";
           		else
               	$lblError .= $error;
 	      		}
    		}
    	else
    	{
    			
    		$_SESSION['Booking']['Step'] = 4;
				
        	//Advance a Step				
        	Header('Location: '.fetchinline($bpages).'payment.php');
        	exit();
    	}
		
	}
	// #####################################################################################################################################################
	
	//Payment -> Booking Complete || Payment Declined
	//@Author: Alex McCormick
	
	//RESUBMISSION OF FORM JAVASCRIPT
	else if (isset($_POST['booking5'])) {
		
		if($_SESSION['Booking']['Step'] < 4) {
			$_SESSION['Booking']['Step'] = 0;
			Header('Location: '.fetchinline($bpages).'page_expired.php');
			exit;
		}
		
		//Check For Reservation Timeout
		reservation_timeout();
		
		$ewayFormAction = $_POST['EWAY_FORMACTION'];
		$ewayAccessCode = $_POST['EWAY_ACCESSCODE'];
		$cardName = $_POST['EWAY_CARDNAME'];
		$cardNumber = $_POST['EWAY_CARDNUMBER'];
		$cardStartMonth = $_POST['EWAY_CARDSTARTMONTH'];
		$cardStartYear = $_POST['EWAY_CARDSTARTYEAR'];
		$cardEndMonth = $_POST['EWAY_CARDEXPIRYMONTH'];
		$cardEndYear = $_POST['EWAY_CARDEXPIRYYEAR'];
		$cardIssueNum = $_POST['EWAY_CARDISSUENUMBER'];
		$cardCVN = $_POST['EWAY_CARDCVN'];
		
?>
<!DOCTYPE html>
	<?php include "ie_js_fix.php"; ?>
	<head>
		<?php include "head.php"; ?>
		<script>
			function makepayment() {
				var frm = document.getElementById("form1");
				frm.submit();
			}
			setTimeout(function() { makepayment(); }, 2500);
		</script>
		<?php 
			//Set Permission for API Booking Step
			$_SESSION['ewayAPI'] = true; 
		?>
	</head>
	<body>
		<!-- Header -->
		<?php include "header.php"; ?>
		<!-- End Header -->
			
		<!-- Main Content --> 
		<div class="row" style="margin-top:3em;">
			<div class="large-12 columns">
				<img style="width:100%;" src="<?php echo fetchdir($img); ?>tumblr_m7epzzfspc1qkmqj8o1_500.gif" alt="Payment Authenticating" />
			</div>
		</div>
		
		<div class="row" style="margin-top:1em;">
			<div class="large-12 columns">
				<h2>Authenticating...</h2>
			</div>
		</div>				
		
		<!-- Hidden Payment Form Submit to API --> 
		<form id="form1" name="makeapayment" action="<?php echo $ewayFormAction ?>" method='post'>
	    	<input type='hidden' name='EWAY_ACCESSCODE' value="<?php echo $ewayAccessCode ?>" />
        	<input type='hidden' name='EWAY_CARDNAME' id='EWAY_CARDNAME' value="<?php echo $cardName; ?>" />
            <input type='hidden' name='EWAY_CARDNUMBER' id='EWAY_CARDNUMBER' value="<?php echo $cardNumber; ?>" />
            <select style="display:none;" ID="EWAY_CARDSTARTMONTH" name="EWAY_CARDSTARTMONTH">
            	<?php
                	echo  "<option></option>";              
                    for($i = 1; $i <= 12; $i++) {
                    	$s = sprintf('%02d', $i);
                    	echo "<option value='$s'";
                   		if ( $cardStartMonth == $i ) {
                       		echo " selected='selected'";
                    	}
                        echo ">$s</option>\n";
           			}
             	?>
      		</select>
            <select style="display:none;" type='hidden' ID="EWAY_CARDSTARTYEAR" name="EWAY_CARDSTARTYEAR"><
            	<?php
            		$i = date("y");
               		$j = $i-11;
                    echo  "<option></option>";
                    for ($i; $i >= $j; $i--) {
                    	$year = sprintf('%02d', $i);
                        echo "<option value='$year'";
                        if ( $cardStartYear == $year ) {
                        	echo " selected='selected'";
                        }
                 		echo ">$year</option>\n";
                    }
           		?>
           	</select>
            <select style="display:none;" type='hidden' ID="EWAY_CARDEXPIRYMONTH" name="EWAY_CARDEXPIRYMONTH">
				<?php
    				for($i = 1; $i <= 12; $i++) {
             			$s = sprintf('%02d', $i);
                		echo "<option value='$s'";
                 		if ( $cardEndMonth == $i ) {
                       		echo " selected='selected'";
                    	}
                        echo ">$s</option>\n";
                   	}
             	?>
            </select>
            <select style="display:none;" type='hidden' ID="EWAY_CARDEXPIRYYEAR" name="EWAY_CARDEXPIRYYEAR">
            	<?php
               		$i = date("y");
                   	$j = $i+11;
             		for ($i; $i <= $j; $i++) {
                 		echo "<option value='$i'";
                        if ( $cardEndYear == $i ) {
                        	echo " selected='selected'";
                      	}
                       	echo ">$i</option>\n";
                	}
            	?>
            </select>
			<input type='hidden' name='EWAY_CARDISSUENUMBER' id='EWAY_CARDISSUENUMBER' value="<?php echo $cardIssueNum ?>" />
            <input type='hidden' name='EWAY_CARDCVN' id='EWAY_CARDCVN' value="<?php echo $cardCVN ?>" />
    	</form>
    	<!-- End Hidden Payment Form Submit to API -->
    	
    	<!-- End Main Content -->
 
 		<!-- Footer -->
 		<?php include "footer.php"; ?>	
 		<!-- End Footer -->	
	</body>
</html>
<?php

	}
	
	//PAYMENT RESPONSE
	if (isset($_GET['AccessCode']) && $_SESSION['ewayAPI'] == true) {
		
			//Reset Permission for API Booking Step
			unset($_SESSION['ewayAPI']);
		
			//Payment API
			//@Author: Alex McCormick
			
			//Include RapidAPI Library
			require(realpath(dirname(__FILE__)."/../api/Rapid3.0.php"));

			//Create RapidAPI Service
			$service = new RapidAPI();

			//Build request for getting the result with the access code.
			$request = new GetAccessCodeResultRequest();

			$request->AccessCode = $_GET['AccessCode'];

			/*if(!isset($request->AccessCode)) {
				Header('Location: payment.php');	
			}*/			

			//Call RapidAPI to get the result
			$result = $service->GetAccessCodeResult($request);

			//Check if any error returns
			if(isset($result->Errors))
			{
    			//Get Error Messages from Error Code. Error Code Mappings are in the Config.php file
    			$ErrorArray = explode(",", $result->Errors);

    			var_dump($ErrorArray);

    			$lblError = "";

    			foreach ( $ErrorArray as $error )
    			{
        			$lblError .= $service->APIConfig[$error]."<br>";
    			}
			}

			//TOKEN CUSTOMER ID
			$_SESSION['User']['APIToken'] = $result->TokenCustomerID;
	
			//Update APIToken in User Database Record
			if(isset($_SESSION['User']['APIToken'])) {		
				$id = $_SESSION['User']['ID'];	
				$apiToken = $_SESSION['User']['APIToken'];  	
				$stmt = $conn->prepare("UPDATE User SET APIToken = :apiToken WHERE UserID = :id");	
				$stmt->execute(array('apiToken' => $apiToken, 'id' => $id));
			}
			
			//UPDATE TRANSACTION ID AND COMPLETE BOOKING
			if (isset($result->TransactionID)) {
	
				$booking_id = $_SESSION['Booking']['Head']['ID'];
				$transactionID = $result->TransactionID;
				$stmt = $conn->prepare("UPDATE Booking_Header SET Status = 1, CreationDate = now(), PaymentID = :transaction_id WHERE BookingHeadID = :booking_id");	
				$stmt->execute(array('transaction_id' => $transactionID, 'booking_id' => $booking_id));
			}
			
			//SEND A CONFIRMATION EMAIL TO CLIENT
			//Fetch Vehicle from Database
			$id = $_SESSION['Booking']['Vehicle']['ID'];
			$stmt = $conn->prepare("SELECT * FROM Asset, Asset_Group, Vehicle WHERE Asset.AssetGroupNo = Asset_Group.AssetGroupNo AND Vehicle.AssetNo = Asset.AssetNo AND Asset.AssetNo = :id");
			$stmt->execute(array('id' => $id));
				
			foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row ) { 
				$manufacturer = $row['Manufacturer'];
				$model = $row['Definition'];
				$litre = $row['EngineCapacity'];
				$category = $row['Category'];	
				$doors = $row['Doors'];
				$seats = $row['Seats'];
			}
			
			//Fetch Pickup Location
			$id = $_SESSION['Booking']['Head']['Pickup']['Location'];
			$stmt = $conn->prepare("SELECT * FROM Location WHERE LocationID = :id");
			$stmt->execute(array('id' => $id));
									
			foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
			{
				$pickuplocation = $row['Add1'];
				$pickupdate = $_SESSION['Booking']['Head']['Pickup']['Date'];
				$pickuptime = $_SESSION['Booking']['Head']['Pickup']['Time'];
			}
			
			//Fetch Dropoff Location
			$id = $_SESSION['Booking']['Head']['Dropoff']['Location'];
			$stmt = $conn->prepare("SELECT * FROM Location WHERE LocationID = :id");
			$stmt->execute(array('id' => $id));
									
			foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
			{
				$dropofflocation = $row['Add1'];
				$dropoffdate = $_SESSION['Booking']['Head']['Dropoff']['Date'];
				$dropofftime = $_SESSION['Booking']['Head']['Dropoff']['Time'];
			}
			
			//Fetch Recepient
			$id = $_SESSION['User']['ID'];
			$stmt = $conn->prepare("SELECT * FROM User WHERE UserID = :id");
			$stmt->execute(array('id' => $id));
					
			foreach( $stmt->fetchAll(PDO::FETCH_ASSOC) as $row )
			{
				$emailaddress = $row['Email'];
			}
			
			$to = $emailaddress;
			$subject = 'Your booking has been made';
			$message =
'This is your Aperture Car Hire booking confirmation. Please keep this email for your records.

CAR
---------------------------------------------------
Manufacturer: '.$manufacturer.'
Model: '.$model.'
Litre: '.$litre.'
Category: '.$category.'
Doors: '.$doors.'
Seats: '.$seats.'
---------------------------------------------------

PICKUP
---------------------------------------------------
Location: '.$pickuplocation.'
Date: '.$pickupdate.'
Time: '.$pickuptime.'
---------------------------------------------------

DROPOFF
---------------------------------------------------
Location: '.$dropofflocation.'
Date: '.$dropoffdate.'
Time: '.$dropofftime.'
---------------------------------------------------';

			$header = 'From: '.$serverEmail;
			
			mail($to, $subject, $message, $header);
			
			//Advance a Step
			$_SESSION['Booking']['Step'] = 5;
			Header('Location: '.fetchinline($bpages).'booking_complete.php');
					
		}
	// #####################################################################################################################################################
	
?>