<?php
	include('global.php');
	dbconnect();
	
	if (isset($_POST['submitname']))
	{
		// Copy inputs to session in case of error
		$_SESSION['Form']['Title'] = $_POST['title'];
		$_SESSION['Form']['FirstName'] = $_POST['fname'];
		$_SESSION['Form']['LastName'] = $_POST['lname'];
	
		$Title = $_POST['title'];
		$FirstName = trim($_POST['fname']);
		$LastName = trim($_POST['lname']);
		
		//PDO PREPARED STATEMENT
		$stmt = $conn->prepare
		("UPDATE User SET
		Title=:Title, FirstName=:FirstName, LastName=:LastName
		WHERE UserID=:UserID");
		
		$stmt->bindParam('UserID',$_SESSION['User']['ID']);
		$stmt->bindParam('Title',$Title);
		$stmt->bindParam('FirstName',$FirstName);
		$stmt->bindParam('LastName',$LastName);
		$stmt->execute();
		$_SESSION['Form']['Success'] = 'Your name has been updated';
		header('Location: '.fetchinline($apages).'my_account.php');
	}
	else if (isset($_POST['submitaddress']))
	{
		// Copy inputs to session in case of error
		$_SESSION['Form']['Add1'] = $_POST['Add1'];
		$_SESSION['Form']['Add2'] = $_POST['Add2'];
		$_SESSION['Form']['Add3'] = $_POST['Add3'];
		$_SESSION['Form']['Postcode'] = $_POST['Postcode'];
	
		$Add1 = trim($_POST['Add1']);
		$Add2 = trim($_POST['Add2']);
		$Add3 = trim($_POST['Add3']);
		$Postcode = $_POST['Postcode'];
		
		//PDO PREPARED STATEMENT
		$stmt = $conn->prepare
		("UPDATE User SET
		Add1=:Add1, Add2=:Add2, Add3=:Add3,
		Postcode=:Postcode
		WHERE UserID=:UserID");
		
		$stmt->bindParam('UserID',$_SESSION['User']['ID']);
		$stmt->bindParam('Add1',$Add1);
		$stmt->bindParam('Add2',$Add2);
		$stmt->bindParam('Add3',$Add3);
		$stmt->bindParam('Postcode',$Postcode);
		$stmt->execute();
		$_SESSION['Form']['Success'] = 'Your address has been updated';
		header('Location: '.fetchinline($apages).'my_account.php');
	}
	else if (isset($_POST['submitcontact']))
	{
		// Copy inputs to session in case of error
		$_SESSION['Form']['Telephone'] = $_POST['Telephone'];
		$_SESSION['Form']['Mobile'] = $_POST['Mobile'];
	
		$Telephone = $_POST['Telephone'];
		$Mobile = $_POST['Mobile'];
		
		//PDO PREPARED STATEMENT
		$stmt = $conn->prepare
		("UPDATE User SET
		Telephone=:Telephone, Mobile=:Mobile
		WHERE UserID=:UserID");
		
		$stmt->bindParam('UserID',$_SESSION['User']['ID']);
		$stmt->bindParam('Telephone',$Telephone);
		$stmt->bindParam('Mobile',$Mobile);
		$stmt->execute();
		$_SESSION['Form']['Success'] = 'Your contact details have been updated';
		header('Location: '.fetchinline($apages).'my_account.php');
	}
	else if (isset($_POST['submitlicense']))
	{
		// Copy inputs to session in case of error
		$_SESSION['Form']['LicenseNo'] = $_POST['LicenseNo'];
	
		$LicenseNo = $_POST['LicenseNo'];
		
		//PDO PREPARED STATEMENT
		$stmt = $conn->prepare
		("UPDATE User SET
		LicenseNo=:LicenseNo
		WHERE UserID=:UserID");
		
		$stmt->bindParam('UserID',$_SESSION['User']['ID']);
		$stmt->bindParam('LicenseNo',$LicenseNo);
		$stmt->execute();
		$_SESSION['Form']['Success'] = 'Your license number has been updated';
		header('Location: '.fetchinline($apages).'my_account.php');
	}
	else if (isset($_POST['submitquestions']))
	{
		// Copy inputs to session in case of error
		$_SESSION['Form']['SQ1'] = $_POST['q1'];
		$_SESSION['Form']['SQ2'] = $_POST['q2'];
		$_SESSION['Form']['SQA1'] = $_POST['a1'];
		$_SESSION['Form']['SQA2'] = $_POST['a2'];
	
		$SQ1 = $_POST['q1'];
		$SQ2 = $_POST['q2'];
		$SQA1 = $_POST['a1'];
		$SQA2 = $_POST['a2'];
		
		//PDO PREPARED STATEMENT
		$stmt = $conn->prepare
		("UPDATE User SET
		SQ1=:SQ1, SQ2=:SQ2, SQA1=:SQA1, SQA2=:SQA2
		WHERE UserID=:UserID");
		
		$stmt->bindParam('UserID',$_SESSION['User']['ID']);
		$stmt->bindParam('SQ1',$SQ1);
		$stmt->bindParam('SQ2',$SQ2);
		$stmt->bindParam('SQA1',$SQA1);
		$stmt->bindParam('SQA2',$SQA2);
		$stmt->execute();
		$_SESSION['Form']['Success'] = 'Your security questions have been updated';
		header('Location: '.fetchinline($apages).'my_account.php');
	}
	else if (isset($_POST['submitemail']))
	{
		// Copy inputs to session in case of error
		$_SESSION['Form']['Email'] = $_POST['Email'];
	
		$Email = $_POST['Email'];
		
		$stmt = $conn->prepare("SELECT Email FROM User WHERE UserID=:id");
		$stmt->execute(array('id' => $_SESSION['User']['ID']));
		$OldEmail = $stmt->fetchColumn();
		
		if ($Email != $OldEmail)
		{
			$to      = $OldEmail; // Send Email to our user
			$subject = 'Email Change Confirmation'; // Give the Email a subject 
			$message =
'This is a confirmation that you have changed the email address of your account with us.

Your new email address is:
'.$Email;
			// Our message above including the link
			$header = 'From:'.$serverEmail."\r\n"; // Set from header
			
			//Check email is unique TODO: use trigger instead once implemented
			$stmt = $conn->prepare("SELECT UserID FROM User WHERE Email=:Email");
			$stmt->execute(array('Email' => $Email));
			$exists = $stmt->fetchColumn();
			if (!$exists)
			{
				mail($to, $subject, $message, $header); // Send our Email
				//PDO PREPARED STATEMENT
				$stmt = $conn->prepare
				("UPDATE User SET
				Email=:Email
				WHERE UserID=:UserID");
				
				$stmt->bindParam('UserID',$_SESSION['User']['ID']);
				$stmt->bindParam('Email',$Email);
				$stmt->execute();
				$_SESSION['Form']['Success'] = 'Your email address has been changed';
				header('Location: '.fetchinline($apages).'my_account.php');
			}
			else // Email already registered
			{
				$_SESSION['Form']['Error'] = "The email address specified is already associated with an account";
				header('Location: '.fetchinline($apages).'my_account.php');
			}
		}
		else // New email is the same as old email
		{
			$_SESSION['Form']['Error'] = "Your account is already registered with the email address specified";
			header('Location: '.fetchinline($apages).'my_account.php');
		}
	}
	else if (isset($_POST['submitpassword']))
	{
		$Password = $_POST['Password'];
		
		// Get the password hash from the database (and email)
		$stmt = $conn->prepare("SELECT Email,Password FROM User WHERE UserID=:id");
		$stmt->bindParam(':id',$_SESSION['User']['ID']);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$Email = $row['Email'];
		$OldPassword = $_POST['OldPassword'];
		
		// Check the passwords match
		$t_hasher = new PasswordHash(8, FALSE);
		$check = $t_hasher->CheckPassword($OldPassword, $row['Password']);
	
		// Check if password is correct
		if ($check)
		{
			// Generate a hash
			$Hash = $t_hasher->HashPassword($Password);

			$to      = $Email; // Send Email to our user
			$subject = 'Password Change Confirmation'; // Give the Email a subject 
			$message =
'This is a confirmation that you have changed the password of your account with us.
Your new credentials are:

------------------------
Email: '.$Email.'
Password: '.$Password.'
------------------------';
			// Our message above including the link
			$header = 'From:'.$serverEmail."\r\n"; // Set from header
			
			mail($to, $subject, $message, $header); // Send our Email
			//PDO PREPARED STATEMENT
			$stmt = $conn->prepare
			("UPDATE User SET
			Password=:Password
			WHERE UserID=:UserID");
			
			$stmt->bindParam('UserID',$_SESSION['User']['ID']);
			$stmt->bindParam('Password',$Hash);
			$stmt->execute();
			$_SESSION['Form']['Success'] = 'Your password has been changed';
			header('Location: '.fetchinline($apages).'my_account.php');
		}
		else // Password is incorrect
		{
			$_SESSION['Form']['Error'] = "The password you entered is incorrect";
			header('Location: '.fetchinline($apages).'my_account.php');
		}
	}
?>