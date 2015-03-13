<?php

	include("global.php");
	dbconnect();

	if(isset($_POST['add_vehicle'])) {
		
		$Active = 1;
		$AssetGroupNo = $_POST['AssetGroup'];
		$Location = $_POST['Location'];
  	
		$stmt = $conn->prepare("INSERT INTO Asset (Active, AssetGroupNo, Location) VALUES (:Active, :AssetGroupNo, :Location)");
		$stmt->execute(array('Active' => $Active, 'AssetGroupNo' => $AssetGroupNo, 'Location' => $Location));
		
		$id = $conn->lastInsertId();
		$LastUpdated = date("Y-m-d H:i:s");
		$RegistrationNo = $_POST['RegistrationNo'];
		$MOTExpiry = $_POST['MOTExpiry'];
		$TaxExpiry = $_POST['TaxExpiry'];
		$Mileage = $_POST['Mileage'];
		$Available = 1;
		
		$stmt = $conn->prepare("INSERT INTO Vehicle VALUES (:AssetNo, :LastUpdated, :RegistrationNo, :MOTExpiry, :TaxExpiry, :Mileage, :Available)");	
		$stmt->execute(array('AssetNo' => $id, 'LastUpdated' => $LastUpdated, 'RegistrationNo' => $RegistrationNo, 'MOTExpiry' => $MOTExpiry, 'TaxExpiry' => $TaxExpiry, 'Mileage' => $Mileage, 'Available' => $Available));
			
	}
	
	Header('Location: '.fetchinline($apages).'manage_vehicles.php');
?>