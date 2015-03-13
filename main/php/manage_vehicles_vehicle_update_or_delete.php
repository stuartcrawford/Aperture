<?php

	include("global.php");
	dbconnect();
	
	//Vehicle ID
	$vehicle = $_POST['Vehicle'];

	if(isset($_POST['update_vehicle'])) {
		
		$RegistrationNo = $_POST['RegistrationNo'];
		$MOTExpiry = $_POST['MOTExpiry'];
		$TaxExpiry = $_POST['TaxExpiry'];
		$Mileage = $_POST['Mileage'];
		$LocationID = $_POST['LocationID'];
  	
		
		$stmt = $conn->prepare("UPDATE Vehicle SET RegistrationNo = :RegistrationNo, MOTExpiry = :MOTExpiry, TaxExpiry = :TaxExpiry, Mileage = :Mileage WHERE AssetNo = :vehicle");	
		$stmt->execute(array('RegistrationNo' => $RegistrationNo, 'MOTExpiry' => $MOTExpiry, 'TaxExpiry' => $TaxExpiry, 'Mileage' => $Mileage, 'vehicle' => $vehicle));
		
		$stmt = $conn->prepare("UPDATE Asset SET Location = :LocationID WHERE AssetNo = :vehicle");	
		$stmt->execute(array('LocationID' => $LocationID, 'vehicle' => $vehicle));
		
	}
	else if(isset($_POST['delete_vehicle'])) {
		
		$stmt = $conn->prepare("UPDATE Asset SET Active = 0 WHERE AssetNo = :vehicle");	
		$stmt->execute(array('vehicle' => $vehicle));
		
		$stmt = $conn->prepare("UPDATE Vehicle SET Available = 0 WHERE AssetNo = :vehicle");	
		$stmt->execute(array('vehicle' => $vehicle));
		
	}
	else if(isset($_POST['enable_vehicle'])) {
		
		$stmt = $conn->prepare("UPDATE Asset SET Active = 1 WHERE AssetNo = :vehicle");	
		$stmt->execute(array('vehicle' => $vehicle));
		
		$stmt = $conn->prepare("UPDATE Vehicle SET Available = 1 WHERE AssetNo = :vehicle");	
		$stmt->execute(array('vehicle' => $vehicle));
		
	}	
	
	Header('Location: '.fetchinline($apages).'manage_vehicles.php');	
?>