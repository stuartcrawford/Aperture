<?php

	include("global.php");
	dbconnect();
	
	//Extras ID
	$id = $_POST['Extra'];

	if(isset($_POST['update_extra'])) {
		
		$SerialNumber = $_POST['SerialNumber'];
		$LocationID = $_POST['LocationID'];
  	
		
		$stmt = $conn->prepare("UPDATE Optional_Extra SET SerialNumber = :SerialNumber WHERE AssetNo = :id");	
		$stmt->execute(array('SerialNumber' => $SerialNumber, 'id' => $id));
		
		$stmt = $conn->prepare("UPDATE Asset SET Location = :LocationID WHERE AssetNo = :id");	
		$stmt->execute(array('LocationID' => $LocationID, 'id' => $id));
		
	}
	else if(isset($_POST['delete_extra'])) {
		
		$stmt = $conn->prepare("UPDATE Asset SET Active = 0 WHERE AssetNo = :id");	
		$stmt->execute(array('id' => $id));
		
	}
	else if(isset($_POST['enable_extra'])) {
		
		$stmt = $conn->prepare("UPDATE Asset SET Active = 1 WHERE AssetNo = :id");	
		$stmt->execute(array('id' => $id));
		
	}	
	
	Header('Location: '.fetchinline($apages).'manage_optional_extras.php');
?>