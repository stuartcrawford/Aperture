<?php

	include("global.php");
	dbconnect();
	
	//Account ID 
	$account = $_POST['Account'];

	if(isset($_POST['update_accounts'])) {
		
		$Title = $_POST['Title'];
		$Email = $_POST['Email'];
		$DOB = $_POST['DOB'];
		$FirstName = $_POST['FirstName'];
		$LastName = $_POST['LastName'];
		$Add1 = $_POST['Add1'];
		$Add2 = $_POST['Add2'];
		$Add3 = $_POST['Add3'];
		$Postcode = $_POST['Postcode'];
		$Telephone = $_POST['Telephone'];
		$Mobile = $_POST['Mobile'];
		$LicenseNo = $_POST['LicenseNo'];
		$SQ1 = $_POST['SQ1'];
		$SQ2 = $_POST['SQ2'];
		$SQA1 = $_POST['SQA1'];
		$SQA2 = $_POST['SQA2'];
		
		//Check email is unique TODO: use trigger instead once implemented
		$stmt = $conn->prepare("SELECT UserID FROM User WHERE Email=:Email AND UserID!=:id");
		$stmt->execute(array('Email' => $Email,'id' => $account));
		$exists = $stmt->fetchColumn();
		if (!$exists)
		{
			if (isset($_POST['Newpassword']) && $_POST['Newpassword'] != '')
			{
				// Generate a hash
				$t_hasher = new PasswordHash(8, FALSE);
				$Password = $_POST['Newpassword'];
				$Hash = $t_hasher->HashPassword($Password);
				
				$stmt = $conn->prepare("UPDATE 
				User SET 
				Title = :Title, 
				Email = :Email, 
				DOB = :DOB, 
				FirstName = :FirstName, 
				LastName = :LastName, 
				Add1 = :Add1, 
				Add2 = :Add2, 
				Add3 = :Add3, 
				Postcode = :Postcode, 
				Telephone = :Telephone, 
				Mobile = :Mobile, 
				LicenseNo = :LicenseNo, 
				SQ1 = :SQ1, 
				SQ2 = :SQ2, 
				SQA1 = :SQA1, 
				SQA2 = :SQA2,
				Password = :Password
				WHERE 
				UserID = :account");	
				$stmt->execute(array(
				'Title' => $Title, 
				'Email' => $Email, 
				'DOB' => $DOB, 
				'FirstName' => $FirstName, 
				'LastName' => $LastName, 
				'Add1' => $Add1, 
				'Add2' => $Add2, 
				'Add3' => $Add3, 
				'Postcode' => $Postcode, 
				'Telephone' => $Telephone, 
				'Mobile' => $Mobile, 
				'LicenseNo' => $LicenseNo, 
				'SQ1' => $SQ1, 
				'SQ2' => $SQ2, 
				'SQA1' => $SQA1, 
				'SQA2' => $SQA2, 
				'account' => $account,
				'Password' => $Hash));
				
				echo 'Success';
				
			}
			else
			{
				$stmt = $conn->prepare("UPDATE 
				User SET 
				Title = :Title, 
				Email = :Email, 
				DOB = :DOB, 
				FirstName = :FirstName, 
				LastName = :LastName, 
				Add1 = :Add1, 
				Add2 = :Add2, 
				Add3 = :Add3, 
				Postcode = :Postcode, 
				Telephone = :Telephone, 
				Mobile = :Mobile, 
				LicenseNo = :LicenseNo, 
				SQ1 = :SQ1, 
				SQ2 = :SQ2, 
				SQA1 = :SQA1, 
				SQA2 = :SQA2 
				WHERE 
				UserID = :account");	
				$stmt->execute(array(
				'Title' => $Title, 
				'Email' => $Email, 
				'DOB' => $DOB, 
				'FirstName' => $FirstName, 
				'LastName' => $LastName, 
				'Add1' => $Add1, 
				'Add2' => $Add2, 
				'Add3' => $Add3, 
				'Postcode' => $Postcode, 
				'Telephone' => $Telephone, 
				'Mobile' => $Mobile, 
				'LicenseNo' => $LicenseNo, 
				'SQ1' => $SQ1, 
				'SQ2' => $SQ2, 
				'SQA1' => $SQA1, 
				'SQA2' => $SQA2, 
				'account' => $account));
				
				echo 'Success';
				
			}
			
		}
		else
		{
			// Error message
			echo "The email address specified is already associated with an account";
			
		}
		
	}
	else if(isset($_POST['delete_account'])) {
		
		if ($account != $_SESSION['User']['ID'])
		{
			$stmt = $conn->prepare("UPDATE User SET Status = 0 WHERE UserID = :account");	
			$stmt->execute(array('account' => $account));
			echo "Success";
		}
		else
		{
			echo "You cannot disable your own account!";
		}
		
	}
	else if(isset($_POST['enable_account'])) {
		
		$stmt = $conn->prepare("UPDATE User SET Status = 1 WHERE UserID = :account");	
		$stmt->execute(array('account' => $account));
		
		Header('Location: '.fetchinline($apages).'manage_accounts.php');
	}	
	
?>