<?php

	include("global.php");
	dbconnect();

	if(isset($_POST['add_location'])) {
		
		$Active = 1;
		$Add1 = $_POST['Add1'];
		$Add2 = $_POST['Add2'];
		$Add3 = $_POST['Add3'];
		$Postcode = $_POST['Postcode'];
		
		
		$stmt = $conn->prepare("INSERT INTO Location(Add1, Add2, Add3, Postcode, Active) VALUES (:Add1, :Add2, :Add3, :Postcode, :Active)");	
		$stmt->execute(array('Add1' => $Add1, 'Add2' => $Add2, 'Add3' => $Add3, 'Postcode' => $Postcode, 'Active' => $Active));
			
	}
	
	Header('Location: '.fetchinline($apages).'manage_locations.php');
?>