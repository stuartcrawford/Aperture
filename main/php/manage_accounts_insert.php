<?php
	include('global.php');
	dbconnect();
	
	if (isset($_POST['admin']))
	{
		$Title = $_POST['title'];
		$FirstName = trim($_POST['fname']);
		$LastName = trim($_POST['lname']);
		$Add1 = trim($_POST['Add1']);
		$Add2 = trim($_POST['Add2']);
		$Add3 = trim($_POST['Add3']);
		$Postcode = $_POST['Postcode'];
		$Telephone = $_POST['Telephone'];
		$Mobile = $_POST['Mobile'];
		$LicenseNo = $_POST['LicenseNo'];
		$Email = $_POST['RegEmail'];
		$t_hasher = new PasswordHash(8, FALSE);
		$Password = $_POST['RegPassword'];
		$Hash = $t_hasher->HashPassword($Password);
		
		//Check email is unique TODO: use trigger instead once implemented
		$stmt = $conn->prepare("SELECT UserID FROM User WHERE Email=:Email");
		$stmt->execute(array('Email' => $Email));
		$exists = $stmt->fetchColumn();
		if (!$exists)
		{
			//PDO PREPARED STATEMENT
			$stmt = $conn->prepare
			("INSERT INTO User
			(UserID, Status, CreationDate,
			AccessLvl, Email, Password,
			DOB, Title, FirstName,
			LastName, Add1, Add2,
			Add3, Postcode, Telephone,
			Mobile, LicenseNo, NextLogin,
			LoginAttempts, Activation, APIToken)
			VALUES 
			(null, 1, now(),
			1, :Email, :Password,
			null, :Title, :FirstName,
			:LastName, :Add1, :Add2,
			:Add3, :Postcode, :Telephone,
			:Mobile, :LicenseNo, now(),
			0, null, null)");
			
			$stmt->bindParam('Email',$Email);
			$stmt->bindParam('Password',$Hash);
			$stmt->bindParam('Title',$Title);
			$stmt->bindParam('FirstName',$FirstName);
			$stmt->bindParam('LastName',$LastName);
			$stmt->bindParam('Add1',$Add1);
			$stmt->bindParam('Add2',$Add2);
			$stmt->bindParam('Add3',$Add3);
			$stmt->bindParam('Postcode',$Postcode);
			$stmt->bindParam('Telephone',$Telephone);
			$stmt->bindParam('Mobile',$Mobile);
			$stmt->bindParam('LicenseNo',$LicenseNo);
			$stmt->execute();
			echo 'Success';
		}
		else // Email already registered
		{
			echo 'The email address specified is already associated with an account';
		}
	}
	else if (isset($_POST['staff']))
	{
		$Title = $_POST['title'];
		$FirstName = trim($_POST['fname']);
		$LastName = trim($_POST['lname']);
		$Add1 = trim($_POST['Add1']);
		$Add2 = trim($_POST['Add2']);
		$Add3 = trim($_POST['Add3']);
		$Postcode = $_POST['Postcode'];
		$Telephone = $_POST['Telephone'];
		$Mobile = $_POST['Mobile'];
		$LicenseNo = $_POST['LicenseNo'];
		$SQ1 = $_POST['SQ1'];
		$SQ2 = $_POST['SQ2'];
		$SQA1 = $_POST['SQA1'];
		$SQA2 = $_POST['SQA2'];
		$Email = $_POST['RegEmail'];
		$t_hasher = new PasswordHash(8, FALSE);
		$Password = $_POST['RegPassword'];
		$Hash = $t_hasher->HashPassword($Password);
		
		//Check email is unique TODO: use trigger instead once implemented
		$stmt = $conn->prepare("SELECT UserID FROM User WHERE Email=:Email");
		$stmt->execute(array('Email' => $Email));
		$exists = $stmt->fetchColumn();
		if (!$exists)
		{
			//PDO PREPARED STATEMENT
			$stmt = $conn->prepare
			("INSERT INTO User
			(UserID, Status, CreationDate,
			AccessLvl, Email, Password,
			DOB, Title, FirstName,
			LastName, Add1, Add2,
			Add3, Postcode, Telephone,
			Mobile, LicenseNo, SQ1,
			SQ2, SQA1, SQA2, NextLogin,
			LoginAttempts, Activation, APIToken)
			VALUES 
			(null, 1, now(),
			2, :Email, :Password,
			null, :Title, :FirstName,
			:LastName, :Add1, :Add2,
			:Add3, :Postcode, :Telephone,
			:Mobile, :LicenseNo, :SQ1,
			:SQ2, :SQA1, :SQA2, now(),
			0, null, null)");
			
			$stmt->bindParam('Email',$Email);
			$stmt->bindParam('Password',$Hash);
			$stmt->bindParam('Title',$Title);
			$stmt->bindParam('FirstName',$FirstName);
			$stmt->bindParam('LastName',$LastName);
			$stmt->bindParam('Add1',$Add1);
			$stmt->bindParam('Add2',$Add2);
			$stmt->bindParam('Add3',$Add3);
			$stmt->bindParam('Postcode',$Postcode);
			$stmt->bindParam('Telephone',$Telephone);
			$stmt->bindParam('Mobile',$Mobile);
			$stmt->bindParam('LicenseNo',$LicenseNo);
			$stmt->bindParam('SQ1',$SQ1);
			$stmt->bindParam('SQ2',$SQ2);
			$stmt->bindParam('SQA1',$SQA1);
			$stmt->bindParam('SQA2',$SQA2);
			$stmt->execute();
			echo 'Success';
		}
		else // Email already registered
		{
			echo 'The email address specified is already associated with an account';
		}
	}
?>