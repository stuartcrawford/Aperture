<?php

	include("global.php");
	dbconnect();
	
	//Location ID
	$LocationID = $_POST['LocationID'];

	if(isset($_POST['update_location'])) {
		
		$Add1 = $_POST['Add1'];
		$Add2 = $_POST['Add2'];
		$Add3 = $_POST['Add3'];
		$Postcode = $_POST['Postcode'];
  	
		
		$stmt = $conn->prepare("UPDATE Location SET Add1 = :Add1, Add2 = :Add2, Add3 = :Add3, Postcode = :Postcode WHERE LocationID = :LocationID");	
		$stmt->execute(array('LocationID' => $LocationID, 'Add1' => $Add1, 'Add2' => $Add2, 'Add3' => $Add3, 'Postcode' => $Postcode));
		
	}
	else if(isset($_POST['delete_location'])) {
		
		$stmt = $conn->prepare("UPDATE Location SET Active = 0 WHERE LocationID = :LocationID");	
		$stmt->execute(array('LocationID' => $LocationID));
	}
	else if(isset($_POST['enable_location'])) {
		
		$stmt = $conn->prepare("UPDATE Location SET Active = 1 WHERE LocationID = :LocationID");	
		$stmt->execute(array('LocationID' => $LocationID));
	}
	
	Header('Location: '.fetchinline($apages).'manage_locations.php');	
?>