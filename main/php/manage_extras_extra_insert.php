<?php

	include("global.php");
	dbconnect();

	if(isset($_POST['add_extra'])) {
		
		$Active = 1;
		$AssetGroupNo = $_POST['AssetGroup'];
		$Location = $_POST['Location'];
  	
		$stmt = $conn->prepare("INSERT INTO Asset (Active, AssetGroupNo, Location) VALUES (:Active, :AssetGroupNo, :Location)");
		$stmt->execute(array('Active' => $Active, 'AssetGroupNo' => $AssetGroupNo, 'Location' => $Location));
		
		$id = $conn->lastInsertId();
		
		$SerialNumber =  $_POST['SerialNo'];

		$stmt = $conn->prepare("INSERT INTO Optional_Extra VALUES (:AssetNo, :SerialNumber)");	
		$stmt->execute(array('AssetNo' => $id, 'SerialNumber' => $SerialNumber));
			
	}
	
	Header('Location: '.fetchinline($apages).'manage_optional_extras.php');
?>